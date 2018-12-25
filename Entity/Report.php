<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * #DoctrineAssert\UniqueEntity(fields="{title}", message="Duplicate Report name found.")
 * @ORM\Table(name="account__report")
 */
class Report {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * @ORM\OneToMany(targetEntity="ReportIndex", mappedBy="report", cascade={"persist"})
     */
    protected $report_index;

    public function __construct() {
        $this->report_index = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getCode().": ".$this->getTitle() ? : 'n/a';
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
     * @return Report
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
     * Add reportIndex
     *
     * @param \SDRO\AccountBundle\Entity\ReportIndex $reportIndex
     *
     * @return Report
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

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Report
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

}
