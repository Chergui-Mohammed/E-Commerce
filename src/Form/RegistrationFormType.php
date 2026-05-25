<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Full Name',
                'attr' => ['placeholder' => 'Enter your full name', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(message: 'Please enter your full name.'),
                    new Length(min: 2, max: 150),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'attr' => ['placeholder' => 'Enter your email', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(message: 'Please enter your email.'),
                    new Assert\Email(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Create a password', 'class' => 'form-control', 'autocomplete' => 'new-password'],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => ['placeholder' => 'Confirm your password', 'class' => 'form-control', 'autocomplete' => 'new-password'],
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank(message: 'Please enter a password.'),
                    new Length(min: 6, minMessage: 'Your password should be at least {{ limit }} characters.'),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'I agree to the Terms & Conditions',
                'constraints' => [
                    new IsTrue(message: 'You must agree to the terms and conditions.'),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign Up',
                'attr' => ['class' => 'btn btn-success btn-lg'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'register',
        ]);
    }
}
