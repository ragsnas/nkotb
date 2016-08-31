<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function indexAction()
    {

        if($this->getUser()) {
          return $this->redirectToRoute('homepage');
        }

        /** @var UserManagerInterface $um */
        $um = $this->get('fos_user.user_manager');
        $rags = $um->findUserByUsername('rags');

        if(!$rags) {
            $user = $um->createUser();
            $user->setUsername('rags')
              ->setEmail('ragsnas@gmail.com')
              ->setPlainPassword('am29070228')
              ->setSuperAdmin(true)
              ->setRoles([
                  'ROLE_ADMIN',
                  'ROLE_SUPER_ADMIN',
                  'ROLE_TRAINING_ADMIN'
              ])
              ->setEnabled(true);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('AppBundle:Welcome:index.html.twig', [
            'supportedLanguages' => $this->getSupportedLanguages()
        ]);
    }

    /**
     * @Route("/intro/{_locale}", name="welcome_intro")
     */
    public function introAction($_locale)
    {
        return $this->render('AppBundle:Welcome:intro.html.twig', [
            'forced_locale' => $_locale,
            'supportedLanguages' => $this->getSupportedLanguages()
        ]);
    }

    /**
     * @Route("/intro/{_locale}/{step}", name="welcome_intro_step")
     */
    public function introStepAction(Request $request, $step)
    {

        return $this->render('AppBundle:Welcome:intro_step_' . $step . '.html.twig', [
            'step' => $step,
            'forced_locale' => $request->getLocale(),
            'supportedLanguages' => $this->getSupportedLanguages()
        ]);
    }

    private function getSupportedLanguages(){
        return ['de', 'en'];
    }
}
