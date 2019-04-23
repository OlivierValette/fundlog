<?php

namespace App\Controller;

use App\Entity\Portfolio;
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
        $user = $this->getUser();
    
        $portfolios = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findBy(['user' => $user]);
    
        return $this->render('portfolios/index.html.twig', [
            'portfolios' => $portfolios,
            'title' => 'fundlog: Portfolios',
        ]);
        
    }
}
