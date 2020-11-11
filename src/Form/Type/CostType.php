<?php

namespace App\Form\Type;

    use App\Entity\Cost;
    use App\Form\AbstractMPType;
    use Doctrine\ORM\EntityRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CostType extends AbstractMPType
    {
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Cost::class,
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('type', EntityType::class, [
                    'class' => \App\Entity\CostType::class,
                    'choice_label' => function ($choice, $key, $value) {
                        return $this->jsonTrans($choice->getTrad());
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('mop')
                                  ->orderBy('mop.position', 'ASC');
                    },
                    'label' => $this->trans('field.type'),
                ])
                ->add('date', DateType::class, [
                    'attr' => ['autofocus' => true, 'wrap_class' => 'col-md-6'],
                    'label' => 'field.date',
                ])
                ->add('amount', NumberType::class, [
                    'attr' => ['autofocus' => true, 'wrap_class' => 'col-md-6'],
                    'label' => 'entity.Cost.field.amount',
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'field.description',
                ])
            ;
        }
    }
