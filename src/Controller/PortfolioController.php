<?php

namespace App\Controller;

use App\Entity\FinInfo;
use App\Entity\Portfolio;
use App\Entity\PortfolioHist;
use App\Entity\PortfolioIo;
use App\Entity\PortfolioLine;
use App\Form\PortfolioIoType;
use App\Form\PortfolioLineAddIoType;
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
        
        // Get total amount and historical values of portfolios
        $totalAmount = 0.;
        // Dates boundaries
        $min = strtotime('today');
        $max = strtotime();
        
        foreach ($portfolios as $portfolio) {
            $totalAmount += $portfolio->getLastTotalAmount();
            $portfolios_hist += [
                $portfolio->getId() => $this->getDoctrine()
                    ->getRepository(PortfolioHist::class)
                    ->findBy([ 'portfolio' => $portfolio->getId() ])
            ];
        }
        
        // Get user's  portfolios historical values
        $portfolios_hist = [];


        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios,
            'portfolios_hist' => $portfolios_hist,
            'totalAmount' => $totalAmount,
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
    
        // links to financial infos
        $links = [];
        foreach ($portfolio_lines as $portfolio_line) {
            $fin_infos = $this->getDoctrine()
                ->getRepository(FinInfo::class)
                ->findBy([ 'fund' => $portfolio_line->getFund() ]);
            $links [$portfolio_line->getId()] = [];
            foreach ($fin_infos as $fin_info) {
                array_push($links [$portfolio_line->getId()], [
                    'source' => $fin_info->getSource()->getName(),
                    'url' => $fin_info->getSource()->getFundUrl() . $fin_info->getCode(),
                    ]);
            }
        }
        
        // Historical data
        $portfolio_hist = $this->getDoctrine()
                    ->getRepository(PortfolioHist::class)
                    ->findBy([ 'portfolio' => $portfolio->getId() ]);
        
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'hist_values' => $portfolio_hist,
            'links' => $links,
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
            $transaction->setNetAmount(0.0);
            // Save to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
        }
        
        // Calculate transaction total amount
        $io_total_amount = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->ioTotalAmount($portfolio);
        
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
            
            // reset dates in case of new line
            $transaction->setValidDate(null);
            $transaction->setSendDate(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
        
            return $this->redirectToRoute('portfolio_edit', ['id' => $portfolio->getId()]);
        }
    
        return $this->render('portfolio/edit.html.twig', [
            'formAdd' => $formAdd->createView(),
            'portfolio' => $portfolio,
            'portfolio_new_line' => $portfolio_new_line,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'io_total_amount' => $io_total_amount,
            'title' => 'fundlog: arbitrage sur' . $portfolio->getName(),
        ]);
    }
    
    /**
     * @Route("/{id}/confirm", name="portfolio_confirm", methods={"GET","POST"})
     */
    public function confirm(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio to be confirmed
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
        
        // If no transaction, no need to confirm!
        if (!$transaction) {
            return $this->redirectToRoute('portfolio_index');
        }
        
        // Retrieve portfolio lines to be confirmed
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findIoLines($portfolio);
        
        // Controls confirmation progression
        $status = false;
        $total_number_lines = sizeof($portfolio_lines);
        $confirmed_lines = 0;
        foreach ($portfolio_lines as $portfolio_line) {
            if ($portfolio_line->getIoConfirm()) {
                ++$confirmed_lines;
            }
        }
        if ($confirmed_lines == $total_number_lines && $transaction->getNetAmount() != null) $status = true;
        
    
        // Prepare form fo net_amount (PortfolioIo) input
        $form = $this->createForm(PortfolioIoType::class, $transaction);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolio);
            $entityManager->flush();
        
            return $this->redirectToRoute('portfolio_confirm', ['id' => $portfolio->getId()]);
        }
    
        return $this->render('portfolio/confirm.html.twig', [
            'form' => $form->createView(),
            'portfolio' => $portfolio,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'status' => $status,
            'title' => 'fundlog: confirmation ' . $portfolio->getName(),
        ]);
    }
    
    /**
     * @Route("/{id}/confirmed", name="portfolio_confirmed", methods={"GET","POST"})
     */
    public function io_confirmed(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        // Get user to check it is the owner of portfolio to be confirmed
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
    
        // If no transaction, no need to confirm!
        if (!$transaction) {
            return $this->redirectToRoute('portfolio_index');
        }
        // Set confirmDate and save to db
        $transaction->setConfirmDate(new DateTime());
        $this->getDoctrine()->getManager()->flush();
    
        // Retrieve portfolio lines to be confirmed
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findIoLines($portfolio);
        
        // reset io related columns
        foreach ($portfolio_lines as $portfolio_line) {
            $portfolio_line->setIoQty(null);
            $portfolio_line->setIoValue(null);
            // reset ioHide to false for next transaction
            $portfolio_line->setIoHide(false);
            // reset ioConfirm to false because transaction is fully confirmed
            $portfolio_line->setIoConfirm(false);
        }
        // save to db
        $this->getDoctrine()->getManager()->flush();
    
        
        // Compute and update new total amount of portfolio
        $totalAmount = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->totalAmount($portfolio);
        $portfolio->setLastTotalAmount($totalAmount);
        $this->getDoctrine()->getManager()->flush();
        
        
        // Then go back to portfolios page
        return $this->redirectToRoute('portfolio_index');
    }
    
    /**
     * @Route("/{id}/reset", name="portfolio_reset", methods={"GET","POST"})
     */
    public function io_reset(Request $request, Portfolio $portfolio): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio to be confirmed
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
        
        // If no transaction, no need to confirm!
        if (!$transaction) {
            return $this->redirectToRoute('portfolio_index');
        }
        
        // delete transaction
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($transaction);
        $entityManager->flush();
        
        // Retrieve portfolio lines
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findBy([ 'portfolio' => $portfolio ]);
        
        // suppress new portfolio lines with qty = 0.0
        foreach ($portfolio_lines as $portfolio_line) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($portfolio_line->getQty === 0.0) {
                // remove line from database
                $entityManager->remove($portfolio_line);
            }
            else {
                // reset io related columns in others
                $portfolio_line->setIoQty(null);
                $portfolio_line->setIoValue(null);
                $portfolio_line->setIoHide(null);
                // reset ioConfirm to false because transaction is fully confirmed
                $portfolio_line->setIoConfirm(false);
                // save to db
            }
            $entityManager->flush();
        }
        
        // Then go back the portfolio page
        return $this->redirectToRoute('portfolio_show', ['id' => $portfolio->getId()]);
    }
    
    /**
     * @Route("/{id}/export/portfolio.csv", name="portfolio_export")
     */
    public function exportCsv(Portfolio $portfolio)
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
        
        $rows = [];
        $data = [
            'PORTEFEUILLE',
            'LIGNE',
            'ISIN',
            'LIBELLE DU FONDS',
            'QUANTITE',
            'DERNIERE VALEUR LIQUIDATIVE',
        ];
        $rows[] = implode(';', $data);
        foreach ($portfolio_lines as $portfolio_line) {
            $data = [
                $portfolio_line->getPortfolio()->getName(),
                $portfolio_line->getId(),
                $portfolio_line->getfund()->getIsin(),
                $portfolio_line->getfund()->getName(),
                number_format($portfolio_line->getQty(),4, ',', ''),
                number_format($portfolio_line->getlvalue(),2, ',', ''),
            ];
            $rows[] = implode(';', $data);
        }
        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        
        return $response;
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
        
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('portfolio_index');
    }
}
