<?php
/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

use App\Entity\Opportunity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AppController.
 */
class AppController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // get data
        $opportunities = $this->getDoctrine()->getRepository(Opportunity::class)
            ->findWelcomeDashboard();

        // render
        return $this->render('app/index.html.twig', [
            'opportunities' => $opportunities,
        ]);
    }

    /**
     * @Route("/language/{code}", name="app.language", requirements={"code"="[a-z]{2}"})
     *
     * @return Response
     */
    public function language(Request $request, $code)
    {
        // si langue pas possible
        if (!in_array($code, ['fr', 'en'])) {
            return $this->redirectToRoute('app.index');
        }

        // on redirigera vers là où on était
        if (null != $request->query->get('from')) {
            $lastRoad = $request->query->get('from');
        } else {
            $lastRoad = $request->headers->get('referer');
        }

        // on set session serveur
        $this->get('session')->set('_locale', $code);

        // on set cookie client et on redirige vers accueil
        $response = new Response('', 307);
        $response->headers->setCookie(new Cookie('locale', $code, strtotime('now + 6 months')));
        $response->headers->set('location', $lastRoad);

        return $response;
    }
}
