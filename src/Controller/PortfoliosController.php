<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class PortfoliosController extends BaseController
{
    /**
     * @Route("/portfolios", name="portfolios")
     */
    public function index()
    {
        return $this->render('portfolios/index.html.twig', [
            'controller_name' => 'PortfoliosController',
        ]);
    }
}
