<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\MotCle;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleAddFromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,
                ['attr' =>
                    ['class' => 'form-control mb-2']
                ])
            ->add('contenu', CKEditorType::class)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Featured image',
                'required' => true
            ])
            ->add('mot_cle', EntityType::class, [
                //Connextion à l'entité
                'class' => MotCle::class,
                'label' => 'Keywords',
                //Choix multiple
                'multiple' => true,
                //Case à cocher
                'expanded' => true
            ])
            ->add('categorie', EntityType::class, [
                    'class' => Categorie::class,
                    'label' => 'Categories',
                    'multiple' => true,
                    'expanded' => true
                ]
            )->add('Publier', SubmitType::class,
                ['attr' =>
                    ['class' => 'btn btn-outline-dark btn-sm btn-block mt-2']
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
