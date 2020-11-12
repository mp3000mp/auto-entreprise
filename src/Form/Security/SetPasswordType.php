<?php

declare(strict_types=1);

namespace App\Form\Security;

use App\Validator\Constraints\ComplexPattern;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class LoginFormType.
 */
class SetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password_new', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'security.password.bad_confirm',
                'first_options' => ['label' => 'security.password.new'],
                'second_options' => ['label' => 'security.password.confirm'],
                'constraints' => [
                    new ComplexPattern([
                        'regexValid' => ['.{8,}'],
                        'regexInvalid' => [],
                        'message' => 'security.password.constraints',
                    ]),
                ],
            ])
            ;
    }
}
