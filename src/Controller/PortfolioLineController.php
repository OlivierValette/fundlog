<?php

namespace App\Controller;

use App\Entity\PortfolioIo;
use App\Entity\PortfolioLine;
use App\Form\PortfolioLineConfirmType;
use App\Form\PortfolioLineType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolio/line")
 */
class PortfolioLineController extends AbstractController
{
    /**
     * @Route("/", name="portfolio_line_index", methods={"GET"})
     */
    public function index(): Response
    {
        $portfolioLines = $this->getDoctrine()
            ->getRepository(PortfolioLine::class)
            ->findAll();

        return $this->render('portfolio_line/index.html.twig', [
            'portfolio_lines' => $portfolioLines,
        ]);
    }

    /**
     * @Route("/new", name="portfolio_line_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $portfolioLine = new PortfolioLine();
        $form = $this->createForm(PortfolioLineType::class, $portfolioLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolioLine);
            $entityManager->flush();

            return $this->redirectToRoute('portfolio_line_index');
        }

        return $this->render('portfolio_line/new.html.twig', [
            'portfolio_line' => $portfolioLine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_line_show", methods={"GET"})
     */
    public function show(PortfolioLine $portfolioLine): Response
    {
        return $this->render('portfolio_line/show.html.twig', [
            'portfolio_line' => $portfolioLine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="portfolio_line_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PortfolioLine $portfolioLine): Response
    {
        $form = $this->createForm(PortfolioLineType::class, $portfolioLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('portfolio_line_index', [
                'id' => $portfolioLine->getId(),
            ]);
        }

        return $this->render('portfolio_line/edit.html.twig', [
            'portfolio_line' => $portfolioLine,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/io_edit", name="portfolio_line_io_edit", methods={"GET","POST"})
     */
    public function io_edit(Request $request, PortfolioLine $portfolioLine): Response
    {
        $form = $this->createForm(PortfolioLineType::class, $portfolioLine);
        $form->handleRequest($request);
    
        // Retrieving an active transaction on this portfolio
        $transaction = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findOneBy([
                'portfolio' => $portfolioLine->getPortfolio(),
                'confirmDate' => NULL
            ]);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
    
            // reset dates in case of line update to force new validation
            // and notification to middleman
            $transaction->setValidDate(null);
            $transaction->setSendDate(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();
            
            return $this->redirectToRoute('portfolio_edit', [
                'id' => $portfolioLine->getPortfolio()->getId()
            ]);
        }
        
        return $this->render('portfolio_line/io_edit.html.twig', [
            'portfolio_line' => $portfolioLine,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/io_confirm", name="portfolio_line_io_confirm", methods={"GET","POST"})
     */
    public function io_confirm(Request $request, PortfolioLine $portfolioLine): Response
    {
        $form = $this->createForm(PortfolioLineConfirmType::class, $portfolioLine);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Set ioConfirm to true once real values updated until transaction fully confirmed
            $portfolioLine->setIoConfirm(true);
            // save to db
            $this->getDoctrine()->getManager()->flush();
            // Then go back to confirmation page
            return $this->redirectToRoute('portfolio_confirm', [
                'id' => $portfolioLine->getPortfolio()->getId()
            ]);
        }
        
        return $this->render('portfolio_line/io_confirm.html.twig', [
            'portfolio_line' => $portfolioLine,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="portfolio_line_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PortfolioLine $portfolioLine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portfolioLine->getId(), $request->request->get('_token'))) {
            // controls if line is not empty
            $line = $this->getDoctrine()
                ->getRepository(PortfolioLine::class)
                ->findOneBy([ 'id' => $portfolioLine ]);
            if ($line->getQty() === 0.0) {
                // line is empty
                // delete line
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($portfolioLine);
                $entityManager->flush();
            } else {
                // line is not empty, is there an input in io_value?
                if ($line->getIoValue() === null or $line->getIoValue() === 0.0) {
                    // suppress IO values and hide line in transaction
                    $portfolioLine->setIoQty(0.0);
                    $portfolioLine->setIoValue(0.0);
                    $portfolioLine->setIoHide(true);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($portfolioLine);
                    $entityManager->flush();
                }
            }
        }
    
        return $this->redirectToRoute('portfolio_edit', [
            'id' => $portfolioLine->getPortfolio()->getId()
        ]);
    }
}
