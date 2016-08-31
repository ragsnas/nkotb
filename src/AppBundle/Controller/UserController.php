<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserCreateType;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('AppBundle:User:index.html.twig', [
            'entities' => $entities
        ]);
    }
    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     */
    public function newAction(Request $request)
    {
        $userManager = $this->getUserManager();

        $entity = $userManager->createUser();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
            try {
                $user = $form->getData();
                $userManager->updateUser($user);
                $userManager->updatePassword($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'user_flash_create_success');
                return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            } catch (\Exception $e) {
                dump($e);exit;
                $this->addFlash('error', 'user_flash_create_failure');
            }
        }

        return $this->render('AppBundle:User:new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserCreateType(), $entity, array(
            'action' => $this->generateUrl('user_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return $this->render('AppBundle:User:show.html.twig', [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/edit/{id}", name="user_edit")
     */
    public function editAction(Request $request, $id)
    {
        $userManager = $this->getUserManager();
        $entity = $userManager->findUserBy(['id' => $id]);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($request->isMethod('post') && $editForm->isValid()) {
            try {
                $user = $editForm->getData();
                $userManager->updateUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'user_flash_update_success');
                return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
            } catch (\Exception $e) {
                dump($e);exit;
                $this->addFlash('error', 'user_flash_update_failure');
            }
        }

        return $this->render('AppBundle:User:edit.html.twig',[
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');
        return $userManager;
    }
}
