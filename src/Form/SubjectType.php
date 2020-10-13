<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Resource;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('subTitle')
            ->add('Description', CKEditorType::class)
            /**
            ->add('resources')
            ->add('resources', EntityType::class, [
                'class' => Resource::class,
                'required' => false,
                'choice_label' => 'title',
                'multiple' => true
                
            ])
            **/
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'choice_label' => 'title',
                'multiple' => false
                
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Illustration'
                ])
            ;
            /*** 
            ->add('resources', EntityType::class, [
                'class' => Subject::class,
                'required' => false,
                'choice_label' => 'resources',
                'multiple' => true
                
            ])
            // Ajout de ressources directement
            ->add('resources') 
            ***/ 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
            'translation_domain' => 'forms'
        ]);
    }
}
