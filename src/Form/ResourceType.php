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

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type', ChoiceType::class, [
                 //'choices' => $this->getChoises()
              //])
            ->add('type')
            ->add('title')
            ->add('lang')
            ->add('comment')
            ->add('person')
            ->add('persons', EntityType::class, [
                'class' => Person::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true
                
            ])
            ->add('oeuvre')
            ->add('organisme')
            ->add('geo')
            ->add('tag')
            ->add('analyse')
            ->add('rights')
            ->add('oai')
            ->add('auteur')
            ->add('resp1')
            ->add('editeur')
            ->add('editeurlieu')
            ->add('anneedit')
            ->add('isbn', null, [
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
            ]);
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
