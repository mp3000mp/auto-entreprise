<?php

namespace App\Form\Type;

    use App\Entity\User;
    use App\Form\AbstractMPType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UserType extends AbstractMPType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('first_name', TextType::class, [
                    'attr' => ['autofocus' => true, 'wrap_class' => 'col-md-6'],
                    'label' => 'entity.User.field.first_name',
                ])
                ->add('last_name', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.User.field.last_name',
                ])
                ->add('email', EmailType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.User.field.email',
                ])
            ;
        }
    }
