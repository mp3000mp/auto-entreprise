<?php

namespace App\Form\Type;

    use App\Entity\Company;
    use App\Entity\Contact;
    use App\Entity\MeanOfPayment;
    use App\Entity\Opportunity;
    use App\Entity\OpportunityStatus;
    use App\Form\AbstractMPType;
    use App\Service\JsonTranslator\JsonTranslator;
    use Doctrine\ORM\EntityRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class OpportunityType extends AbstractMPType
    {
        /**
         * @var string
         */
        protected $doc_billsPath;

        public function __construct(TranslatorInterface $translator, JsonTranslator $jsonTranslator, string $doc_billsPath)
        {
            parent::__construct($translator, $jsonTranslator);
            $this->doc_billsPath = $doc_billsPath;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Opportunity::class,
                'action' => 'new',
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
                    'attr' => ['wrap_class' => 'col-md-6'],
                ])
                ->add('contacts', EntityType::class, [
                    'class' => Contact::class,
                    'choice_label' => 'fullName',
                    'label' => $this->trans('entity.Contact.label'),
                    'multiple' => true,
                    'attr' => ['wrap_class' => 'col-md-6'],
                ])
                ->add('status', EntityType::class, [
                    'class' => OpportunityStatus::class,
                    'choice_label' => function ($choice, $key, $value) {
                        return $this->jsonTrans($choice->getTrad());
                    },
                    'label' => 'field.status',
                    'attr' => ['wrap_class' => 'col-md-6'],
                    'query_builder' => function (EntityRepository $repo) {
                        return $repo->createQueryBuilder('status')
                                  ->orderBy('status.position', 'ASC');
                    },
                ])
                ->add('ref', TextType::class, [
                    'label' => 'entity.Opportunity.field.ref',
                    'attr' => ['wrap_class' => 'col-md-6'],
                ])
                ->add('description', TextType::class, [
                    'label' => 'field.description',
                ])
            ;

            if ('edit' == $options['action']) {
                $builder
                    ->add('forecasted_delivery', DateType::class, [
                        'label' => 'entity.Opportunity.field.forecasted_delivery',
                        'attr' => ['wrap_class' => 'col-md-6'],
                        'required' => false,
                    ])
                    ->add('customer_ref1', TextType::class, [
                        'label' => 'entity.Opportunity.field.customer_ref1',
                        'attr' => ['wrap_class' => 'col-md-6'],
                        'required' => false,
                    ])
                    ->add('customer_ref2', TextType::class, [
                        'label' => 'entity.Opportunity.field.customer_ref2',
                        'attr' => ['wrap_class' => 'col-md-6'],
                        'required' => false,
                    ])
                    ->add('payment_ref', TextType::class, [
                        'label' => 'entity.Opportunity.field.payment_ref',
                        'attr' => ['wrap_class' => 'col-md-6'],
                        'required' => false,
                    ])
                    ->add('file_docx', FileType::class, [
                        'label' => $this->trans('entity.Opportunity.field.bill').' (.docx)',
                        'attr' => ['wrap_class' => 'col-md-6', 'fileType' => 'word'],
                        'required' => false,
                        'mapped' => false,
                    ])
                    ->add('file_pdf', FileType::class, [
                        'label' => $this->trans('entity.Opportunity.field.bill').' (.pdf)',
                        'attr' => ['wrap_class' => 'col-md-6', 'fileType' => 'pdf'],
                        'required' => false,
                        'mapped' => false,
                    ])
                    ->add('comments', TextareaType::class, [
                        'label' => 'field.comments',
                        'required' => false,
                    ])
                    ;
            }

            $builder
                ->add('mean_of_payment', EntityType::class, [
                    'class' => MeanOfPayment::class,
                    'choice_label' => function ($choice, $key, $value) {
                        return $this->jsonTrans($choice->getTrad());
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('mop')
                                  ->orderBy('mop.position', 'ASC');
                    },
                    'label' => 'entity.Opportunity.field.mean_of_payment',
                    'attr' => ['wrap_class' => 'col-md-6'],
                ])
            ;

            $builder->addEventListener(FormEvents::SUBMIT, [$this, 'onSubmit']);
        }

        public function onSubmit(FormEvent $event): void
        {
            $form = $event->getForm();
            $opportunity = $form->getData();

            if ($form->has('file_docx')) {
                $docx = $form->get('file_docx')->getData();
                if (null != $docx) {
                    $name = uniqid().'.'.$docx->guessExtension();
                    $opportunity->setBillFileDocx($name);
                    $docx->move($this->doc_billsPath, $opportunity->getBillFileDocx());
                }
            }

            if ($form->has('file_pdf')) {
                $pdf = $form->get('file_pdf')->getData();
                if (null != $pdf) {
                    $name = uniqid().'.'.$pdf->guessExtension();
                    $opportunity->setBillFilePdf($name);
                    $pdf->move($this->doc_billsPath, $opportunity->getBillFilePdf());
                }
            }

            $event->setData($opportunity);
        }
    }
