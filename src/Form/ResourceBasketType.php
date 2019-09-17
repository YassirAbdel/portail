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
use App\Entity\Basket;


class ResourceBasketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type', ChoiceType::class, [
                 //'choices' => $this->getChoises()
              //])
            ->add('type', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('title', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('lang', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('comment', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('person', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('persons', EntityType::class, [
                'class' => Person::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true
                
            ])
            
            ->add('baskets', EntityType::class, [
                'class' => Basket::class,
                'required' => false,
                'choice_label' => 'title',
                'multiple' => false
                
            ])
            
            ->add('oeuvre', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('organisme', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('geo', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('tag', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('analyse')
            ->add('rights')
            ->add('oai')
            ->add('auteur', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('resp1', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('editeur', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('editeurlieu', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
            ])
            ->add('anneedit', TextType::class, [
                'attr' => array(
                    'readonly' => true,
                ),
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
