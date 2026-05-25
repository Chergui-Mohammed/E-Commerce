<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddToCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity',
                'data' => 1,
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                    'class' => 'form-control',
                    'style' => 'max-width: 100px;',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\Range(min: 1, max: 10),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add to Cart',
                'attr' => ['class' => 'btn btn-primary btn-lg'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'submit',
        ]);
    }
}
