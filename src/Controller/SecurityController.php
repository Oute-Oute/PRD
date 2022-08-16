<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class SecurityController extends AbstractController
{

    /**
      * @brief Allows to make checks when a user is trying to authenticate on the website
     */

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
      * @brief Allows to log out a logged user and delete his session
     */

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): Response
    {


        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
      * @brief A generic function that redirects to the login page
     */

    public function redirection($user): Response
    {
        if ($user == null) {
            return $this->render('security/login.html.twig');
        }
    }
}
