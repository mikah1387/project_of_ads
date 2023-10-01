<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Departements;
use App\Entity\Regions;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class)     
            ->add('content',TextType::class)
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
            ] )
            ->add('regions', EntityType::class,[
                'class'=> Regions::class,
                'mapped'=>false,
                'choice_label'=>'name',
                'placeholder'=>'region',
                'label'=>'Région'
            ])
            ->add('departements', ChoiceType::class,[
                'label'=>'departements',
                'placeholder'=> 'vous devez choisir une region',
                'choice_label'=>'name',


            ]);
            $formModifier = function (FormInterface $form, Regions $region = null){
                  $departements = ($region===null) ? []:$region->getDepartements();
                  $form->add('departements', EntityType::class,[
                    'class'=> Departements::class,
                    'choices'=>$departements,
                    'choice_label'=>'name',
                    'placeholder'=>'(choisir une region)',
                    'label'=>'départements'

                  ]);

            };
            $builder->get('regions')->addEventListener(
                 FormEvents::POST_SUBMIT,
                 function (FormEvent $event) use ($formModifier){
                  
                    $region = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(),$region);
                 }

            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
