<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Training
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TrainingRepository")
 */
class Training
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end = null;

    /**
     * @var string
     *
     * @ORM\Column(name="destinaton", type="string", length=255)
     */
    private $destinaton;

    /**
     * @var integer
     *
     * @ORM\Column(name="spots", type="integer")
     */
    private $spots;

    /**
     * @var ArrayCollection|TrainingRegistration[]
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\TrainingRegistration", mappedBy="training")
     */
    private $trainingRegistrations;

    public function __construct(){
        $this->trainingRegistrations = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Training
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
     * Set type
     *
     * @param string $type
     * @return Training
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Training
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Training
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set destinaton
     *
     * @param string $destinaton
     * @return Training
     */
    public function setDestinaton($destinaton)
    {
        $this->destinaton = $destinaton;

        return $this;
    }

    /**
     * Get destinaton
     *
     * @return string 
     */
    public function getDestinaton()
    {
        return $this->destinaton;
    }

    /**
     * Set spots
     *
     * @param integer $spots
     * @return Training
     */
    public function setSpots($spots)
    {
        $this->spots = $spots;

        return $this;
    }

    /**
     * Get spots
     *
     * @return integer 
     */
    public function getSpots()
    {
        return $this->spots;
    }

    /**
     * @return TrainingRegistration[]|ArrayCollection
     */
    public function getTrainingRegistrations()
    {
        return $this->trainingRegistrations;
    }

    /**
     * @param ArrayCollection|TrainingRegistration[] $trainingRegistrations
     */
    public function setTrainingRegistrations($trainingRegistrations)
    {
        $this->trainingRegistrations = $trainingRegistrations;
    }

    public function isUserAttending(User $user)
    {
        if($this->trainingRegistrations) {
            foreach ($this->trainingRegistrations as $trainingRegistration) {
                if ($trainingRegistration->getUser()->getId() == $user->getId()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isFullyBooked()
    {
        return ($this->trainingRegistrations && count($this->trainingRegistrations) >= $this->getSpots());
    }
}
