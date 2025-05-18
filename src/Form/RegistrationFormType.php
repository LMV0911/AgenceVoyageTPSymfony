<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('nom', TextType::class, [
        'label' => 'Nom'
      ])
      ->add('prenom', TextType::class, [
        'label' => 'Prénom'
      ])
      ->add('email', EmailType::class)
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'mapped' => false,
        'invalid_message' => 'Les mots de passe doivent correspondre',
        'first_options' => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Confirmez le mot de passe'],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez saisir un mot de passe',
          ]),
          new Length([
            'min' => 6,
            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
            'max' => 4096,
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
