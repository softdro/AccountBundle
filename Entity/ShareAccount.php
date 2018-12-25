<?php

namespace App\Acme\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\Entity
 * #DoctrineAssert\UniqueEntity(fields="account", message="Duplicate account name")
 * @ORM\Table(name="account__share_account")
 */
class ShareAccount {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Account", inversedBy="share_account", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $investment;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $percentage;

    public function __construct() {

//        $this->invoice_ledger = new \Doctrine\Common\Collections\ArrayCollection();
        $this->investment = 0;
    }

    public function __toString() {
        return (string) $this->getAccount() . "(" . $this->getInvestment() . ")" ? : 'n/a ';
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
     * Set investment
     *
     * @param string $investment
     *
     * @return ShareAccount
     */
    public function setInvestment($investment)
    {
        $this->investment = $investment;

        return $this;
    }

    /**
     * Get investment
     *
     * @return string
     */
    public function getInvestment()
    {
        return $this->investment;
    }

    /**
     * Set percentage
     *
     * @param string $percentage
     *
     * @return ShareAccount
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get percentage
     *
     * @return string
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set account
     *
     * @param \App\Acme\AccountBundle\Entity\Account $account
     *
     * @return ShareAccount
     */
    public function setAccount(\App\Acme\AccountBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \App\Acme\AccountBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
