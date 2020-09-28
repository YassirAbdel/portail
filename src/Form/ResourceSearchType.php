<?php

namespace App\Form;

use App\Entity\ResourceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Person;
use App\Entity\Work;
use Doctrine\DBAL\Types\BooleanType;

class ResourceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->setAction('adminSearch')
        ->setMethod('GET')
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
        
        ->add('id', TextType::class, [
            'required' => false,
            'label' => false,
            'attr' => [
                'placeHolder' => 'ID',
            ]
        ])

        ->add('front', CheckboxType::class, [
            'label' => 'Ressource à la une',
            'required' => false,

        ])

        ->add('persons', EntityType::class, [
            'required' => false,
            'label' => 'Articles personnes',
            'class' => Person::class,
            'choice_label' => 'name',
            'multiple' => true,
            'attr' => [
                'placeHolder' => 'Persones',
            ]
        ])

        ->add('works', EntityType::class, [
            'required' => false,
            'label' => 'Articles oeuvres',
            'class' => Work::class,
            'choice_label' => 'name',
            'multiple' => true,
            'attr' => [
                'placeHolder' => 'Oeuvres',
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
    
    public function getBlockPrefix() 
    {
        return '';
    }
    
}
