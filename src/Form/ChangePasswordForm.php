<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre mot de passe actuel.']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un nouveau mot de passe.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ]);
    }
}
