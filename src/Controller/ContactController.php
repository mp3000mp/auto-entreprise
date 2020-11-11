<?php

/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

    use App\Entity\Company;
    use App\Entity\Contact;
    use App\Form\Type\ContactType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class ContactController extends AbstractController
    {
        /**
         * @Route("/contact", name="contact.index")
         */
        public function index(Request $request): Response
        {
            $list = $this->getDoctrine()->getRepository(Contact::class)
                              ->findAll();

            // render
            return $this->render('contact/index.html.twig', [
                'list' => $list,
            ]);
        }

        /**
         * @Route("/contact/{id}", name="contact.show", requirements={"id"="\d+"})
         */
        public function show(Contact $contact): Response
        {
            return $this->render('contact/show.html.twig', [
                'contact' => $contact,
            ]);
        }

        /**
         * @Route("/contact/{id}/edit", name="contact.edit", requirements={"id"="\d+"})
         */
        public function edit(Request $request, Contact $contact): Response
        {
            $form = $this->createForm(ContactType::class, $contact, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($contact);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('contact.show', ['id' => $contact->getId()]);
            }

            return $this->render('contact/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/contact/new", name="contact.new")
         */
        public function new(Request $request): Response
        {
            $contact = new Contact();

            // company
            if (null != $request->get('company_id')) {
                $company = $this->getDoctrine()->getRepository(Company::class)->find($request->get('company_id'));
                if (null != $company) {
                    $contact->setCompany($company);
                }
            }

            $form = $this->createForm(ContactType::class, $contact, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($contact);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('contact.show', ['id' => $contact->getId()]);
            }

            return $this->render('contact/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
