<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')     
            ->add('content')
            ->add('categories',EntityType::class,[
                'class'=>Categories::class,
                'choice_label' => 'name',
                // 'by_reference'=>false, // permet de remplir les tables de liason (many to many)
                'group_by'=>'parent.name',
                'query_builder'=>function (CategoriesRepository $cr){

                    return $cr->createQueryBuilder('c')
                              -> where('c.parent IS NOT NULL')
                              ->orderBy('c.name','ASC');
                }
            ])
            ->add('images', FileType::class,[
                'multiple'=>true,
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new All(new Image([
                        'maxWidth'=>1600,
                        'maxWidthMessage'=> 'l\'image doit faire {{ max_width }} pixels de large au max '
                                ] ))
                    
                ]
            ] );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
