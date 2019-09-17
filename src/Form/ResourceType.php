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


class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type', ChoiceType::class, [
                 //'choices' => $this->getChoises()
              //])
            ->add('type', TextType::class, [
                
            ])
            ->add('title', TextType::class, [
                            ])
            ->add('lang', TextType::class, [
                
            ])
            ->add('comment', TextType::class, [
                
            ])
            ->add('person', TextType::class, [
                
            ])
            ->add('persons', EntityType::class, [
                'class' => Person::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true
                
            ])
            ->add('oeuvre', TextType::class, [
                
            ])
            ->add('organisme', TextType::class, [
                
            ])
            ->add('geo', TextType::class, [
                
            ])
            ->add('tag', TextType::class, [
                
            ])
            ->add('analyse')
            ->add('rights')
            ->add('oai')
            ->add('auteur', TextType::class, [
                
            ])
            ->add('resp1', TextType::class, [
                
            ])
            ->add('editeur', TextType::class, [
                
            ])
            ->add('editeurlieu', TextType::class, [
                
            ])
            ->add('anneedit', TextType::class, [
                
            ])
            ->add('isbn', TextType::class, [
                "required" => false,
                'empty_data' => ''
            ])
            ->add('pagination', null, [
                "required" => false,
                'empty_data' => ''
            ])
            ->add('collection', null, [
                "required" => false,
                'empty_data' => ''
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
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
