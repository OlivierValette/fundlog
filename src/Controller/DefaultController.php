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
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        // If user connected redirect to portfolio page
        if ($user) {
            if ($isAdmin) return $this->redirectToRoute('currency_update');
            $this->addFlash('warning', 'Accès restreint aux bêta-testeurs.');
        }
        // Otherwise redirect to login page
        return $this->redirectToRoute('app_login');
    }
}