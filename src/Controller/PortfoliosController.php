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
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        return $this->render('portfolios/index.html.twig', [
            'controller_name' => 'PortfoliosController',
        ]);
    }
}
