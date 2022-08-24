<?php

namespace App\Controller;

use App\Entity\MaterialResource;
use App\Entity\CategoryOfMaterialResource;
use App\Entity\ScheduledActivity;
use App\Entity\Unavailability;
use App\Entity\UnavailabilityMaterialResource;
use App\Repository\MaterialResourceRepository;
use App\Repository\MaterialResourceCategoryRepository;
use App\Repository\UnavailabilityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryOfMaterialResourceRepository;
use App\Repository\MaterialResourceScheduledRepository;
use App\Repository\ScheduledActivityRepository;
use App\Repository\UnavailabilityMaterialResourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/material/resource")
 */
class MaterialResourceController extends AbstractController
{

    /*
      * @brief Allows to list every material resources in the database
     */

    public function index(MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator,String $type="resources"): Response
    {
        $listMaterialResources = $this->listMaterialResources($materialResourceRepository, $doctrine,$request,$paginator);
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $materialResourceCategories = $materialResourceCategoryRepository->findMaterialCategoriesSorted();
        $materialResources = $materialResourceRepository->findAll();
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $nbMaterialResource = count($materialResources);
        $nbMaterialResourceCategory = count($materialResourceCategories);
        $nbCategBy = count($categOfMaterialResource);
        $categoriesByResources = array();
        //categories by resources in JSON object
        $categoriesByMaterialResources = $this->listCategoriesByMaterialResourcesJSON($doctrine);
        //unavailabilities of material resources in JSON object
        $unavailabilities = $this->listUnavailabilitiesMaterialJSON($doctrine);
        //Listing of categories by resources
        for($indexResource = 0; $indexResource < $nbMaterialResource; $indexResource++) {
                $listCategOf = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResources[$indexResource]]);
            
                $categoriesByResource = array();
                for($indexCategOf = 0; $indexCategOf < count($listCategOf); $indexCategOf++) {
                    $materialResourceCategoriesBy =  $materialResourceCategoryRepository->findBy(['id' => $listCategOf[$indexCategOf]->getMaterialresourcecategory()->getId()]);
                    if($materialResourceCategoriesBy != null){
                        array_push($categoriesByResource,$materialResourceCategoriesBy[0]);
                    }
                    
                }
                array_push($categoriesByResources, $categoriesByResource);
        }
        //Listing of resources by categories
        $resourcesByCategories = array();
        for($indexCategory = 0; $indexCategory< $nbMaterialResourceCategory; $indexCategory++) {
            $listMaterialOf = $categOfMaterialResourceRepository->findBy(['materialresourcecategory' => $materialResourceCategories[$indexCategory]]);
        
            $resourcesByCategory = array();
            for($indexMaterialOf = 0; $indexMaterialOf < count($listMaterialOf); $indexMaterialOf++) {
                $materialResourceBy =  $materialResourceRepository->findBy(['id' => $listMaterialOf[$indexMaterialOf]->getMaterialresource()->getId()]);
                if($materialResourceBy != null){
                    array_push($resourcesByCategory,$materialResourceBy[0]);
                }
            }
            array_push($resourcesByCategories, $resourcesByCategory);
        }
        return $this->render('material_resource/index.html.twig', [
            'material_resources' => $listMaterialResources,
            'material_resources_categories' => $materialResourceCategories,
            'categoriesByMaterialResources' => $categoriesByMaterialResources,
            'unavailabilities' => $unavailabilities,
            'resourceType' => "material",
            'type' => $type,
        ]); 
    }


    /*
      * @brief Allows to list every material resources in the database with the pagination to not display everything at the same time
     */
    public function listMaterialResources(MaterialResourceRepository $materialResourceRepository, ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator){
        $categoryOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $categoryOfMaterialResources = $categoryOfMaterialResourceRepository->findAll();
        
        $materialResources = array();
        foreach($materialResourceRepository->findMaterialResourcesSorted() as $materialResource){
            $categories = array();
            foreach($categoryOfMaterialResources as $categoryOfMaterialResource){
                if($categoryOfMaterialResource->getMaterialresource()->getId() == $materialResource->getId()){
                    $categories[] = [
                        'id' => $categoryOfMaterialResource->getMaterialresourcecategory()->getId(),
                        'categoryname' => $categoryOfMaterialResource->getMaterialresourcecategory()->getCategoryname()
                    ];
                }
            }

            $materialResources[] = [
                'id' => $materialResource->getId(),
                'materialresourcename' => $materialResource->getMaterialresourcename(),
                'categories' => $categories
            ];
        }
        //pagination
        $materialResources=$paginator->paginate(
            $materialResources, 
            $request->query->getInt('page',1),
            10
        ); 
        return $materialResources;
    }

    /*
     * @brief Allows to create a JSON object from a list of unavailabilities of material resources
     */
    public function listUnavailabilitiesMaterialJSON(ManagerRegistry $doctrine)
    {
        $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
        $unavailabilities = $unavailabilitiesRepository->findAll();
        $unavailabilitiesArray = array();

        if ($unavailabilities != null) {
            foreach ($unavailabilities as $unavailability) {
                $unavailabilitiesArray[] = array('id' => strval($unavailability->getId()),
                    'startdatetime' => $unavailability->getStartdatetime(),
                    'enddatetime' => $unavailability->getEnddatetime(),
                );
            }
        }

        $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($doctrine);
        $unavailabilitiesMaterial = $unavailabilitiesMaterialRepository->findAll();
        $unavailabilitiesMaterialArray = array();

        if ($unavailabilitiesMaterial != null) {
            foreach ($unavailabilitiesMaterial as $unavailabilityMaterial) {
                $unavailabilitiesMaterialArray[] = array('id' => strval($unavailabilityMaterial->getId()),
                    'materialresource_id' => $unavailabilityMaterial->getMaterialResource()->getId(),
                    'unavailability_id' => $unavailabilityMaterial->getUnavailability()->getId()
                );
            }
        }
        //Filtering the list of unavailabilities
        $unavailabilitiesFiltered = array();
        foreach ($unavailabilitiesArray as $unavailability) {
            foreach($unavailabilitiesMaterial as $unavailabilityMaterial) {
                if($unavailability['id'] == $unavailabilityMaterial->getUnavailability()->getId()) {
                    $unavailabilitiesFiltered[] = array('id_unavailability' => $unavailability['id'],
                        'id_unavailability_material' =>strval($unavailabilityMaterial->getId()),
                        'id_human_resource' =>strval($unavailabilityMaterial->getMaterialresource()->getId()),
                        'startdatetime' => $unavailability['startdatetime'],
                        'enddatetime' => $unavailability['enddatetime'] 
                    );
                }

            }
        }
    




        //Converting data into a JSON object
        $unavailabilitiesFilteredJson = new JsonResponse($unavailabilitiesFiltered);
        return $unavailabilitiesFilteredJson;    
    }

   /*
     * @brief Allows to create a new material resource in the dabatase
     */
    
    public function new(Request $request, MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine): Response
    {
        if ($request->getMethod() === 'POST') {
            $materialResource = new MaterialResource();
            $param = $request->request->all();
            $name = $param['resourcename'];
            $materialResource->setMaterialresourcename($name);
            $materialResourceRepository = new MaterialResourceRepository($doctrine);
            $materialResourceRepository->add($materialResource, true);
            $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);


            //We get all categories
            $categoryOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
            $categories = $categoryOfMaterialResourceRepository->findAll();            

            //We get the number of categories linked to the new resource
            $nbCategory = $param['nbCategory'];
            //Adding links to categories
            for($i = 0; $i < $nbCategory; $i++)
            {
                $linkCategRes = new CategoryOfMaterialResource();      

                $linkCategRes->setMaterialresource($materialResource);
                $linkCategRes->setMaterialResourcecategory($materialResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                $categoryOfMaterialResourceRepository->add($linkCategRes, true);
            }
            return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
        }
    
}

    /*
     * @brief Allows to create a new unavailability linked to a specific material resource
     */

    public function unavailability(Request $request,ManagerRegistry $doctrine) {

    $param = $request->request->all(); 
    $startTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-begin-unavailability'].":00"));
    $endTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-end-unavailability'].":00"));

    $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
    $unavailabilities = $unavailabilitiesRepository->findAll();
    $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($doctrine);
    $unavailabilitiesMaterial = $unavailabilitiesMaterialRepository->findAll();
    $materialResourcesRepository = new MaterialResourceRepository($doctrine);
    $materialResource = $materialResourcesRepository->findBy(['id' => $param['id-material-resource-unavailability']]);

    
    $unavailability = new Unavailability();
    $unavailabilityMaterialResource = new UnavailabilityMaterialResource();
    $unavailability->setStartdatetime($startTime);
    $unavailability->setEnddatetime($endTime);
    $unavailabilitiesRepository->add($unavailability, true);
    $unavailabilityMaterialResource->setMaterialresource($materialResource[0]);
    $unavailabilityMaterialResource->setUnavailability($unavailability);
    $unavailabilitiesMaterialRepository->add($unavailabilityMaterialResource, true);
    return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
}

    /*
     * @brief Allows to create a JSON object from a list of categories of material resources
     */
    public function listCategoriesByMaterialResourcesJSON(ManagerRegistry $doctrine)
    {
        $categoriesByMaterialResourcesRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $categoriesByMaterialResources = $categoriesByMaterialResourcesRepository->findAll();
        $categoriesByMaterialResourcesArray = array();

        if ($categoriesByMaterialResources != null) {
            foreach ($categoriesByMaterialResources as $category) {
                $categoriesByMaterialResourcesArray[] = array('id' => strval($category->getId()),
                    'materialresource_id' => $category->getMaterialresource()->getId(),
                    'materialresourcecategory_id' => $category->getMaterialresourcecategory()->getId()                    
                );
            }
        }
        //Conversion des données ressources en json
        $categoriesByMaterialResourcesArrayJson = new JsonResponse($categoriesByMaterialResourcesArray);
        return $categoriesByMaterialResourcesArrayJson;    
    }

    /*
     * @brief Allows to edit a material resource that is already in the database
     */

    public function edit(Request $request,ManagerRegistry $doctrine)
    {
       if ($request->getMethod() === 'POST' ) {
            
        //We get all parameters
        $param = $request->request->all();
        //We get the resource that we want to edit
        $materialResourceRepository = new MaterialResourceRepository($doctrine);
        $materialResource = $materialResourceRepository->findById($param['id'])[0];
        $materialResource->setMaterialResourceName($param['resourcename']);

        //We add the resource to the database
        $materialResourceRepository->add($materialResource, true);

        //We get all the links between resources and categories
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $materialResourcesCategories = $materialResourceCategoryRepository->findAll();

        //We delete links between resources and categories if needed
        $em=$doctrine->getManager();
        $categsOfResources = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResource]);
            for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
                $em->remove($categsOfResources[$indexCategOf]);
            }
            $em->flush();
        }

        //We get the number of categories now linked to the resource
        $nbCategories = $param['nbcategory'];

        if ($nbCategories != 0) {

            for($i = 0; $i < $nbCategories; $i++)
            {
                
                $categOf = new CategoryOfMaterialResource();
                $categOf->setMaterialresource($materialResource);
                $categOf->setMaterialresourcecategory($materialResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                $categOfMaterialResourceRepository->add($categOf, true);

            }
        }
        
        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }
    /*
     * @brief Allows to delete an unavailability linked to a resource that is already in the database
     */
    public function deleteUnavailability(Request $request, ManagerRegistry $doctrine)
    {
        if (isset($_POST['idMaterialAvailability'])) {
            $idMaterialAvailability = $_POST['idMaterialAvailability'];

            if (isset($_POST['idUnavailability'])) {
                $idUnavailability = $_POST['idUnavailability'];

                $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
                $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($doctrine);
                $unavailabilityToDelete = $unavailabilitiesRepository->findBy(['id' => $idUnavailability]);
                $unavailabilityMaterialToDelete = $unavailabilitiesMaterialRepository->findBy(['id' => $idMaterialAvailability]);
        
                $em=$doctrine->getManager();
                $unavailabilitiesRepository->remove($unavailabilityToDelete[0], true);
                $unavailabilitiesMaterialRepository->remove($unavailabilityMaterialToDelete[0], true);
                $em->flush();

            }
        }
        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);

    }

    /*
     * @brief Allows to delete a material resource that is already in the database
     */

    /**
     * @Route("/{id}", name="app_material_resource_delete", methods={"POST"})
     */
    public function delete(MaterialResource $materialResource, CategoryOfMaterialResourceRepository $categoryOfMaterialResourceRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, UnavailabilityMaterialResourceRepository $unavailabilityMaterialResourceRepository, MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine): Response
    {
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($doctrine);
        $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
        $scheduledMaterialResourcesRepository = new MaterialResourceScheduledRepository($doctrine);
        $scheduledActivity = new ScheduledActivityRepository($doctrine);


        $em=$doctrine->getManager();
        $categsOfResources = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResource]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }
        $em->flush();
            $listCategoryOfMaterialResource = $categoryOfMaterialResourceRepository->findBy(['materialresource' => $materialResource]);

            foreach($listCategoryOfMaterialResource as $categoryOfMaterialResource)
            {
                $categoryOfMaterialResourceRepository->remove($categoryOfMaterialResource, true);
            }

            $listMaterialResourceScheduled = $materialResourceScheduledRepository->findBy(['materialresource' => $materialResource]);

            foreach($listMaterialResourceScheduled as $materialResourceScheduled)
            {
                $materialResourceScheduledRepository->remove($materialResourceScheduled, true);
            }

            

            $unavailabilitiesMaterial = $unavailabilitiesMaterialRepository->findBy(['materialresource' => $materialResource]);

            for ($indexUnavailabilityMaterial = 0; $indexUnavailabilityMaterial < count($unavailabilitiesMaterial); $indexUnavailabilityMaterial++)
            {
            $unavailabilityToDelete = $unavailabilitiesRepository->findBy(['id' => $unavailabilitiesMaterial[$indexUnavailabilityMaterial]->getUnavailability()->getId()]);
            $unavailabilitiesRepository->remove($unavailabilityToDelete[0], true);
            $em->remove($unavailabilitiesMaterial[$indexUnavailabilityMaterial]);

            }     

            $scheduledMaterialResources = $scheduledMaterialResourcesRepository->findBy(['materialresource' => $materialResource]);
            for ($indexScheduledMaterialResource = 0; $indexScheduledMaterialResource < count($scheduledMaterialResources); $indexScheduledMaterialResource++){
            $em->remove($scheduledMaterialResources[$indexScheduledMaterialResource]);
            } 

            $em->flush();

            $materialResourceRepository->remove($materialResource, true);
        

        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }

    /*
      * @brief Allows to get all data of a specified material resource
     */
    public function getDataMaterialResource(ManagerRegistry $doctrine)
    {
        if(isset($_POST["idMaterialResource"])){
            if(isset($_POST["date"])){
                $categories = $this->getCategoryByMaterialResourceId($_POST["idMaterialResource"], $doctrine);
                $unavailability= $this->getUnavailabilityByMaterialResourceId($_POST["idMaterialResource"], $doctrine);
                $activities = $this->getActivities($_POST["idMaterialResource"],$_POST["date"],$doctrine);
                $data = array(
                    "categories" => $categories,
                    "unavailability" => $unavailability,
                    "activities" => $activities
                );
                return new JsonResponse($data);
            }
        }
        if(isset($_POST["idMaterialResourceCategory"])){
            $resources = $this->getResourceByMaterialResourceCategoryId($_POST["idMaterialResourceCategory"], $doctrine);
            return new JsonResponse($resources);
        }
    }

    /*
      * @brief Allows to get all categories of a material resource
     */
    public function getCategoryByMaterialResourceId($id, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getManager()->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();
        $categoryArray=[];
        foreach ($categories as $category) {
            if ($category->getMaterialresource()->getId() == $id){
                $categoryArray[] = [
                    'materialresourcecategory' => $category->getMaterialresourcecategory()->getCategoryname(),
                ];
            }
        }
        return $categoryArray;
    }

    /*
      * @brief Allows to get all resources of a material resource category
     */
    public function getResourceByMaterialResourceCategoryId($id, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getManager()->getRepository("App\Entity\CategoryOfMaterialResource")->findAll();
        $resourceArray=[];
        foreach ($categories as $category) {
            if ($category->getMaterialresourceCategory()->getId() == $id){
                $resourceArray[] = [
                    'materialresource' => $category->getMaterialresource()->getMaterialresourcename(),
                ];
            }
        }
        return $resourceArray;
    }

    public function getActivitiesByMaterialResourceCategoryId(ManagerRegistry $doctrine)
    {
        if(isset($_POST['idMaterialResourceCategory'])){
            $id = $_POST['idMaterialResourceCategory'];
            $activities = $doctrine->getManager()->getRepository("App\Entity\ActivityMaterialResource")->findActivitiesByMaterialResourceCategory($id);
            $activityArray=[];
            $i = 0;
            foreach ($activities as $activity) {
                if(isset($activityArray[$i])){
                    if($activityArray[$i]['pathwayname'] == $activity['pathwayname']){
                        $activityArray[$i]['activities'][] = [
                            'activityname' => $activity['activityname'],
                            'quantity' => $activity['quantity']
                        ];
                    }
                    else{
                        $i++;
                        $activitiesPathway = [];
                        $activitiesPathway[] = [
                            'activityname' => $activity['activityname'],
                            'quantity' => $activity['quantity']
                        ];
                        $activityArray[$i] = [
                            'pathwayname' => $activity['pathwayname'],
                            'activities' => $activitiesPathway
                        ];
                    }
                }
                else{
                    $activitiesPathway = [];
                    $activitiesPathway[] = [
                        'activityname' => $activity['activityname'],
                        'quantity' => $activity['quantity']
                    ];
                    $activityArray[$i] = [
                        'pathwayname' => $activity['pathwayname'],
                        'activities' => $activitiesPathway
                    ];
                }
            }
            return new JsonResponse($activityArray);
        }
        else{
            return null;
        }
    }

    /*
      * @brief Allows to get all unavailabilities of a material resource
     */
    public function getUnavailabilityByMaterialResourceId($id, ManagerRegistry $doctrine)
    {
        $unavailabilities = $doctrine->getManager()->getRepository("App\Entity\UnavailabilityMaterialResource")->findBy(['materialresource' => $id]);
        $unavailabilityArray=[];
        foreach ($unavailabilities as $unavailability) {
            if ($unavailability->getMaterialresource()->getId() == $id){
                $unavailabilityArray[] = [
                    'starttime' => str_replace(" ","T",$unavailability->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                    'endtime' =>  str_replace(" ","T",$unavailability->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                ];
            }
        }
        return $unavailabilityArray;
    }

    /*
      * @brief Allows to get all activites linked to a material resource
     */
    public function getActivities($id, $dateStr, ManagerRegistry $doctrine)
    {
        $activities = $doctrine->getManager()->getRepository("App\Entity\MaterialResourceScheduled")->findBy(['materialresource' => $id]);
        $activityArray=[];
        $activitiesArray=[];
        $date=new \DateTime($dateStr);
        $dayOfWeek=date('w', $date->getTimestamp());
        $date->modify('-'.$dayOfWeek.' days');
        $monday=new \DateTime($date->format('Y-m-d'));
        $monday->modify('+1 days');
        $date->modify('+7 days');
        $sunday=new \DateTime($date->format('Y-m-d'));
        foreach ($activities as $activity) {
            if($activity->getScheduledActivity()->getAppointment()->getDayappointment() >= $monday 
            && $activity->getScheduledActivity()->getAppointment()->getDayappointment() <= $sunday){
            $activityArray[] = [
                'dayappointment' => $activity->getScheduledActivity()->getAppointment()->getDayappointment()->format('Y-m-d'),
                    'activity' => $activity->getScheduledActivity()->getActivity()->getActivityname(),
                    'pathway' => $activity->getScheduledActivity()->getActivity()->getPathway()->getPathwayname(),
                    'starttime' => $activity->getScheduledActivity()->getStarttime()->format('H:i:s'),
                    'endtime' => $activity->getScheduledActivity()->getEndtime()->format('H:i:s'),
                    'patient' => $activity->getScheduledActivity()->getAppointment()->getPatient()->getLastname()." ".$activity->getScheduledActivity()->getAppointment()->getPatient()->getFirstname(),
            ];
        }
        }
        return $activityArray;
    }

    /*
      * @brief Allows to autocomplete the material resources researches
     */
    public function autocompleteMR(Request $request, MaterialResourceRepository $MRRepository, CategoryOfMaterialResourceRepository $categoryOfMRRepository){
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
        $MRs = $MRRepository->findBy(array(), array('materialresourcename' => 'ASC'));
        $results = array();
        foreach ($MRs as $MR) {
            $name = strtr(mb_strtolower($MR->getMaterialresourcename(),'UTF-8'), $utf8);
            if (strpos($name, $term) !== false ){
                $categories=$categoryOfMRRepository->findBy(['materialresource' => $MR->getId()]);
                $categoriesArray=[];
                foreach($categories as $category){
                    $categoriesArray[] = [
                        'category' => $category->getMaterialresourcecategory()->getCategoryname(),
                    ];
                }
                $results[] = [
                    'id' => $MR->getId(),
                    'value' => $MR->getMaterialresourcename(),
                    'categories' => $categoriesArray,

                ];
            }
        }
        return new JsonResponse($results);
    }

    public function showCategory(MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator){
        return $this->index($materialResourceRepository,$doctrine,$request, $paginator,"categories");
    }

    public function GetAppointmentFromMaterialResourceId(ManagerRegistry $doctrine, int $id) {
        $HRSRepository= $doctrine->getManager()->getRepository("App\Entity\MaterialResourceScheduled");
        $appointments = $HRSRepository->findAppointmentsByMaterialResource($id, date('Y-m-d'));

        $appointmentArray = [];
        foreach ($appointments as $appointment) {
            $appointmentArray[] = [
                'lastname' => $appointment['lastname'],
                'firstname' => $appointment['firstname'],
                'pathwayname' => $appointment['pathwayname'],
                'date' => $appointment['dayappointment']->format('d/m/Y'),
            ];
        }

        return new JsonResponse($appointmentArray);
    }
}
