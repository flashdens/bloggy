<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * User type.
 */
class UserType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'maxlength' => 64,
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter an email.',
                ]),
                new Email([
                    'message' => 'Please enter a valid email address.',
                ]),
                new Length([
                    'max' => 64,
                    'maxMessage' => 'The email should be a maximum of {{ limit }} characters long.',
                ]),
                new Regex([
                    'pattern' => '/^[A-Za-z0-9\s]{0,64}$/',
                    'message' => 'The email should only contain alphanumeric characters and spaces.',
                ]),
            ],
        ])
        ->add('password', PasswordType::class, [
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
            new NotBlank([
                'message' => 'Please enter a password.',
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'The password must be at least {{ limit }} characters long.',
                'max' => 4096, // Maximum length allowed by Symfony for security reasons
            ]),
        ],
    ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
