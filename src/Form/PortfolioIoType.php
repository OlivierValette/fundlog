<?php

namespace App\Form;

use App\Entity\PortfolioIo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioIoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('netAmount')
            ->add('creationDate')
            ->add('updateDate')
            ->add('validDate')
            ->add('sendDate')
            ->add('confirmDate')
            ->add('portfolio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PortfolioIo::class,
        ]);
    }
}
