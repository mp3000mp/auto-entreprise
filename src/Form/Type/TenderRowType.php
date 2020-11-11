<?php

namespace App\Form\Type;

    use App\Entity\Tender;
    use App\Entity\TenderRow;
    use App\Form\AbstractMPType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class TenderRowType extends AbstractMPType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => TenderRow::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('tender', EntityType::class, [
                    'class' => Tender::class,
                    'choice_label' => 'version',
                    'label' => $this->trans('field.version'),
                    'attr' => ['wrap_class' => 'd-none'],
                ])
                ->add('position', TextType::class, [
                    'label' => 'field.position',
                    'attr' => ['wrap_class' => 'col-md-1'],
                ])
                ->add('sold_days', TextType::class, [
                    'label' => 'entity.TenderRow.field.sold_days',
                    'attr' => ['wrap_class' => 'col-md-2'],
                ])
                ->add('title', TextType::class, [
                    'label' => 'entity.TenderRow.field.title',
                    'attr' => ['wrap_class' => 'col-md-9'],
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'field.description',
                ])
            ;
        }
    }
