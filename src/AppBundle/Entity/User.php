<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDoorkeys()
    {
        return $this->doorkeys;
    }

    /**
     * @param mixed $doorkeys
     */
    public function setDoorkeys($doorkeys)
    {
        $this->doorkeys = $doorkeys;
    }

    /**
     * @return DoorkeyStatus
     */
    public function getDoorkeyStatuses()
    {
        return $this->doorkeyStatuses;
    }

    /**
     * @param DoorkeyStatus $doorkeyStatuses
     */
    public function setDoorkeyStatuses($doorkeyStatuses)
    {
        $this->doorkeyStatuses = $doorkeyStatuses;
    }
}
