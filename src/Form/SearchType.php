<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\City;
use App\Entity\Food;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => City::class,
                'placeholder' => 'Ville (toutes)',
            ])

            ->add('food', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Food::class,
                'placeholder' => 'Cuisine (toutes)',
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Trier',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
