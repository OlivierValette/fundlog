<?php

namespace App\Form;

use App\Entity\PortfolioLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qty')
            ->add('lvalue')
            ->add('ioQty')
            ->add('ioValue')
            ->add('ioHide')
            ->add('fund')
            ->add('portfolio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PortfolioLine::class,
        ]);
    }
}
