<?php

namespace App\Form\Type;

    use App\Entity\Opportunity;
    use App\Entity\Tender;
    use App\Entity\TenderStatus;
    use App\Form\AbstractMPType;
    use App\Service\JsonTranslator\JsonTranslator;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class TenderType extends AbstractMPType
    {
        protected $doc_tendersPath;

        public function __construct(TranslatorInterface $translator, JsonTranslator $jsonTranslator, string $doc_tendersPath)
        {
            parent::__construct($translator, $jsonTranslator);
            $this->doc_tendersPath = $doc_tendersPath;
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Tender::class,
                'action' => 'new',
            ]);
            $resolver->setRequired([]);
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('opportunity', EntityType::class, [
                    'class' => Opportunity::class,
                    'choice_label' => 'ref',
                    'label' => $this->trans('entity.Opportunity.label'),
                    'attr' => ['wrap_class' => 'd-none'],
                ])
                ->add('version', NumberType::class, [
                    'label' => 'field.version',
                    'attr' => ['wrap_class' => 'col-md-4'],
                ])
                ->add('status', EntityType::class, [
                    'class' => TenderStatus::class,
                    'choice_label' => function ($choice, $key, $value) {
                        return $this->jsonTrans($choice->getTrad());
                    },
                    'label' => 'field.status',
                    'attr' => ['wrap_class' => 'col-md-4'],
                ])
                ->add('average_daily_rate', NumberType::class, [
                    'label' => 'entity.Tender.field.average_daily_rate',
                    'attr' => ['wrap_class' => 'col-md-4'],
                    'data' => 450,
                ])
                ->add('file_docx', FileType::class, [
                    'label' => $this->trans('entity.Tender.label').' (.docx)',
                    'attr' => ['wrap_class' => 'col-md-6', 'fileType' => 'word'],
                    'required' => false,
                    'mapped' => false,
                ])
                ->add('file_pdf', FileType::class, [
                    'label' => $this->trans('entity.Tender.label').' (.pdf)',
                    'attr' => ['wrap_class' => 'col-md-6', 'fileType' => 'pdf'],
                    'required' => false,
                    'mapped' => false,
                ])
            ;

            if ('edit' == $options['action']) {
                $builder
                    ->add('comments', TextareaType::class, [
                        'label' => 'field.comments',
                        'required' => false,
                    ])
                ;
            }

            $builder->addEventListener(FormEvents::SUBMIT, [$this, 'onSubmit']);
        }

        public function onSubmit(FormEvent $event)
        {
            $form = $event->getForm();
            $tender = $form->getData();

            $docx = $form->get('file_docx')->getData();
            if (null != $docx) {
                $name = uniqid().'.'.$docx->guessExtension();
                $tender->setTenderFileDocx($name);
                $docx->move($this->doc_tendersPath, $tender->getTenderFileDocx());
            }

            $pdf = $form->get('file_pdf')->getData();
            if (null != $pdf) {
                $name = uniqid().'.'.$pdf->guessExtension();
                $tender->setTenderFilePdf($name);
                $pdf->move($this->doc_tendersPath, $tender->getTenderFilePdf());
            }

            $event->setData($tender);
        }
    }
