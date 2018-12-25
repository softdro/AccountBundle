<?php

namespace App\Acme\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields={"account","report"}, message="Duplicate Account Title under Report.")
 * @ORM\Table(name="account__report_index")
 */
class ReportIndex {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Report", inversedBy="report_index", cascade={"persist"})
     * @ORM\JoinColumn(name="report_id", referencedColumnName="id")
     */
    protected $report;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="report_index", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @var account_group
     * @ORM\ManyToOne(targetEntity="AccountHead", cascade={"persist"})
     * @ORM\JoinColumn(name="account_head_id", referencedColumnName="id")
     */
    protected $account_head;

    /**
     * @var account_group
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="report_index", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $groups;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $group_title;

    /**
     * @ORM\Column(type="integer")
     */
    protected $priority;

    public function __construct() {
//        $this->person = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return (string) $this->getAccount() ? : 'n/a';
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
     * Set groupTitle
     *
     * @param string $groupTitle
     *
     * @return ReportIndex
     */
    public function setGroupTitle($groupTitle)
    {
        $this->group_title = $groupTitle;

        return $this;
    }

    /**
     * Get groupTitle
     *
     * @return string
     */
    public function getGroupTitle()
    {
        return $this->group_title;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return ReportIndex
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set report
     *
     * @param \App\Acme\AccountBundle\Entity\Report $report
     *
     * @return ReportIndex
     */
    public function setReport(\App\Acme\AccountBundle\Entity\Report $report = null)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return \App\Acme\AccountBundle\Entity\Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set account
     *
     * @param \App\Acme\AccountBundle\Entity\Account $account
     *
     * @return ReportIndex
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

    /**
     * Set accountHead
     *
     * @param \App\Acme\AccountBundle\Entity\AccountHead $accountHead
     *
     * @return ReportIndex
     */
    public function setAccountHead(\App\Acme\AccountBundle\Entity\AccountHead $accountHead = null)
    {
        $this->account_head = $accountHead;

        return $this;
    }

    /**
     * Get accountHead
     *
     * @return \App\Acme\AccountBundle\Entity\AccountHead
     */
    public function getAccountHead()
    {
        return $this->account_head;
    }

    /**
     * Set groups
     *
     * @param \App\Acme\AccountBundle\Entity\Group $groups
     *
     * @return ReportIndex
     */
    public function setGroups(\App\Acme\AccountBundle\Entity\Group $groups = null)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return \App\Acme\AccountBundle\Entity\Group
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
