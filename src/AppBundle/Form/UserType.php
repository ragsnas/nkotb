<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('role_choices', 'choice', [
                'mapped' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'ROLE_USER' => 'user_roles_role_user',
                    'ROLE_USER_ADMIN' => 'user_roles_role_user_admin',
                    'ROLE_TRAINING_OBSERVER' => 'user_roles_role_training_observer',
                    'ROLE_TRAINING_ADMIN' => 'user_roles_role_training_admin'
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $roleChoices = $event->getForm()->get('role_choices')->getData();
                if(!in_array('ROLE_USER', $roleChoices)) {
                    $roleCoices[] = 'ROLE_USER';
                }
                /** @var User $user */
                $user = $event->getForm()->getData();
                $currentUserRoles = $user->getRoles();
                foreach($currentUserRoles as $currentRole) {
                    if(!in_array($currentRole, $roleChoices)) {
                        $user->removeRole($currentRole);
                    }
                }
                foreach ($roleChoices as $role) {
                    $user->addRole($role);
                }
            })
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                /** @var User $user */
                $user = $event->getForm()->getData();
                $roleChoices = $event->getForm()->get('role_choices')->setData($user->getRoles());
            });
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }
}
