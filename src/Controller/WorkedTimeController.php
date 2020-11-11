<?php

    /**
     * Created by PhpStorm.
     * Tender: mperret
     * Date: 24/10/2018
     * Time: 16:37.
     */

namespace App\Controller;

    use App\Entity\Tender;
    use App\Entity\WorkedTime;
    use App\Form\Type\WorkedTimeType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class WorkedTimeController extends AbstractController
    {
        public function __construct()
        {
        }

        /**
         * @Route("/time/{id}/edit", name="worked_time.edit", requirements={"id"="\d+"})
         *
         * @return Response
         */
        public function edit(Request $request, WorkedTime $workedTime)
        {
            $form = $this->createForm(WorkedTimeType::class, $workedTime);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($workedTime);
                $manager->flush();

                return $this->json([
                    'status' => '1',
                    'workedTime' => $this->jsonizer->jsonizeEntity($workedTime),
                    'totalWorkedDays' => $workedTime->getTender()->getTotalWorkedDays(),
                ]);

                // goto show
                //return $this->redirectToRoute('tender.show',['id' => $workedTime->getTender()->getId()]);
            }

            return $this->render('worked_time/edit.html.twig', [
                'form' => $form->createView(),
                'workedTime' => $workedTime,
            ]);
        }

        /**
         * @Route("/tender/{id}/time/new", name="tender.worked_time.new")
         *
         * @return Response
         */
        public function new(Tender $tender, Request $request)
        {
            $time = new WorkedTime();
            $time->setTender($tender);
            $time->setUser($this->getUser());

            $form = $this->createForm(WorkedTimeType::class, $time, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($time);
                $manager->flush();

                return $this->json([
                    'status' => '1',
                    'workedTime' => $time->jsonize(),
                    'totalWorkedDays' => $tender->getTotalWorkedDays(),
                ]);
            }

            return $this->render('worked_time/new.html.twig', [
                'form' => $form->createView(),
                'tender' => $tender,
            ]);
        }
    }
