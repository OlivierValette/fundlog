<?php

namespace App\Form;

use App\Entity\Lifeinsurance;
use App\Entity\Middleman;
use App\Entity\Portfolio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => "Nom du portefeuille"])
            ->add('account', TextType::class, ['label' => "N° de compte/contrat"])
            ->add('middleman', EntityType::class, [
                'label' => "Intermédiaire",
                // looks for choices from this entity
                'class' => Middleman::class,
                // uses the Middleman.company property as the visible option string
                'choice_label' => 'company',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('lifeinsurance', EntityType::class, [
                'label' => "Cie d'assurance",
                // looks for choices from this entity
                'class' => Lifeinsurance::class,
                // uses the Lifeinsurance.companyName property as the visible option string
                'choice_label' => 'companyName',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
