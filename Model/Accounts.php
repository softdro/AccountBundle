<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SDRO\AccountBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SDRO\AccountBundle\Entity\Account;

/**
 * Description of Accounts
 *
 * @author modasser
 */
class Accounts {

    protected $em;
    protected $fy;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->fy = $this->getFiscalYear();
        ;
    }

    /**
     * @return \InventoryBundle\Entity\Repository\ProductRepository
     */
    public function getAccountRepository() {
        return $this->em->getRepository('SDROAccountBundle:Account');
    }

    public function getBatchRepository() {
        return $this->em->getRepository('SDROAccountBundle:Batch');
    }

    public function getAccontGroupRepository() {
        return $this->em->getRepository('SDROAccountBundle:AccountGroup');
    }

    public function getGroupRepository() {
        return $this->em->getRepository('SDROAccountBundle:Group');
    }

    /**
     * 
     * @param type $accountHeadCode
     * @return type
     * @throws \Exception
     */
    public function getAccountByHead($accountHeadCode) {

        $query = $this->em
                ->createQuery("SELECT a FROM SDROAccountBundle:Account a "
                        . " LEFT JOIN SDROAccountBundle:AccountHead ah WITH a.account_head = ah.id"
                        . " WHERE ah.code in (:ah) "
                )
                ->setParameter('ah', $accountHeadCode)
        ;

        $results = $query->getResult();

        return $results;
    }

    public function getAccountsByGroupCode($accountGroupCode) {
//        \Doctrine\Common\Util\Debug::dump($accountGroupCode);
//        throw new \Exception($accountGroupCode);
        $results = $this->em->getRepository("SDROAccountBundle:Account")
                ->createQueryBuilder('a')
                ->select('a')
                ->leftJoin('a.account_group', 'ag')
                ->leftJoin('ag.groups', 'g')
                ->where('g.code in (:g)')
                ->setParameter('g', $accountGroupCode)
                ->getQuery()
                ->getResult();

//        if (sizeof($results) == 0)
//            throw new \Exception("not found accounts by Group Code: " . $accountGroupCode);
//        $query = $this->em
//                ->createQuery("SELECT a FROM SDROAccountBundle:Account a "
//                        . " LEFT JOIN SDROAccountBundle:AccountGroup ag WITH  ag.id = a.account_group"
//                        . " WHERE ag.code in (:ag) "
//                )
//                ->setParameter('ag', $accountGroupCode)
//        ;
//
//        $results = $query->getResult();

        return $results;
    }

    public function newAccountObj() {
        $fy = $this->fy;
//        throw new \Exception($fy);
        $acc = new Account();
        $accBalance = new \SDRO\AccountBundle\Entity\AccountBalance();
        $accBalance->setPeriod($fy);
        $accBalance->setBalance(0);
        $accBalance->setAccount($acc);
        $acc->addAccountBalance($accBalance);
        return $acc;
    }

    /**
     * @param type $account
     * @param type $amount
     * @param type $sign
     * @throws \Exception
     */
    public function updateAccount($account, $amount, $sign = AccountUtil::Cr) {

//        \Doctrine\Common\Util\Debug::dump($account->getIsAsset());
//        throw new \Exception($account);
        if (!$account)
            throw new \Exception("Account Not Found");


//        $isCr = ( $account->getIsAsset() === TRUE) ? (($sign == AccountUtil::Cr) ? AccountUtil::Dr : AccountUtil::Cr ) : $sign;
//        throw new \Exception($isCr);
//        if ($isCr == AccountUtil::Cr)
        if (AccountUtil::Cr === $sign)
            $account->setBalance($account->getBalance() + $amount);
        else
            $account->setBalance($account->getBalance() - $amount);

        $this->em->persist($account);
    }

    /**
     * 
     * @param type $batch
     * @param type $first_amount
     * @param type $second_amount
     * @param type $des
     * @throws \Exception
     */
    public function entryBatchAccount($batch, $first_amount, $second_amount = false, $des = null) {

        if ($second_amount == FALSE)
            $second_amount = $first_amount;

        if (!$batch->getFirstAccount())
            throw new \Exception("Please defind first batch account");
        if (!$batch->getSecondAccount())
            throw new \Exception("Please defind second batch account");

        $this->updateAccount($batch->getFirstAccount(), $first_amount, $batch->getFirstCr() ? AccountUtil::Cr : AccountUtil::Dr);

        $this->updateAccount($batch->getSecondAccount(), $second_amount, $batch->getSecondCr() ? AccountUtil::Cr : AccountUtil::Dr);

//        \Doctrine\Common\Util\Debug::dump($batch);
//        throw new \Exception($fAcc->getBalance() . ", " . $sAcc->getBalance() . " - " . $batch->getFirstCr() . " - " . $batch->getSecondCr() . " value: $fVal, $sVal");

        $newLedger1 = new \SDRO\AccountBundle\Entity\Ledger($this->fy, $batch->getFirstAccount());
        $newLedger2 = new \SDRO\AccountBundle\Entity\Ledger($this->fy, $batch->getSecondAccount());

        $newLedger1->setDescription($des);
        $newLedger2->setDescription($des);

        if ($batch->getFirstCr())
            $newLedger1->setCredit($first_amount);
        else
            $newLedger1->setDebit($first_amount);

        if ($batch->getSecondCr())
            $newLedger2->setCredit($second_amount);
        else
            $newLedger2->setDebit($second_amount);

        $this->em->persist($newLedger1);
        $this->em->persist($newLedger2);
    }

    /**
     * Update Person Account
     * @param type $account
     * @param type $amount
     * @param type $sign
     * @param type $des
     */
    public function updatePersonAccount($account, $amount, $sign = AccountUtil::Cr, $des = null) {
        $this->updateAccount($account, $amount, $sign);

        $newLedger = new \SDRO\AccountBundle\Entity\Ledger($this->fy, $account);
        if ($sign == AccountUtil::Dr)
            $newLedger->setDebit($amount);
        else
            $newLedger->setCredit($amount);
        $newLedger->setDescription($des);

        $this->em->persist($newLedger);
    }

    /**
     * 
     * @param type $invoice
     * @param type $account
     * @param type $sign
     * @param type $des
     */
    public function entryInvoiceLedgerAccount($invoice, $account, $sign = AccountUtil::Cr, $des = null) {

        $this->updateAccount($account, $invoice->getDue(), $sign);

        $newLedger = new \SDRO\AccountBundle\Entity\Ledger($this->fy, $account);
        if ($sign == AccountUtil::Dr)
            $newLedger->setDebit($invoice->getAmount());
        else
            $newLedger->setCredit($invoice->getAmount());
        $newLedger->setDescription($des);

        $invoiceLedger = new \SDRO\AccountBundle\Entity\InvoiceLedger($invoice);
        $invoiceLedger->setLedger($newLedger);

        $this->em->persist($invoiceLedger);
    }

    public function salesBatch($cash_account, $amount, $sign = AccountUtil::Cr, $des = null) {

        $s_account = $this->getAccountByHead(AccountUtil::ACCOUNT_HEAD_REVENUE);
        if (!$s_account)
            throw new \Exception("Please defind the sales batch account");
        
        $batch = new \SDRO\AccountBundle\Entity\Batch($cash_account, $s_account[0]);
        $batch->setFirstCr(TRUE);
        $batch->setSecondCr(True);

        $this->entryBatchAccount($batch, $amount);
    }

    /**
     * Create InvoiceLedger, update person account, update SalesBatch (cash, revenue)
     * @param type $cash_account
     * @param type $invoice
     * @param type $person_account
     * @param type $sign
     * @param type $des
     */
    public function invoicePayament($cash_account, $invoice, $person_account, $sign = AccountUtil::Cr, $des = null) {

        $this->entryInvoiceLedgerAccount($invoice, $person_account, $sign, $des);

        // single asset account to create bath account
        $this->salesBatch($cash_account, $invoice->getDue(), $sign, $des);
    }

    public function getAccountBalance($account) {

        $account1 = $this->getEntityManager()
                ->createQuery('SELECT a.* FROM SDROAccountBundle:Account a'
                        . ' WHERE a.id = ?1 AND a.period = ?2'
                )
                ->setParameter('1', $account)
                ->setParameter('2', $this->fy);

        try {
            $result = $fy->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        return $result;
    }

    public function getFiscalYear() {
        $result = null;
        $result = $this->em->getRepository('SDROAccountBundle:Period')->findOneBy(array('enable' => TRUE));
//                $this->getEntityManager()
//                ->createQuery('SELECT a FROM SDROAccountBundle:Period a'
//                        . ' WHERE a.enable = ?1 '
//                )
//                ->setParameter('1', true);
//        try {
//            $result = $query->getSingleResult();
//        } catch (\Exception $ex) {
//            throw new \Exception("error :" . $ex);
//        }

        return $result;
    }

    public function getReportIndex($report_code) {
        $query = $this->em
                ->createQuery("SELECT ri FROM SDROAccountBundle:ReportIndex ri "
                        . " LEFT JOIN SDROAccountBundle:Report r WITH r.id = ri.report "
                        . " WHERE r.code in (:r) "
                        . " ORDER BY ri.priority ASC"
                )
                ->setParameter('r', $report_code)
        ;
        $results = $query->getResult();
        if (sizeof($results) > 0)
            return $results;
        else
            return null;
    }

    public function getIncomeStatement() {
        $report_index = null;
//        $query = $this->em
//         ->from('ProProposalBundle:Proposal', 'p')
//    ->leftJoin('ProProposalBundle:Proposal\Vote', 'v', 'WITH', 'v.proposal = p AND v.user = :user AND (v.decision = :in_favor OR v.decision = :against)')
//    ->setParameter('user', $options['user'])
//    ->setParameter('in_favor', 'in_favor')
//    ->setParameter('against', 'against')
//    ->andWhere('p.community = :community')
//    ->setParameter('community', $community)
//    ->andWhere('p.archived = :archived')
//    ->setParameter('archived', $options['archived'])
//    ->leftJoin('p.convocation', 'c')
//    ->andWhere("p.convocation IS NULL OR c.status = :pending")
//    ->setParameter('pending', Convocation::STATUS_PENDING);

        $report_index = $this->getReportIndex(AccountUtil::REPORT_INCOME_STATEMENT);

//        \Doctrine\Common\Util\Debug::dump($report_index);
//        throw new \Exception(sizeof($report_index));
//        $query = $this->em
//                ->createQuery("SELECT l FROM SDROAccountBundle:Ledger l "
//                        . " LEFT JOIN SDROAccountBundle:Account a WITH l.account = a.id"
//                        . " JOIN SDROAccountBundle:AccountGroup ag WITH a.account_group = ag.id "
//                        . " WHERE ag.code in (:ag) "
//                        . " AND l.period = ?3"
//                )
//                ->setParameter('ag', array(AccountUtil::ACCOUNT_GROUP_REVINUE, AccountUtil::ACCOUNT_GROUP_EXPENSES))
////                ->setParameter('1', array(Util::ACCOUNCODE_ACCOUNT_GROUP_REVINUET_GROUP_REVINUE ))
//                ->setParameter("3", $this->getFiscalYear())
//        ;
//
//
//        $results = $query->getResult();
        try {
//            throw new \Exception('sdf');
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        return $report_index;
    }

    public function getBalanceSheet() {
        $report_index = null;
        $data = array();
        $report_index = $this->getReportIndex(AccountUtil::REPORT_BALANCE_SHEET);

        $results = $this->em->getRepository("SDROAccountBundle:Account")
                ->createQueryBuilder('a')
                ->select('SUM(a.balance) as total')
                ->leftJoin('a.account_group', 'ag')
                ->leftJoin('ag.groups', 'g')
                ->where('g.code in (:g)')
                ->setParameter('g', array(AccountUtil::ACCOUNT_GROUP_REVINUE, AccountUtil::ACCOUNT_GROUP_EXPENSES))
                ->getQuery()
                ->getSingleResult();

        $codes = array();
        foreach ($report_index as $ri) {
//            throw new \Exception($ri->getAccountGroup()->getCode());
            $codes[] = $ri->getGroups()->getCode();
        }
        $accounts = $this->getAccountsByGroupCode($codes);

//        \Doctrine\Common\Util\Debug::dump($codes);
//        throw new \Exception(sizeof($results) . ", " . sizeof($results['total']));
//        throw new \Exception(sizeof($accounts).", ");

        try {
//            throw new \Exception('sdf');
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        $data[0] = $results['total'];
        $data[1] = $accounts;
        $data[2] = $codes;
        return $data;
    }

    public function updateInventoryAndExpanseAccount($expense) {
//        $batch = new \SDRO\AccountBundle\Entity\Batch();

        $batch = $this->getBatchRepository()->findOneBy(array('code' => AccountUtil::BATCH_INVENTORY_INVENTORY_EXPENSE));
        if (!isset($batch))
            throw new \Exception("Batch Inventory_Expanse not found by code: " . AccountUtil::BATCH_INVENTORY_INVENTORY_EXPENSE);
        $batch->setFirstCr(TRUE);
        $batch->setSecondCr(FALSE);
        $this->entryBatchAccount($batch, $expense, $expense, "Enventory updated");
    }

}
