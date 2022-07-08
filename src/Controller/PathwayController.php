<?php

namespace App\Controller;

use App\Entity\Pathway;
use App\Entity\Activity;
use App\Entity\Successor;
use App\Entity\AP;
use App\Form\PathwayType;
use App\Repository\PathwayRepository;
use App\Repository\ActivityRepository;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\MaterialResourceCategoryRepository;
use App\Repository\MaterialResourceRepository;
use App\Repository\SuccessorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

/**
 * @Route("/circuit")
 */
class PathwayController extends AbstractController
{


    /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource humaine
     */
    public function listHumanResourcesJSON()
    {
        $humanResourceCategoryRepo = new HumanResourceCategoryRepository($this->getDoctrine());
        $humanResourceCategories = $humanResourceCategoryRepo->findAll();
        $humanResourceCategoriesArray = array();

        if ($humanResourceCategories != null) {
            foreach ($humanResourceCategories as $humanResourceCategory) {
                $humanResourceCategoriesArray[] = array(
                    'id' => strval($humanResourceCategory->getId()),
                    'categoryname' => $humanResourceCategory->getCategoryname(),
                );
            }
        }
        //Conversion des données ressources en json
        $humanResourceCategoriesArrayJson = new JsonResponse($humanResourceCategoriesArray);
        return $humanResourceCategoriesArrayJson;    
    }

    /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource materielle
     */
    public function listMaterialResourcesJSON()
    {
        $materialResourceCategoryRepo = new MaterialResourceCategoryRepository($this->getDoctrine());
        $materialResourceCategories = $materialResourceCategoryRepo->findAll();
        $materialResourceCategoriesArray = array();

        if ($materialResourceCategories != null) {
            foreach ($materialResourceCategories as $materialResourceCategory) {
                $materialResourceCategoriesArray[] = array(
                    'id' => strval($materialResourceCategory->getId()),
                    'categoryname' => $materialResourceCategory->getCategoryname(),
                );
            }
        }
        //Conversion des données ressources en json
        $materialResourceCategoriesArrayJson = new JsonResponse($materialResourceCategoriesArray);
        return $materialResourceCategoriesArrayJson;    
    }


    /**
     * Redirige vers la page qui liste les utilisateurs 
     */
    public function pathwayGet(PathwayRepository $pathwayRepository): Response
    {

        $activityRepository = new ActivityRepository($this->getDoctrine());

        //$humanResourceRepo = new HumanResourceRepository($this->getDoctrine());
        //$humanResources = $humanResourceRepo->findAll();
        // dd($humanResources);
        $humanResourceCategoriesJson = $this->listHumanResourcesJSON();
        $materialResourceCategoriesJson = $this->listMaterialResourcesJSON();
        //$materialResourceRepo = new MaterialResourceRepository($this->getDoctrine());
        //$materialResources = $materialResourceRepo->findAll();

        $pathways = $pathwayRepository->findAll();
        $nbPathway = count($pathways);

        $activitiesByPathways = array();

        for ($i = 0; $i < $nbPathway; $i++) {
            array_push($activitiesByPathways, $activityRepository->findBy(['pathway' => $pathways[$i]]));
        }

        return $this->render('pathway/index.html.twig', [
            'pathways' => $pathways,
            'activitiesByPathways' => $activitiesByPathways,
            'humanResourceCategories' => $humanResourceCategoriesJson,
            'materialResourceCategories' => $materialResourceCategoriesJson,
        ]);
    }


    /**
     * Redirige vers la page qui liste les utilisateurs 
     */
    public function pathwayAdd(Request $request, PathwayRepository $pathwayRepository): Response
    {

        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {
            
            // On recupere toutes les données de la requete
            $param = $request->request->all();

            $resourcesByActivities = json_decode($param['json-resources-by-activities']);

            //dd($resourcesByActivities);

            //dd($param);

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
            $nbActivity = count($resourcesByActivities);

            if ($nbActivity != 0) {
                //$activity_old;   //pour ne pas que la variable soit locale au    
                
                $firstActivityAvailableFound = false;
                for ($indexActivity = 0; $indexActivity < $nbActivity; $indexActivity++) {

                    if ($resourcesByActivities[$indexActivity]->available) {
                        
                        // On cherche la premiere activité available = true
                        if ($firstActivityAvailableFound === false) {
                            $firstActivityAvailableFound = true;
                            $activity_old = new Activity();
                            $activity_old->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity_old->setDuration($resourcesByActivities[$indexActivity]->activityduration);
                            $activity_old->setPathway($pathway);

                            $activityRepository->add($activity_old, true);

                        } else {
                            // cas ou la premiere activité à déjà été trouvée 

                            $activity = new Activity();
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration($resourcesByActivities[$indexActivity]->activityduration);
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
                    }

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
