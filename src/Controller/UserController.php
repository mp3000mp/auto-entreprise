<?php

    /**
     * Created by PhpStorm.
     * User: mperret
     * Date: 24/10/2018
     * Time: 16:37.
     */

namespace App\Controller;

    use App\Entity\User;
    use App\Form\Type\UserType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class UserController extends AbstractController
    {
        /**
         * @Route("/user", name="user.index")
         *
         * @return Response
         */
        public function index(Request $request)
        {
            $list = $this->getDoctrine()->getRepository(User::class)
                         ->findAll();

            // render
            return $this->render('user/index.html.twig', [
                'list' => $list,
            ]);
        }

        /**
         * @Route("/user/{id}", name="user.show", requirements={"id"="\d+"})
         *
         * @return Response
         */
        public function show(User $user)
        {
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }

        /**
         * @Route("/user/{id}/edit", name="user.edit", requirements={"id"="\d+"})
         *
         * @return Response
         */
        public function edit(Request $request, User $user)
        {
            $form = $this->createForm(UserType::class, $user, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('user.show', ['id' => $user->getId()]);
            }

            return $this->render('user/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        /**
         * @Route("/user/new", name="user.new")
         *
         * @return Response
         */
        public function new(Request $request)
        {
            $user = new User();

            $form = $this->createForm(UserType::class, $user, []);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // doctrine
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();

                // goto show
                return $this->redirectToRoute('user.show', ['id' => $user->getId()]);
            }

            return $this->render('user/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
