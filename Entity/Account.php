<?php

namespace App\Acme\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Acme\AccountBundle\Entity\AccountRepository")
 * @DoctrineAssert\UniqueEntity(fields="title", message="Duplicate ACCOUNT name found in same period.")
 * @DoctrineAssert\UniqueEntity(fields="code", message="Duplicate ACCOUNT CODE found in same period.")
 * @ORM\Table(name="account__account")
 */
class Account {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var account_head
     * @ORM\ManyToOne(targetEntity="AccountHead", inversedBy="account", cascade={"persist"})
     * @ORM\JoinColumn(name="account_head_id", referencedColumnName="id")
     */
    protected $account_head;

    /**
     * @ORM\ManyToOne(targetEntity="Account", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $code;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $isAsset;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $balance;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enable = true;

    /**
     * @ORM\OneToMany(targetEntity="Ledger", mappedBy="account", cascade={"persist"})
     */
    protected $ledger;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="account", cascade={"persist"})
     */
    protected $invoice;

    /**
     * @ORM\OneToMany(targetEntity="ReportIndex", mappedBy="account", cascade={"persist"})
     */
    protected $report_index;

    /**
     * @ORM\OneToMany(targetEntity="Batch", mappedBy="first_account", cascade={"persist"})
     */
    protected $first_batch;

    /**
     * @ORM\OneToMany(targetEntity="Batch", mappedBy="second_account", cascade={"persist"})
     */
    protected $second_batch;

    /**
     * @ORM\OneToOne(targetEntity="\App\Acme\ProfileBundle\Entity\Person", mappedBy="account", cascade={"persist"})
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="AccountGroup", mappedBy="accounts", cascade={"persist"})
     */
    protected $account_group;

    /**
     * @ORM\OneToOne(targetEntity="ShareAccount", mappedBy="account", cascade={"persist"})
     */
    protected $share_account;

    public function __construct() {
        $this->isAsset = false;
        $this->balance = 0;
        $this->ledger = new \Doctrine\Common\Collections\ArrayCollection();
        $this->first_batch = new \Doctrine\Common\Collections\ArrayCollection();
        $this->second_batch = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account_group = new \Doctrine\Common\Collections\ArrayCollection();
        
        
    }

    public function __toString() {
        return $this->getTitle() . " (" . $this->getBalance() . ")" ? : 'n/a';
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
     * Set title
     *
     * @param string $title
     *
     * @return Account
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Account
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set isAsset
     *
     * @param boolean $isAsset
     *
     * @return Account
     */
    public function setIsAsset($isAsset) {
        $this->isAsset = $isAsset;

        return $this;
    }

    /**
     * Get isAsset
     *
     * @return boolean
     */
    public function getIsAsset() {
        return $this->isAsset;
    }

    /**
     * Set balance
     *
     * @param string $balance
     *
     * @return Account
     */
    public function setBalance($balance) {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Account
     */
    public function setEnable($enable) {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable() {
        return $this->enable;
    }

    /**
     * Set accountHead
     *
     * @param \App\Acme\AccountBundle\Entity\AccountHead $accountHead
     *
     * @return Account
     */
    public function setAccountHead(\App\Acme\AccountBundle\Entity\AccountHead $accountHead = null) {
        $this->account_head = $accountHead;

        return $this;
    }

    /**
     * Get accountHead
     *
     * @return \App\Acme\AccountBundle\Entity\AccountHead
     */
    public function getAccountHead() {
        return $this->account_head;
    }

    /**
     * Set parent
     *
     * @param \App\Acme\AccountBundle\Entity\Account $parent
     *
     * @return Account
     */
    public function setParent(\App\Acme\AccountBundle\Entity\Account $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \App\Acme\AccountBundle\Entity\Account
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add ledger
     *
     * @param \App\Acme\AccountBundle\Entity\Ledger $ledger
     *
     * @return Account
     */
    public function addLedger(\App\Acme\AccountBundle\Entity\Ledger $ledger) {
        $this->ledger[] = $ledger;

        return $this;
    }

    /**
     * Remove ledger
     *
     * @param \App\Acme\AccountBundle\Entity\Ledger $ledger
     */
    public function removeLedger(\App\Acme\AccountBundle\Entity\Ledger $ledger) {
        $this->ledger->removeElement($ledger);
    }

    /**
     * Get ledger
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLedger() {
        return $this->ledger;
    }

    /**
     * Add invoice
     *
     * @param \App\Acme\AccountBundle\Entity\Invoice $invoice
     *
     * @return Account
     */
    public function addInvoice(\App\Acme\AccountBundle\Entity\Invoice $invoice) {
        $this->invoice[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice
     *
     * @param \App\Acme\AccountBundle\Entity\Invoice $invoice
     */
    public function removeInvoice(\App\Acme\AccountBundle\Entity\Invoice $invoice) {
        $this->invoice->removeElement($invoice);
    }

    /**
     * Get invoice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * Add reportIndex
     *
     * @param \App\Acme\AccountBundle\Entity\ReportIndex $reportIndex
     *
     * @return Account
     */
    public function addReportIndex(\App\Acme\AccountBundle\Entity\ReportIndex $reportIndex) {
        $this->report_index[] = $reportIndex;

        return $this;
    }

    /**
     * Remove reportIndex
     *
     * @param \App\Acme\AccountBundle\Entity\ReportIndex $reportIndex
     */
    public function removeReportIndex(\App\Acme\AccountBundle\Entity\ReportIndex $reportIndex) {
        $this->report_index->removeElement($reportIndex);
    }

    /**
     * Get reportIndex
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReportIndex() {
        return $this->report_index;
    }

    /**
     * Add firstBatch
     *
     * @param \App\Acme\AccountBundle\Entity\Batch $firstBatch
     *
     * @return Account
     */
    public function addFirstBatch(\App\Acme\AccountBundle\Entity\Batch $firstBatch) {
        $this->first_batch[] = $firstBatch;

        return $this;
    }

    /**
     * Remove firstBatch
     *
     * @param \App\Acme\AccountBundle\Entity\Batch $firstBatch
     */
    public function removeFirstBatch(\App\Acme\AccountBundle\Entity\Batch $firstBatch) {
        $this->first_batch->removeElement($firstBatch);
    }

    /**
     * Get firstBatch
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFirstBatch() {
        return $this->first_batch;
    }

    /**
     * Add secondBatch
     *
     * @param \App\Acme\AccountBundle\Entity\Batch $secondBatch
     *
     * @return Account
     */
    public function addSecondBatch(\App\Acme\AccountBundle\Entity\Batch $secondBatch) {
        $this->second_batch[] = $secondBatch;

        return $this;
    }

    /**
     * Remove secondBatch
     *
     * @param \App\Acme\AccountBundle\Entity\Batch $secondBatch
     */
    public function removeSecondBatch(\App\Acme\AccountBundle\Entity\Batch $secondBatch) {
        $this->second_batch->removeElement($secondBatch);
    }

    /**
     * Get secondBatch
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecondBatch() {
        return $this->second_batch;
    }

    /**
     * Set person
     *
     * @param \App\Acme\ProfileBundle\Entity\Person $person
     *
     * @return Account
     */
    public function setPerson(\App\Acme\ProfileBundle\Entity\Person $person = null) {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \App\Acme\ProfileBundle\Entity\Person
     */
    public function getPerson() {
        return $this->person;
    }


    /**
     * Set shareAccount
     *
     * @param \App\Acme\AccountBundle\Entity\ShareAccount $shareAccount
     *
     * @return Account
     */
    public function setShareAccount(\App\Acme\AccountBundle\Entity\ShareAccount $shareAccount = null) {
        $this->share_account = $shareAccount;

        return $this;
    }

    /**
     * Get shareAccount
     *
     * @return \App\Acme\AccountBundle\Entity\ShareAccount
     */
    public function getShareAccount() {
        return $this->share_account;
    }


    /**
     * Add shareAccount
     *
     * @param \App\Acme\AccountBundle\Entity\ShareAccount $shareAccount
     *
     * @return Account
     */
    public function addShareAccount(\App\Acme\AccountBundle\Entity\ShareAccount $shareAccount)
    {
        $this->share_account[] = $shareAccount;

        return $this;
    }

    /**
     * Remove shareAccount
     *
     * @param \App\Acme\AccountBundle\Entity\ShareAccount $shareAccount
     */
    public function removeShareAccount(\App\Acme\AccountBundle\Entity\ShareAccount $shareAccount)
    {
        $this->share_account->removeElement($shareAccount);
    }

    /**
     * Add accountGroup
     *
     * @param \App\Acme\AccountBundle\Entity\AccountGroup $accountGroup
     *
     * @return Account
     */
    public function addAccountGroup(\App\Acme\AccountBundle\Entity\AccountGroup $accountGroup)
    {
        $accountGroup->setAccounts($this);
        $this->account_group[] = $accountGroup;

        return $this;
    }

    /**
     * Remove accountGroup
     *
     * @param \App\Acme\AccountBundle\Entity\AccountGroup $accountGroup
     */
    public function removeAccountGroup(\App\Acme\AccountBundle\Entity\AccountGroup $accountGroup)
    {
        $this->account_group->removeElement($accountGroup);
    }

    /**
     * Get accountGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountGroup()
    {
        return $this->account_group;
    }
}
