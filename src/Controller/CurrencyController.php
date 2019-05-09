<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Service\HttpQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CurrencyController extends BaseController
{
    /**
     * @Route("/currency", name="currency_update", methods={"GET","POST"})
     */
    public function getCurrencies()
    {
        $currencies = $this->getDoctrine()
            ->getRepository(Currency::class)
            ->findAll();
        
        $request = new HttpQuery('http://api.openrates.io/latest');
        $request->createCurl();
        if ($request->getHttpStatus() != 200) {
            return new Response([
                "code" => $request->getHttpStatus(),
                "error" => $request->getHttpError(),
            ]);
        }
        $response = $request->getHttpJson();
        $rates = json_decode($response, true)["rates"];
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($rates as $cur => $rate) {
            $isfound = false;
            foreach ($currencies as $currency) {
                if ($cur == $currency->getCode()) {
                    // update existing currencies
                    $currency->setValue($rate);
                    $entityManager->persist($currency);
                    $isfound = true;
                }
            }
            if (!$isfound) {
                // create new currencies
                $new_currency = new Currency();
                $new_currency->setCode($cur);
                $new_currency->setValue($rate);
                $entityManager->persist($new_currency);
            }
        }
        $entityManager->flush();
    
        return $this->redirectToRoute('portfolio_index');
    }
}
