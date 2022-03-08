<?php

namespace App\Form;

use App\Entity\Review;
use App\Entity\Tag;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAddingType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tags', TextType::class, [
                'getter' => function (Review $review, FormInterface $form): string
                {
                    $tags = $review->getTags()->toArray();
                    return implode(', ', $tags);
                },
                'setter' => function (Review &$review, string $tags, FormInterface $form): void
                {
                    $tagsRepository = $this->entityManager->getRepository(Tag::class);
                    $explodedTags = explode(', ', $tags);
                    foreach ($explodedTags as $tag){
                        $t = $tagsRepository->findOneBy(['tag' => $tag]);
                        if(!$t){
                            $t = new Tag();
                            $t->setTag($tag);
                            $this->entityManager->persist($t);
                            $this->entityManager->flush();
                        }
                        $review->addTag($t);
                    }
                }
            ])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
