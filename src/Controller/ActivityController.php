<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @Route("/activity")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/", name="app_activity_index", methods={"GET"})
     */
    public function index(ActivityRepository $activityRepository): Response
    {
        return $this->render('activity/index.html.twig', [
            'activities' => $activityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_activity_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ActivityRepository $activityRepository): Response
    {

        // Méthode POST pour ajouter une activité
        if ($request->getMethod() === 'POST' ) {
    
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $name = $param['name'];             // le nom
            $duration = $param['duration'];     // la durée

            // Création de l'activité
            $activity = new Activity(); 
            $activity->setActivityname($name);
            $activity->setDuration($duration);
          
            // ajout dans la bd 
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        // Methode GET
        return $this->renderForm('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_activity_show", methods={"GET"})
     */
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    /**
     * @Route("/edit", name="app_activity_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request): Response
    {

        // Méthode POST pour ajouter une activité
        if ($request->getMethod() === 'POST' ) {
    
            // On recupere toutes les données de la requete
            $param = $request->request->all();
                        
            $id = $param['id'];                 // l'id
            $name = $param['name'];             // le nom
            $duration = $param['duration'];     // la durée

        
          
            // Création du repository pour avoir accès aux requetes
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $activity = $activityRepository->find($id);

            // Création de l'activité
           // $activity = new Activity();
            $activity->setActivityname($name);
            $activity->setDuration($duration);

            // ajout dans la bd 
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_activity_delete", methods={"POST"})
     */
    public function delete(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $activityRepository->remove($activity, true);
        }

        return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
    }
}
