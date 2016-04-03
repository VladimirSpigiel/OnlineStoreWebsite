<?php

namespace Sru\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends UserType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("username")
            ->add("email")

            ->add("profil")
            ->add('firstname')
            ->add('lastname')

        ;

    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'sru_corebundle_user';
    }


}
