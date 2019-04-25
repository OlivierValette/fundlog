<?php

namespace App\Form;

use App\Entity\Fund;
use App\Entity\PortfolioLine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ioQty',NumberType::class, ['label' => " "])
            ->add('ioValue',NumberType::class, ['label' => " "])
            ->add('fund', EntityType::class, [
                'label' => " ",
                // looks for choices from this entity
                'class' => Fund::class,
                // uses the Fund.isin property as the visible option string
                'choice_label' => 'ISIN',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
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
