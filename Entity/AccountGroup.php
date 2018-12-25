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
 * @DoctrineAssert\UniqueEntity(fields="title", message="Duplicate name found.") 
 * @ORM\Table(name="account__account_group")
 */
class AccountGroup {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var accounts
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="account_group", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $accounts;

    /**
     * @var group
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="account_group", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $groups;

    public function __construct($group = null) {
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
        if ($group)
            $this->setGroups($group);
    }

    public function __toString() {
        return (string) $this->getGroups() ? $this->getAccounts()." (" .$this->getGroups().")"  : 'n/a';
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
     * Set accounts
     *
     * @param \SDRO\AccountBundle\Entity\Account $accounts
     *
     * @return AccountGroup
     */
    public function setAccounts(\SDRO\AccountBundle\Entity\Account $accounts = null) {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * Get accounts
     *
     * @return \SDRO\AccountBundle\Entity\Account
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * Set groups
     *
     * @param \SDRO\AccountBundle\Entity\Group $groups
     *
     * @return AccountGroup
     */
    public function setGroups(\SDRO\AccountBundle\Entity\Group $groups = null) {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return \SDRO\AccountBundle\Entity\Group
     */
    public function getGroups() {
        return $this->groups;
    }

}
