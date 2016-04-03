<?php

namespace Sru\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromotionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('beginAtDate')
            ->add('expireAtDate')
            ->add('enabled')
            ->add('min')
            ->add('reduction')
            ->add('picture',null , array( 'required' => false))
            ->add('category',null , array( 'required' => false))
            ->add('product',null , array( 'required' => false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sru\CoreBundle\Entity\Promotion',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sru_corebundle_promotion';
    }
}
