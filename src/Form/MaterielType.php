<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;


class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>"form.materielNom",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control'),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom est obligatoire.',
                    ]),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 30,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractère.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s]+$/',
                        'message' => 'Le nom doit être alphanumérique.',
                    ])
                ]
            ])
            ->add('version',TextType::class,[
                'label'=>"form.materielVersion",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control'),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La version est obligatoire.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'La version doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La version ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\.]+$/',
                        'message' => 'La version doit être alphanumérique.',
                    ]),
                ],
            ])
            ->add('ref',TextType::class,[
                'label'=>"form.materielRef",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control'),
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La référence est obligatoire.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(AN|AP|XX)[0-9]{3}$/',
                        'message' => 'La référence doit commencer par AN, AP ou XX, suivie de 3 chiffres.',
                    ]),
                ],
            ])
            ->add('telephone',TextType::class,[
                'label'=>"form.materielTelephone",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control'),
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(\+33|0)[1-9][0-9]{8}$/',
                        'message' => 'Le numéro de téléphone doit être au format +33XXXXXXXXX ou 0XXXXXXXXX.',
                    ]),
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'form.materielPhoto',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
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
