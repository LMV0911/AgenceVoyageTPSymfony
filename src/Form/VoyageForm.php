<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Pays;
use App\Entity\Voyage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClass = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50';
        $labelClass = 'block text-sm font-medium text-gray-700 mb-1';

        $builder
            ->add('photo', TextType::class, [
                'label' => 'URL de l\'image',
                'attr' => [
                    'placeholder' => 'https://example.com/image.jpg',
                    'class' => $inputClass
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre du voyage',
                'attr' => [
                    'placeholder' => 'Séjour en Italie...',
                    'class' => $inputClass
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'attr' => [
                    'rows' => 5,
                    'class' => $inputClass
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix (€)',
                'currency' => 'EUR',
                'help' => 'Prix total par personne en euros',
                'attr' => [
                    'class' => $inputClass
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => $inputClass . ' datepicker'
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => $inputClass . ' datepicker'
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'label' => 'Pays de destination',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un pays',
                'attr' => [
                    'class' => $inputClass . ' select2'
                ],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('activites', EntityType::class, [
                'class' => Activite::class,
                'label' => 'Activités incluses',
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => $inputClass . ' select2',
                    'data-placeholder' => 'Sélectionnez des activités'
                ],
                'help' => 'Maintenez Ctrl/Cmd pour sélectionner plusieurs activités',
                'label_attr' => ['class' => $labelClass]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
