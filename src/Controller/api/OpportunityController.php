<?php

/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller\api;

    use App\Entity\Opportunity;
    use App\Entity\OpportunityFile;
    use App\Form\Type\OpportunityFileType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class OpportunityController.
     */
    class OpportunityController extends AbstractController
    {
        /**
         * @Route("/api/opportunity", name="api.opportunity.index")
         */
        public function index(Request $request): Response
        {
            // render
            return $this->render('opportunity/index.html.twig', [
            ]);
        }

        /**
         * @Route("/api/opportunity/{id}/file/new", name="api.opportunity.files.new")
         *
         * @return \Symfony\Component\HttpFoundation\JsonResponse
         *
         * @throws \Exception
         */
        public function uploadFile(Opportunity $opportunity, Request $request): Response
        {
            $opportunityFile = new OpportunityFile();
            $opportunityFile->setCreatedAt(new \DateTime());
            $opportunityFile->setCreatedBy($this->getUser());
            $opportunityFile->setOpportunity($opportunity);
            $form = $this->createForm(OpportunityFileType::class, $opportunityFile, [
                'action' => 'new',
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /*
                $file = $form['file_pdf']->getData();
                $path = uniqid().'.'.$file->guessExtension();
                $opportunityFile->setPath($path);
                $file->move($doc_path, $opportunityFile->getPath());
                */

                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($opportunityFile);
                $manager->flush();

                // goto show
                return $this->json([
                    'status' => '1',
                    'file' => $opportunityFile->jsonize(),
                ]);
            } else {
                return $this->json([
                    'status' => '0',
                ]);
            }
        }
    }
