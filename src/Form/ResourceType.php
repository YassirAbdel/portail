<?php

namespace App\Form;

use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Person;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Subject;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type', ChoiceType::class, [
                 //'choices' => $this->getChoises()
              //])
            ->add('type', TextType::class, [
                'disabled' => true
                
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('lang', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('comment', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('person', TextareaType::class, [
                'required' => false,
                'disabled' => true
                
            ])
            ->add('oeuvre', TextareaType::class, [
                'required' => false,
                'disabled' => true,
            ])
            ->add('organisme', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('geo', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('tag', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('analyse')
            ->add('rights')
            ->add('oai')
            ->add('auteur', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('resp1', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('editeur', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('editeurlieu', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('anneedit', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('isbn', TextType::class, [
                "required" => false,
                'empty_data' => '',
                'disabled' => true
            ])
            ->add('pagination', null, [
                "required" => false,
                'empty_data' => '',
                'disabled' => true
            ])
            ->add('collection', null, [
                "required" => false,
                'empty_data' => '',
                'disabled' => true
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('persons', EntityType::class, [
                'class' => Person::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true
                
            ])
            ->add('subjects', EntityType::class, [
                'class' => Subject::class,
                'required' => false,
                'choice_label' => 'title',
                'multiple' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resource::class,
            'translation_domain' => 'forms'
        ]);
    }
    
    public function getChoises()
    {
        $choises = Resource::TypeDoc;
        $output = [];
        foreach ($choises as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }
}
