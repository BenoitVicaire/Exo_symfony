<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
				'label' => 'Titre',
				'attr' => ['placeholder' => 'Le titre du livre']
			])
            ->add('resume', TextareaType::class, [
				'label' => 'Résumé', 
				'required' => false
				])
            ->add('publishAt', DateType::class, [
                'label' => 'Date de publication',
				'widget' => 'single_text',
				'input' => 'datetime_immutable',
            ])
            ->add('author', TextType::class, ['label' => 'Auteur'])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégories',
                'choice_label' => 'name',
                'multiple' => true,
				'expanded' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Loué par',
                'required' => false,
                'placeholder' => 'Personne (disponible)',
                'choice_label' => fn (User $user): string => $user->getFirstname() . ' ' . $user->getLastname(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
