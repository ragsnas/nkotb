<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingRegistration
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TrainingRegistrationRepository")
 */
class TrainingRegistration
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var Training
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Training")
     */
    private $training;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showedUp", type="boolean", nullable=true)
     */
    private $showedUp;


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
     * Set user
     *
     * @param User $user
     * @return TrainingRegistration
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set training
     *
     * @param Training $training
     * @return TrainingRegistration
     */
    public function setTraining(Training $training)
    {
        $this->training = $training;

        return $this;
    }

    /**
     * Get training
     *
     * @return Training
     */
    public function getTraining()
    {
        return $this->training;
    }

    /**
     * Set showedUp
     *
     * @param boolean $showedUp
     * @return TrainingRegistration
     */
    public function setShowedUp($showedUp)
    {
        $this->showedUp = $showedUp;

        return $this;
    }

    /**
     * Get showedUp
     *
     * @return boolean 
     */
    public function getShowedUp()
    {
        return $this->showedUp;
    }
}
