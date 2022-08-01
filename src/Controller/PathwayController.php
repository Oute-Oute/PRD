<?php

namespace App\Controller;

use App\Entity\Pathway;
use App\Entity\Activity;
use App\Entity\Successor;
use App\Entity\Target;
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
use App\Repository\TargetRepository;
use App\Repository\UnavailabilityMaterialResourceRepository;
use App\Repository\UnavailabilityHumanResourceRepository;
use App\Form\PathwayType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\BinaryOp\Concat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Validator\Constraints\Length;

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
        
        $activityHumanResourceRepo = new ActivityHumanResourceRepository($this->getDoctrine());
        $activityMaterialResourceRepo = new ActivityMaterialResourceRepository($this->getDoctrine());

        $activitiesArray = array();
        $successorsArray = array();
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

            $successorRepo = new SuccessorRepository($this->getDoctrine());
            $successors = $successorRepo->findBy(["activitya"=>$activity]);
            foreach($successors as $successor){
                $nameActivityA = $activityRepo->findOneBy(['id' => $successor->getActivitya()])->getActivityname();
                $nameActivityB = $activityRepo->findOneBy(['id' => $successor->getActivityb()])->getActivityname();
                $successorsArray[] = array(
                    'idActivityA' => $successor->getActivitya()->getId(),
                    'idActivityB' => $successor->getActivityb()->getId(),
                    'nameActivityA' => $nameActivityA,
                    'nameActivityB' => $nameActivityB,
                    'delayMin' => $successor->getDelaymin(),
                    'delayMax' =>$successor->getDelaymax()
                );
            }
        }
        
        $pathwayArray += [ 'activities' => $activitiesArray ];
        $pathwayArray += [ 'successors' => $successorsArray ];
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
     * Permet de créer un objet json contenant les targets d'un pathway
     */
    public function listTargetsJSON($pathway)
    {
        $targetRepository = new TargetRepository($this->getDoctrine());

        $targets = $targetRepository->findBy(["pathway" => $pathway]);

        $targetsArrayJson = new JsonResponse([]);

        if ($targets != null) {
            foreach ($targets as $target) {
                $targetsArray[] = array(
                    'id' => strval($target->getId()),
                    'target' => $target->getTarget(),
                    'dayweek' => $target->getDayweek(),
                );
            }
            $targetsArrayJson = new JsonResponse($targetsArray);

        }

        //Conversion des données ressources en json
        return $targetsArrayJson;    
    }

    /**
     * Redirige vers la page qui liste les utilisateurs 
     * route : "/pathways"
     */
    public function pathwayGet(PathwayRepository $pathwayRepository, ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
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
        $pathways=$paginator->paginate(
            $pathways, 
            $request->query->getInt('page',1),
            8
        ); 
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
     * Redirige vers la page d'édition d'un parcours
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
            $pathwayJson = $this->pathwayJSON($pathway[0]);
            //dd($pathwayJson);

            $activitiesByPathways = $activityRepository->findBy(['pathway' => $pathway]);

            $targetsJson = $this->listTargetsJSON($pathway);            

            return $this->render('pathway/edit.html.twig', [
                'pathway' => $pathway,
                'pathwayJson' => $pathwayJson,
                'activitiesByPathways' => $activitiesByPathways,
                'humanResourceCategories' => $humanResourceCategoriesJson,
                'materialResourceCategories' => $materialResourceCategoriesJson,
                'targets' => $targetsJson,
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

        // POST method to add a pathway 
        if ($request->getMethod() === 'POST' ) {
            
            // Create all the repository 
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $AHRRepository = new ActivityHumanResourceRepository($this->getDoctrine());
            $AMRRepository = new ActivityMaterialResourceRepository($this->getDoctrine());
            $HRCRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $MRCRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
            $MRCRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
            $targetRepository = new TargetRepository($this->getDoctrine());
            //$targetRepository = $doctrine->getManager()->getRepository("App\Entity\Target");


            // We get all the datas from the request
            $param = $request->request->all();

            // On get the json which contains the list of the resources by activities and successors
            // et we convert it into a PHP Array
            $resourcesByActivities = json_decode($param['json-resources-by-activities']);
            $successors= json_decode($param['json-successors']);
            // First we add the pathway to the db :
            
            // We create the pathway object
            $pathway = new Pathway();
            $pathway->setPathwayname($param['pathwayname']);

            // We add the pathway to the db
            $pathwayRepository->add($pathway, true);

            // First we create the targets
            for ($i = 0; $i < 7; $i++) {
                $target = new Target();
                $target->setTarget(intval($param['target-'.$i]));
                $target->setDayWeek($i);
                $target->setPathway($pathway);
                $targetRepository->add($target, true);
            }

            // We handle the link between pathway and activities 

            // We get the number of activities and successors
            $nbActivity = count($resourcesByActivities);
            $nbSuccessor = count($successors);

            // We create an array to store the activies id in the database after we added them
            // So we don't have to use the name (which can be the same for different activities)
            $activitiesIdArray = array();

            if ($nbActivity != 0) {
                for ($indexActivity = 0; $indexActivity < $nbActivity; $indexActivity++) {
                    if ($resourcesByActivities[$indexActivity]->available) {
                        // Création of the activity
                        $activity = new Activity();
                        $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                        $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                        $activity->setPathway($pathway);
                        $activityRepository->add($activity, true);
        
                        // Get the last inserted row, i.e the activity we just added
                        $activity =  $activityRepository->findOneBy(array(),array('id'=>'DESC'),1,0);
                        array_push($activitiesIdArray, $activity->getId());

                        // Add the link between activity - human resources
                        
                        $nbHRC = count($resourcesByActivities[$indexActivity]->humanResourceCategories);
                    
                        if ($nbHRC != 0) {
                            for ($indexHRC = 0; $indexHRC < $nbHRC; $indexHRC++) {

                                if ($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->available) {
                                    // First we get the category in the db
                                    $HRC = $HRCRepository->findById($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->id)[0];
                                                                                            
                                    // The we create the object ActivityMaterialResource
                                    $activityHumanResource = new ActivityHumanResource();
                                    $activityHumanResource->setActivity($activity);
                                    $activityHumanResource->setHumanresourcecategory($HRC);
                                    $activityHumanResource->setQuantity(intval($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb));
                                                                    
                                    // We add it to the db
                                    $AHRRepository->add($activityHumanResource , true);
                                }
                            }
                        }
                    

                        // Add the link between activity - material resources
                        
                        $nbMRC = count($resourcesByActivities[$indexActivity]->materialResourceCategories);
                    
                        if ($nbMRC != 0) {
                            for ($indexMRC = 0; $indexMRC < $nbMRC; $indexMRC++) {

                                if ($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->available) {

                                    // first we get the category of the db
                                    $MRC = $MRCRepository->findById($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->id)[0];
                                    
                                    // We create the object ActivityMaterialResource
                                    $activityMaterialResource = new ActivityMaterialResource();
                                    $activityMaterialResource->setActivity($activity);
                                    $activityMaterialResource->setMaterialresourcecategory($MRC);
                                    $activityMaterialResource->setQuantity(intval($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->nb));
                                    
                                    // then we add it to the db
                                    $AMRRepository->add($activityMaterialResource , true);
                                }
                            }
                        }
                    }
                }
                for($indexSuccessor = 0; $indexSuccessor < $nbSuccessor; $indexSuccessor++){
                    // Creation of the successor between the 2 activities
                    $successor = new Successor();
                    
                    $idA = intval(explode("activity", $successors[$indexSuccessor]->idActivityA)[1]);
                    $idB = intval(explode("activity", $successors[$indexSuccessor]->idActivityB)[1]);
                    
                    $activitya = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idA-1]]);
                    $activityb = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idB-1]]);
                    $successor->setActivitya($activitya);
                    $successor->setActivityb($activityb);
                    
                    $successor->setDelaymin($successors[$indexSuccessor]->delayMin);
                    $successor->setDelaymax($successors[$indexSuccessor]->delayMax);
                    
                    $successorRepository->add($successor, true);
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
     * Editing pathway method
     */
    public function pathwayEdit(Request $request): Response
    {
        
        // POST method to edit a pathway
        if ($request->getMethod() === 'POST' ) {
            
            $em=$this->getDoctrine()->getManager();

            // Create all the repository 
            $pathwayRepository = new PathwayRepository($this->getDoctrine());
            $activityRepository = new ActivityRepository($this->getDoctrine());
            $successorRepository = new SuccessorRepository($this->getDoctrine());
            $AHRRepository = new ActivityHumanResourceRepository($this->getDoctrine());
            $AMRRepository = new ActivityMaterialResourceRepository($this->getDoctrine());
            $HRCRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $MRCRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
            $targetRepository = new TargetRepository($this->getDoctrine());

            // We get all the data from the request
            $param = $request->request->all();

            //We get the json which contains the list of the resources by activities
            // then we transform it into a PHP Array
            $resourcesByActivities = json_decode($param['json-resources-by-activities']);
            $successors= json_decode($param['json-successors']);
            
            // First we want to add the pathway to the db :
            
            // We create the pathway object
            $pathway = $pathwayRepository->findBy(["id"=>$param['pathwayid']])[0];
            $pathway->setPathwayname($param['pathwayname']);

            // We add the pathway to the db
            $em->flush();

            // We update the targets
            $targets = $targetRepository->findBy(["pathway" => $pathway]);
            for ($i = 0 ; $i < 7 ; $i++) {
                if ( count($targetRepository->findBy(["pathway" => $pathway, "dayweek" => $i])) != 0) {
                    $targetRepository->findBy(["pathway" => $pathway, "dayweek" => $i])[0]->setTarget(intval($param['target-'.$i]));
                }
                else 
                {
                    $target = new Target();
                    $target->setTarget(intval($param['target-'.$i]));
                    $target->setDayWeek($i);
                    $target->setPathway($pathway);
                    $targetRepository->add($target, true);
                }
            }
            
            // We handle the links between pathway and ativities

            // We get the number of activities
            $nbActivity = count($resourcesByActivities);
            $nbSuccessor = count($successors);

            $pathwaySuccessors = array();

            // We create an array to store the activies id in the database after we added them
            // So we don't have to use the name (which can be the same for different activities)
            $activitiesIdArray = array();

            if ($nbActivity != 0) {
                
                $firstActivityAvailableFound = false;
                for ($indexActivity = 0; $indexActivity < $nbActivity; $indexActivity++) {
                    $activitySuccessors = $successorRepository->findBy(['activitya' => $resourcesByActivities[$indexActivity]->id]);
                    for($i = 0; $i < count($activitySuccessors); $i++){
                        $pathwaySuccessors[] = $activitySuccessors[$i];
                    }
                    if ($resourcesByActivities[$indexActivity]->available) {
                        
                        $activity = new Activity();

                        // We verify if the activity already exists (if its id if lesser than -1)
                        if ($resourcesByActivities[$indexActivity]->id == -1) {
                            // Création de l'activité
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                            $activity->setPathway($pathway);

                            $activityRepository->add($activity, true);
                            // Get the last inserted row, i.e the activity we just added
                            $activity =  $activityRepository->findOneBy(array(),array('id'=>'DESC'),1,0);
                            array_push($activitiesIdArray, $activity->getId());
                            }
                        else {
                            // if the activity already exists
                            $activity =  $activityRepository->findBy(['id' => $resourcesByActivities[$indexActivity]->id])[0];

                            // Set the activity
                            $activity->setActivityname($resourcesByActivities[$indexActivity]->activityname);
                            $activity->setDuration(intval($resourcesByActivities[$indexActivity]->activityduration));
                            $em->flush();

                            array_push($activitiesIdArray, $activity->getId());
                        }

                        
                        // Add the links activity - human resources 
                        
                        $nbMRC = count($resourcesByActivities[$indexActivity]->materialResourceCategories);
                    
                        if ($nbMRC != 0) {
                            for ($indexMRC = 0; $indexMRC < $nbMRC; $indexMRC++) {

                                // First we get the category in the db
                                $MRC = $MRCRepository->findById($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->id)[0];
                                

                                // We check if it hasn't been deleted by the user 
                                if ($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->available) {
                                    if (!($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->already)) {
                                        // If the resources is available but wasnt in the bd before the edition : We need to add it in the db
                                        
                                        // We create the object ActivityMaterialResource
                                        $activityMaterialResource = new ActivityMaterialResource();
                                        $activityMaterialResource->setActivity($activity);
                                        $activityMaterialResource->setMaterialresourcecategory($MRC);
                                        $activityMaterialResource->setQuantity(intval($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->nb));
                                        
                                        // Then we add it to the db
                                        $AMRRepository->add($activityMaterialResource , true);
                                    } else {
                                        $activityMaterialResource = $AMRRepository->findBy(["activity" => $activity, "materialresourcecategory" => $MRC]);
                                        $activityMaterialResource[0]->setQuantity(intval($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->nb));
                                    }
                                } else {
                                    // If it has been removed, we verify if it was in the db before edition
                                    if ($resourcesByActivities[$indexActivity]->materialResourceCategories[$indexMRC]->already) {
                                        // We need to find it in the db and delete it 
                                        $linkAMR = $AMRRepository->findBy(["activity" => $activity, "materialresourcecategory" => $MRC]);
                                        
                                        $em->remove($linkAMR[0]);
                                        $em->flush();
                                    }
                                }
                            }
                        }


                        $nbHRC = count($resourcesByActivities[$indexActivity]->humanResourceCategories);
                    
                        if ($nbHRC != 0) {
                            for ($indexHRC = 0; $indexHRC < $nbHRC; $indexHRC++) {

                                // First we get the category in the db
                                $HRC = $HRCRepository->findById($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->id)[0];
                                
                                // We check if it hasn't been deleted by the user 
                                if ($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->available) {
                                    if (!($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->already)) {
                                        // If the resources is available but wasnt in the bd before the edition : We need to add it in the db
                                        
                                        // We create the object ActivityHumanResource
                                        $activityHumanResource = new ActivityHumanResource();
                                        $activityHumanResource->setActivity($activity);
                                        $activityHumanResource->setHumanresourcecategory($HRC);
                                        $activityHumanResource->setQuantity(intval($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb));
                                        
                                        // Then we add it to the db
                                        $AHRRepository->add($activityHumanResource , true);
                                    } else {
                                        $activityHumanResource = $AHRRepository->findBy(["activity" => $activity, "humanresourcecategory" => $HRC]);
                                        $activityHumanResource[0]->setQuantity(intval($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb));
                                    }
                                } else {
                                    // If it has been removed, we verify if it was in the db before edition
                                    if ($resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->already) {
                                        // We need to find it in the db and delete it 
                                        $linkAHR = $AHRRepository->findBy(["activity" => $activity, "humanresourcecategory" => $HRC, "quantity" => $resourcesByActivities[$indexActivity]->humanResourceCategories[$indexHRC]->nb]);
                                        
                                        $em->remove($linkAHR[0]);
                                        $em->flush();
                                    }
                                }
                            }
                        }

                    } else {
                        // if the activity is available = false
                        // on doit verifier si elle existe dans la bd pour la supprimer 
                        if ($resourcesByActivities[$indexActivity]->id != -1) {
                            $activity = $activityRepository->findOneBy(['id' => $resourcesByActivities[$indexActivity]->id]);
                            $em->remove($activity);
                            $em->flush();
                        }
                    }
                }
            }
            for($indexSuccessor = 0; $indexSuccessor < $nbSuccessor; $indexSuccessor++){
                // Creating of the successor between the 2 activities
                $successor = new Successor();
                
                $idA = intval(explode("activity", $successors[$indexSuccessor]->idActivityA)[1]);
                $idB = intval(explode("activity", $successors[$indexSuccessor]->idActivityB)[1]);
                $activitya = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idA-1]]);
                $activityb = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idB-1]]);
                $successor->setActivitya($activitya);
                $successor->setActivityb($activityb);
                
                $successor->setDelaymin($successors[$indexSuccessor]->delayMin);
                $successor->setDelaymax($successors[$indexSuccessor]->delayMax);
                
                // Check if the successor already exist in database
                // If no, create it; else update the delays
                $successorDB = $successorRepository->findOneBy(['activitya' => $activitya, 'activityb' => $activityb]);
                if($successorDB != null){
                    $successorDB->setDelaymin($successors[$indexSuccessor]->delayMin);
                    $successorDB->setDelaymax($successors[$indexSuccessor]->delayMax);
                }
                else{
                    $pathwaySuccessors[] = $successor;
                    $successorRepository->add($successor, true);
                }
                $em->flush();
            }
            for($i = 0; $i < count($pathwaySuccessors); $i++){
                $succ_found = false;
                for($j = 0; $j < count($successors); $j++){
                    $idA = intval(explode("activity", $successors[$j]->idActivityA)[1]);
                    $idB = intval(explode("activity", $successors[$j]->idActivityB)[1]);
                    $activitya = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idA-1]]);
                    $activityb = $activityRepository->findOneBy(['id' => $activitiesIdArray[$idB-1]]);
                    if($pathwaySuccessors[$i]->getActivitya() == $activitya && $pathwaySuccessors[$i]->getActivityb() == $activityb){
                        $succ_found = true;
                    }
                }
                if(!$succ_found){
                    $em->remove($pathwaySuccessors[$i]);
                    $em->flush();
                }
            }
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
        }
    }


    public function pathwayDelete(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            
            /*
            Order of deletion :
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
            $unavailabilityMaterialResourceRepository = new UnavailabilityMaterialResourceRepository($this->getDoctrine());
            $unavailabilityHumanResourceRepository = new UnavailabilityHumanResourceRepository($this->getDoctrine());
            
            // We get all the data from the resuest
            $param = $request->request->all();

            // Get the pathway we want to delete
            $pathway = $pathwayRepository->findById($param['pathwayid'])[0];


            // --------- DELETION OF THE APPOINTMENTS --------- //

            $appointmentsInPathway = $appointmentRepository->findBy(['pathway' => $pathway]);

            $doctrine = $this->getDoctrine();

            foreach ($appointmentsInPathway as $appointment) {

                // We get all the activities associated with the appointment
                $scheduledActivityRepository = $doctrine->getManager()->getRepository("App\Entity\ScheduledActivity");
                $scheduledActivities = $scheduledActivityRepository->findBy(['appointment' => $appointment]);

                foreach ($scheduledActivities as $scheduledActivity) {
                    $date = $appointment->getDayappointment()->format('Y-m-d');

                    // Deletion of the data associated with the appointment in the table MaterialResourceScheduled
                    $materialResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
                    $allMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                    foreach ($allMaterialResourceScheduled as $materialResourceScheduled) {
                        $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
                    }


                    //suppression des données associées au rendez-vous de la table HumanResourceScheduled
                    $humanResourceScheduledRepository = $doctrine->getManager()->getRepository("App\Entity\HumanResourceScheduled");
                    $allHumanResourceScheduled = $humanResourceScheduledRepository->findBy(['scheduledactivity' => $scheduledActivity]);

                    foreach ($allHumanResourceScheduled as $humanResourceScheduled) {
                        $humanResourceScheduledRepository->remove($humanResourceScheduled, true);
                    }


                    //suppression des données associées au rendez-vous de la table ScheduledActivity
                    $scheduledActivityRepository->remove($scheduledActivity, true);
                }

                // deletion of the appointment
                $appointmentRepository->remove($appointment, true);

            }

            
            // get the activities of the pathway  
            $activitiesInPathway = $activityRepository->findBy(['pathway' => $pathway]);
            


            // --------- DELETION OF SUCCESSORS  --------- //
            // --------- DELETION OF LINKS BETWEEN ACTIVITIES AND CATEGORIES --------- //

        
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


            // --------- DELETION OF ACTIVITIES --------- //

            for ($indexActivity = 0; $indexActivity < count($activitiesInPathway); $indexActivity++) {
                $em->remove($activitiesInPathway[$indexActivity]);
                $em->flush();

            } 

            // To finish we delete the pathway
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
            $activityArray[] = $this->activityToArray($doctrine, $activity);
        }
        $successors = $doctrine->getManager()->getRepository("App\Entity\Successor")->findAll();
        $arraySuccessor = [];
        foreach($successors as $successor){
            $arraySuccessor[] = [
                'idA' => $successor->getActivityA()->getId(),
                'idB' => $successor->getActivityb()->getId(),
                'name' => $successor->getActivityb()->getActivityName(),
                'delaymin' => $successor->getDelaymin(),
                'delaymax' => $successor->getDelaymax(),
            ];
        }

        if ($activityArray == null){
            return new JsonResponse((null));
        }

        $data = $this->sortActivities($activityArray, $arraySuccessor, $doctrine);
        return new JsonResponse($data);
    }

    public function sortActivities($activityArray, $arraySuccessor, ManagerRegistry $doctrine){
        $activitiesSortedByLevel = [];
        
        for($i=0; $i < count($activityArray); $i++){
            $racine = true;
            for($j=0; $j < count($arraySuccessor); $j++){
                if($activityArray[$i]['id'] == $arraySuccessor[$j]['idB']){
                    $racine = false;
                }
            }
            if($racine){
                $activitiesSortedByLevel[0][] = $activityArray[$i];
            }
        }
        $level = 0;
        while(isset($activitiesSortedByLevel[$level]) && $activitiesSortedByLevel[$level] != null){
            for($i = 0; $i < count($activitiesSortedByLevel[$level]); $i++){
                $listSuccessors = $activitiesSortedByLevel[$level][$i]['successor'];
                for($j = 0; $j < count($listSuccessors); $j++){
                    $activityToAdd = $doctrine->getManager()->getRepository("App\Entity\Activity")->findOneBy(['id'=>$listSuccessors[$j]['idB']]);
                    $activityToAddArray = $this->activityToArray($doctrine, $activityToAdd);
                    if(!isset($activitiesSortedByLevel[$level+1])){
                        $activitiesSortedByLevel[$level+1][] = $activityToAddArray;
                    }
                    else{
                        $found = false; 
                        for($k = 0; $k < count($activitiesSortedByLevel[$level+1]); $k++){
                            if($activitiesSortedByLevel[$level+1][$k] == $activityToAddArray){
                                $found = true;
                            }
                        }
                        if(!$found){
                            $activitiesSortedByLevel[$level+1][] = $activityToAddArray;
                        }
                    }
                }
            }
            $level++;
        }
        return $activitiesSortedByLevel;
    }

    public function activityToArray(ManagerRegistry $doctrine, $activity){
        $successors = $doctrine->getManager()->getRepository("App\Entity\Successor")->findBy(["activitya"=>$activity]);
        $arraySuccessor = [];
        foreach($successors as $successor){
            $arraySuccessor[] = [
                'idB' => $successor->getActivityb()->getId(),
                'delaymin' => $successor->getDelaymin(),
                'delaymax' => $successor->getDelaymax(),
            ];
        }
        $activityArray = [
            'id' => $activity->getId(),
            'name' => $activity->getActivityname(),
            'duration' => $activity->getDuration(),
            'successor' => $arraySuccessor,
        ];
        return $activityArray;
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
            $date = $appointment->getDayappointment()->format('U');
            if($date >= date('U')){
                $appointmentArray[] = [
                    'lastname' => $appointment->getPatient()->getLastname(),
                    'firstname' => $appointment->getPatient()->getFirstname(),
                    'date' => $appointment->getDayappointment()->format('d-m-Y'),
                ];
            }
        }

        return new JsonResponse($appointmentArray);
    }

    public function autocompletePathway(Request $request, PathwayRepository $pathwayRepository){
        $term = strtolower($request->query->get('term'));
        $patwhays = $pathwayRepository->findAll();
        $results = array();
        foreach ($patwhays as $pathway) {
            if (   strpos(strtolower($pathway->getPathwayname()), $term) !== false){
                $results[] = [
                    'id' => $pathway->getId(),
                    'value' => $pathway->getPathwayname()

                ];
            }
        }
        return new JsonResponse($results);
    }
}
