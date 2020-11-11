<?php

    /**
     * Created by PhpStorm.
     * User: mperret
     * Date: 24/10/2018
     * Time: 16:37.
     */

namespace App\Controller;

    use App\Entity\Company;
    use App\Form\Type\CompanyType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class CompanyController extends AbstractController
    {
        /**
         * @Route("/company", name="company.index")
         *
         * @return Response
         */
        public function index(Request $request)
        {
            $list = $this->getDoctrine()->getRepository(Company::class)
                ->findAll();

            // render
            return $this->render('company/index.html.twig', [
                'list' => $list,
            ]);
        }

        /**
         * @Route("/company/{id}", name="company.show", requirements={"id"="\d+"})
         *
         * @return Response
         */
        public function show(Company $company)
        {
            return $this->render('company/show.html.twig', [
                'company' => $company,
            ]);
        }

        /**
         * @Route("/company/{id}/edit", name="company.edit", requirements={"id"="\d+"})
         *
         * @param Company $contact
         *
         * @return Response
         */
        public function edit(Request $request, Company $company)
        {
            $form = $this->createForm(CompanyType::class, $company, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($company);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
            }

            return $this->render('company/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/company/new", name="company.new")
         *
         * @return Response
         */
        public function new(Request $request)
        {
            $company = new Company();

            $form = $this->createForm(CompanyType::class, $company, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($company);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('company.show', ['id' => $company->getId()]);
            }

            return $this->render('company/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
