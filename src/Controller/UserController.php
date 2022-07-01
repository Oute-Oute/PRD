<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{

    public function userGet(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    public function userAdd(Request $request, userRepository $userRepository): Response
    {
        // Méthode POST pour ajouter un utilisateur
        if ($request->getMethod() === 'POST') {
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $username = $param['username'];     // le nom d'utilisateur
            $password = $param['password'];     // le mot de passe temporaire
            $role = $param['role'];             // le role

            // Création de l'utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); //Encodage
            $user->setRoles(['ROLE_USER', $role]);

            // ajout dans la bdd
            $userRepository->add($user, true);
            return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
        }
    }


    public function userEdit(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $iduser = $request->request->get("iduser");
        $username = $request->request->get("username");
        $password = $request->request->get("password");
        $role = $request->request->get("role");

        $user = $userRepository->findOneBy(['id' => $iduser]);
        $user->setUsername($username);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_USER', $role]);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
    }

    public function userDelete(User $user, UserRepository $userRepository): Response
    {
        $modificationRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\Modification");
        $allModification = $modificationRepository->findBy(['id' => $user]);

        foreach ($allModification as $modification) {
            $modificationRepository->remove($modification, true);
        }

        //suppression du patient dans la table User
        $userRepository->remove($user, true);
        return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
    }
}
