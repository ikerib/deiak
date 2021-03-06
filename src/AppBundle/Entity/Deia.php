<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deia
 *
 * @ORM\Table(name="deia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeiaRepository")
 */
class Deia
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
     * @ORM\Column(name="userid", type="string", length=255, nullable=true)
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="teknikoa", type="string", length=255)
     */
    private $teknikoa;

    /**
     * @var string
     *
     * @ORM\Column(name="azalpena", type="text", nullable=true)
     */
    private $azalpena;


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

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getUserid();
    }

    /**
     * @ORM\ManyToOne(targetEntity="Inzidentzia", inversedBy="deiak")
     */
    private $inzidentzia;

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
     * @return Deia
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
     * Set azalpena
     *
     * @param string $azalpena
     *
     * @return Deia
     */
    public function setAzalpena($azalpena)
    {
        $this->azalpena = $azalpena;

        return $this;
    }

    /**
     * Get azalpena
     *
     * @return string
     */
    public function getAzalpena()
    {
        return $this->azalpena;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Deia
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
     * @return Deia
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
     * Set inzidentzia
     *
     * @param \AppBundle\Entity\Inzidentzia $inzidentzia
     *
     * @return Deia
     */
    public function setInzidentzia(\AppBundle\Entity\Inzidentzia $inzidentzia = null)
    {
        $this->inzidentzia = $inzidentzia;

        return $this;
    }

    /**
     * Get inzidentzia
     *
     * @return \AppBundle\Entity\Inzidentzia
     */
    public function getInzidentzia()
    {
        return $this->inzidentzia;
    }

    /**
     * Set teknikoa
     *
     * @param string $teknikoa
     *
     * @return Deia
     */
    public function setTeknikoa($teknikoa)
    {
        $this->teknikoa = $teknikoa;

        return $this;
    }

    /**
     * Get teknikoa
     *
     * @return string
     */
    public function getTeknikoa()
    {
        return $this->teknikoa;
    }
}
