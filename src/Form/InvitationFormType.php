<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvitationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email du partenaire',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom.']),
                ],
            ])
            ->add('duration', ChoiceType::class, [
                'label' => 'Durée de validité du lien',
                'choices' => [
                    '6 heures' => '6',
                    '12 heures' => '12',
                    '24 heures' => '24',
                    '3 jours' => '72',
                    '7 jours' => '168',
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

}
