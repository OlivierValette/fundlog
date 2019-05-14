<?php

namespace App\Form;

use App\Entity\Alert;
use App\Entity\Portfolio;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('periodicity', TextType::class, ['label' => "Périodicité (Q/S/M)"])
            ->add('object', TextType::class, ['label' => "Cible (Performance/Valeur)"])
            ->add('threshold', NumberType::class, ['label' => "Seuil de déclenchement (en € ou en %)"])
            ->add('portfolio', EntityType::class, [
                'label' => "Portefeuille",
                'class' => Portfolio::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
        ]);
    }
}
