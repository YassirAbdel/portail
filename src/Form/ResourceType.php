<?php

namespace App\Form;

use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Annotations\Annotation\Required;

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                 'choices' => $this->getChoises()
              ])
            ->add('title')
            ->add('lang')
            ->add('comment')
            ->add('person')
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
