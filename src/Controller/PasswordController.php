<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Security\ForgottenPasswordType;
use App\Form\Security\ResetPasswordType;
use App\Form\Security\SetPasswordType;
use App\Service\Mailer\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppController.
 */
class PasswordController extends AbstractController
{
    /**
     * @Route("/reset-password", name="reset_password")
     *
     * @throws \Exception
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // create form
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get user
            /** @var User $user */
            $user = $this->getUser();

            // check password
            if ($encoder->isPasswordValid($user, $form->get('password_current')->getData())) {
                // change password
                $newPassword = $form->get('password_new')->getData();
                $user->setPasswordUpdatedAt(new \DateTime());
                $user->setResetPasswordAt(null);
                $user->setResetPasswordToken(null);
                $user->setPassword($encoder->encodePassword($user, $newPassword));

                // persist
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // redirect
                $this->addFlash('success', 'security.reset_password_success');

                return $this->redirectToRoute('app.home');
            } else {
                $form->get('password_current')->addError(new FormError('security.connexion.err.bad_password'));
            }
        }

        // view
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forgotten-password", name="forgotten_password")
     *
     * @throws \Exception
     */
    public function forgottenPassword(Request $request, MailerService $mailerService): Response
    {
        // create form
        $form = $this->createForm(ForgottenPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get user
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)
                       ->findOneBy(['email' => $form->get('email')->getData()])
            ;

            if (null !== $user) {
                // set reset token
                $user->setResetPasswordAt(new \DateTime());
                $user->setResetPasswordToken(md5(random_bytes(64)));

                // send mail
                $mailerService->sendEmail('forgotten_password', [
                    'token' => $user->getResetPasswordToken(),
                ], 'security.forgotten_password', [], [$user->getEmail()]);

                // persist
                $em->persist($user);
                $em->flush();
            }

            // redirect login
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'security.forgotten_password.msg.success');

            return $this->redirectToRoute('login');
        }

        // view
        return $this->render('security/forgotten_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/set-password/{token}", name="set_password")
     *
     * @throws \Exception
     */
    public function setPassword(Request $request, UserPasswordEncoderInterface $encoder, string $token): Response
    {
        // get user from token
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)
                   ->findOneBy(['reset_password_token' => $token])
        ;

        // if not user, bad token
        if (null === $user) {
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('warning', 'security.set_password.bad_token');

            return $this->redirectToRoute('login');
        }

        // create form
        $form = $this->createForm(SetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // change password
            $newPassword = $form->get('password_new')->getData();
            $user->setPasswordUpdatedAt(new \DateTime());
            $user->setResetPasswordAt(null);
            $user->setResetPasswordToken(null);
            $user->setPassword($encoder->encodePassword($user, $newPassword));

            // persist
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // redirect
            $this->addFlash('success', 'security.set_password_success');

            return $this->redirectToRoute('app.home');
        }

        // view
        return $this->render('security/set_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/expired-password", name="expired_password")
     *
     * @throws \Exception
     */
    public function expiredPassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // create form
        $form = $this->createForm(SetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get user
            /** @var User $user */
            $user = $this->getUser();

            // change password
            $newPassword = $form->get('password_new')->getData();
            $user->setPasswordUpdatedAt(new \DateTime());
            $user->setResetPasswordAt(null);
            $user->setResetPasswordToken(null);
            $user->setPassword($encoder->encodePassword($user, $newPassword));

            // persist
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // redirect
            $this->addFlash('success', 'security.set_password_success');

            return $this->redirectToRoute('app.home');
        }

        // view
        return $this->render('security/expired_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
