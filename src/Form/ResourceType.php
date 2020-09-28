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
use App\Entity\Work;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;




class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type', ChoiceType::class, [
                 //'choices' => $this->getChoises()
              //])
            ->add('type', TextType::class, [
                'disabled' => false
                
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('lang', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('comment', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('person', TextareaType::class, [
                'required' => false,
                'disabled' => false
                
            ])
            ->add('oeuvre', TextareaType::class, [
                'required' => false,
                'disabled' => false,
            ])
            ->add('organisme', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('geo', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('tag', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('analyse')
            ->add('rights')
            ->add('oai')
            ->add('auteur', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('resp1', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('editeur', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('editeurlieu', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('anneedit', TextType::class, [
                'required' => false,
                'disabled' => false
            ])
            ->add('isbn', TextType::class, [
                "required" => false,
                'empty_data' => '',
                'disabled' => false
            ])
            ->add('pagination', null, [
                "required" => false,
                'empty_data' => '',
                'disabled' => false
            ])
            ->add('collection', null, [
                "required" => false,
                'empty_data' => '',
                'disabled' => false
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
            ->add('front')
            ->add('folderFront')
            ->add('lecteur')
            ->add('works', EntityType::class, [
                'class' => Work::class,
                'required' => false,
                'choice_label' => 'name',
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
