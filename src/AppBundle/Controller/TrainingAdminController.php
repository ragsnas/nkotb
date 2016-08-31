<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\TrainingRegistrationRepository;
use AppBundle\Entity\Repository\TrainingRepository;
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
 * @Route("/admin/training")
 */
class TrainingAdminController extends Controller
{

    /**
     * Lists all Training entities.
     *
     * @Route("/trainings", name="training_admin")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var TrainingRepository $trainingRepo */
        $trainingRepo = $em->getRepository('AppBundle:Training');
        $query = $trainingRepo->getFullCurrentTrainingQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1));

        return $this->render('AppBundle:TrainingAdmin:index.html.twig', array(
            'pagination' => $pagination,
        ));
        
        
        return $this->render('AppBundle:TrainingAdmin:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Training entity.
     *
     * @Route("/new", name="training_admin_new")
     */
    public function newAction(Request $request)
    {
        $entity = new Training();
        $entity->setStart(new \DateTime('now'));
        $entity->setEnd(new \DateTime('now +2 hours'));

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'training_edit_success');

            return $this->redirect($this->generateUrl('training_admin_show', array('id' => $entity->getId())));
            } catch (\Exception $e) {
                $this->get('logger')->addError('training_edit_error: ' . $e->getMessage());
                $this->addFlash('error', 'training_edit_error');
            }


        }

    return $this->render('AppBundle:TrainingAdmin:new.html.twig', array(
        'entity' => $entity,
        'form'   => $form->createView(),
    ));
    }

    /**
     * Creates a form to create a Training entity.
     *
     * @param Training $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Training $entity)
    {
        $form = $this->createForm(new TrainingType(), $entity, array(
            'action' => $this->generateUrl('training_admin_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Training entity.
     *
     * @Route("/show/{id}", name="training_admin_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }
        $deleteForm = $this->createDeleteForm($id);

        /** @var TrainingRegistrationRepository $trainingRegistrationRepo */
        $trainingRegistrationRepo = $em->getRepository('AppBundle:TrainingRegistration');
        $registrations = $trainingRegistrationRepo->getRegistrationsForSingleTraining($id)->getQuery()->getResult();

        return $this->render('AppBundle:TrainingAdmin:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'registrations' => $registrations,
        ));
    }
    /**
     * Edits an existing Training entity.
     *
     * @Route("/edit/{id}", name="training_admin_edit")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Training')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Training entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            try {
                $em->flush();
                $this->addFlash('success', 'training_edit_success');
            } catch (\Exception $e) {
                $this->get('logger')->addError('training_edit_error: ' . $e->getMessage());
                $this->addFlash('error', 'training_edit_error');
            }
            return $this->redirect($this->generateUrl('training_admin_edit', array('id' => $id)));
        }

    return $this->render('AppBundle:TrainingAdmin:edit.html.twig', array(
        'entity'      => $entity,
        'edit_form'   => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
    ));
    }

    /**
    * Creates a form to edit a Training entity.
    *
    * @param Training $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Training $entity)
    {
        $form = $this->createForm(new TrainingType(), $entity, array(
        'action' => $this->generateUrl('training_admin_edit', array('id' => $entity->getId())),
        'method' => 'PUT',
    ));

    $form->add('submit', 'submit', array('label' => 'common_update'));

    return $form;
    }
    /**
     * Deletes a Training entity.
     *
     * @Route("/delete{id}", name="training_admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Training')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Training entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('training_admin'));
    }

    /**
     * Creates a form to delete a Training entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('training_admin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
