<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="SDRO\AccountBundle\Entity\AccountRepository")
 * @DoctrineAssert\UniqueEntity(fields="code", message="Duplicate ACCOUNT TITLE already added.") 
 * #DoctrineAssert\UniqueEntity(fields={"first_account", "first_cr", "second_account"}, message="Duplicate ACCOUNT TITLE already added.")
 * @ORM\Table(name="account__batch")
 */
class Batch {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $code;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="first_batch", cascade={"persist"})
     * @ORM\JoinColumn(name="first_account_id", referencedColumnName="id")
     */
    protected $first_account;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $first_cr;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="second_batch", cascade={"persist"})
     * @ORM\JoinColumn(name="second_account_id", referencedColumnName="id")
     */
    protected $second_account;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $second_cr;

    /**
     * @ORM\OneToOne(targetEntity="Batch", cascade={"persist"})
     * @ORM\JoinColumn(name="ref_batch_id", referencedColumnName="id")
     */
    protected $ref_batch;

//    public function __construct() {
////        $this->ledger = new \Doctrine\Common\Collections\ArrayCollection();
//    }

    public function __toString() {
        $fcr = $this->getFirstCr() ? "Cr" : "Dr";
        $scr = $this->getSecondCr() ? "Cr" : "Dr";
        return (string) $this->getFirstAccount() ? $this->getFirstAccount() . " ($fcr) => " . $this->getSecondAccount() . " ($scr)" : 'n/a';
    }

    /**
     * 
     * @param type $first_account
     * @param type $second_account
     */
    public function __construct($first_account, $second_account) {
//         parent::__construct();
        $this->first_account = $first_account;
        $this->second_account = $second_account;
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
     * Set code
     *
     * @param string $code
     *
     * @return Batch
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
     * Set firstCr
     *
     * @param boolean $firstCr
     *
     * @return Batch
     */
    public function setFirstCr($firstCr) {
        $this->first_cr = $firstCr;

        return $this;
    }

    /**
     * Get firstCr
     *
     * @return boolean
     */
    public function getFirstCr() {
        return $this->first_cr;
    }

    /**
     * Set secondCr
     *
     * @param boolean $secondCr
     *
     * @return Batch
     */
    public function setSecondCr($secondCr) {
        $this->second_cr = $secondCr;

        return $this;
    }

    /**
     * Get secondCr
     *
     * @return boolean
     */
    public function getSecondCr() {
        return $this->second_cr;
    }

    /**
     * Set firstAccount
     *
     * @param \SDRO\AccountBundle\Entity\Account $firstAccount
     *
     * @return Batch
     */
    public function setFirstAccount(\SDRO\AccountBundle\Entity\Account $firstAccount = null) {
        $this->first_account = $firstAccount;

        return $this;
    }

    /**
     * Get firstAccount
     *
     * @return \SDRO\AccountBundle\Entity\Account
     */
    public function getFirstAccount() {
        return $this->first_account;
    }

    /**
     * Set secondAccount
     *
     * @param \SDRO\AccountBundle\Entity\Account $secondAccount
     *
     * @return Batch
     */
    public function setSecondAccount(\SDRO\AccountBundle\Entity\Account $secondAccount = null) {
        $this->second_account = $secondAccount;

        return $this;
    }

    /**
     * Get secondAccount
     *
     * @return \SDRO\AccountBundle\Entity\Account
     */
    public function getSecondAccount() {
        return $this->second_account;
    }

    /**
     * Set refBatch
     *
     * @param \SDRO\AccountBundle\Entity\Batch $refBatch
     *
     * @return Batch
     */
    public function setRefBatch(\SDRO\AccountBundle\Entity\Batch $refBatch = null) {
        $this->ref_batch = $refBatch;

        return $this;
    }

    /**
     * Get refBatch
     *
     * @return \SDRO\AccountBundle\Entity\Batch
     */
    public function getRefBatch() {
        return $this->ref_batch;
    }

    /**
     * Add ledger
     *
     * @param \SDRO\AccountBundle\Entity\Ledger $ledger
     *
     * @return Batch
     */
    public function addLedger(\SDRO\AccountBundle\Entity\Ledger $ledger) {
        $this->ledger[] = $ledger;

        return $this;
    }

    /**
     * Remove ledger
     *
     * @param \SDRO\AccountBundle\Entity\Ledger $ledger
     */
    public function removeLedger(\SDRO\AccountBundle\Entity\Ledger $ledger) {
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

}
