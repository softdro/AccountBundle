<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Acme\CoreBundle\Model\Util;
use Acme\CoreBundle\Model\Type;
use SDRO\AccountBundle\Model\AccountUtil;

class AccountRepository extends EntityRepository {

    public function entryAccount($account, $sign = false) {

//        $fy = $this->getFiscalYear();
        $accountBalance = $this->getAccountBalance($account);

        \Doctrine\Common\Util\Debug::dump($accountBalance);
        throw new \Exception(sizeof($accountBalance));

        $query = $this->getEntityManager()
                ->createQuery('SELECT a.account_number FROM AcmeAccountBundle:Account a'
                )
//                ->setParameter('1', "$acc_code" . '%')
        ;
//        throw new \Exception($acc_code);
        try {
            $result = $query->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

//        throw new \Exception(sizeof($result).' '.++$result['account_number']);

        if (sizeof($result) > 0)
            return ++$result['account_number'];
        else
//            return $acc_code . '0011';
            return $acc_code . '1011';
    }

    public function getAccountBalance($account) {

        $account1 = $this->getEntityManager()
                ->createQuery('SELECT a.* FROM AcmeAccountBundle:Account a'
                        . ' WHERE a.id = ?1 AND a.period = ?2'
                )
                ->setParameter('1', $account)
                ->setParameter('2', $this->getFiscalYear());

        try {
            $result = $fy->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

        return $result;
    }

    public function getFiscalYear() {
        $result = null;
        $query = $this->getEntityManager()
                ->createQuery('SELECT a FROM AcmeAccountBundle:Period a'
                        . ' WHERE a.enable = ?1 '
                )
                ->setParameter('1', true)
        ;

        try {
            $result = $query->getSingleResult();
        } catch (\Exception $ex) {
            throw new \Exception("error :" . $ex);
        }

        return $result;
    }

    public function getNewAccountNumber() {

//SELECT a . * 
//FROM account__account a
//WHERE a.account_number LIKE  '11%'
//ORDER BY a.account_number DESC 

        $query = $this->getEntityManager()
                ->createQuery('SELECT a.account_number FROM AcmeAccountBundle:Account a'
//                        . ' WHERE a.account_number like ?1 '
                . ' ORDER BY a.account_number DESC'
                )
//                ->setParameter('1', "$acc_code" . '%')
        ;
//        throw new \Exception($acc_code);
        try {
            $result = $query->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }

//        throw new \Exception(sizeof($result).' '.++$result['account_number']);

        if (sizeof($result) > 0)
            return ++$result['account_number'];
        else
//            return $acc_code . '0011';
            return $acc_code . '1011';
    }

    public function getAllAccountBalance() {

//SELECT a . * 
//FROM account__account a
//GROUP BY a.account_id
//ORDER BY a.account_id DESC 

        $query = $this->getEntityManager()
                ->createQuery('SELECT a FROM AcmeAccountBundle:Ledger a GROUP BY a.account ORDER BY a.account DESC'
        );
        try {
            $result = $query->getResult();
        } catch (\Exception $ex) {
            return 0;
        }

//        if (sizeof($result) > 0 )
        return $result;
//        else
//            return 0;
    }

    public function getLastBalanceByAccountId($id) {
        $query = $this->getEntityManager()
                ->createQuery(
                        'SELECT a.balance FROM AcmeAccountBundle:Ledger a WHERE a.account = :id ORDER BY a.id DESC'
                )
                ->setParameter('id', $id);
        try {
            $result = $query->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            return 0;
        }


//        if (sizeof($result) > 0 )
        return $result['balance'];
//        else
//            return 0;
    }

    public function getLastBalanceByAccountNumber($number) {

        $account = $this->getRepository('AcmeAccountBundle:Account')->findByAccountNumber($number);


        $query = $this->getEntityManager()
                ->createQuery(
                        'SELECT a.balance FROM AcmeAccountBundle:Ledger a WHERE a.account = :id ORDER BY a.id DESC'
                )
                ->setParameter('id', $account->getId());
        try {
            $result = $query->setMaxResults(1)->getSingleResult();
        } catch (\Exception $ex) {
            return 0;
        }


//        if (sizeof($result) > 0 )
        return $result['balance'];
//        else
//            return 0;
    }

    public function getFBBankAccount() {
        $query = $this->getEntityManager()
                ->createQuery(
                        'SELECT a FROM AcmeAccountBundle:Account a WHERE a.account_number = :id'
                )
//                ->setParameter('id', AccountUtil::ACCOUNT_NUMBER_FB_BANK)
                ;
        try {
            $result = $query->getSingleResult();
        } catch (\Exception $ex) {
            return 0;
        }
        return $result;
    }



}
