<?php

namespace App\Form\Type;

    use App\Entity\Company;
    use App\Form\AbstractMPType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CompanyType extends AbstractMPType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Company::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                    'attr' => ['autofocus' => true],
                    'label' => 'field.name',
                ])
                ->add('street1', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Company.field.street1',
                ])
                ->add('street2', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Company.field.street2',
                    'required' => false,
                ])
                ->add('city', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Company.field.city',
                ])
                ->add('postcode', TextType::class, [
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'label' => 'entity.Company.field.postcode',
                ])
            ;
        }
    }
