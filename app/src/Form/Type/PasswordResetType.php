<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class PasswordChangeType.
 *
 * Form type for changing the user's password.
 */
class PasswordResetType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                 'label' => 'Email',
                 'attr' => [
                     'maxlength' => 64,
                 ],
                 'constraints' => [
                    new NotBlank([
                         'message' => 'message.blank',
                    ]),
                    new Email([
                         'message' => 'message.email_invalid',
                    ]),
                    new Length([
                         'min' => 6,
                         'max' => 64,
                         'maxMessage' => 'message.invalid_length',
                    ]),
                    new Regex([
                         'pattern' => '/^[A-Za-z0-9\s]{0,64}$/',
                         'message' => 'message.invalid_characters',
                    ]),
                 ],
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'message.password_mismatch',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => false,
                'first_options' => ['label' => 'new_password'],
                'second_options' => ['label' => 'repeat_new_password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'message.blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'message.password_too_short',
                        // The maximum length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ],
            ]);
    }

    /**
     * Configure the options for the form.
     *
     * @param OptionsResolver $resolver the options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Get the block prefix for the form.
     *
     * @return string the block prefix
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
