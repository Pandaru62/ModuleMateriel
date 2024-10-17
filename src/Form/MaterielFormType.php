<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\Tva;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-auto',
                ),
                'label' => 'Nom du produit : '
            ])
            ->add('description', TextareaType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-100',
                ),
                'label' => 'Description du produit : '
            ])
            ->add('prixHT', NumberType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-auto',
                ),
                'label' => 'Prix H.T. (en euros) : '
            ])
            ->add('prixTTC', NumberType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-auto',
                ),
                'label' => 'Prix T.T.C. (en euros) : '
            ])
            ->add('Tva', EntityType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-auto',
                ),
                'class' => Tva::class,
                'choice_label' => 'libelle',
                'label' => 'Sélectionnez la T.V.A. appliquée : ',
                'choice_attr' => function(Tva $tva) {
                    return ['data-value' => $tva->getValeur()];
                }
            ])
            ->add('quantite', NumberType::class, [
                'attr' => array(
                    'class' => 'form-control  my-3 w-auto',
                ),
                'label' => 'Quantité : '
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
