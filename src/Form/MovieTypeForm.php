<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Movie Title',
                'attr' => ['placeholder' => 'Enter movie title', 'class' => 'bg-transparent border-0 w-full h-20 text-6xl'],
            ])
            ->add('releaseYear',IntegerType::class, [
                'label' => 'Release Year',
                'attr' => ['placeholder' => 'Enter release year'],
            ])
            ->add('description',TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Enter movie description'],
            ])
            ->add('imagePath',FileType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
