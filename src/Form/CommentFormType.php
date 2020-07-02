<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2']
                ])
            ->add('pseudo', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2']
                ])
            ->add('contenu', TextareaType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2']
                ]
            )
            ->add('rgpd', CheckboxType::class, [
                'label' => 'I agree to the collection of my data...',
                'attr' =>
                    ['class' => 'form-control mb-2']
            ])
            ->add('Send', SubmitType::class,
                ['attr' =>
                    ['class' => 'btn btn-outline-dark btn-sm btn-block mt-2']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
