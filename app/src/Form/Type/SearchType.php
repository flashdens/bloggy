<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class SearchType.
 *
 * Form type for search functionality.
 */
class SearchType extends AbstractType
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
            ->setMethod('GET')
            ->add('search', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'action.search',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Za-z0-9\s]*$/',
                        'message' => 'message.invalid_characters',
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
        // No custom options to configure in this case
    }
}
