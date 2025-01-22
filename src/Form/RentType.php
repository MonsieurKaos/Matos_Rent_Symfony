<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt', DateType::class, [
                'label' => 'form.empruntDateEmprunt',
                'widget' => 'single_text',
                'help' => 'Format : JJ-MM-AAAA',
                'model_timezone' => 'Europe/Paris',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('dateRetourPrev', DateType::class, [
                'label' => 'form.empruntDateRetourPrev',
                'widget' => 'single_text',
                'help' => 'Format : JJ-MM-AAAA',
                'model_timezone' => 'Europe/Paris',
                'attr' => ['class' => 'js-datepicker'],
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
