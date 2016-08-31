<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\TrainingRegistrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\TrainingRegistration;
use AppBundle\Form\TrainingRegistrationType;

/**
 * TrainingRegistration controller.
 *
 * @Route("/admin/training-registration")
 */
class TrainingRegistrationController extends Controller
{

    /**
     * Lists all TrainingRegistration entities.
     *
     * @Route("/", name="training_registration_admin")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var TrainingRegistrationRepository $trainingRegistrationRepo */
        $trainingRegistrationRepo = $em->getRepository('AppBundle:TrainingRegistration');
        $query = $trainingRegistrationRepo->getRegistrationsOrderedByDateDescending()->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1));

        return $this->render('AppBundle:TrainingRegistration:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new TrainingRegistration entity.
     *
     * @Route("/new", name="training_registration_admin_new")
     */
    public function newAction(Request $request)
    {
        $entity = new TrainingRegistration();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'trainingregistration_edit_success');

            return $this->redirect($this->generateUrl('training_registration_admin_show', array('id' => $entity->getId())));
            } catch (\Exception $e) {
                $this->get('logger')->addError('trainingregistration_edit_error: ' . $e->getMessage());
                $this->addFlash('error', 'trainingregistration_edit_error');
            }


        }

    return $this->render('AppBundle:TrainingRegistration:new.html.twig', array(
        'entity' => $entity,
        'form'   => $form->createView(),
    ));
    }

    /**
     * Creates a form to create a TrainingRegistration entity.
     *
     * @param TrainingRegistration $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TrainingRegistration $entity)
    {
        $form = $this->createForm(new TrainingRegistrationType(), $entity, array(
            'action' => $this->generateUrl('training_registration_admin_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a TrainingRegistration entity.
     *
     * @Route("/show/{id}", name="training_registration_admin_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TrainingRegistration')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TrainingRegistration entity.');
        }
        $deleteForm = $this->createDeleteForm($id);

    return $this->render('AppBundle:TrainingRegistration:show.html.twig', array(
        'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            ));
    }
    /**
     * Edits an existing TrainingRegistration entity.
     *
     * @Route("/edit/{id}", name="training_registration_admin_edit")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TrainingRegistration')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TrainingRegistration entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $em->flush();
                $this->addFlash('success', 'trainingregistration_edit_success');
            } catch (\Exception $e) {
                $this->get('logger')->addError('trainingregistration_edit_error: ' . $e->getMessage());
                $this->addFlash('error', 'trainingregistration_edit_error');
            }
            return $this->redirect($this->generateUrl('training_registration_admin_edit', array('id' => $id)));
        }

    return $this->render('AppBundle:TrainingRegistration:edit.html.twig', array(
        'entity'      => $entity,
        'edit_form'   => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
    ));
    }

    /**
    * Creates a form to edit a TrainingRegistration entity.
    *
    * @param TrainingRegistration $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TrainingRegistration $entity)
    {
        $form = $this->createForm(new TrainingRegistrationType(), $entity, array(
        'action' => $this->generateUrl('training_registration_admin_edit', array('id' => $entity->getId())),
        'method' => 'PUT',
    ));

    $form->add('submit', 'submit', array('label' => 'common_update'));

    return $form;
    }

    /**
     * Deletes a TrainingRegistration entity.
     *
     * @Route("/delete{id}", name="training_registration_admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:TrainingRegistration')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TrainingRegistration entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('training_registration_admin'));
    }

    /**
     * Deletes a TrainingRegistration entity.
     *
     * @Route("/showed-up/{id}", name="training_registration_admin_set_showedup", defaults={"showedUp" = true})
     * @Route("/did-not-show-up/{id}", name="training_registration_admin_set_notshowedup", defaults={"showedUp" = false})
     */
    public function markAsShowedUpOrNot(Request $request, $id, $showedUp)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TrainingRegistration $entity */
        $entity = $em->getRepository('AppBundle:TrainingRegistration')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TrainingRegistration entity.');
        }

        $entity->setShowedUp($showedUp);
        $em->flush();

        return $this->redirect($this->generateUrl('training_admin_show', ['id' => $entity->getTraining()->getId()]));
    }

    /**
     * Creates a form to delete a TrainingRegistration entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('training_registration_admin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
