<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_registration")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            // 3bis) Define the default role
            $user->setRoles(['ROLE_USER']);
            
            // 3ter) Add createdAt info
            $user->setCreatedAt(new DateTime());
            
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            // send an email to the user / get its confirmation
            $message = (new \Swift_Message('Bienvenue sur fundlog !'))
                ->setFrom('fundlog.app@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        ['user' => $user]
                    ),
                    'text/html'
                )
            ;
    
            $mailer->send($message);
            
            // set a "flash" success message for the user
            
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }
}