<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ConnexionController extends AbstractController
{
    // GET + 1er affichage page
    public function afficherPage()
    {
        $noError = '';
        $message = '';
        return $this->render('connexion/connexion.html.twig', [
            'message' => $message,
            'error' => $noError,
        ]);
    }

    // POST 
    public function connexionPost(Request $request, ManagerRegistry $doctrine)
    {
        $userRepository = new UserRepository($doctrine);
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $user = $userRepository->findOneBy(['username' => $username]);

        //Creation variable erreur si probleme
        $messageError1 = "Erreur";
        $messageError2 = "Un probleme a eu lieu, veuillez réessayer";

        if ($user === null) {
            // user not found
            return $this->render('connexion/connexion.html.twig', [
                'error' => $messageError1,
                'message' => $messageError2,
            ]);
        } else {
            if (password_verify($password, $user->getPassword())) {
                return $this->redirectToRoute('Simulations', []);
            } else {
                return $this->render('connexion/connexion.html.twig', [
                    'error' => $messageError1,
                    'message' => $messageError2,
                ]);
            }
        }
    }
}