<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Entity\PortfolioIo;
use App\Entity\PortfolioLine;
use App\Form\PortfolioLineAddIoType;
use App\Form\PortfolioLineType;
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
    
        // Get user's (not archived) portfolios
        $portfolios = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findBy([
                'user' => $user,
                'archived' => false,
            ]);

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
        
        // Initialize with current date-time
        $portfolio->setCreateDate(new DateTime());
        
        // Set default values
        $portfolio->setInputs(0);
        $portfolio->setOutputs(0);
        $portfolio->setLastTotalAmount(0);
        $portfolio->setLastPerf(0);
        $portfolio->setArchived(false);

        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolio);
            $entityManager->flush();

            return $this->redirectToRoute('portfolio_index');
        }

        return $this->render('portfolio/new.html.twig', [
            'portfolio' => $portfolio,
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
        
        // Get user to check it is the owner of portfolio to be shown
        $user = $this->getUser();
        if ($user != $portfolio->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
        
        // List of active lines on this portfolio
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findActiveLines($portfolio);
    
        // Looking for an active transaction on this portfolio
        $transaction = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findOneBy([
                'portfolio' => $portfolio,
                'confirmDate' => NULL
            ]);
        
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'title' => 'fundlog: ' . $portfolio->getName(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="portfolio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        // Get user to check it is the owner of portfolio to be edited
        $user = $this->getUser();
        if ($user != $portfolio->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
    
        // Retrieving an active transaction on this portfolio
        $transaction = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findOneBy([
                'portfolio' => $portfolio,
                'confirmDate' => NULL
            ]);
        // Otherwise create new transaction
        if (!$transaction) {
            $transaction = new PortfolioIo();
            $transaction->setPortfolio($portfolio);
            // Initialize with current date-time
            $transaction->setCreationDate(new DateTime());
            // Set default values
            $transaction->setNetAmount(0);
            // Save to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
        }
        
        // Retrieve portfolio lines if not hidden
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findBy([
                'portfolio' => $portfolio,
                'ioHide' => false,
            ]);
        
        // Managing new line in portfolio for transaction
        $portfolio_new_line = new PortfolioLine();
        // Initialize new line
        $portfolio_new_line->setQty(0.0);
        $portfolio_new_line->setLvalue(0.0);
        $portfolio_new_line->setPortfolio($portfolio);
        $portfolio_new_line->setIoHide(false);
        
        $formAdd = $this->createForm(PortfolioLineAddIoType::class, $portfolio_new_line);
        $formAdd->handleRequest($request);
    
        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolio_new_line);
            $entityManager->flush();
        
            return $this->redirectToRoute('portfolio_edit', ['id' => $portfolio->getId()]);
        }
    
        return $this->render('portfolio/edit.html.twig', [
            'formAdd' => $formAdd->createView(),
            'portfolio' => $portfolio,
            'portfolio_new_line' => $portfolio_new_line,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'title' => 'fundlog: arbitrage sur' . $portfolio->getName(),
        ]);
    }
    
    /**
     * @Route("/{id}/archive", name="portfolio_archive", methods={"GET|POST"})
     */
    public function archive(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio to be closed
        $user = $this->getUser();
        if ($user != $portfolio->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
    
        // Update object dateEnd with current date-time
        $portfolio->setArchived(true);
    
        // database update
        $this->getDoctrine()->getManager()->flush();
    
        return $this->redirectToRoute('portfolio_index');
    }
    
    /**
     * @Route("/{id}", name="portfolio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        //TODO: archive portfolio before deleting or desactivate
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('portfolio_index');
    }
}
