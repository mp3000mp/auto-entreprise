<?php
	/**
	 * Created by PhpStorm.
	 * User: mperret
	 * Date: 24/10/2018
	 * Time: 16:37
	 */
	
	namespace App\Controller\api;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
	use Symfony\Component\Routing\Annotation\Route;
	
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	
	
	class CompanyController extends AbstractController  {
		
		
		/**
		 * @Route("/api/company", name="api.company.index")
		 *
		 * @return Response
		 */
		public function index(Request $request)
		{
			// render
			return $this->render('contact/index.html.twig', [
			
			]);
		}
		
		
		
	}
