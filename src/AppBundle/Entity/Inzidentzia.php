<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inzidentzia
 *
 * @ORM\Table(name="inzidentzia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InzidentziaRepository")
 */
class Inzidentzia
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="userid", type="string", length=255)
     */
    private $userid;

    /**
     * @var bool
     *
     * @ORM\Column(name="konpondua", type="boolean", nullable=true)
     */
    private $konpondua;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */

    /**
     * @ORM\OneToMany(targetEntity="Deia", mappedBy="inzidentzia")
     */
    private $deiak;

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getUserid();
    }
    
    /**
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     * ***** ERLAZIOAK
     * ************************************************************************************************************************************************************************
     * ************************************************************************************************************************************************************************
     */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userid
     *
     * @param string $userid
     *
     * @return Inzidentzia
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return string
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set konpondua
     *
     * @param boolean $konpondua
     *
     * @return Inzidentzia
     */
    public function setKonpondua($konpondua)
    {
        $this->konpondua = $konpondua;

        return $this;
    }

    /**
     * Get konpondua
     *
     * @return boolean
     */
    public function getKonpondua()
    {
        return $this->konpondua;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Inzidentzia
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Inzidentzia
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add deiak
     *
     * @param \AppBundle\Entity\Deia $deiak
     *
     * @return Inzidentzia
     */
    public function addDeiak(\AppBundle\Entity\Deia $deiak)
    {
        $this->deiak[] = $deiak;

        return $this;
    }

    /**
     * Remove deiak
     *
     * @param \AppBundle\Entity\Deia $deiak
     */
    public function removeDeiak(\AppBundle\Entity\Deia $deiak)
    {
        $this->deiak->removeElement($deiak);
    }

    /**
     * Get deiak
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeiak()
    {
        return $this->deiak;
    }
}
