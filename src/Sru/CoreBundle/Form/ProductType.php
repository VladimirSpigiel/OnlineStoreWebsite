<?php

namespace Sru\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref')
            ->add('ean')
            ->add('name')
            ->add('shortDescription')
            ->add('description')
            ->add('weight')
            ->add('priceHt')
            ->add('priceTtc',null,array('by_reference' => false))
            ->add('priceProvider')
            ->add('ecoParticipation')
            ->add('keywords')
            ->add('stock')



            ->add('margin')
            ->add('provider')

            ->add('category',null , array( 'required' => false))

            ->add('feature',null , array( 'required' => false))
            ->add('picture',null , array( 'required' => false))
            ->add('brand',null , array( 'required' => false))
            ->add('tva')


        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sru\CoreBundle\Entity\Product',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sru_corebundle_product';
    }
}
