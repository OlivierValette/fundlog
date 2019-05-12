<?php

namespace App\Form;

use App\Entity\Fund;
use App\Entity\PortfolioLine;
use App\Repository\FundRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioLineAddIoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ioValue',NumberType::class, ['label' => " "])
            ->add('fund', EntityType::class, [
                'label' => " ",
                // looks for choices from this entity
                'class' => Fund::class,
                // uses the Fund.label property as the visible option string
                'query_builder' => function (FundRepository $repository) {
                    return $repository->createQueryBuilder('f')
                        ->orderBy('f.name', 'ASC');
                },
                'choice_label' => 'label',
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
