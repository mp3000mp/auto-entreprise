<?php

namespace App\Form\Type;

    use App\Entity\Tender;
    use App\Entity\WorkedTime;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class WorkedTimeType extends AbstractType
    {
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => WorkedTime::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('tender', EntityType::class, [
                    'class' => Tender::class,
                    'choice_label' => 'version',
                    'label' => 'field.version',
                    'attr' => ['wrap_class' => 'd-none'],
                ])
                ->add('date', DateType::class, [
                    'label' => 'field.date',
                ])
                ->add('worked_days', NumberType::class, [
                    'label' => 'entity.WorkedTime.label',
                ])
            ;
        }
    }
