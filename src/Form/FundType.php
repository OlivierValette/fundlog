<?php

namespace App\Form;

use App\Entity\AssetClass;
use App\Entity\Category;
use App\Entity\Fund;
use App\Repository\AssetClassRepository;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isin',TextType::class, ['label' => "ISIN"])
            ->add('name',TextType::class, ['label' => "Nom du fonds"])
            ->add('assetClass', EntityType::class, [
                'label' => "Classe d'asset",
                // looks for choices from this entity
                'class' => AssetClass::class,
                // used property as the visible option string
                'query_builder' => function (AssetClassRepository $repository) {
                    return $repository->createQueryBuilder('a')
                        ->orderBy('a.label', 'ASC');
                },
                'choice_label' => 'label',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('category', EntityType::class, [
                'label' => "Catégorie du fonds",
                // looks for choices from this entity
                'class' => Category::class,
                // used property as the visible option string
                'query_builder' => function (CategoryRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.label', 'ASC');
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
            'data_class' => Fund::class,
        ]);
    }
}
