<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, ['required' => true])
            ->add('content', TextareaType::class,['required' => true])
            ->add('author',TextType::class,['required' => true])
            ->add('email', EmailType::class,['required' => true])
            ->add('category',EntityType::class,['class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une categorie',
                'required' => true
                ])
            ->add('price',NumberType::class,['required' => true])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
