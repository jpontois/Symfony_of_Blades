<?php

namespace App\Form;

use App\Entity\GameEntity;
use App\Entity\EditorEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType,TextareaType,DateType,SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameCreate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('plateform', TextType::class)
            ->add('description', TextareaType::class)
            ->add('releaseDate', DateType::class)

            ->add('editor', EntityType::class, [
                'class' => EditorEntity::class,
                'choice_label' => function (EditorEntity $editor) {
                    return $editor->getName();
                }
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameEntity::class,
        ]);
    }
}
