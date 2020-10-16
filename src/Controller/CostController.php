<?php
	/**
	 * Created by PhpStorm.
	 * User: mperret
	 * Date: 24/10/2018
	 * Time: 16:37
	 */
	
	namespace App\Controller;
	
	use App\Entity\Cost;
	use App\Form\Type\CostType;
	use App\Repository\ReportingRepository;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
	use Symfony\Component\Routing\Annotation\Route;
	
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	
	
	class CostController extends AbstractController  {
		
		
		/**
		 * @Route("/cost", name="cost.index")
		 *
		 * @return Response
		 */
		public function index(Request $request, ReportingRepository $rep)
		{
			
			$list = $this->getDoctrine()->getRepository(Cost::class)
			                  ->findAll();
			
			// taxe à payer
			$tx = 0.221;
			$rep->setInterval('Q');
			$stats = $rep->rptgTurnover('turnover');
			$turnOver = $stats['datasets'][0]['data'][count($stats['datasets'][0]['data'])-2];
			
			// render
			return $this->render('cost/index.html.twig', [
				'list' => $list,
				'vjsData' => [
					'locale' => $request->getLocale(),
					'trad' => [
					
					],
					'costs' => array_map(function($cost){
						return $cost->jsonize();
					}, $list),
					'taxe' => round($tx*$turnOver,2),
				],
			]);
		}
		
		/**
		 * @Route("/cost/{id}/edit", name="cost.edit", requirements={"id"="\d+"})
		 *
		 * @param Cost $cost
		 *
		 * @return Response
		 */
		public function edit(Request $request, Cost $cost)
		{
			$form = $this->createForm(CostType::class, $cost, []);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				// doctrine
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($cost);
				$manager->flush();
				
				// goto show
				return $this->json([
					'status' => '1',
					'cost' => $cost->jsonize(),
				]);
			}
			
			return $this->render('cost/edit.html.twig', [
				'form' => $form->createView(),
				'cost' => $cost,
			]);
		}
		
		
		/**
		 * @Route("/cost/new", name="cost.new")
		 *
		 * @param Request $request
		 * @return Response
		 */
		public function new(Request $request)
		{
			
			$cost = new Cost();
			
			$form = $this->createForm(CostType::class, $cost, []);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				// doctrine
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($cost);
				$manager->flush();
				
				// goto show
				return $this->json([
					'status' => '1',
					'cost' => $cost->jsonize(),
				]);
			}
			
			return $this->render('cost/new.html.twig', [
				'form' => $form->createView(),
			]);
			
		}
		
	}
