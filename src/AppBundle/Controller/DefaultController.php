<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\TrainingRepository;

use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Loggers_ArrayLogger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TrainingRepository $trainingRepo */
        $trainingRepo = $em->getRepository('AppBundle:Training');
        $trainings = $trainingRepo->getFullCurrentTrainings();

        /** @var UserManagerInterface $userManager */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername('rags');
        if (!$user->hasRole('ROLE_ADMIN')) { $user->addRole('ROLE_ADMIN'); }
        if (!$user->hasRole('ROLE_SUPER_ADMIN')) { $user->addRole('ROLE_SUPER_ADMIN'); }
        if (!$user->hasRole('ROLE_TRAINING_ADMIN')) { $user->addRole('ROLE_TRAINING_ADMIN'); }
        if (!$user->hasRole('ROLE_TRAINING_OBSERVER')) { $user->addRole('ROLE_TRAINING_OBSERVER'); }
        $userManager->updateUser($user);

        return $this->render('AppBundle:Default:index.html.twig', [
            'trainings' => $trainings
        ]);
    }
}
