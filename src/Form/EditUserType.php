<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Workshop;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('username', TextType::class)
            ->add('badge', PasswordType::class, [
                'required' => false
            ])
            ->add('mail', EmailType::class)
            ->add('workshop', EntityType::class, [
                'class' => Workshop::class,
                'choice_label' => 'name'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 1,
                    'Gestionnaire' => 2,
                    'Conducteur de ligne' => 3,
                    'Opérateur' => 4
                ]
            ])
            ->add('access', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
