<?php

namespace App\Form;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class,[

                'label'=>false,
                 'required'=>false
            ])
            ->add('categories',EntityType::class,[
                'class'=>Categories::class,
                'label'=>false,
                'required'=>false,
                'choice_label' => 'name',
                'group_by'=>'parent.name',
                'query_builder'=>function (CategoriesRepository $cr){

                    return $cr->createQueryBuilder('c')
                              -> where('c.parent IS NOT NULL')
                              ->orderBy('c.name','ASC');
                }
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
