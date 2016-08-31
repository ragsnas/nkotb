<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\TrainingRegistrationRepository;
use AppBundle\Entity\Repository\TrainingRepository;
use AppBundle\Entity\Training;
use AppBundle\Entity\TrainingRegistration;
use AppBundle\Form\TrainingRegistrationSimpleType;
use AppBundle\Form\TrainingRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class RegisterForTrainingController extends Controller
{
    /**
     * register for Training.
     *
     * @Route("/register-for-training/{trainingId}", name="register_for_training")
     */
    public function registerAction(Request $request, $trainingId)
    {

        /** @var TrainingRepository $trainingRepo */
        $trainingRepo = $this->getDoctrine()->getRepository('AppBundle:Training');
        /** @var TrainingRegistrationRepository $trainingRegistrationRepo */
        $trainingRegistrationRepo = $this->getDoctrine()->getRepository('AppBundle:TrainingRegistration');

        /** @var Training $training */
        $training = $trainingRepo->getFullCurrentTrainingEntityById($trainingId);

        $trainingRegistration = new TrainingRegistration();
        $trainingRegistration->setUser($this->getUser())->setTraining($training);

        $registerForm = $this->createTrainingRegistrationForm($trainingRegistration);
        $registerForm->handleRequest($request);

        if($registerForm->isValid()) {
            try {
                $trainingRegistrationRepo->storeTrainingRegistration($trainingRegistration);
                $this->addFlash('success', 'training_register_registered_successfully');
                return $this->redirectToRoute('homepage');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

        }

        return $this->render('AppBundle:RegisterForTraining:register.html.twig', [
            'training' => $training,
            'registerForm' => $registerForm->createView()
        ]);

    }

    /**
     * register for Training.
     *
     * @Route("/un-register-for-training/{trainingId}", name="unregister_for_training")
     */
    public function unregisterAction(Request $request, $trainingId)
    {

        /** @var TrainingRepository $trainingRepo */
        $trainingRepo = $this->getDoctrine()->getRepository('AppBundle:Training');
        /** @var TrainingRegistrationRepository $trainingRegistrationRepo */
        $trainingRegistrationRepo = $this->getDoctrine()->getRepository('AppBundle:TrainingRegistration');

        /** @var Training $training */
        $training = $trainingRepo->getFullCurrentTrainingEntityById($trainingId);
        $trainingRegistration =
          $trainingRegistrationRepo->getRegistrationForTrainingAndUser($training->getId(), $this->getUser())
            ->getQuery()
            ->getOneOrNullResult();
//        dump($trainingRegistrationRepo->removeTrainingRegistration($trainingRegistration[0]));exit;

        $registerForm = $this->createTrainingRegistrationForm($trainingRegistration);
        $registerForm->handleRequest($request);

        if($registerForm->isValid()) {
            try {
                $trainingRegistrationRepo->removeTrainingRegistration($trainingRegistration);
                $this->addFlash('success', 'training_register_unregistered_successfully');
                return $this->redirectToRoute('homepage');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

        }

        return $this->render('AppBundle:RegisterForTraining:unregister.html.twig', [
            'training' => $training,
            'registerForm' => $registerForm->createView()
        ]);

    }

    /**
     * @param $trainingRegistration
     * @return \Symfony\Component\Form\Form
     */
    private function createTrainingRegistrationForm($trainingRegistration)
    {
        $registerForm = $this->createForm(new TrainingRegistrationSimpleType(), $trainingRegistration);
        $registerForm->add('submit', 'submit', array('label' => 'training_registration_form_submit'));
        return $registerForm;
    }
}
