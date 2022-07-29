<?php

namespace App\Controller;

use App\Entity\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;   



class ProfilController extends AbstractController
{


    public function profilGet(): Response
    {
        // Variable message  
        $messageError = '';
        $messageSucces = '';

        return $this->renderForm('profil/index.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
    }


    public function profilEdit(Request $request,  UserRepository $userRepository,ManagerRegistry $doctrine): Response
    {
        // Variable message  

        $messageError = '';
        $messageSucces = '';
        //Récuperation des differents élements rentré dans le formulaire
        $username = $request->request->get('username');
        $password1 = $request->request->get('old_password');
        $password2 = $request->request->get('new_password');
        $user = $userRepository->findOneBy(['username' => $username]);
        if (password_verify($password1, $user->getPassword())) {
            //Succes et MAJ de la base de donnée
            $user->setPassword(password_hash($password2, PASSWORD_DEFAULT));
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $messageSucces = "Modification effectuée avec succès";
            return $this->renderForm('profil/index.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
        } else {
            // Pas le bon mot de passe 
            $messageError = "L'ancien mot de passe est éronné";
            return $this->renderForm('profil/index.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
        }
    }
}
