<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CalcController extends AbstractController
{
	private function division(int $nb1, int $nb2): int|float{
			if($nb2 === 0){
				throw new \InvalidArgumentException('Division par 0 impossible');
			}
			else{
				return $nb1 / $nb2 ;
			}
		}

    #[Route('/calc', name: 'app_calc')]
    public function index(Request $request): Response
    {
		$nb1 = $request->query->get('nb1');
		$nb2 = $request->query->get('nb2');
		$operateur = $request->query->get('operateur');

		if ($nb1 === null && $nb2 === null && $operateur === null ){
			return $this->render('calc/index.html.twig', [
				'result' => null,
				'error' => null]);
		}
		if(!preg_match('/^\d+$/', $nb1)){
			return $this->render('calc/index.html.twig', [
				'result' => null,
				'error' => 'Nombre 1 invalide']);
		}
		if(!preg_match('/^\d+$/', $nb2)){
			return $this->render('calc/index.html.twig', [
				'result' => null,
				'error' => 'Nombre 2 invalide']);
		}
		if(!preg_match('/^[+\-*\/]$/', $operateur)){
			return $this->render('calc/index.html.twig', [
				'result' => null,
				'error' => 'Operateur invalide']);
		}

		$nb1 = (int) $nb1;
		$nb2 = (int) $nb2;
		$result = null;
		$error = null;

		try{
			$result = match($operateur){
				'+' => $nb1 + $nb2,
				'-' => $nb1 - $nb2,
				'*' => $nb1 * $nb2,
				'/' => $this->division($nb1,$nb2),
				default => 'operateur inconnu'
			};
		}catch (\InvalidArgumentException $e){
			$error = $e->getMessage();
		}
		
        return $this->render('calc/index.html.twig', [ 
			'result' => $result,
			'error' => $error
			]);
    }

}
