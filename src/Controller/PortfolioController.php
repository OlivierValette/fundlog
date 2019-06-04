<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\FinInfo;
use App\Entity\Portfolio;
use App\Entity\PortfolioHist;
use App\Entity\PortfolioIo;
use App\Entity\PortfolioLine;
use App\Entity\PortfolioLineHist;
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
        $min = new DateTime('now');
        $max = new DateTime('2000-01-01');
        // Get portfolios historical values
        $portfolios_hist = [];
        foreach ($portfolios as $portfolio) {
            $pf_id = $portfolio->getId();
            $totalAmount += $portfolio->getLastTotalAmount();
            $portfolio_hist = $this->getDoctrine()
                    ->getRepository(PortfolioHist::class)
                    ->findBy([ 'portfolio' => $pf_id ]);
            foreach ($portfolio_hist as $key => $hist) {
                $cur_date = $hist->getLvdate();
                $cur_value = $hist->getLvalue();
                $max = $cur_date > $max ? $cur_date : $max;
                $min = $cur_date < $min ? $cur_date : $min;
                $portfolios_hist[$pf_id][$key] = ['lvdate' => $cur_date->format('Y-n-d'), 'lvalue' => $cur_value ];
            }
        }
        if ($max->format('Y-n-d') < $max->format('Y-n-t')) {
            $max->modify("last day of previous month");
        }
        // Get historical data in a matrix convenient for Google Charts
        $hist_values = [];
        $lvdate = $max;
        for ($line = 0; $line < 30; $line++) {
            if ( $lvdate < $min) break;
            $hist_values[$line][0] = $lvdate->format('Y-n-d');
            $lvdate = $lvdate->modify("last day of previous month");
        }
        $col = 1;
        foreach ($portfolios_hist as $portfolio_hist) {
            for ($line = count($hist_values)-1; $line >= 0; $line--) {
                $hist_values[$line][$col] = 0.;
                foreach ($portfolio_hist as $hist) {
                    // echo '<pre>Col:'.$col.' Line:'.$line.' - Test: '.$hist['lvdate'].' = '.$hist_values[$line][0].'</pre>';
                    if ($hist['lvdate'] == $hist_values[$line][0]) {
                        $hist_values[$line][$col] = $hist['lvalue'];
                        break;
                    }
                }
            }
            $col += 1;
        }

        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $portfolios,
            'hist_values' => $hist_values,
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
        $portfolio_hist = new PortfolioHist();
    
        // Get user to set portfolio owner
        $user = $this->getUser();
        if ($user) {
            $portfolio->setUser($user);
        } else {
            // Just in case (ceinture et bretelles)
            return $this->redirectToRoute('app_login');
        }
        
        // Initialize Portfolio with current date-time
        $now = new DateTime();
        $portfolio->setCreateDate($now);
        
        // Create one line in PortfolioHist
        $portfolio_hist->setPortfolio($portfolio);
        $portfolio_hist->setLvalue(0);
        $portfolio_hist->setLvdate($now->modify("last day of previous month"));
        
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
            $entityManager->persist($portfolio_hist);
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
    
        // data and links to financial infos
        $perfs = [];
        $links = [];
        foreach ($portfolio_lines as $portfolio_line) {
            $fin_infos = $this->getDoctrine()
                ->getRepository(FinInfo::class)
                ->findBy([ 'fund' => $portfolio_line->getFund() ]);
            $links [$portfolio_line->getId()] = [];
            if ($fin_infos) {
                foreach ($fin_infos as $fin_info) {
                    array_push($links [$portfolio_line->getId()], [
                        'source' => $fin_info->getSource()->getName(),
                        'url' => $fin_info->getSource()->getFundUrl() . $fin_info->getCode(),
                        ]);
                    if ($fin_info->getSource()->getName() == 'morningstar') {
                        $perfs[$portfolio_line->getId()] = $fin_info->getPerfA();
                    }
                }
            }
        }
        
        // Historical data (req: ordered by date asc)
        $portfolio_hist = $this->getDoctrine()
                    ->getRepository(PortfolioHist::class)
                    ->findBy(
                        ['portfolio' => $portfolio->getId()],
                        ['lvdate' => 'ASC']);
    
        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
            'portfolio_lines' => $portfolio_lines,
            'portfolio_io' => $transaction,
            'hist_values' => $portfolio_hist,
            'links' => $links,
            'perfs' => $perfs,
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
        
        // Calculate transaction total amount and set netAmount default value
        $io_total_amount = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->ioTotalAmount($portfolio);
        if ($transaction->getNetAmount() == 0.) {
            $transaction->setNetAmount($io_total_amount);
        }
        
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
    
        // Update inputs/outputs in portfolio with net total amount of transaction
        $netAmount = $transaction->getNetAmount();
        if ($netAmount > 0) {
            $inputs = $portfolio->getInputs();
            $portfolio->setInputs($inputs + $netAmount);
        } elseif ($netAmount < 0) {
            $outputs = $portfolio->getOutputs();
            $portfolio->setOutputs($outputs - $netAmount);
        }
        // save to db
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($portfolio);
        $entityManager->flush();
        
        // Set confirmDate and save to db
        $transaction->setConfirmDate(new DateTime());
        // save to db
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($transaction);
        $entityManager->flush();
        
        // Retrieve portfolio lines to be confirmed
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findActiveLines($portfolio);
        // reset io related columns
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($portfolio_lines as $portfolio_line) {
            $portfolio_line->setIoQty(null);
            $portfolio_line->setIoValue(null);
            // reset ioHide to false for next transaction
            $portfolio_line->setIoHide(false);
            // reset ioConfirm to false because transaction is fully confirmed
            $portfolio_line->setIoConfirm(false);
            $entityManager->persist($portfolio_line);
        }
        // save to db
        $entityManager->flush();
    
        // Compute and update new total amount of portfolio
        $totalAmount = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->totalAmount($portfolio);
        $portfolio->setLastTotalAmount($totalAmount);
        $this->getDoctrine()->getManager()->flush();
    
        // Creating new lines in portfolio_line_hist
        foreach ($portfolio_lines as $portfolio_line) {
            $pfl_hist_new = new PortfolioLineHist();
            $pfl_hist_new->setPortfolioLine($portfolio_line);
            $pfl_hist_new->setQty($portfolio_line->getQty());
            $pfl_hist_new->setLvdate($transaction->getConfirmDate());
            $pfl_hist_new->setLvalue($portfolio_line->getLvalue());
            $entityManager->persist($pfl_hist_new);
            $entityManager->flush();
        }
    
        // Creating a new line in portfolio_hist
        $pf_hist_new = new PortfolioHist();
        $pf_hist_new->setPortfolio($portfolio);
        $pf_hist_new->setLvdate($transaction->getConfirmDate());
        $pf_hist_new->setLvalue($totalAmount);
        $entityManager->persist($pf_hist_new);
        $entityManager->flush();
        
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
        
        // If no transaction, no need to reset!
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
        
        $warning = false;
        foreach ($portfolio_lines as $portfolio_line) {
            $entityManager = $this->getDoctrine()->getManager();
            // suppress new portfolio lines with qty = 0.0
            if ($portfolio_line->getQty() === 0.0) {
                // remove line from database
                $entityManager->remove($portfolio_line);
            }
            elseif ($portfolio_line->getIoConfirm()) {
            // TODO: get back to previous values if line is already confirmed
                $warning = true;
            }
            
            // reset io related properties
            $portfolio_line->setIoQty(null);
            $portfolio_line->setIoValue(null);
            $portfolio_line->setIoHide(false);
            $portfolio_line->setIoConfirm(false);
            // save to db
            $entityManager->flush();
        }
        
        if ($warning) {
            $this->addFlash(
                'danger',
                 "Des lignes étaient déjà confirmées, leurs quantités seront à reprendre. La demande d'arbitrage est supprimée."
            );
        } else {
            $this->addFlash(
                'warning',
                "La demande d'arbitrage est supprimée"
            );
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
    
        $this->addFlash(
            'warning',
            "La portefeuille " . $portfolio->getName() . " est archivé."
        );
        
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
            // check for existing lines in this portfolio
            $portfolio_lines = $this->getDoctrine()
                ->getRepository(PortfolioLine::class)
                ->findBy([ 'portfolio' => $portfolio ]);
            if ($portfolio_lines) {
                $this->addFlash(
                    'danger',
                    "La portefeuille " . $portfolio->getName() . " ne peut pas être supprimé, seulement archivé."
                );
                return $this->redirectToRoute('portfolio_show', ['id' => $portfolio->getId()]);
            }

            // check if existing transactions for this portfolio
            $portfolio_io = $this->getDoctrine()
                ->getRepository(PortfolioIo::class)
                ->findBy([ 'portfolio' => $portfolio ]);
            if ($portfolio_io) {
                $this->addFlash(
                    'danger',
                    "La portefeuille " . $portfolio->getName() . " ne peut pas être supprimé, seulement archivé."
                );
                return $this->redirectToRoute('portfolio_show', ['id' => $portfolio->getId()]);
            }
            // check if existing transactions for this portfolio
            $alert = $this->getDoctrine()
                ->getRepository(Alert::class)
                ->findBy([ 'portfolio' => $portfolio ]);
            if ($alert) {
                $this->addFlash(
                    'danger',
                    "La portefeuille " . $portfolio->getName() . " ne peut pas être supprimé, seulement archivé."
                );
                return $this->redirectToRoute('portfolio_show', ['id' => $portfolio->getId()]);
            }
            // retrieve existing history for this portfolio
            $portfolio_hist = $this->getDoctrine()
                ->getRepository(PortfolioHist::class)
                ->findBy([ 'portfolio' => $portfolio ]);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolio);
            foreach ($portfolio_hist as $pfh){
                $entityManager->remove($pfh);
            }
            $entityManager->flush();
    
            $this->addFlash(
                'warning',
                "La portefeuille " . $portfolio->getName() . " est supprimé."
            );
        }

        return $this->redirectToRoute('portfolio_index');
    }
}
