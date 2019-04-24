<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        // Check if user connected
        $user = $this->getUser();
        if ($user) return $this->redirectToRoute('portfolio_index');
        return $this->redirectToRoute('app_login');
    }
}
