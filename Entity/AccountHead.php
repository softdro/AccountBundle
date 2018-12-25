<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks  
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="code", message="Duplicate code found.") 
 * @DoctrineAssert\UniqueEntity(fields="title", message="Duplicate name found.") 
 * @ORM\Table(name="account__account_head")
 */
class AccountHead {

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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="account_head", cascade={"persist"})
     */
    protected $account;

    public function __construct() {
        $this->account = new ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle()." (".$this->getCode().")" ? : 'n/a';
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
     * @return AccountHead
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return AccountHead
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add account
     *
     * @param \SDRO\AccountBundle\Entity\Account $account
     *
     * @return AccountHead
     */
    public function addAccount(\SDRO\AccountBundle\Entity\Account $account)
    {
        $this->account[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param \SDRO\AccountBundle\Entity\Account $account
     */
    public function removeAccount(\SDRO\AccountBundle\Entity\Account $account)
    {
        $this->account->removeElement($account);
    }

    /**
     * Get account
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccount()
    {
        return $this->account;
    }
}
