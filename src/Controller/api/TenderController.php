<?php

    /**
     * Created by PhpStorm.
     * User: mperret
     * Date: 24/10/2018
     * Time: 16:37.
     */

namespace App\Controller\api;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class TenderController extends AbstractController
    {
        /**
         * @Route("/api/tender", name="api.tender.index")
         *
         * @return Response
         */
        public function index(Request $request)
        {
            // render
            return $this->render('tender/index.html.twig', [
            ]);
        }
    }
