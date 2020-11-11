<?php
/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

use App\Form\Type\UserType;
use OTPHP\TOTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * page login normale.
     *
     * @Route("/login", name="login")
     *
     * @return Response
     */
    public function login(AuthorizationCheckerInterface $authChecker, AuthenticationUtils $authenticationUtils)
    {
        // si déjà authentifié => home
        if ($authChecker->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app.home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // formulaire de connexion
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * on génère un token et on envoi un mail avec lien reset.
     *
     * @Route("/forgottenpass/{email}", name="security.forgottenpass", requirements={"email"=".*"})
     *
     * @param $email
     *
     * @return Response
     */
    public function forgottenPasswordAction($email)
    {
        // render
        return $this->render('app/index.html.twig', [
        ]);
    }

    /**
     * @Route("/logincheck", name="login_check", methods={"POST"})
     */
    public function loginCheck()
    {
        // def route
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // def route
    }

    /**
     * @Route("/profile", name="security.my_account")
     */
    public function myAccount(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // doctrine
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            // goto show
            return $this->redirectToRoute('security.my_account');
        }

        // otp url pour QR code
        $otp = TOTP::create('JVIDGMBQGBJUKQ2SIVKA====');
        $otp->setLabel($user->getUserName());
        $otp->setIssuer('MP3000');
        $otp->setParameter('image', 'https://assets.gitlab-static.net/uploads/-/system/user/avatar/2876944/avatar.png');

        return $this->render('security/myaccount.html.twig', [
            'form' => $form->createView(),
            'otpUrl' => $otp->getProvisioningUri(),
        ]);
    }
}
