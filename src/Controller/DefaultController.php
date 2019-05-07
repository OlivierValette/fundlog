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
        $user = $this->getUser();
        // If user connected redirect to portfolio page
        if ($user) return $this->redirectToRoute('currency_update');
        // Otherwise redirect to login page
        return $this->redirectToRoute('app_login');
    }
}
