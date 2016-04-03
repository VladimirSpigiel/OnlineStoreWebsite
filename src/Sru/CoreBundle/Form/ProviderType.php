<?php

namespace Sru\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProviderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('city')
            ->add('country')
            ->add('zipCode')
            ->add('street')
            ->add('siret')
            ->add('memo', null,  array( 'required' => false))
            ->add('picture')
            ->add('transport', null,  array( 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sru\CoreBundle\Entity\Provider',
            'csrf_protection' => false,

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sru_corebundle_provider';
    }
}
