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
            $firstname = $param['firstnameAdd'];     // le nom d'utilisateur
            $lastname = $param['lastnameAdd'];     // le nom d'utilisateur
            $password = $param['passwordAdd'];     // le mot de passe temporaire
            $role = $param['roleAdd'];             // le role

            // Création de l'utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
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
        $firstnameEdit = $request->request->get("firstnameEdit");
        $lastnameEdit = $request->request->get("lastnameEdit");
        $passwordEdit = $request->request->get("passwordEdit");
        $roleEdit = $request->request->get("roleEdit");

        $user = $userRepository->findOneBy(['id' => $iduserEdit]);
        $user->setUsername($usernameEdit);
        $user->setFirstname($firstnameEdit);
        $user->setLastname($lastnameEdit);
        $user->setPassword(password_hash($passwordEdit, PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_USER', $roleEdit]);

        $entityManager->persist($user);
        $entityManager->flush();


        return $this->redirectToRoute('User', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * Allows to delete a user that is already in the database
     */
    public function userDelete(User $user, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {
        $modificationRepository = $doctrine->getManager()->getRepository("App\Entity\Modification");
        $allModification = $modificationRepository->findBy(['user' => $user]);

        foreach ($allModification as $modification) {
            $modificationRepository->remove($modification, true);
        }
        $userSettingsRepository = $doctrine->getManager()->getRepository("App\Entity\UserSettings");
        $userSettings = $user->getUsersettings();
        if ($userSettings != null) {
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
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),

            );
        }

        $usersArrayJSON = new JsonResponse($usersArray);
        return $usersArrayJSON;
    }

    public function autocompleteUser(Request $request, UserRepository $userRepository)
    {
        $utf8 = array( 
            "œ"=>"oe",
            "æ"=>"ae",
            "à" => "a",
            "á" => "a",
            "â" => "a",
            "à" => "a",
            "ä" => "a",
            "å" => "a",
            "&#257;" => "a",
            "&#259;" => "a",
            "&#462;" => "a",
            "&#7841;" => "a",
            "&#7843;" => "a",
            "&#7845;" => "a",
            "&#7847;" => "a",
            "&#7849;" => "a",
            "&#7851;" => "a",
            "&#7853;" => "a",
            "&#7855;" => "a",
            "&#7857;" => "a",
            "&#7859;" => "a",
            "&#7861;" => "a",
            "&#7863;" => "a",
            "&#507;" => "a",
            "&#261;" => "a",
            "ç" => "c",
            "&#263;" => "c",
            "&#265;" => "c",
            "&#267;" => "c",
            "&#269;" => "c",
            "&#271;" => "d",
            "&#273;" => "d",
            "è" => "e",
            "é" => "e",
            "ê" => "e",
            "ë" => "e",
            "&#275;" => "e",
            "&#277;" => "e",
            "&#279;" => "e",
            "&#281;" => "e",
            "&#283;" => "e",
            "&#7865;" => "e",
            "&#7867;" => "e",
            "&#7869;" => "e",
            "&#7871;" => "e",
            "&#7873;" => "e",
            "&#7875;" => "e",
            "&#7877;" => "e",
            "&#7879;" => "e",
            "&#285;" => "g",
            "&#287;" => "g",
            "&#289;" => "g",
            "&#291;" => "g",
            "&#293;" => "h",
            "&#295;" => "h",
            "&#309;" => "j",
            "&#314;" => "l",
            "&#316;" => "l",
            "&#318;" => "l",
            "&#320;" => "l",
            "&#322;" => "l",
            "ñ" => "n",
            "&#324;" => "n",
            "&#326;" => "n",
            "&#328;" => "n",
            "&#329;" => "n",
            "ò" => "o",
            "ó" => "o",
            "ô" => "o",
            "õ" => "o",
            "ö" => "o",
            "ø" => "o",
            "&#333;" => "o",
            "&#335;" => "o",
            "&#337;" => "o",
            "&#417;" => "o",
            "&#466;" => "o",
            "&#511;" => "o",
            "&#7885;" => "o",
            "&#7887;" => "o",
            "&#7889;" => "o",
            "&#7891;" => "o",
            "&#7893;" => "o",
            "&#7895;" => "o",
            "&#7897;" => "o",
            "&#7899;" => "o",
            "&#7901;" => "o",
            "&#7903;" => "o",
            "&#7905;" => "o",
            "&#7907;" => "o",
            "ð" => "o",
            "&#341;" => "r",
            "&#343;" => "r",
            "&#345;" => "r",
            "&#347;" => "s",
            "&#349;" => "s",
            "&#351;" => "s",
            "&#355;" => "t",
            "&#357;" => "t",
            "&#359;" => "t",
            "ù" => "u",
            "ú" => "u",
            "û" => "u",
            "ü" => "u",
            "&#361;" => "u",
            "&#363;" => "u",
            "&#365;" => "u",
            "&#367;" => "u",
            "&#369;" => "u",
            "&#371;" => "u",
            "&#432;" => "u",
            "&#468;" => "u",
            "&#470;" => "u",
            "&#472;" => "u",
            "&#474;" => "u",
            "&#476;" => "u",
            "&#7909;" => "u",
            "&#7911;" => "u",
            "&#7913;" => "u",
            "&#7915;" => "u",
            "&#7917;" => "u",
            "&#7919;" => "u",
            "&#7921;" => "u",
            "&#373;" => "w",
            "&#7809;" => "w",
            "&#7811;" => "w",
            "&#7813;" => "w",
            "ý" => "y",
            "ÿ" => "y",
            "&#375;" => "y",
            "&#7929;" => "y",
            "&#7925;" => "y",
            "&#7927;" => "y",
            "&#7923;" => "y",
            );
        $term = strtr(mb_strtolower($request->query->get('term'),'UTF-8'), $utf8);
        $users = $userRepository->findAll();
        $results = array();
        foreach ($users as $user) {
            if (   strpos(strtr(mb_strtolower($user->getLastname()." ".$user->getFirstname(),'UTF-8'),$utf8), $term) !== false 
                || strpos(strtr(mb_strtolower($user->getFirstname()." ".$user->getLastname(),'UTF-8'),$utf8), $term) !== false 
                || strpos(strtr(mb_strtolower($user->getUserIdentifier(),'UTF-8'),$utf8), $term) !== false) {
                $results[] = [
                    'id' => $user->getId(),
                    'value' => $user->getUserIdentifier() . ' - ' . $user->getLastname() . ' ' . $user->getFirstname(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'username' => $user->getUserIdentifier(),
                    'role' => $user->getRoles(),

                ];
            }
        }
        return new JsonResponse($results);
    }
}

