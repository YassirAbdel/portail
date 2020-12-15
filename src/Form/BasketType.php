<?php

namespace App\Form;

use App\Entity\Basket;
use App\Entity\Resource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class BasketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title')
            ->add('resources', EntityType::class, [
                'class' => Resource::class,
                'required' => false, 
                'choice_label' => 'title',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                    ->setMaxResults(30);    
                },
                
            ])
          
            //->add('creat_at')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Basket::class,
            'translation_domain' => 'forms',
            'allow_extra_fields' => true
        ]);
    }
}