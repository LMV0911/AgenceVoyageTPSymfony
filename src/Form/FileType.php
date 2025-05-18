<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class VoyageForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('titre')
      ->add('description')
      ->add('photo', FileType::class, [
        'label' => 'Photo (JPEG/PNG)',
        'mapped' => false,
        'required' => false,
        'constraints' => [
          new File([
            'maxSize' => '2M',
            'mimeTypes' => ['image/jpeg', 'image/png'],
          ])
        ]
      ]);
  }
}
