<?php

namespace App\Controller;

use App\Entity\MaterialResource;
use App\Entity\CategoryOfMaterialResource;
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
use App\Repository\UnavailabilityMaterialResourceRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/material/resource")
 */
class MaterialResourceController extends AbstractController
{
    /**
     * @Route("/", name="app_material_resource_index", methods={"GET"})
     */
    public function index(MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine): Response
    {
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $materialResourceCategories = $materialResourceCategoryRepository->findAll();
        $materialResources = $materialResourceRepository->findAll();
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $nbMaterialResource = count($materialResources);
        $nbMaterialResourceCategory = count($materialResourceCategories);
        $nbCategBy = count($categOfMaterialResource);
        $categoriesByResources = array();
        $categoriesByMaterialResources = $this->listCategoriesByMaterialResourcesJSON($doctrine);
        $unavailabilities = $this->listUnavailabilitiesMaterialJSON($doctrine);
        for($indexResource = 0; $indexResource < $nbMaterialResource; $indexResource++) {
                $listCategOf = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResources[$indexResource]]);
            
                $categoriesByResource = array();
                for($indexCategOf = 0; $indexCategOf < count($listCategOf); $indexCategOf++) {
                    //dd($humanResourceCategories[$indexCategOf]->getCategoryname());
                    //dd( $humanResourceCategoryRepository->findBy(['id' => $humanResourceCategories[$indexCategOf]]));
                    //array_push($categoriesByResource, $humanResourceCategoryRepository->findBy(['id' => $humanResourceCategories[$indexCategOf]])[0]);
                    //dd($listCategOf[$indexCategOf]);
                    $materialResourceCategoriesBy =  $materialResourceCategoryRepository->findBy(['id' => $listCategOf[$indexCategOf]->getMaterialresourcecategory()->getId()]);
                    //dd($materialResourceCategories);
                    if($materialResourceCategoriesBy != null){
                        array_push($categoriesByResource,$materialResourceCategoriesBy[0]);
                    }
                    
                }
                array_push($categoriesByResources, $categoriesByResource);
        }

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
        //dd($categoriesByResources);
        return $this->render('material_resource/index.html.twig', [
            'material_resources' => $materialResourceRepository->findAll(),
            'material_resources_categories' => $materialResourceCategories,
            'categoriesByMaterialResources' => $categoriesByMaterialResources,
            'unavailabilities' => $unavailabilities
        ]); 
    }


    /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource humaine
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
    




        //Conversion des données ressources en json
        $unavailabilitiesFilteredJson = new JsonResponse($unavailabilitiesFiltered);
        return $unavailabilitiesFilteredJson;    
    }

    /**
     * @Route("/new", name="app_material_resource_new", methods={"GET", "POST"})
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


            // On récupère toutes les catégories
            $categoryOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
            $categories = $categoryOfMaterialResourceRepository->findAll();            

            // On récupère le nombre de catégories
            $nbCategory = $param['nbCategory'];


            //$activityArray = array();


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
public function unavailability(Request $request) {

    $param = $request->request->all(); 
    $startTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-begin-unavailability'].":00"));
    $endTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-end-unavailability'].":00"));

    $unavailabilitiesRepository = new UnavailabilityRepository($this->getDoctrine());
    $unavailabilities = $unavailabilitiesRepository->findAll();
    $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($this->getDoctrine());
    $unavailabilitiesMaterial = $unavailabilitiesMaterialRepository->findAll();
    $materialResourcesRepository = new MaterialResourceRepository($this->getDoctrine());
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

    /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource humaine
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



    /**
     * @Route("/{id}/edit", name="app_material_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,ManagerRegistry $doctrine)
    {
       // Méthode POST pour ajouter un circuit
       if ($request->getMethod() === 'POST' ) {
            
        // On recupere toutes les données de la requete
        $param = $request->request->all();
        // On récupère l'objet parcours que l'on souhaite modifier grace a son id
        $materialResourceRepository = new MaterialResourceRepository($doctrine);
        $materialResource = $materialResourceRepository->findById($param['id'])[0];
        $materialResource->setMaterialResourceName($param['resourcename']);
        //$pathway->setAvailable(true);

        // On ajoute le parcours a la bd
        $materialResourceRepository->add($materialResource, true);

        // On s'occupe ensuite ds liens entre le parcours et les activités :

        // On récupère toutes les activités
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($doctrine);
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $materialResourcesCategories = $materialResourceCategoryRepository->findAll();

        // On supprime toutes les activités et leurs successor
        $em=$doctrine->getManager();
        $categsOfResources = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResource]);
            for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
                $em->remove($categsOfResources[$indexCategOf]);
            }
            $em->flush();
        }

        // On récupère le nombre de catégories
        $nbCategories = $param['nbcategory'];

        //$activityArray = array();
        if ($nbCategories != 0) {
            /* $categOf_old = new CategoryOfHumanResource();      
            
            $categOf_old->setHumanresource($param["name-activity-0"]);
            $categOf_old->setHumanresourcecategory($param[ "duration-activity-0"]);

            $categOfHumanResourceRepository->add($categOf_old, true); */

            for($i = 0; $i < $nbCategories; $i++)
            {
                
                $categOf = new CategoryOfMaterialResource();
                $categOf->setMaterialresource($materialResource);
                $categOf->setMaterialresourcecategory($materialResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                //dd($categOf);
                $categOfMaterialResourceRepository->add($categOf, true);
                //dd($categOfHumanResourceRepository->findAll());
               //}

              //  $categOf_old = $categOfHumanResourceRepository->findById($humanResource->getId())[0];

            }
        }
        
        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }

    public function deleteUnavailability(Request $request, ManagerRegistry $doctrine)
    {
        if (isset($_POST['idMaterialAvailability'])) {
            $idMaterialAvailability = $_POST['idMaterialAvailability'];

            if (isset($_POST['idUnavailability'])) {
                $idUnavailability = $_POST['idUnavailability'];

                $unavailabilitiesRepository = new UnavailabilityRepository($this->getDoctrine());
                $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($this->getDoctrine());
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
    /**
     * @Route("/{id}", name="app_material_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, MaterialResource $materialResource, CategoryOfMaterialResourceRepository $categoryOfMaterialResourceRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, UnavailabilityMaterialResourceRepository $unavailabilityMaterialResourceRepository, MaterialResourceRepository $materialResourceRepository,ManagerRegistry $doctrine): Response
    {
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($doctrine);
        $unavailabilitiesMaterialRepository = new UnavailabilityMaterialResourceRepository($doctrine);
        $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);

        $em=$doctrine->getManager();
        $categsOfResources = $categOfMaterialResourceRepository->findBy(['materialresource' => $materialResource]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }
        $em->flush();
        if ($this->isCsrfTokenValid('delete'.$materialResource->getId(), $request->request->get('_token'))) {
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

            $em->flush();

            $materialResourceRepository->remove($materialResource, true);
        }

        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }

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
}
