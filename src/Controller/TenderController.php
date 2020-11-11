<?php

/**
 * Created by PhpStorm.
 * Tender: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

    use App\Entity\Opportunity;
    use App\Entity\Tender;
    use App\Form\Type\TenderType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class TenderController extends AbstractController
    {
        /**
         * @Route("/tender", name="tender.index")
         */
        public function index(Request $request): Response
        {
            $list = $this->getDoctrine()->getRepository(Tender::class)
                         ->findAll();

            // render
            return $this->render('tender/index.html.twig', [
                'list' => $list,
            ]);
        }

        /**
         * @Route("/tender/{id}", name="tender.show", requirements={"id"="\d+"})
         */
        public function show(Tender $tender): Response
        {
            return $this->render('tender/show.html.twig', [
                'tender' => $tender,
                'vjsData' => [
                    'tender' => $tender->jsonize(),
                    'workedTimes' => array_map(function ($workedTime) {
                        return $workedTime->jsonize();
                    }, $tender->getWorkedTimes()->toArray()),
                ],
            ]);
        }

        /**
         * @Route("/tender/{id}/edit", name="tender.edit", requirements={"id"="\d+"})
         */
        public function edit(Request $request, Tender $tender): Response
        {
            $form = $this->createForm(TenderType::class, $tender, [
                'action' => 'edit',
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($tender);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('tender.show', ['id' => $tender->getId()]);
            }

            return $this->render('tender/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/tender/new", name="tender.new")
         */
        public function new(Request $request): Response
        {
            $tender = new Tender();
            $tender->setCreatedAt(new \DateTime());

            // opportunity
            if (null != $request->get('opportunity_id')) {
                $opportunity = $this->getDoctrine()->getRepository(Opportunity::class)->find($request->get('opportunity_id'));
                if (null != $opportunity) {
                    $tender->setOpportunity($opportunity);
                    $tender->setVersion($tender->getOpportunity()->getNextVersion());
                }
            }

            $form = $this->createForm(TenderType::class, $tender, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($tender);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('tender.show', ['id' => $tender->getId()]);
            }

            return $this->render('tender/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
