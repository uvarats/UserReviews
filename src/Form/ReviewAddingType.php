<?php

namespace App\Form;

use App\Entity\Review;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAddingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tags', TextType::class)
            ->add('rating', TextType::class, [
                'attr' => [
                    'id' => 'stars',
                    'class' => 'rating',
                    'min' => 1,
                    'max' => 5,
                    'data-size' => 'sm',
                ]
            ])
            ->add('name', TextType::class)
            ->add('reviewGroup', ChoiceType::class, [
                'choices' => [
                    'Games' => 'Games',
                    'Books' => 'Books',
                    'Films' => 'Films',
                ]
            ])
            ->add('text', TextareaType::class, [
                'attr' => [
                    'style' => 'height: 100%',
                    'rows' => '7',
                ],
            ])
            //->add('illustrations')
        ;
        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function($tagsAsArray){
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    return explode(', ', $tagsAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
