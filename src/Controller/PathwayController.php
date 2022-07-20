<?php

namespace App\Controller;

use App\Entity\Pathway;
use App\Entity\Activity;
use App\Entity\Successor;
use App\Entity\ActivityHumanResource;
use App\Entity\ActivityMaterialResource;
use App\Repository\PathwayRepository;
use App\Repository\ActivityRepository;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\ActivityHumanResourceRepository;
use App\Repository\ActivityMaterialResourceRepository;
use App\Repository\MaterialResourceCategoryRepository;
use App\Repository\AppointmentRepository;
use App\Repository\MaterialResourceRepository;
use App\Repository\SuccessorRepository;
use App\Form\PathwayType;
use Doctrine\Persistence\ManagerRegistry;
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
     * Permet de créer un objet json a partir d'une entité de type pathway
     */
    public function pathwayJSON(Pathway $pathway)
    {
        $activityRepo = new ActivityRepository($this->getDoctrine());
        $activitiesOfPathway = $activityRepo->findBy(['pathway' => $pathway]);
        
        //$pathwayArray = array();
        $pathwayArray = array(
            'id' => $pathway->getId(),
            'pathwayname' => $pathway->getPathwayname()
        );
        //dd($pathwayArray);

        
        $activityHumanResourceRepo = new ActivityHumanResourceRepository($this->getDoctrine());
        $activityMaterialResourceRepo = new ActivityMaterialResourceRepository($this->getDoctrine());

        $activitiesArray = array();
        foreach ($activitiesOfPathway as $activity) {

            $humanResources = $activityHumanResourceRepo->findBy(['activity' => $activity]);
            $hrArray = array();
            foreach ($humanResources as $hr) {

                $hrobject = array(
                    'id' => $hr->getHumanresourcecategory()->getId(),
                    'name' => $hr->getHumanresourcecategory()->getCategoryname(),
                    'nb' => $hr->getQuantity()
                );
                array_push($hrArray, $hrobject);     
            }

            $materialResources = $activityMaterialResourceRepo->findBy(['activity' => $activity]);
            $mrArray = array();

            foreach ($materialResources as $mr) {

                $mrobject = array(
                    'id' => $mr->getMaterialresourcecategory()->getId(),
                    'name' => $mr->getMaterialresourcecategory()->getCategoryname(),
                    'nb' => $mr->getQuantity()
                );
                array_push($mrArray, $mrobject);

            }
            $activitiesArray[] = array(
                'id' => $activity->getId(),
                'activityname' => $activity->getActivityname(),
                'activityduration' => $activity->getDuration(),
                'humanResourceCategories' => $hrArray,
                'materialResourceCategories' =>$mrArray
            );
        }
        
        $pathwayArray += [ 'activities' => $activitiesArray ];
/*
        $activityHumanResourceRepo = new ActivityHumanResourceRepository($this->getDoctrine());

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
        }*/

        //Conversion des données ressources en json
        $pathwayJson = new JsonResponse($pathwayArray);
        return $pathwayJson;    
    }


    /**
     * Redirige vers la page qui liste les utilisateurs 
     * route : "/pathways"
     */
    public function pathwayGet(PathwayRepository $pathwayRepository, ManagerRegistry $doctrine): Response
    {

        $activityRepository = $doctrine->getManager()->getRepository("App\Entity\Activity");

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
     * Redirige vers la page d'ajout d'un parcours
     * route : "/pathway/edit/{id}"
     */
    public function pathwayEditPage(Request $request, PathwayRepository $pathwayRepository, int $id): Response
    {

        // Méthode GET pour aller vers la page d'ajout d'un parcours 
        if ($request->getMethod() === 'GET' ) {

            $activityRepository = new ActivityRepository($this->getDoctrine());

            $humanResourceCategoriesJson = $this->listHumanResourcesJSON();
            $materialResourceCategoriesJson = $this->listMaterialResourcesJSON();

            $pathway = $pathwayRepository->findBy(['id' => $id]);
            //dd($pathway[0]);
            $pathwayJson = $this->pathwayJSON($pathway[0]);

            $activitiesByPathways = $activityRepository->findBy(['pathway' => $pathway]);

            // création d'un tableau contenant les ressources des activités
            $resourcesByActivities = array();
            //for ()

            return $this->render('pathway/edit.html.twig', [
                'pathway' => $pathway,
                'pathwayJson' => $pathwayJson,
                'activitiesByPathways' => $activitiesByPathways,
                'humanResourceCategories' => $humanResourceCategoriesJson,
                'materialResourceCategories' => $materialResourceCategoriesJson,
            ]);
        }
            
    }

    /**
     * Redirige vers la page d'ajout d'un parcours
     * route : "/pathway/add"
     */
    public function pathwayAddPage(Request $request, PathwayRepository $pathwayRepository): Response
    {

        // Méthode GET pour aller vers la page d'ajout d'un parcours 
        if ($request->getMethod() === 'GET' ) {

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

            return $this->render('pathway/add.html.twig', [
                'pathways' => $pathways,
                'activitiesByPathways' => $activitiesByPathways,
                'humanResourceCategories' => $humanResourceCategoriesJson,
                'materialResourceCategories' => $materialResourceCategoriesJson,
            ]);
        }
            
    }

    
    /**
     * Ajoute dans la base de données :
     * Ajoute un parcours  
     * Ajoute les activités liées a ce parcours
     * Ajoute les successors liés aux activités
     * Ajoute les ressources humaines et materielles liées aux activités 
     * 
     * route : "/pathway/add"
     */
    public function pathwayAdd(Request $request, PathwayRepository $pathwayRepository): Response
    {

        // Méthode POST pour ajouter un parcours
        if ($request->getMethod() === 'POST' ) {
            
            // Création de tous les repository
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $AHRRepository = new ActivityHumanResourceRepository($this->getDoctrine());
            $AMRRepository = new ActivityMaterialResourceRepository($this->getDoctrine());
            $HRCRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $MRCRepository = new MaterialResourceCategoryRepository($this->getDoctrine());


            // On recupere toutes les données de la requete
            $param = $request->request->all();

            // On recupere le json qui contient la liste de ressources par activités 
            // et on le transforme en tableau PHP
            $resourcesByActivities = json_decode($param['json-resources-by-activities']);
            //dd($resourcesByActivities);


            // Premierement on s'occupe d'ajouter le parcours dans la bd :
            
            // On crée l'objet parcours
            $pathway = new Pathway();
            $pathway->setPathwayname($param['pathwayname']);
            //$pathway->setAvailable(true);

            // On ajoute le parcours a la bd
            $pathwayRepository->add($pathway, true);

            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $activities = $activityRepository->findAll();

            // On récupère le nombre d'activité
            $nbActivity = count($resourcesByActivities);

            if ($nbActivity != 0) {
                
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
                            // Dans le cas ou la premiere activité à déjà été trouvée 

                            // Création de l'activité
                            $activity = new Activity();
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                            $activity->setPathway($pathway);
                            $activityRepository->add($activity, true);
            
                            $activity =  $activityRepository->findBy(['activityname' => $activity->getActivityname()])[0];
        
                            // Création du successor entre les 2 activités
                            $successor = new Successor();
                            $successor->setActivitya($activity_old);
                            $successor->setActivityb($activity);
                            $successor->setDelaymin(0);
                            $successor->setDelaymax(1);
                            $successorRepository->add($successor, true);


                            // Récupération de la nouvelle activity_old qui est l'activity en cours
                            $activity_old = $activityRepository->findById($activity->getId())[0];
                        }


                        // Ajout des liens activity - ressources humaines
                        
                        $nbHRC = count($resourcesByActivities[$indexActivity]->humanResourceCategories);
                    
                        if ($nbHRC != 0) {
                            for ($indexHRC = 0; $indexHRC < $nbHRC; $indexHRC++) {

                                // Premierement on recupere la categorie de la bd
                                $HRC = $HRCRepository->findById($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->id)[0];
                                                                
                                // Ensuite on crée l'objet ActivityMaterialResource
                                $activityHumanResource = new ActivityHumanResource();
                                $activityHumanResource->setActivity($activity_old);
                                $activityHumanResource->setHumanresourcecategory($HRC);
                                $activityHumanResource->setQuantity(strval($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb));
                                                                
                                // Puis on l'ajoute dans la bd
                                $AHRRepository->add($activityHumanResource , true);
                            }
                        }
                    

                        // Ajout des liens activity - ressources materielles
                        
                        $nbMRC = count($resourcesByActivities[$indexActivity]->materialResourceCategories);
                    
                        if ($nbMRC != 0) {
                            for ($indexMRC = 0; $indexMRC < $nbMRC; $indexMRC++) {

                                // Premierement on recupere la categorie de la bd
                                $MRC = $MRCRepository->findById($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->id)[0];
                                
                                // Ensuite on crée l'objet ActivityMaterialResource
                                $activityMaterialResource = new ActivityMaterialResource();
                                $activityMaterialResource->setActivity($activity_old);
                                $activityMaterialResource->setMaterialresourcecategory($MRC);
                                $activityMaterialResource->setQuantity(strval($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->nb));
                                
                                // Puis on l'ajoute dans la bd
                                $AMRRepository->add($activityMaterialResource , true);
                            }
                        }

                    }

                }
            
            }                
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
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
            
            $em=$this->getDoctrine()->getManager();


            //$em->remove($activitiesInPathway[$indexActivity]);
            //$em->flush();

            // Création de tous les repository
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $AHRRepository = new ActivityHumanResourceRepository($this->getDoctrine());
            $AMRRepository = new ActivityMaterialResourceRepository($this->getDoctrine());
            $HRCRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $MRCRepository = new MaterialResourceCategoryRepository($this->getDoctrine());


            // On recupere toutes les données de la requete
            $param = $request->request->all();

            // On recupere le json qui contient la liste de ressources par activités 
            // et on le transforme en tableau PHP
            $resourcesByActivities = json_decode($param['json-resources-by-activities']);
            //dd($resourcesByActivities);


            // Premierement on s'occupe d'ajouter le parcours dans la bd :
            
            // On crée l'objet parcours
            $pathway = new Pathway();
            $pathway->setPathwayname($param['pathwayname']);
            //$pathway->setAvailable(true);

            // On ajoute le parcours a la bd
            $em->flush();

            //dd($resourcesByActivities);

            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $activities = $activityRepository->findAll();

            // On récupère le nombre d'activité
            $nbActivity = count($resourcesByActivities);
            //var_dump($nbActivity);
            //dd($resourcesByActivities);

            if ($nbActivity != 0) {
                
                $firstActivityAvailableFound = false;
                for ($indexActivity = 0; $indexActivity < $nbActivity; $indexActivity++) {

                    if ($resourcesByActivities[$indexActivity]->available) {
                        
                        $activity = new Activity();

                        // On verifie si l'activité exsite déjà (si son id est different de -1)
                        if ($resourcesByActivities[$indexActivity]->id == -1) {
                            $activity =  $activityRepository->findBy(['id' => $resourcesByActivities[$indexActivity]->id])[0];

                            // Création de l'activité
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                            $activity->setPathway($pathway);
                            $activityRepository->add($activity, true);

                        } else {
                            // Dans le cas ou l'activité existe déjà
                            $activity =  $activityRepository->findBy(['id' => $resourcesByActivities[$indexActivity]->id])[0];

                            // Création de l'activité
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                            //$activity->setPathway($pathway);
                            $em->flush();
            
                        }


                        // Ajout des liens activity - ressources humaines
                        
                        $nbHRC = count($resourcesByActivities[$indexActivity]->humanResourceCategories);
                    
                        if ($nbHRC != 0) {
                            for ($indexHRC = 0; $indexHRC < $nbHRC; $indexHRC++) {

                                // Premierement on recupere la categorie de la bd
                                $HRC = $HRCRepository->findById($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->id)[0];
                                                                
                                // Ensuite on crée l'objet ActivityMaterialResource
                                $activityHumanResource = new ActivityHumanResource();
                                $activityHumanResource->setActivity($activity);
                                $activityHumanResource->setHumanresourcecategory($HRC);
                                $activityHumanResource->setQuantity(strval($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb));
                                                                
                                // Puis on l'ajoute dans la bd
                                $AHRRepository->add($activityHumanResource , true);
                            }
                        }
                    

                        // Ajout des liens activity - ressources materielles
                        
                        $nbMRC = count($resourcesByActivities[$indexActivity]->materialResourceCategories);
                    
                        if ($nbMRC != 0) {
                            for ($indexMRC = 0; $indexMRC < $nbMRC; $indexMRC++) {

                                // Premierement on recupere la categorie de la bd
                                $MRC = $MRCRepository->findById($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->id)[0];
                                
                                // Ensuite on crée l'objet ActivityMaterialResource
                                $activityMaterialResource = new ActivityMaterialResource();
                                $activityMaterialResource->setActivity($activity);
                                $activityMaterialResource->setMaterialresourcecategory($MRC);
                                $activityMaterialResource->setQuantity(strval($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->nb));
                                
                                // Puis on l'ajoute dans la bd
                                $AMRRepository->add($activityMaterialResource , true);
                            }
                        }

                    }

                }
            }
         
            
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }
    }


    public function pathwayDelete(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            
            /*
            Ordre des suppressions :
            1)
                HumanResourceScheduled
                MaterialResourceScheduled
                ActivityHumanResource
                ActvityMaterialResource
                Successor
            2)
                ScheduledActivity
            3)
                Appointment
                Activity
            4)
                Pathway
            */

            $em = $this->getDoctrine()->getManager();

            $activityRepository = new ActivityRepository($this->getDoctrine());
            $pathwayRepository = new PathwayRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $appointmentRepository = new AppointmentRepository($this->getDoctrine());

            $activityHumanResourceRepository = new ActivityHumanResourceRepository($this->getDoctrine());
            $activityMaterialResourceRepository = new ActivityMaterialResourceRepository($this->getDoctrine());

            // On recupere toutes les informations de la requete 
            $param = $request->request->all();

            // recuperation du parcours que l'on veut supprimer 
            $pathway = $pathwayRepository->findById($param['pathwayid'])[0];


            // --------- SUPPRESSION DES RDV --------- //

            $appointmentsInPathway = $appointmentRepository->findBy(['pathway' => $pathway]);
            //dd($appointmentsInPathway);

            foreach ($appointmentsInPathway as $appointment) {
                //on récupère toutes les activités programmées associées au rendez-vous
                $scheduledActivityRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\ScheduledActivity");
                $scheduledActivities = $scheduledActivityRepository->findBy(['appointment' => $appointment]);

                foreach($scheduledActivities as $scheduledActivity)
                {
                    $date = $appointment->getDayappointment()->format('Y-m-d');

                    //suppression des données associées au rendez-vous de la table MaterialResourceScheduled
                    $materialResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
                    $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                    foreach($allMaterialResourceScheduled as $materialResourceScheduled)
                    {
                        $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                        $strDate = substr($date, 0, 10);
                        $strStart = $strDate . " " . $scheduledActivity->getStarttime()->format('H:i:s');

                        $listUnavailabilityMaterialResource = $unavailabilityMaterialResourceRepository->findUnavailabilityMaterialResourceByDate($strStart, $materialResourceScheduled->getMaterialresource()->getId());

                        foreach($listUnavailabilityMaterialResource as $unavailabilityMaterialResource)
                        {
                            $unavailability = $unavailabilityMaterialResource->getUnavailability();
                            $entityManager->remove($unavailabilityMaterialResource);
                            $entityManager->flush($unavailabilityMaterialResource);
                            $entityManager->remove($unavailability);
                            $entityManager->flush($unavailability);
                        }
                    }


                    //suppression des données associées au rendez-vous de la table HumanResourceScheduled
                    $humanResourceScheduledRepository = $this->getDoctrine()->getManager()->getRepository("App\Entity\HumanResourceScheduled");
                    $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                    foreach($allHumanResourceScheduled as $humanResourceScheduled)
                    {
                        $humanResourceScheduledRepository->remove($humanResourceScheduled, true);

                        $listUnavailabilityHumanResource = $unavailabilityHumanResourceRepository->findUnavailabilityHumanResourceByDate($strStart, $humanResourceScheduled->getHumanresource()->getId());

                        foreach($listUnavailabilityHumanResource as $unavailabilityHumanResource)
                        {
                            $unavailability = $unavailabilityHumanResource->getUnavailability();
                            $entityManager->remove($unavailabilityHumanResource);
                            $entityManager->flush($unavailabilityHumanResource);
                            $entityManager->remove($unavailability);
                            $entityManager->flush($unavailability);
                        }
                    }


                    //suppression des données associées au rendez-vous de la table ScheduledActivity
                    $scheduledActivityRepository->remove($scheduledActivity, true);
                }

                //suppression du rendez-vous
                $appointmentRepository->remove($appointment, true);

            }

            
            // recuperation des activités du parcours 
            $activitiesInPathway = $activityRepository->findBy(['pathway' => $pathway]);
            


            // --------- SUPPRESSION DES SUCCESSOR --------- //
            // --------- SUPPRESSION DES LIENS ENTRE LES ACTIVITY ET LES CATEGORIES --------- //

        
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

                $activityHumanResources = $activityHumanResourceRepository->findBy(['activity' => $activitiesInPathway[$indexActivity]]);
                for ($indexAHR = 0; $indexAHR < count($activityHumanResources); $indexAHR++) {
                    echo 'on supprime'.$indexAHR;
                    $em->remove($activityHumanResources[$indexAHR]);
                }

                $activityMaterialResources = $activityMaterialResourceRepository->findBy(['activity' => $activitiesInPathway[$indexActivity]]);
                for ($indexAMR = 0; $indexAMR < count($activityMaterialResources); $indexAMR++) {
                    $em->remove($activityMaterialResources[$indexAMR]);
                }
                $em->flush();

            }


            // --------- SUPPRESSION DES ACTIVITY --------- //

            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {
                $em->remove($activitiesInPathway[$indexActivity]);
                $em->flush();

            } 

            // Puis on supprime le pathway
            $pathwayRepository->remove($pathway, true);
        }

        return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
    }

    public function getActivitiesByPathwayId(ManagerRegistry $doctrine)
    {
        if(isset($_POST['idPathway'])){
            $id = $_POST['idPathway'];
        }
        else{
            return new JsonResponse('');
        }
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findOneBy(["id"=>$id]);
        $activities = $doctrine->getManager()->getRepository("App\Entity\Activity")->findBy(["pathway"=>$pathway]);
        $activityArray=[];
        foreach ($activities as $activity) {
            $successors = $doctrine->getManager()->getRepository("App\Entity\Successor")->findBy(["activitya"=>$activity]);
            $arraySuccessor = [];
            foreach($successors as $successor){
                $arraySuccessor[] = [
                    'name' => $successor->getActivityb()->getActivityName(),
                    'delaymin' => $successor->getDelaymin(),
                    'delaymax' => $successor->getDelaymax(),
                ];
            }
            $activityArray[] = [
                'name' => $activity->getActivityname(),
                'duration' => $activity->getDuration(),
                'successor' => $arraySuccessor,
            ];
        }

        return new JsonResponse($activityArray);
    }

    public function getAppointmentsByPathwayId(ManagerRegistry $doctrine)
    {
        if(isset($_POST['idPathway'])){
            $id = $_POST['idPathway'];
        }
        else{
            return new JsonResponse('');
        }
        $pathway = $doctrine->getManager()->getRepository("App\Entity\Pathway")->findOneBy(["id"=>$id]);
        $appointments = $doctrine->getManager()->getRepository("App\Entity\Appointment")->findBy(["pathway"=>$pathway]);
        $appointmentArray=[];
        foreach ($appointments as $appointment) {
            $date = $appointment->getDayappointment()->format('d-m-Y');
            if($date >= date('d-m-Y')){
                $appointmentArray[] = [
                    'lastname' => $appointment->getPatient()->getLastname(),
                    'firstname' => $appointment->getPatient()->getFirstname(),
                    'date' => $appointment->getDayappointment()->format('d-m-Y'),
                ];
            }
        }

        return new JsonResponse($appointmentArray);
    }
}
