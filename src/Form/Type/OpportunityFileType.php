<?php

namespace App\Form\Type;

    use App\Entity\Opportunity;
    use App\Entity\OpportunityFile;
    use App\Form\AbstractMPType;
    use App\Service\JsonTranslator\JsonTranslator;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormView;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Contracts\Translation\TranslatorInterface;

    /**
     * Class OpportunityFileType.
     */
    class OpportunityFileType extends AbstractMPType
    {
        protected $translator;

        protected $doc_path;

        public function __construct(TranslatorInterface $translator, JsonTranslator $jsonTranslator, string $doc_path)
        {
            parent::__construct($translator, $jsonTranslator);
            $this->doc_path = $doc_path;
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => OpportunityFile::class,
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
                ->add('title', TextType::class, [
                    'label' => 'field.name',
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'field.description',
                ])
                ->add('file_pdf', FileType::class, [
                    'label' => 'field.file',
                    'attr' => ['fileType' => 'pdf'],
                    'mapped' => false,
                ])
            ;

            $builder->addEventListener(FormEvents::SUBMIT, [$this, 'onSubmit']);
        }

        public function buildView(FormView $view, FormInterface $form, array $options)
        {
            parent::buildView($view, $form, $options);

            if (isset($options['wrap_class'])) {
                $view->vars = array_merge($view->vars, [
                    'wrap_class' => $options['wrap_class'],
                ]);
            }
        }

        public function onSubmit(FormEvent $event)
        {
            $form = $event->getForm();
            $opportunityFile = $form->getData();

            $pdf = $form->get('file_pdf')->getData();
            if (null != $pdf) {
                $name = uniqid().'.'.$pdf->guessExtension();
                $opportunityFile->setPath($name);
                $pdf->move($this->doc_path, $opportunityFile->getPath());
            }

            $event->setData($opportunityFile);
        }
    }
