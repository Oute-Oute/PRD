<?php

namespace App\Controller;

use App\Entity\Pathway;
use App\Entity\Activity;
use App\Entity\Successor;
use App\Entity\AP;
use App\Form\PathwayType;
use App\Repository\PathwayRepository;
use App\Repository\ActivityRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\MaterialResourceRepository;
use App\Repository\SuccessorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Route("/circuit")
 */
class PathwayController extends AbstractController
{
    public function index(PathwayRepository $pathwayRepository): Response
    {

        $activityRepository = new ActivityRepository($this->getDoctrine());

        $humanResourceRepo = new HumanResourceRepository($this->getDoctrine());
        $humanResources = $humanResourceRepo->findAll();

        $materialResourceRepo = new MaterialResourceRepository($this->getDoctrine());
        $materialResources = $materialResourceRepo->findAll();

        $pathways = $pathwayRepository->findAll();
        $nbPathway = count($pathways);

        $activitiesByPathways = array();

        for ($i = 0; $i < $nbPathway; $i++) {
            array_push($activitiesByPathways, $activityRepository->findBy(['pathway' => $pathways[$i]]));
            //dd($activitiesByPathway[$i]);
        }

        return $this->render('pathway/index.html.twig', [
            'pathways' => $pathways,
            'activitiesByPathways' => $activitiesByPathways,
            'humanResources' => $humanResources,
            'materialResources' => $materialResources,
        ]);
    }

    public function new(Request $request, PathwayRepository $pathwayRepository): Response
    {

        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            
            // On recupere toutes les données de la requete
            $param = $request->request->all();


            // Premierement on s'occupe d'ajouter le parcours dans la bd :
            // On crée l'objet parcours
            $pathway = new Pathway();
            $pathway->setPathwayname($param['pathwayname']);
            $pathway->setAvailable(true);

            // On ajoute le parcours a la bd
            $pathwayRepository->add($pathway, true);

            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $activities = $activityRepository->findAll();
            $successorRepository = new SuccessorRepository($this->getDoctrine());

            // On récupère le nombre d'activité
            $nbActivity = $param['nbactivity'];


            //$activityArray = array();
            if ($nbActivity != 0) {
                $activity_old = new Activity();      
                
                $activity_old->setActivityname($param["name-activity-0"]);
                $activity_old->setDuration($param[ "duration-activity-0"]);
                $activity_old->setPathway($pathway);

                $activityRepository->add($activity_old, true);

                for($i = 1; $i < $nbActivity; $i++)
                {
                    $activity = new Activity();
                    $strName = "name-activity-" . $i;
                    $strDuration = "duration-activity-" . $i;
    
                    $activity->setActivityname($param[$strName]);
                    $activity->setDuration($param[$strDuration]);
                    $activity->setPathway($pathway);
                    $activityRepository->add($activity, true);
    
                    $activity =  $activityRepository->findBy(['activityname' => $activity->getActivityname()])[0];

                    $successor = new Successor();
                    $successor->setActivitya($activity_old);
                    $successor->setActivityb($activity);
                    $successor->setDelaymin(0);
                    $successor->setDelaymax(1);
                    $successorRepository->add($successor, true);
    
                    $activity_old = $activityRepository->findById($activity->getId())[0];

                }
            
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }
    }
        
        
    }

    /**
     * @Route("/{id}", name="app_circuit_show", methods={"GET"})
     */
    public function show(Pathway $pathway): Response
    {
        return $this->render('pathway/show.html.twig', [
            'pathway' => $pathway,
        ]);
    }


    /**
     * Methode d'edition d'un pathway dans la base de données
     */
    public function edit(Request $request): Response
    {
        
        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            
            // On recupere toutes les données de la requete
            $param = $request->request->all();
            //dd($param);

            // On récupère l'objet parcours que l'on souhaite modifier grace a son id
            $pathwayRepository = new PathwayRepository($this->getDoctrine());
            $pathway = $pathwayRepository->findById($param['pathwayid'])[0];
            $pathway->setPathwayname($param['pathwayname']);
            //$pathway->setAvailable(true);

            // On ajoute le parcours a la bd
            $pathwayRepository->add($pathway, true);

            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $activities = $activityRepository->findAll();

            // On supprime toutes les activités et leurs successor
            $em=$this->getDoctrine()->getManager();
            $activitiesInPathway = $activityRepository->findBy(['pathway' => $pathway]);
            
            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {

                $successorsa = $successorRepository->findBy(['activitya' => $activitiesInPathway[$indexActivity]]);
                for ($indexSuccessora = 0; $indexSuccessora < count($successorsa); $indexSuccessora++) {
                    $em->remove($successorsa[$indexSuccessora]);
                }

                $successorsb = $successorRepository->findBy(['activityb' => $activitiesInPathway[$indexActivity]]);
                for ($indexSuccessorb = 0; $indexSuccessorb < count($successorsa); $indexSuccessorb++) {
                    $em->remove($successorsa[$indexSuccessorb]);
                }
                $em->flush();

               // $em->remove($activitiesInPathway[$indexActivity]);
            }
            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {
                $em->remove($activitiesInPathway[$indexActivity]);
                $em->flush();

            }
            //$em->flush();

            //dd($activityRepository->findAll());

            // On récupère le nombre d'activité
            $nbActivity = $param['nbactivity'];

            //$activityArray = array();
            if ($nbActivity != 0) {
                $activity_old = new Activity();      
                
                $activity_old->setActivityname($param["name-activity-0"]);
                $activity_old->setDuration($param[ "duration-activity-0"]);
                $activity_old->setPathway($pathway);

                $activityRepository->add($activity_old, true);

                for($i = 1; $i < $nbActivity; $i++)
                {
                    $activity = new Activity();
                    $strName = "name-activity-" . $i;
                    $strDuration = "duration-activity-" . $i;
    
                    $activity->setActivityname($param[$strName]);
                    $activity->setDuration($param[$strDuration]);
                    $activity->setPathway($pathway);
        
                    $activityRepository->add($activity, true);
    
                    $activity =  $activityRepository->findBy(['activityname' => $activity->getActivityname()])[0];

                    //dd($activity);
                    
                    $successor = new Successor();
    
                    //if  ($i < ($nbActivity - 1)) {
                    $successor->setActivitya($activity_old);
                    $successor->setActivityb($activity);
                    $successor->setDelaymin(0);
                    $successor->setDelaymax(1);
                    $successorRepository->add($successor, true);
                   //}

                    $activity_old = $activityRepository->findById($activity->getId())[0];

                }
            }
            
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }
    }


    public function delete(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            
            // On supprime toutes les activités et leurs successor
            $em = $this->getDoctrine()->getManager();

            $activityRepository = new ActivityRepository($this->getDoctrine());
            $pathwayRepository = new PathwayRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            
            $param = $request->request->all();
            $pathway = $pathwayRepository->findById($param['pathwayid'])[0];
            $activitiesInPathway = $activityRepository->findBy(['pathway' => $pathway]);
            
            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {

                $successorsa = $successorRepository->findBy(['activitya' => $activitiesInPathway[$indexActivity]]);
                for ($indexSuccessora = 0; $indexSuccessora < count($successorsa); $indexSuccessora++) {
                    $em->remove($successorsa[$indexSuccessora]);
                }

                $successorsb = $successorRepository->findBy(['activityb' => $activitiesInPathway[$indexActivity]]);
                for ($indexSuccessorb = 0; $indexSuccessorb < count($successorsa); $indexSuccessorb++) {
                    $em->remove($successorsa[$indexSuccessorb]);
                }
                $em->flush();

               // $em->remove($activitiesInPathway[$indexActivity]);
            }
            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {
                $em->remove($activitiesInPathway[$indexActivity]);
                $em->flush();

            } 

            // Puis on supprime le pathway
            $pathwayRepository->remove($pathway, true);
        }

        return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
    }
}
