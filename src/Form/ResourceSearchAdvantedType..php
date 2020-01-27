<?php

namespace App\Form;

use App\Entity\ResourceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Person;


class ResourceSearchAdvantedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Type de document'
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Titre'
                ]
            ])
            ->add('auteur', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Auteur',
                ]
            ])
            
            ->add('persons', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Person::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => [
                    'placeHolder' => 'Persones',
                ]
            ])

            ->add('texte', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Recherche partout',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResourceSearch::class,
            'method' => 'post',
            'csrf_protection' => false

        ]);
    }
    
    public function getBlockPrefix() {
        
        return '';
    }
}
