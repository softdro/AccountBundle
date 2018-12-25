<?php

namespace SDRO\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Description of Domain
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="title", message="Duplicate name found.")
 * @ORM\Table(name="account__period")
 */
class Period {

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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $end_date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enable;

    /**
     * @ORM\OneToMany(targetEntity="Ledger", mappedBy="period", cascade={"persist"})
     */
    protected $ledger;

    public function __construct() {
        $this->start_date = new \DateTime("now");
        $this->end_date = new \DateTime("now");
        $this->ledger = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Period
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Period
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Period
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Period
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Add ledger
     *
     * @param \SDRO\AccountBundle\Entity\Ledger $ledger
     *
     * @return Period
     */
    public function addLedger(\SDRO\AccountBundle\Entity\Ledger $ledger)
    {
        $this->ledger[] = $ledger;

        return $this;
    }

    /**
     * Remove ledger
     *
     * @param \SDRO\AccountBundle\Entity\Ledger $ledger
     */
    public function removeLedger(\SDRO\AccountBundle\Entity\Ledger $ledger)
    {
        $this->ledger->removeElement($ledger);
    }

    /**
     * Get ledger
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLedger()
    {
        return $this->ledger;
    }
}
