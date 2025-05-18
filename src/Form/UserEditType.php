<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class)
      ->add('plainPassword', PasswordType::class, [
        'required' => false,
        'label' => 'Nouveau mot de passe (laisser vide si inchangé)',
        'mapped' => false
      ])
      ->add('roles', ChoiceType::class, [
        'choices' => [
          'Administrateur' => 'ROLE_ADMIN',
          'Utilisateur' => 'ROLE_USER',
          'Banni' => 'ROLE_BANNED'
        ],
        'multiple' => true,
        'expanded' => true
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
