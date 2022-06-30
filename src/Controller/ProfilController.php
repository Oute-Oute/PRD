<?php

namespace App\Controller;

use App\Entity\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class ProfilController extends AbstractController
{


    public function profilEdit(Request $request, User $user, UserRepository $userRepository): Response
    {
        // Variable message  
        $messageError = '';
        $messageSucces = '';

        //Récuperation des differents élements rentré dans le formulaire
        $password1 = $request->request->get('old_password');
        $password2 = $request->request->get('new_password');

        //Affichage de la page 
        if ($password1 == null) {
            return $this->renderForm('user/profile.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
        }
        // Si des information on été rentré, on test si le mot de passe 
        // Puis si les nouveau mot de passe coincident
        else {
            if (password_verify($password1, $user->getPassword())) {

                //Succes et MAJ de la base de donnée
                $user->setPassword(password_hash($password2, PASSWORD_DEFAULT));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $messageSucces = "Modification effectué avec succès";
                return $this->renderForm('user/profile.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
            } else {
                // Pas le bon mot de passe 
                $messageError = "L'ancien mot de passe est éronné";
                return $this->renderForm('user/profile.html.twig', ['messageSucces'  => $messageSucces, 'messageError'  => $messageError]);
            }
        }
    }
}
