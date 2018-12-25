<?php

namespace App\Acme\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * #DoctrineAssert\UniqueEntity(fields="title", message="Duplicate product name found") 
 * @ORM\Table(name="account__invoice")
 */
class Invoice {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="invoice", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $note;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $discount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $discount_type;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $vat;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $amount;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $due;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status = 'pending';

    /**
     * @ORM\OneToMany(targetEntity="InvoiceLedger", mappedBy="invoice", cascade={"persist"})
     */
    protected $invoice_ledger;

    /**
     * @ORM\OneToOne(targetEntity="App\Acme\SalesBundle\Entity\Sales", mappedBy="invoice", cascade={"persist"})
     */
    protected $sales;

    public function __construct() {
        $this->created = new \DateTime("now");
        $this->invoice_ledger = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {

        return $this->getTitle() ? : 'n/a';
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
     * @return Invoice
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
     * Set note
     *
     * @param string $note
     *
     * @return Invoice
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return Invoice
     */
    public function setDiscount($discount) {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount() {
        return $this->discount;
    }

    /**
     * Set discountType
     *
     * @param string $discountType
     *
     * @return Invoice
     */
    public function setDiscountType($discountType) {
        $this->discount_type = $discountType;

        return $this;
    }

    /**
     * Get discountType
     *
     * @return string
     */
    public function getDiscountType() {
        return $this->discount_type;
    }

    /**
     * Set vat
     *
     * @param string $vat
     *
     * @return Invoice
     */
    public function setVat($vat) {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat() {
        return $this->vat;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Invoice
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set due
     *
     * @param string $due
     *
     * @return Invoice
     */
    public function setDue($due) {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due
     *
     * @return string
     */
    public function getDue() {
        return $this->due;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Invoice
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

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Invoice
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set account
     *
     * @param \App\Acme\AccountBundle\Entity\Account $account
     *
     * @return Invoice
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
     * @return Invoice
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


    /**
     * Set sales
     *
     * @param \App\Acme\SalesBundle\Entity\Sales $sales
     *
     * @return Invoice
     */
    public function setSales(\App\Acme\SalesBundle\Entity\Sales $sales = null)
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * Get sales
     *
     * @return \App\Acme\SalesBundle\Entity\Sales
     */
    public function getSales()
    {
        return $this->sales;
    }
}
