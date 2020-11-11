<?php

/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Contracts\Translation\TranslatorInterface;

    class ReportingController extends AbstractController
    {
        /**
         * @Route("/reporting", name="reporting.index")
         */
        public function indexAction(Request $request, TranslatorInterface $translator): Response
        {
            // render
            return $this->render('reporting/index.html.twig', [
                'trads' => $translator->getCatalogue()->all('reporting'),
            ]);
        }
    }
