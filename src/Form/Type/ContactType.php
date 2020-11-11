<?php

namespace App\Form\Type;

    use App\Entity\Company;
    use App\Entity\Contact;
    use App\Form\AbstractMPType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ContactType extends AbstractMPType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Contact::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('company', EntityType::class, [
                    'class' => Company::class,
                    'choice_label' => 'name',
                    'label' => $this->trans('entity.Company.label'),
                    'translation_domain' => false,
                ])
                ->add('first_name', TextType::class, [
                    'attr' => ['autofocus' => true, 'wrap_class' => 'col-md-6'],
                    'label' => 'entity.Contact.field.first_name',
                ])
                ->add('last_name', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Contact.field.last_name',
                ])
                ->add('email', EmailType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Contact.field.email',
                ])
                ->add('phone', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Contact.field.phone',
                    'required' => false,
                ])
                ->add('comments', TextareaType::class, [
                    'label' => 'field.comments',
                    'required' => false,
                ])
            ;
        }
    }
