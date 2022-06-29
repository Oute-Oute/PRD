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

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/profile/{id}", name="app_user_edit_profile", methods={"GET", "POST"})
     */
    public function editProfile(Request $request, User $user, UserRepository $userRepository): Response
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


    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
