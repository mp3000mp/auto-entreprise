<?php

/**
 * Created by PhpStorm.
 * Opportunity: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

    use App\Entity\Company;
    use App\Entity\Contact;
    use App\Entity\Opportunity;
    use App\Entity\OpportunityFile;
    use App\Form\Type\OpportunityFileType;
    use App\Form\Type\OpportunityType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class OpportunityController extends AbstractController
    {
        /**
         * @Route("/opportunity", name="opportunity.index")
         */
        public function index(Request $request): Response
        {
            $list = $this->getDoctrine()->getRepository(Opportunity::class)
                         ->findAll();

            // render
            return $this->render('opportunity/index.html.twig', [
                'list' => $list,
            ]);
        }

        /**
         * @Route("/opportunity/{id}", name="opportunity.show", requirements={"id"="\d+"})
         *
         * @throws \Exception
         */
        public function show(Opportunity $opportunity, TranslatorInterface $translator): Response
        {
            // formulaire nouveau fichier
            $file = new OpportunityFile();
            $file->setCreatedAt(new \DateTime());
            $file->setCreatedBy($this->getUser());
            $file->setOpportunity($opportunity);
            $fileForm = $this->createForm(OpportunityFileType::class, $file, [
                'action' => 'edit',
            ]);

            return $this->render('opportunity/show.html.twig', [
                'opportunity' => $opportunity,
                'fileForm' => $fileForm->createView(),
                'vjsData' => [
                    'trad' => [
                        'file' => [
                            'add' => $translator->trans('entity.File.action.new'),
                        ],
                    ],
                    'files' => array_map(function ($opportunityFile) {
                        return $opportunityFile->jsonize();
                    }, $opportunity->getOpportunityFiles()->toArray()),
                    'tenders' => array_map(function ($tender) {
                        return $tender->jsonize();
                    }, $opportunity->getTenders()->toArray()),
                ],
            ]);
        }

        /**
         * @Route("/opportunity/{id}/edit", name="opportunity.edit", requirements={"id"="\d+"})
         */
        public function edit(Request $request, Opportunity $opportunity): Response
        {
            $form = $this->createForm(OpportunityType::class, $opportunity, [
                'action' => 'edit',
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($opportunity);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('opportunity.show', ['id' => $opportunity->getId()]);
            }

            return $this->render('opportunity/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/opportunity/new", name="opportunity.new")
         *
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
         *
         * @throws \Exception
         */
        public function new(Request $request): Response
        {
            $opportunity = new Opportunity();
            $opportunity->setCreatedAt(new \DateTime());
            $opportunity->setTrackedAt(new \DateTime());
            // company
            if (null != $request->get('company_id')) {
                $company = $this->getDoctrine()->getRepository(Company::class)->find($request->get('company_id'));
                if (null != $company) {
                    $opportunity->setCompany($company);
                }
            }
            // contact
            if (null != $request->get('contact_id')) {
                $contact = $this->getDoctrine()->getRepository(Contact::class)->find($request->get('contact_id'));
                if (null != $contact) {
                    $opportunity->add($contact);
                }
            }

            $form = $this->createForm(OpportunityType::class, $opportunity, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($opportunity);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('opportunity.show', ['id' => $opportunity->getId()]);
            }

            return $this->render('opportunity/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
