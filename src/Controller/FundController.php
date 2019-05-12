<?php

namespace App\Controller;

use App\Entity\Fund;
use App\Entity\FundBase;
use App\Form\FundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundController extends BaseController
{
    /**
     * @Route("/fund", name="fund_index")
     */
    public function index(): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        // Get funds
        $funds = $this->getDoctrine()
            ->getRepository(Fund::class)
            ->findAll();
        
        //TODO: see if it is worth keeping it
        return $this->render('fund/index.html.twig', [
            'funds' => $funds,
            'title' => 'Fundlog: liste des fonds',
        ]);
    }
    
    /**
     * @Route("/fund/new", name="fund_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Create new fund
        $fund = new Fund();
        
        // Initialize with default values
        $fund->setLastLvalue(0.0);
        
        // Get fund list (array of objects)
        $fund_base = $this->getDoctrine()
            ->getRepository(FundBase::class)
            ->findBy([], ['name' => 'ASC']);
        
        // Create form
        $form = $this->createForm(FundType::class, $fund);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            // TODO: Some controls before saving to DB
            // check if ISIN is in list
            // check name
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fund);
            $entityManager->flush();
            
            return $this->redirectToRoute('fund_index');
        }
        
        return $this->render('fund/new.html.twig', [
            'fund' => $fund,
            'fund_base' => $fund_base,
            'form' => $form->createView(),
        ]);
    
    }
    
    
    
}
