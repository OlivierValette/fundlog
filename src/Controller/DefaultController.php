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
        $isActive = $this->isGranted('ROLE_ACTIVE');
        // If user connected redirect to portfolio page
        if ($user) {
            if ($isActive) return $this->redirectToRoute('currency_update');
            $this->addFlash('warning', 'Accès restreint aux bêta-testeurs.');
        // Otherwise redirect to login page
        return $this->redirectToRoute('app_login');
    }
}
