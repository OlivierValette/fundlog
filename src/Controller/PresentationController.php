<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class PresentationController extends BaseController
{
    /**
     * @Route("/presentation", name="presentation")
     */
    public function index()
    {
        return $this->render('presentation/index.html.twig', [
            'controller_name' => 'PresentationController',
        ]);
    }
}
