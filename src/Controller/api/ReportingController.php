<?php

/**
 * Created by PhpStorm.
 * User: mperret
 * Date: 24/10/2018
 * Time: 16:37.
 */

namespace App\Controller\api;

    use App\Repository\ReportingRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class ReportingController extends AbstractController
    {
        /**
         * @Route("/api/reporting", name="api.reporting.index")
         */
        public function index(Request $request): Response
        {
            // render
            return $this->render('reporting/index.html.twig', [
            ]);
        }

        /**
         * @Route("/api/reporting/{report}", name="api.reporting.show")
         *
         * @return \Symfony\Component\HttpFoundation\JsonResponse
         *
         * @throws \Doctrine\DBAL\DBALException
         */
        public function show(string $report, Request $request, ReportingRepository $rep): Response
        {
            // todo gestion droits

            $rep->setInterval($request->get('dateX', 'Q'));

            $options = [
                'unit' => '€',
                'responsive' => true,
                'title' => [
                    'display' => true,
                    'text' => 'menu.'.$report,
                ],
            ];

            if ('comp' == $report) {
                $data = $rep->rptgTurnover('purchased');
                $d2 = $rep->rptgTurnover('billed');
                $d3 = $rep->rptgTurnover('turnover');

                $data['datasets'][] = $d2['datasets'][0];
                $data['datasets'][] = $d3['datasets'][0];

                $data['datasets'][0]['label'] = 'menu.purchased';
                $data['datasets'][1]['label'] = 'menu.billed';
                $data['datasets'][2]['label'] = 'menu.turnover';
                $data['datasets'][0]['borderColor'] = '#0000dd';
                $data['datasets'][1]['borderColor'] = '#1d45dd';
                $data['datasets'][2]['borderColor'] = '#3e95cd';
                $data['datasets'][0]['backgroundColor'] = $data['datasets'][0]['borderColor'];
                $data['datasets'][1]['backgroundColor'] = $data['datasets'][1]['borderColor'];
                $data['datasets'][2]['backgroundColor'] = $data['datasets'][2]['borderColor'];

                $data['datasets'][0]['fill'] = false;
                $data['datasets'][1]['fill'] = false;
                $data['datasets'][2]['fill'] = false;

            //$options['fill'] = false;
            } else {
                $data = $rep->rptgTurnover($report);

                // todo trad
                $data['datasets'][0]['label'] = 'menu.'.$report;
                $data['datasets'][0]['backgroundColor'] = '#3e95cd';
            }

            return $this->json([
                'data' => $data,
                'options' => $options,
            ]);
        }
    }
