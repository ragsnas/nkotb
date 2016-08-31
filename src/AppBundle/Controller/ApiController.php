<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AppointmentRepository;
use AppBundle\Entity\Repository\AbstractAppointmentRepository;
use AppBundle\Services\CalendarEventService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ApiController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * Lists all Appointment entities.
     *
     * @Route("/calendar.json", name="api_calendar_json")
     */
    public function indexAction()
    {

        return $this->render('AppBundle:Appointment:index.html.twig', array(
            'entities' => [],
        ));
    }


}
