<?php
	/**
	 * Created by PhpStorm.
	 * Tender: mperret
	 * Date: 24/10/2018
	 * Time: 16:37
	 */
	
	namespace App\Controller;
	
	use App\Entity\Tender;
	use App\Entity\TenderRow;
	use App\Form\Type\TenderRowType;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
	use Symfony\Component\Routing\Annotation\Route;
	
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	
	
	class TenderRowController extends AbstractController  {
		
		
		/**
		 * @Route("/tender-row/{id}", name="tender_row.show", requirements={"id"="\d+"})
		 *
		 * @param TenderRow $tenderRow
		 *
		 * @return Response
		 */
		public function show(TenderRow $tenderRow)
		{
			
			return $this->render('tender_row/show.html.twig', [
				'tenderRow' => $tenderRow,
			]);
		}
		
		
		/**
		 * @Route("/tender-row/{id}/edit", name="tender_row.edit", requirements={"id"="\d+"})
		 *
		 * @param Request $request
		 * @param TenderRow $tenderRow
		 *
		 * @return Response
		 */
		public function edit(Request $request, TenderRow $tenderRow)
		{
			
			$form = $this->createForm(TenderRowType::class, $tenderRow, [
				'action' => 'edit'
			]);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				// doctrine
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($tenderRow);
				$manager->flush();
				
				// goto show
				return $this->redirectToRoute('tender_row.show',['id' => $tenderRow->getId()]);
			}
			
			return $this->render('tender_row/edit.html.twig', [
				'form' => $form->createView(),
			]);
		}
		
		/**
		 * @Route("/tender-row/new", name="tender_row.new")
		 *
		 * @param Request $request
		 * @return Response
		 */
		public function new(Request $request)
		{
			
			$tenderRow = new TenderRow();
			
			// tender
			if($request->get('tender_id') != null){
				$tender = $this->getDoctrine()->getRepository(Tender::class)->find($request->get('tender_id'));
				if($tender != null){
					$tenderRow->setTender($tender);
					$tenderRow->setPosition($tender->getNextPosition());
				}
			}
			
			$form = $this->createForm(TenderRowType::class, $tenderRow, []);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				
				// doctrine
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($tenderRow);
				$manager->flush();
				
				// goto show
				//return $this->redirectToRoute('tender_row.show',['id' => $tenderRow->getId()]);
				return $this->redirectToRoute('tender_row.new',['tender_id' => $tenderRow->getTender()->getId()]);
			}
			
			return $this->render('tender_row/new.html.twig', [
				'form' => $form->createView(),
			]);
			
		}
		
	}
