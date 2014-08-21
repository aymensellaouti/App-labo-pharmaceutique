<?php

namespace Aym3ntn\UserBundle\Entity;

use Aym3ntn\MedecinBundle\Entity\Secteur;
use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="Aym3ntn\UserBundle\Entity\NoteRepository")
 */
class Note
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="public", type="integer")
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text")
     */
    private $descr;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=255)
     */
    private $userId;

    /**
     * @ORM\ManyToMany(targetEntity="Aym3ntn\UserBundle\Entity\User")
     */
    private $targets;

    /**
     * @var string
     *
     * @ORM\Column(name="related_to", type="string", length=255, nullable=true)
     */
    private $relatedTo;

    /**
     * @ORM\ManyToOne(targetEntity="Aym3ntn\MedecinBundle\Entity\Secteur")
     */
    private $secteur;


    public function __construct() //$public = 1, $descr = '',  $secteur = null, $relatedTo = '', $type = '', $userId = '', $isDone = true)
    {
        $this->targets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addTarget(\Aym3ntn\UserBundle\Entity\User $targets)
    {
        $this->targets[] = $targets;
        return $this;
    }

    public function removeTarget(\Aym3ntn\UserBundle\Entity\User $target)
    {
        $this->targets->removeElement($target);
    }

    public function getTargets()
    {
        return $this->targets;
    }

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
     * Set public
     *
     * @param integer $public
     * @return Note
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return integer 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set descr
     *
     * @param string $descr
     * @return Note
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Secteur
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * @param Secteur $secteur
     */
    public function setSecteur(Secteur $secteur)
    {
        $this->secteur = $secteur;
    }

    /**
     * @param string $relatedTo
     */
    public function setRelatedTo($relatedTo)
    {
        $this->relatedTo = $relatedTo;
    }

    /**
     * @return string
     */
    public function getRelatedTo()
    {
        return $this->relatedTo;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
