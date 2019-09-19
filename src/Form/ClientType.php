<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validator\Constraints\PostCode;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
            ])
            ->add('street', TextType::class, [
                'label' => 'Ulica',
            ])
            ->add('homeNumber', TextType::class, [
                'label' => 'Nr domu',
            ])
            ->add('flatNumber', TextType::class, [
                'label' => 'Nr mieszkania',
            ])
            ->add('postCode', TextType::class, [
                'label' => 'Kod pocztowy',
//                'constraints' => [
//                    new PostCode()
//                ]
            ])
            ->add('locality', TextType::class, [
                'label' => 'Miejscowość',
            ])
            ->add('fromUmcs', ChoiceType::class, [
                'label' => 'Z Umcs?',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Tak' => true,
                    'Nie' => false,
                ],
//                'choice_attr' => function ($choice, $key, $value) {
//                    dd($choice, $key, $value);
//                    return $choice == true ? ['checked' => ''] : [];
//                },
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz',
                'attr' => [
                    'class' => 'btn-primary pull-right',
                ]
            ]);
    }
}