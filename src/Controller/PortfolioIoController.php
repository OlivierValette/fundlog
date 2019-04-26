<?php

namespace App\Controller;

use App\Entity\PortfolioIo;
use App\Form\PortfolioIoType;
use DateTime;
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
        $portfolioIos = $this->getDoctrine()
            ->getRepository(PortfolioIo::class)
            ->findAll();

        return $this->render('portfolio_io/index.html.twig', [
            'portfolio_ios' => $portfolioIos,
        ]);
    }

//    /**
//     * @Route("/new", name="portfolio_io_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $portfolioIo = new PortfolioIo();
//        $form = $this->createForm(PortfolioIoType::class, $portfolioIo);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($portfolioIo);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('portfolio_io_index');
//        }
//
//        return $this->render('portfolio_io/new.html.twig', [
//            'portfolio_io' => $portfolioIo,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="portfolio_io_show", methods={"GET"})
//     */
//    public function show(PortfolioIo $portfolioIo): Response
//    {
//        return $this->render('portfolio_io/show.html.twig', [
//            'portfolio_io' => $portfolioIo,
//        ]);
//    }
    
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
    public function send(Request $request, PortfolioIo $portfolioIo): Response
    {
        
        // Checking to see if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // Get user to check it is the owner of portfolio
        $user = $this->getUser();
        if ($user != $portfolioIo->getPortfolio()->getUser()) {
            return $this->redirectToRoute('portfolio_index');
        }
        
        //TODO: send mail to middleman!
        
        // Set validation date
        $portfolioIo->setSendDate(new DateTime());
        // Save to database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($portfolioIo);
        $entityManager->flush();
        
        // Back to portfolio edit page
        return $this->redirectToRoute('portfolio_edit', [
            'id' => $portfolioIo->getPortfolio()->getId()
        ]);
    }
    
//    /**
//     * @Route("/{id}/edit", name="portfolio_io_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, PortfolioIo $portfolioIo): Response
//    {
//        $form = $this->createForm(PortfolioIoType::class, $portfolioIo);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('portfolio_io_index', [
//                'id' => $portfolioIo->getId(),
//            ]);
//        }
//
//        return $this->render('portfolio_io/edit.html.twig', [
//            'portfolio_io' => $portfolioIo,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="portfolio_io_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, PortfolioIo $portfolioIo): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$portfolioIo->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($portfolioIo);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('portfolio_io_index');
//    }

}
