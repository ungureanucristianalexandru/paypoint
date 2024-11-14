<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Nume',
                'attr' => ['placeholder' => 'Introdu numele']
            ])
            ->add('surname', TextType::class, [
                'label' => 'Prenume',
                'attr' => ['placeholder' => 'Introdu prenumele']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Introdu adresa de email'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Introdu parola contului tău',
                    'attr' => ['placeholder' => 'Introdu parola']
                ],
                'second_options' => [
                    'label' => 'Confirmă parola contului tău',
                    'attr' => ['placeholder' => 'Confirmă parola']
                ],
                'invalid_message' => 'Parolele nu se potrivesc.',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inregistreaza-te'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
