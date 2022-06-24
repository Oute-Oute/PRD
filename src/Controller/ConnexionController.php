<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;
use PhpParser\Node\Name;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ManagerRegistry;

class ConnexionController extends AbstractController
{

    public function afficherPage()
    {
        $message = '';
        return $this->render('connexion/connexion.html.twig', [
            'Message' => $message,
        ]);
    }

    public function connexionPost(Request $request, ManagerRegistry $doctrine)
    {
        $userRepository = new UserRepository($this->getDoctrine());
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $user     = $userRepository->findOneBy(['username' => $username]);
        $messageError1 = "Erreur";
        $messageError2 = "Un probleme a eu lieu, veuillez réessayer";

        if ($user === null) {
            // user not found
            // throw exception or return error or however you handle it

            return $this->render('connexion/connexion.html.twig', [
                'Message' => $messageError,
            ]);
        } else {
            if (password_verify($password, $user->getPassword())) {




                return $this->render("base.html.twig");
            } else {

                return $this->render('connexion/connexion.html.twig', [
                    'Message' => $messageError,
                ]);
            }
        }
    }


    //public function testIdentifiant(Request $request)
    // {
    //  $user = new User();
    //$identifiant = $user->findOneBy(['login' => username]);
    // return $identifiant;
    //}
}
