<?php

namespace App\Controller;

use App\Entity\Lifeinsurance;
use App\Entity\Middleman;
use App\Entity\Portfolio;
use App\Form\PortfolioType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolio")
 */
class PortfolioController extends BaseController
{
    /**
     * @Route("/", name="portfolio_index", methods={"GET"})
     */
    public function index(): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        $portfolios = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findBy(['user' => $user]);

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios,
            'title' => 'fundlog: Portfolios',
        ]);
    }

    /**
     * @Route("/new", name="portfolio_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Create new portfolio
        $portfolio = new Portfolio();
    
        // Get user to set portfolio owner
        $user = $this->getUser();
        if ($user) {
            $portfolio->setUser($user);
        } else {
            // Just in case (ceinture et bretelles)
            return $this->redirectToRoute('app_login');
        }
        
        // Set default values
        $portfolio->setInputs(0);
        $portfolio->setOutputs(0);
        $portfolio->setLastTotalAmount(0);
        $portfolio->setLastPerf(0);
        $portfolio->setArchived('false');

        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolio);
            $entityManager->flush();

            return $this->redirectToRoute('portfolio_index');
        }
        
        // Get middleman and lifeinsurance lists
        $middleman = $this->getDoctrine()
            ->getRepository(Middleman::class)
            ->findAll();
        $lifeinsurance = $this->getDoctrine()
            ->getRepository(Lifeinsurance::class)
            ->findAll();

        return $this->render('portfolio/new.html.twig', [
            'portfolio' => $portfolio,
            'middleman' => $middleman,
            'lifeinsurance' => $lifeinsurance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_show", methods={"GET"})
     */
    public function show(Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="portfolio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('portfolio_index', [
                'id' => $portfolio->getId(),
            ]);
        }

        return $this->render('portfolio/edit.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('portfolio_index');
    }
}
