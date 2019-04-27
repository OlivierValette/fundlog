<?php

namespace App\Form;

use App\Entity\PortfolioLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioLineConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qty',NumberType::class, [
                'label' => "Qté nette",
            ])
            ->add('lvalue',NumberType::class, [
                'label' => "Valeur liquidative (€)",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PortfolioLine::class,
        ]);
    }
}
