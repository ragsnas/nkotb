<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Training;
use AppBundle\Form\TrainingType;

/**
 * Training controller.
 *
 * @Route("/training")
 */
class TrainingController extends Controller
{

    /**
     * Lists all Training entities.
     *
     * @Route("/list", name="training")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Training')->findAll();

        return $this->render('AppBundle:Training:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Training entity.
     *
     * @Route("/show/{id}", name="training_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }

        return $this->render('AppBundle:Training:show.html.twig', [
            'entity' => $entity,
        ]);
    }
}
