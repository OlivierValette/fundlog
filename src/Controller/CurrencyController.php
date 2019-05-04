<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CurrencyController extends BaseController
{
    /**
     * @Route("/currency", name="currency", methods={"GET","POST"})
     * @param HttpRequest $httpRequest
     * @return Response
     */
    public function getCurrencies()
    {
        $url = 'http://api.openrates.io/latest?base=EUR';
        $request = new Request();
    
        return new Response($request);
        
    }
}
