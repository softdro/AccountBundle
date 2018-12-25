<?php

namespace App\Acme\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="account", message="Duplicate account name")
 * @ORM\Table(name="account__ledger")
 */
class Ledger {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//    /**
//     * @ORM\ManyToOne(targetEntity="Batch", inversedBy="ledger", cascade={"persist"})
//     * @ORM\JoinColumn(name="batch_id", referencedColumnName="id")
//     */
//    protected $batch;

    /**
     * @ORM\ManyToOne(targetEntity="Period", inversedBy="ledger", cascade={"persist"})
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     */
    protected $period;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="ledger", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $debit;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $credit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="InvoiceLedger", mappedBy="ledger", cascade={"persist"})
     */
    protected $invoice_ledger;

//    public function __construct() {
//        $this->created = new \DateTime("now");
//        $this->debit = 0;
//        $this->credit = 0;
//        $this->invoice_ledger = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->batch = new Batch();
//    }

    public function __construct($fy, $acc) {
        $this->created = new \DateTime("now");
//        if ($batch)
//            $this->setBatch($batch);
        $this->setPeriod($fy);
        $this->setAccount($acc);

//        $this->invoice_ledger = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->batch = new Batch();
    }

    public function __toString() {
        return (string) $this->getAccountBalance() ? : 'n/a ';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ledger
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set debit
     *
     * @param string $debit
     *
     * @return Ledger
     */
    public function setDebit($debit) {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string
     */
    public function getDebit() {
        return $this->debit;
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return Ledger
     */
    public function setCredit($credit) {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit() {
        return $this->credit;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Ledger
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

//    /**
//     * Set batch
//     *
//     * @param \App\Acme\AccountBundle\Entity\Batch $batch
//     *
//     * @return Ledger
//     */
//    public function setBatch(\App\Acme\AccountBundle\Entity\Batch $batch = null) {
//        $this->batch = $batch;
//
//        return $this;
//    }
//
//    /**
//     * Get batch
//     *
//     * @return \App\Acme\AccountBundle\Entity\Batch
//     */
//    public function getBatch() {
//        return $this->batch;
//    }

    /**
     * Set period
     *
     * @param \App\Acme\AccountBundle\Entity\Period $period
     *
     * @return Ledger
     */
    public function setPeriod(\App\Acme\AccountBundle\Entity\Period $period = null) {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \App\Acme\AccountBundle\Entity\Period
     */
    public function getPeriod() {
        return $this->period;
    }

    /**
     * Set account
     *
     * @param \App\Acme\AccountBundle\Entity\Account $account
     *
     * @return Ledger
     */
    public function setAccount(\App\Acme\AccountBundle\Entity\Account $account = null) {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \App\Acme\AccountBundle\Entity\Account
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * Add invoiceLedger
     *
     * @param \App\Acme\AccountBundle\Entity\InvoiceLedger $invoiceLedger
     *
     * @return Ledger
     */
    public function addInvoiceLedger(\App\Acme\AccountBundle\Entity\InvoiceLedger $invoiceLedger) {
        $this->invoice_ledger[] = $invoiceLedger;

        return $this;
    }

    /**
     * Remove invoiceLedger
     *
     * @param \App\Acme\AccountBundle\Entity\InvoiceLedger $invoiceLedger
     */
    public function removeInvoiceLedger(\App\Acme\AccountBundle\Entity\InvoiceLedger $invoiceLedger) {
        $this->invoice_ledger->removeElement($invoiceLedger);
    }

    /**
     * Get invoiceLedger
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceLedger() {
        return $this->invoice_ledger;
    }

}
