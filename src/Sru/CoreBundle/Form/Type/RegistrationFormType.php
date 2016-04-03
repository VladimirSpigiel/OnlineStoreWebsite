<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 02/06/14
 * Time: 15:23
 */

namespace Sru\CoreBundle\Form\Type;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add("firstname")
            ->add("lastname")
            ->remove("username")
            ->add('cg', 'checkbox',array('mapped' => false))

            ;


    }

    public function getName()
    {
        return 'sru_corebundle_user_registration';
    }
} 