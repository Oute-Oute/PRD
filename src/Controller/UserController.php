<?php
/**
 * @file        UserController.php
 * @brief       Contains the functions that handle the users in the database
 * @date        2022
 */
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
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;



class UserController extends AbstractController
{
    
    /**
      * Allows to list every users in the database
     */

    public function userGet(UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {

        $listeUser = $this->listUserJSON($doctrine);
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'listeUser' => $listeUser
        ]);
    }


    /**
      * Allows to add a new user in the database
     */

    public function userAdd(Request $request, userRepository $userRepository): Response
    {
        // Méthode POST pour ajouter un utilisateur
        if ($request->getMethod() === 'POST') {
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $username = $param['usernameAdd'];     // le nom d'utilisateur
            $password = $param['passwordAdd'];     // le mot de passe temporaire
            $role = $param['roleAdd'];             // le role

            // Création de l'utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); //Encodage
            $user->setRoles(['ROLE_USER', $role]);
            // ajout dans la bdd
            $userRepository->add($user, true);

            //redirection erreur

            return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
      * Allows to edit a user that is already in the database
     */
    public function userEdit(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $iduserEdit = $request->request->get("iduserEdit");
        $usernameEdit = $request->request->get("usernameEdit");
        $passwordEdit = $request->request->get("passwordEdit");
        $roleEdit = $request->request->get("roleEdit");

        $user = $userRepository->findOneBy(['id' => $iduserEdit]);
        $user->setUsername($usernameEdit);
        $user->setPassword(password_hash($passwordEdit, PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_USER', $roleEdit]);

        $entityManager->persist($user);
        $entityManager->flush();


        return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
    }


    /**
      * Allows to delete a user that is already in the database
     */
    public function userDelete(User $user, UserRepository $userRepository,ManagerRegistry $doctrine): Response
    {
        $modificationRepository = $doctrine->getManager()->getRepository("App\Entity\Modification");
        $allModification = $modificationRepository->findBy(['user' => $user]);

        foreach ($allModification as $modification) {
            $modificationRepository->remove($modification, true);
        }
        $userSettingsRepository = $doctrine->getManager()->getRepository("App\Entity\UserSettings");
        $userSettings = $user->getUsersettings();
        if($userSettings != null){
            $userSettingsRepository->remove($userSettings, true);
        }

        $userRepository->remove($user, true);
        return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
    }

    /**
      * Allows to create a JSON object from a list of users in the database
     */
    public function listUserJSON(ManagerRegistry $doctrine)
    {
        $users = $doctrine->getRepository('App\Entity\User')->findAll();
        $usersArray = array();
        foreach ($users as $user) {
            $usersArray[] = array(
                'username' => $user->getUsername(),
                'id' => $user->getId(),

            );
        }

        $usersArrayJSON = new JsonResponse($usersArray);
        return $usersArrayJSON;
    }
}
