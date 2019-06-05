<?php

namespace App\Form;

use App\Entity\PortfolioIo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioIoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('netAmount', NumberType::class, ['label' => " "])
            ->add('confirmDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => " ",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PortfolioIo::class,
        ]);
    }
}
