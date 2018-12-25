<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks  
 * @ORM\Entity
 * @UniqueEntity(fields="title", message="Duplicate name found.") 
 * @ORM\Table(name="account__group")
 */
class Group {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $code;

    /**
     * @ORM\OneToMany(targetEntity="AccountGroup", mappedBy="groups", cascade={"persist"})
     */
    protected $account_group;

    /**
     * @ORM\OneToMany(targetEntity="ReportIndex", mappedBy="groups", cascade={"persist"})
     */
    protected $report_index;

    public function __construct() {
        $this->account_group = new \Doctrine\Common\Collections\ArrayCollection();
        $this->report_index = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle() ?: 'n/a';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Group
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
     * Set title
     *
     * @param string $title
     *
     * @return Group
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
     * Add accountGroup
     *
     * @param \SDRO\AccountBundle\Entity\AccountGroup $accountGroup
     *
     * @return Group
     */
    public function addAccountGroup(\SDRO\AccountBundle\Entity\AccountGroup $accountGroup) {
        $accountGroup->setAccount($this);

        $this->account_group[] = $accountGroup;

        return $this;
    }

    /**
     * Remove accountGroup
     *
     * @param \SDRO\AccountBundle\Entity\AccountGroup $accountGroup
     */
    public function removeAccountGroup(\SDRO\AccountBundle\Entity\AccountGroup $accountGroup) {
        $this->account_group->removeElement($accountGroup);
    }

    /**
     * Get accountGroup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountGroup() {
        return $this->account_group;
    }

    /**
     * Add reportIndex
     *
     * @param \SDRO\AccountBundle\Entity\ReportIndex $reportIndex
     *
     * @return Group
     */
    public function addReportIndex(\SDRO\AccountBundle\Entity\ReportIndex $reportIndex) {
        $this->report_index[] = $reportIndex;

        return $this;
    }

    /**
     * Remove reportIndex
     *
     * @param \SDRO\AccountBundle\Entity\ReportIndex $reportIndex
     */
    public function removeReportIndex(\SDRO\AccountBundle\Entity\ReportIndex $reportIndex) {
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

}
