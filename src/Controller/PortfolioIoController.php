<?php

namespace App\Controller;

use App\Entity\PortfolioIo;
use App\Form\PortfolioIoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolio/io")
 */
class PortfolioIoController extends AbstractController
{
    /**
     * @Route("/", name="portfolio_io_index", methods={"GET"})
     */
    public function index(): Response
    {
        $portfolioIos = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findAll();

        return $this->render('portfolio_io/index.html.twig', [
            'portfolio_ios' => $portfolioIos,
        ]);
    }

    /**
     * @Route("/new", name="portfolio_io_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $portfolioIo = new PortfolioIo();
        $form = $this->createForm(PortfolioIoType::class, $portfolioIo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolioIo);
            $entityManager->flush();

            return $this->redirectToRoute('portfolio_io_index');
        }

        return $this->render('portfolio_io/new.html.twig', [
            'portfolio_io' => $portfolioIo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_io_show", methods={"GET"})
     */
    public function show(PortfolioIo $portfolioIo): Response
    {
        return $this->render('portfolio_io/show.html.twig', [
            'portfolio_io' => $portfolioIo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="portfolio_io_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PortfolioIo $portfolioIo): Response
    {
        $form = $this->createForm(PortfolioIoType::class, $portfolioIo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('portfolio_io_index', [
                'id' => $portfolioIo->getId(),
            ]);
        }

        return $this->render('portfolio_io/edit.html.twig', [
            'portfolio_io' => $portfolioIo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="portfolio_io_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PortfolioIo $portfolioIo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portfolioIo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($portfolioIo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('portfolio_io_index');
    }
}
