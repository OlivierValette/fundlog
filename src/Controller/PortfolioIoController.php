<?php

namespace App\Controller;

use App\Entity\Middleman;
use App\Entity\Portfolio;
use App\Entity\PortfolioIo;
use App\Entity\PortfolioLine;
use DateTime;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/portfolioio")
 */
class PortfolioIoController extends AbstractController
{
    /**
     * @Route("/", name="portfolio_io_index", methods={"GET"})
     */
    public function index(): Response
    {
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        // Get user
        $user = $this->getUser();
    
        // Get user's portfolios
        // TODO: see if keeping archived portfolios excluded
        $portfolios = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findBy([
                'user' => $user,
                'archived' => false,
            ]);
    
        // Get user's PortfolioIo history
        $portfolioIos = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findPortfolioIoByUser($user->getId());
    
    
        return $this->render('portfolio_io/index.html.twig', [
            'portfolios' => $portfolios,
            'portfolio_ios' => $portfolioIos,
        ]);
    }
    
    /**
     * @Route("/{id}", name="portfolio_io_show", methods={"GET","POST"})
     */
    public function show(Request $request, PortfolioIo $portfolioIo): Response
    {
        
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio
        $user = $this->getUser();
        if ($user != $portfolioIo->getPortfolio()->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
    
        return $this->render('portfolio_io/show.html.twig', [
            'portfolio_io' => $portfolioIo,
        ]);
    }
   
    /**
     * @Route("/{id}/validate", name="portfolio_io_validate", methods={"GET","POST"})
     */
    public function validate(Request $request, PortfolioIo $portfolioIo): Response
    {
    
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        // Get user to check it is the owner of portfolio
        $user = $this->getUser();
        if ($user != $portfolioIo->getPortfolio()->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }

        // Set validation date
        $portfolioIo->setValidDate(new DateTime());
        // Save to database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($portfolioIo);
        $entityManager->flush();
    
        // Back to portfolio edit page
        return $this->redirectToRoute('portfolio_edit', [
            'id' => $portfolioIo->getPortfolio()->getId()
        ]);
    }
    
    /**
     * @Route("/{id}/send", name="portfolio_io_send", methods={"GET","POST"})
     */
    public function send(Request $request, PortfolioIo $portfolioIo, Swift_Mailer $mailer): Response
    {
        
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio
        $user = $this->getUser();
        if ($user != $portfolioIo->getPortfolio()->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
        
        // Retrieving the portfolio
        $portfolio = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findOneBy([
                'id' => $portfolioIo->getPortfolio(),
            ]);
        
        $middleman = $this->getDoctrine()
            ->getRepository(Middleman::class)
            ->findOneBy([
                'id' => $portfolio->getMiddleman(),
            ]);
    
        // Retrieving an active transaction on this portfolio
        $transaction = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findOneBy([
                'portfolio' => $portfolio,
                'confirmDate' => NULL
            ]);
    
        // Retrieve portfolio lines to be confirmed
        $portfolio_lines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findIoLines($portfolioIo->getPortfolio());
        
        // If no transaction to validate, go back
        if ($transaction and $transaction->getValidDate()) {
            // Send mail to middleman
            $message = (new Swift_Message("Demande d'arbitrage"))
                ->setFrom($user->getEmail())
                ->setTo($portfolioIo->getPortfolio()->getMiddleman()->getEmail())
                ->setBody(
                    $this->renderView('emails/transaction.html.twig',[
                        'user' => $user,
                        'middleman' => $middleman,
                        'portfolio' => $portfolio,
                        'portfolio_io' => $transaction,
                        'portfolio_lines' => $portfolio_lines,
                        ]),
                    'text/html'
                )
            ;
            $mailer->send($message);
            
            // Set validation date
            $portfolioIo->setSendDate(new DateTime());
            // Save to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolioIo);
            $entityManager->flush();
            
        }
        
        
        // Back to portfolio edit page
        return $this->redirectToRoute('portfolio_edit', [
            'id' => $portfolioIo->getPortfolio()->getId()
        ]);
    }
    
}
