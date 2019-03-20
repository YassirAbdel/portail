<?php

namespace App\Form;

use App\Entity\ResourceSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ResourceSearchType extends AbstractType
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
            ->add('titre', TextType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResourceSearch::class,
            'method' => 'get',
            'csrf_protection' => false

        ]);
    }
    
    public function getBlockPrefix() {
        
        return '';
    }
}
