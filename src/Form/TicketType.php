<?php

namespace App\Form;

use App\Entity\Tickets;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titlu'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descriere'
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Open' => 'Open',
                    'In Progress' => 'In Progress',
                    'Done' => 'Done'
                ]
            ])
            ->add('user', EntityType::class, [
                'label' => 'Atribuit utilizatorului',
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'Alege un utilizator',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
