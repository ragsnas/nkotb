<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\TrainingRepository;

use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Loggers_ArrayLogger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InfoController extends Controller
{
    /**
     * @Route("/info/{kind}", name="info")
     */
    public function indexAction($kind)
    {
        return $this->render('AppBundle:Info:'.$kind.'.html.twig', [
            'kind' => $kind
        ]);
    }
}
