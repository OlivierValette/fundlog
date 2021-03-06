<?php

namespace App\Form;

use App\Entity\PortfolioLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('ioQty',NumberType::class, ['label' => " "])
            ->add('ioValue',NumberType::class, [
                'label' => "Nouvelle valeur à arbitrer (€)"
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
