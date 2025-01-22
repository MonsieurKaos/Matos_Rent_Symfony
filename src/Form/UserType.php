<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = ["Administrateur" => "ROLE_ADMIN", "Emprunteur" => "ROLE_USER"];
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom : ',
                'attr' => ['class' => 'inputclass'],
            ],)
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'inputclass'],
            ],)
            ->add('email', TextType::class, [
                'label' => 'Email : ',
                'attr' => ['class' => 'inputclass'],
            ],)
            ->add('matricule', TextType::class, [
                'label' => 'Matricule : ',
                'attr' => ['class' => 'inputclass'],
            ],)
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe : ',
                'attr' => ['class' => 'inputclass'],
                'required' => false
            ],)
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Confirmat du mot de passe : ',
                'attr' => ['class' => 'inputclass'],
                'required' => false
            ],)
            ->add('rue',TextType::class,[
                'label'=>"Adresse :",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control'),
                'required' => false
            ])
            ->add('cp',TextType::class,[
                'label'=>"Code postal :",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control','autocomplete' => 'on'),
                'required' => false
            ])
            ->add('ville',TextType::class,[
                'label'=>"Ville :",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('class' => 'form-control','autocomplete' => 'on'),
                'required' => false
            ])
            ->add('latitude',TextType::class,[
                'label'=>"Latitude :",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('readonly' => true,'class' => 'form-control'),
                'required' => false
            ])
            ->add('longitude',TextType::class,[
                'label'=>"Longitude :",
                'label_attr' => array('class' => 'form-label'),
                'attr' => array('readonly' => true,'class' => 'form-control'),
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'label' => 'rôles',
                'placeholder' => 'Choisissez un rôles : ',
                'expanded' => true,
                'multiple' => true,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
