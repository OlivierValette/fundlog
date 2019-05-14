<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Portfolio;
use App\Form\ResetPasswordType;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function profil(Request $request, UserPasswordEncoderInterface $passwordEncoder, Swift_Mailer $mailer): Response
    {
        // Get user
        $user = $this->getUser();
        
        // Get portfolios
        $portfolios = $this->getDoctrine()
            ->getRepository(Portfolio::class)
            ->findBy([ 'user' => $user ]);
        
        // Get user's alerts
        $alerts = $this->getDoctrine()
            ->getRepository(Alert::class)
            ->findAlertsByUser($user->getId());
        
        // Form for password change
        // 1) build the form
        $form = $this->createForm(ResetPasswordType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password
            $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($newEncodedPassword);
            // 4) save user to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // 5) send an email to the user / get its confirmation
            $message = (new \Swift_Message('Mot de passe modifié !'))
                ->setFrom('fundlog.app@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('emails/reset_password.html.twig', ['user' => $user ]),'text/html');
            $mailer->send($message);
            // 6) set a "flash" success message for the user
            $this->addFlash('info', 'Votre mot de passe à bien été changé !');
            return $this->redirectToRoute('portfolio_index');
        }
        
        return $this->render('security/profile.html.twig', [
            'user' => $user,
            'portfolios' => $portfolios,
            'alerts' => $alerts,
            'form' => $form->createView(),
        ]);
    }
}
