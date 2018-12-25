<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * @ORM\Table(name="account__invoice_ledger")
 */
class InvoiceLedger {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="invoice_ledger", cascade={"persist"})
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    protected $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="Ledger", inversedBy="invoice_ledger", cascade={"persist"})
     * @ORM\JoinColumn(name="ledger_id", referencedColumnName="id")
     */
    protected $ledger;

    public function __construct($invoice) {
//        $this->created = new \DateTime("now");
        $this->setInvoice($invoice);
    }

    public function __toString() {
        return $this->getInvoice()? : 'n/a';
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
     * Set invoice
     *
     * @param \SDRO\AccountBundle\Entity\Invoice $invoice
     *
     * @return InvoiceLedger
     */
    public function setInvoice(\SDRO\AccountBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \SDRO\AccountBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set ledger
     *
     * @param \SDRO\AccountBundle\Entity\Ledger $ledger
     *
     * @return InvoiceLedger
     */
    public function setLedger(\SDRO\AccountBundle\Entity\Ledger $ledger = null)
    {
        $this->ledger = $ledger;

        return $this;
    }

    /**
     * Get ledger
     *
     * @return \SDRO\AccountBundle\Entity\Ledger
     */
    public function getLedger()
    {
        return $this->ledger;
    }
}
