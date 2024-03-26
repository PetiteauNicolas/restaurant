<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Food;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom:',
                'attr' => [
                    'placeholder' => 'Veuillez saisir un nom...',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse:',
                'attr' => [
                    'placeholder' => 'Veuillez saisir une adresse...',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description:',
                'attr' => [
                    'placeholder' => 'Veuillez saisir une description...',
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix moyen:',
                'attr' => [
                    'placeholder' => 'Veuillez saisir un prix moyen...',
                ],
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site web:',
                'attr' => [
                    'placeholder' => 'Veuillez saisir un site web...',
                ],
            ])
            ->add('already_done', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => 'Déja fait ?',
                'attr' => [
                    'placeholder' => 'Déja fait ?',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('food', EntityType::class, [
                'class' => Food::class,
                'choice_label' => 'name',
                'label' => 'Type de cuisine:',
                'multiple' => true,
                'attr' => [
                    'placeholder' => 'Veuillez sélectionner un type de cuisine...',
                ],
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville:',
                'attr' => [
                    'placeholder' => 'Veuillez sélectionner une ville...',
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Veuillez saisir un numéro de téléphone...',
                ],
            ])
            ->add('picture', FileType::class, [
                'attr' => [
                    'placeholder' => 'Sélectionner une photo:',
                    'autocomplete' => 'picture',
                ],
                'label' => 'Sélectionner une photo au format: jpeg, jpg, png',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1000000',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un format de type: jpeg, jpg, png !',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
