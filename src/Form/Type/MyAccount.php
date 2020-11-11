<?php

namespace App\Form\Type;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class MyAccount extends AbstractType
    {
        /**
         * @var TranslatorInterface
         */
        protected $translator;

        public function __construct(TranslatorInterface $translator)
        {
            $this->translator = $translator;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('first_name', TextType::class, [
                    'attr' => ['autofocus' => true],
                    'label' => 'entity.User.field.first_name',
                ])
                ->add('last_name', TextType::class, [
                    'label' => 'entity.User.field.last_name',
                ])
                ->add('email', EmailType::class, [
                    'label' => 'entity.User.field.email',
                ])
            ;
        }
    }
