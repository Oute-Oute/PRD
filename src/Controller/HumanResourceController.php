<?php

namespace App\Controller;

use App\Entity\HumanResource;
use App\Entity\CategoryOfHumanResource;
use App\Entity\HumanResourceCategory;
use App\Entity\HumanResourceScheduled;
use App\Entity\Unavailability;
use App\Entity\WorkingHours;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\CategoryOfHumanResourceRepository;
use App\Repository\UnavailabilityHumanResourceRepository;

use App\Entity\UnavailabilityHumanResource;
use App\Repository\ActivityHumanResourceRepository;
use App\Repository\HumanResourceScheduledRepository;
use App\Repository\UnavailabilityRepository;
use App\Repository\WorkingHoursRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/*
 * @file        HumanResourceController.php
 * @brief       Contains the functions that allow to handle the human resources
 * @details     Allows to create, read, update, delete every human resources
 * @date        2022
 */

/**
 * @Route("/human/resource")
 */
class HumanResourceController extends AbstractController
{

    /*
      * @brief Allows to list every human resources in the database
     */
    public function index(HumanResourceRepository $humanResourceRepository,ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator,String $type="resources"): Response
    {
        $humanResources = $this->listHumanResources($humanResourceRepository, $doctrine,$request,$paginator);

        $humanResourceCategoryRepository = new HumanResourceCategoryRepository($doctrine);
        $humanResourceCategories = $humanResourceCategoryRepository->findHumanCategoriesSorted();

        //get working hours of human resources
        $workingHours = $this->listWorkingHoursJSON($doctrine);
        //get unavaibilities of human resources
        $unavailabilities = $this->listUnavailabilitiesHumanJSON($doctrine);
        //get links of human resources with their categories
        $categoriesByHumanResources = $this->listCategoriesByHumanResourcesJSON($doctrine);
        return $this->render('human_resource/index.html.twig', [
            'human_resources' => $humanResources,
            'human_resources_categories' => $humanResourceCategories,
            'workingHours' => $workingHours,
            'categoriesByHumanResources' => $categoriesByHumanResources,
            'unavailabilities' => $unavailabilities,
            'resourceType' => "human",
            'type' => $type
        ]); 
    }

    /*
      * @brief Allows to list every human resources with a pagination included, to not display every resources at the same time
     */
    public function listHumanResources(HumanResourceRepository $humanResourceRepository, ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator){
        $categoryOfHumanResourceRepository = new CategoryOfHumanResourceRepository($doctrine);
        $categoryOfHumanResources = $categoryOfHumanResourceRepository->findAll();
        
        $humanResources = array();
        //$humanResourceRepository->findBy(array(), array('humanresourcename' => 'ASC'))
        foreach($humanResourceRepository->findHumanResourcesSorted() as $humanResource){
            $categories = array();
            foreach($categoryOfHumanResources as $categoryOfHumanResource){
                if($categoryOfHumanResource->getHumanresource()->getId() == $humanResource->getId()){
                    $categories[] = [
                        'id' => $categoryOfHumanResource->getHumanresourcecategory()->getId(),
                        'categoryname' => $categoryOfHumanResource->getHumanresourcecategory()->getCategoryname()
                    ];
                }
            }

            $humanResources[] = [
                'id' => $humanResource->getId(),
                'humanresourcename' => $humanResource->getHumanresourcename(),
                'categories' => $categories
            ];
        }
        //pagination
        $humanResources=$paginator->paginate(
            $humanResources, 
            $request->query->getInt('page',1),
            10
        ); 
        return $humanResources;
    }

    /*
     * @brief Allows to create a JSON object from a list of working hours of human resources
     */
    public function listWorkingHoursJSON(ManagerRegistry $doctrine)
    {
        $workingHoursRepository = new WorkingHoursRepository($doctrine);
        $workingHours = $workingHoursRepository->findAll();
        $workingHoursArray = array();

        if ($workingHours != null) {
            foreach ($workingHours as $workingHour) {
                $workingHoursArray[] = array('id' => strval($workingHour->getId()),
                    'humanresource_id' => $workingHour->getHumanresource()->getId(),
                    'starttime' => $workingHour->getStarttime(),
                    'endtime' => $workingHour->getEndtime(),
                    'dayweek' => $workingHour->getDayweek()
                );
            }
        }
        //Converting data into a JSON object
        $workingHoursArrayJson = new JsonResponse($workingHoursArray);
        return $workingHoursArrayJson;    
    }

    /*
     * @brief Allows to create a JSON object from a list of unavailibilities of human resources
     */
    public function listUnavailabilitiesHumanJSON(ManagerRegistry $doctrine)
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

        $unavailabilitiesHumanRepository = new UnavailabilityHumanResourceRepository($doctrine);
        $unavailabilitiesHuman = $unavailabilitiesHumanRepository->findAll();
        $unavailabilitiesHumanArray = array();

        if ($unavailabilitiesHuman != null) {
            foreach ($unavailabilitiesHuman as $unavailabilityHuman) {
                $unavailabilitiesHumanArray[] = array('id' => strval($unavailabilityHuman->getId()),
                    'humanresource_id' => $unavailabilityHuman->getHumanResource()->getId(),
                    'unavailability_id' => $unavailabilityHuman->getUnavailability()->getId()
                );
            }
        }
        //Filtering unavailabilities since we don't need every of them
        $unavailabilitiesFiltered = array();
        foreach ($unavailabilitiesArray as $unavailability) {
            foreach($unavailabilitiesHuman as $unavailabilityHuman) {
                if($unavailability['id'] == $unavailabilityHuman->getUnavailability()->getId()) {
                    $unavailabilitiesFiltered[] = array('id_unavailability' => $unavailability['id'],
                        'id_unavailability_human' =>strval($unavailabilityHuman->getId()),
                        'id_human_resource' =>strval($unavailabilityHuman->getHumanresource()->getId()),
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
     * @brief Allows to create a JSON object from a list of categories links with human resources
     */
    public function listCategoriesByHumanResourcesJSON(ManagerRegistry $doctrine)
    {
        $categoriesByHumanResourcesRepository = new CategoryOfHumanResourceRepository($doctrine);
        $categoriesByHumanResources = $categoriesByHumanResourcesRepository->findAll();
        $categoriesByHumanResourcesArray = array();

        if ($categoriesByHumanResources != null) {
            foreach ($categoriesByHumanResources as $category) {
                $categoriesByHumanResourcesArray[] = array('id' => strval($category->getId()),
                    'humanresource_id' => $category->getHumanresource()->getId(),
                    'humanresourcecategory_id' => $category->getHumanresourcecategory()->getId()               
                );
            }
        }
        //Converting data into a JSON object
        $categoriesByHumanResourcesArrayJson = new JsonResponse($categoriesByHumanResourcesArray);
        return $categoriesByHumanResourcesArrayJson;    
    }

   

    /*
      * @brief Allows to create a new human resource in the database
     */


    public function new(Request $request, HumanResourceRepository $humanResourceRepository,ManagerRegistry $doctrine): Response
    {
        if ($request->getMethod() === 'POST') {
            $humanResource = new HumanResource();
            $param = $request->request->all();  
            //name          
            $name = $param['resourcename'];

            // Check if the firt letter of the name is an accent, and transform it
            // This way we can sort names accordingly (for example the Ándre next to Alex)
            // Note that it will be printed as Andre and not Ándre
            $name_splitted = mb_str_split($name);
            $accents = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'];

            $name_splitted[0] = strtr($name_splitted[0], $accents);
            $name = implode('', $name_splitted);

            //working hours
            $monday = array();
            $tuesday = array();
            $wednesday = array();
            $thursday = array();
            $friday = array();
            $saturday = array();
            $sunday = array();
            array_push($monday, $param['monday-begin'].':00', $param['monday-end'].':00');
            array_push($tuesday, $param['tuesday-begin'].':00', $param['tuesday-end'].':00');
            array_push($wednesday, $param['wednesday-begin'].':00', $param['wednesday-end'].':00');
            array_push($thursday, $param['thursday-begin'].':00', $param['thursday-end'].':00');
            array_push($friday, $param['friday-begin'].':00', $param['friday-end'].':00');
            array_push($saturday, $param['saturday-begin'].':00', $param['saturday-end'].':00');
            array_push($sunday, $param['sunday-begin'].':00', $param['sunday-end'].':00');
            $humanResource->setHumanresourcename($name);
            $humanResourceRepository = new HumanResourceRepository($doctrine);
            $humanResourceRepository->add($humanResource, true);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($doctrine);

            //We get all categories from the database
            $categoryOfHumanResourceRepository = new CategoryOfHumanResourceRepository($doctrine);
            $categories = $categoryOfHumanResourceRepository->findAll(); 

            //We get all working hours
            $workingHoursRepository = new WorkingHoursRepository($doctrine);
            
            //We get the number of category linked to the new human resource
            $nbCategory = $param['nbCategory'];
            //filling working hours
            for($j = 0; $j <= 6; $j++) {
                switch ($j) {
                    case 0:
                        if(($sunday[0] != ':00') && ($sunday[1] != ':00')){
                            $workingHoursSunday = new WorkingHours();
                            $sunday0 = DateTime::createFromFormat('H:i:s', $sunday[0]);
                            $sunday1 = DateTime::createFromFormat('H:i:s', $sunday[1]);
                            $workingHoursSunday->setStarttime($sunday0);
                            $workingHoursSunday->setEndtime($sunday1);
                            $workingHoursSunday->setHumanresource($humanResource);
                            $workingHoursSunday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursSunday, true);
                        }
                        break;
                    case 1:
                        if(($monday[0] != ':00') && ($monday[1] != ':00')){
                            $workingHoursMonday = new WorkingHours();
                            $monday0 = DateTime::createFromFormat('H:i:s', $monday[0]);
                            $monday1 = DateTime::createFromFormat('H:i:s', $monday[1]);
                            $workingHoursMonday->setStarttime($monday0);
                            $workingHoursMonday->setEndtime($monday1);
                            $workingHoursMonday->setHumanresource($humanResource);
                            $workingHoursMonday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursMonday, true);
                        }
                        break;
                    case 2:

                        if(($tuesday[0] != ':00') && ($tuesday[1] != ':00')){
                            $workingHoursTuesday = new WorkingHours();
                            $tuesday0 = DateTime::createFromFormat('H:i:s', $tuesday[0]);
                            $tuesday1 = DateTime::createFromFormat('H:i:s', $tuesday[1]);
                            $workingHoursTuesday->setStarttime($tuesday0);
                            $workingHoursTuesday->setEndtime($tuesday1);
                            $workingHoursTuesday->setHumanresource($humanResource);
                            $workingHoursTuesday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursTuesday, true);
                        }
                        break;
                    case 3:
                        if(($wednesday[0] != ':00') && ($wednesday[1] != ':00')){
                            $workingHoursWednesday = new WorkingHours();
                            $wednesday0 = DateTime::createFromFormat('H:i:s', $wednesday[0]);
                            $wednesday1 = DateTime::createFromFormat('H:i:s', $wednesday[1]);
                            $workingHoursWednesday->setStarttime($wednesday0);
                            $workingHoursWednesday->setEndtime($wednesday1);
                            $workingHoursWednesday->setHumanresource($humanResource);
                            $workingHoursWednesday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursWednesday, true);
                        }
                        break;
                    case 4:
                        if(($thursday[0] != ':00') && ($thursday[1] != ':00')){
                            $workingHoursThursday = new WorkingHours();
                            $thursday0 = DateTime::createFromFormat('H:i:s', $thursday[0]);
                            $thursday1 = DateTime::createFromFormat('H:i:s', $thursday[1]);
                            $workingHoursThursday->setStarttime($thursday0);
                            $workingHoursThursday->setEndtime($thursday1);
                            $workingHoursThursday->setHumanresource($humanResource);
                            $workingHoursThursday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursThursday, true);
                        }
                        break;
                    case 5:
                        if(($friday[0] != ':00') && ($friday[1] != ':00')){
                            $workingHoursFriday = new WorkingHours();
                            $friday0 = DateTime::createFromFormat('H:i:s', $friday[0]);
                            $friday1 = DateTime::createFromFormat('H:i:s', $friday[1]);
                            $workingHoursFriday->setStarttime($friday0);
                            $workingHoursFriday->setEndtime($friday1);
                            $workingHoursFriday->setHumanresource($humanResource);
                            $workingHoursFriday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursFriday, true);
                        }
                        break;
                    case 6:
                        if(($saturday[0] != ':00') && ($saturday[1] != ':00')){
                            $workingHoursSaturday = new WorkingHours();
                            $saturday0 = DateTime::createFromFormat('H:i:s', $saturday[0]);
                            $saturday1 = DateTime::createFromFormat('H:i:s', $saturday[1]);
                            $workingHoursSaturday->setStarttime($saturday0);
                            $workingHoursSaturday->setEndtime($saturday1);
                            $workingHoursSaturday->setHumanresource($humanResource);
                            $workingHoursSaturday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursSaturday, true);
                        }
                        break;
                }
            }
            //creating links between categories and the resource
            for($i = 0; $i < $nbCategory; $i++)
            {
                $linkCategRes = new CategoryOfHumanResource();      

                $linkCategRes->setHumanresource($humanResource);
                $linkCategRes->setHumanResourcecategory($humanResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                $categoryOfHumanResourceRepository->add($linkCategRes, true);
            }
            return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
        }
    }


    /*
      * @brief Allows to show data of a specific human resource in the database
     */


    public function show(HumanResource $humanResource): Response
    {
        return $this->render('human_resource/show.html.twig', [
            'human_resource' => $humanResource,
        ]);
    }

    /*
      * @brief Allows to edit a human resource that is already in the database
     */


    public function edit(Request $request,ManagerRegistry $doctrine) 
    { 
        if ($request->getMethod() === 'POST' ) {

            //We get all data and parameters of the request
            $param = $request->request->all();

            //We get the human resource object that we want to edit
            $humanResourceRepository = new HumanResourceRepository($doctrine);
            $humanResource = $humanResourceRepository->findById($param['id'])[0];
            $humanResource->setHumanResourceName($param['resourcename']);
            //working hours treatment
            $monday = array();
            $tuesday = array();
            $wednesday = array();
            $thursday = array();
            $friday = array();
            $saturday = array();
            $sunday = array();
            array_push($monday, $param['monday-begin-edit'].':00', $param['monday-end-edit'].':00');
            array_push($tuesday, $param['tuesday-begin-edit'].':00', $param['tuesday-end-edit'].':00');
            array_push($wednesday, $param['wednesday-begin-edit'].':00', $param['wednesday-end-edit'].':00');
            array_push($thursday, $param['thursday-begin-edit'].':00', $param['thursday-end-edit'].':00');
            array_push($friday, $param['friday-begin-edit'].':00', $param['friday-end-edit'].':00');
            array_push($saturday, $param['saturday-begin-edit'].':00', $param['saturday-end-edit'].':00');
            array_push($sunday, $param['sunday-begin-edit'].':00', $param['sunday-end-edit'].':00');
            //We add the human resource to the database
            $humanResourceRepository->add($humanResource, true);
            

            //We get all links of categories between resources
            $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($doctrine);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($doctrine);
            $categOfHumanResource = $categOfHumanResourceRepository->findAll();
            $humanResourcesCategories = $humanResourceCategoryRepository->findAll();

            //We get all working hours
            $workingHoursRepository = new WorkingHoursRepository($doctrine);

            // On supprime toutes les activités et leurs successor
            $em=$doctrine->getManager();
            $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResource]);
            for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
                $em->remove($categsOfResources[$indexCategOf]);
            }
            $em->flush();
        

            //We get the number of categories now linked to the edited human resource
            $nbCategories = $param['nbcategory'];
            //Editing working hours
            for($j = 0; $j <= 6; $j++) {
                switch ($j) {
                    case 0:
                        $workingHoursRepoSunday = $workingHoursRepository->findBy(
                            ['dayweek' => 0, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoSunday) == 0) {

                            if(($sunday[0] != ':00') && ($sunday[1] != ':00')){
                                $workingHoursSunday = new WorkingHours();
                                $sunday0 = DateTime::createFromFormat('H:i:s', $sunday[0]);
                                $sunday1 = DateTime::createFromFormat('H:i:s', $sunday[1]);
                                $workingHoursSunday->setStarttime($sunday0);
                                $workingHoursSunday->setEndtime($sunday1);
                                $workingHoursSunday->setHumanresource($humanResource);
                                $workingHoursSunday->setDayweek($j);
                                $workingHoursRepository->add($workingHoursSunday, true);
                            }

                        }
                        else {
                            if(($sunday[0] != ':00') && ($sunday[1] != ':00')){
                                $workingHoursUpdateSunday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoSunday[0]->getId());
                                $sunday0 = DateTime::createFromFormat('H:i:s', $sunday[0]);
                                $sunday1 = DateTime::createFromFormat('H:i:s', $sunday[1]);
                                $workingHoursUpdateSunday->setStarttime($sunday0);
                                $workingHoursUpdateSunday->setEndtime($sunday1);
                                $workingHoursUpdateSunday->setHumanresource($humanResource);
                                $workingHoursUpdateSunday->setDayweek($j);
                                $em->flush();       
                            }
                            else {
                                $em=$doctrine->getManager();
                                $em->remove($workingHoursRepoSunday[0]);
                                $em->flush();
                            }
                        }
                        break;
                    case 1:
                        $workingHoursRepoMonday = $workingHoursRepository->findBy(
                            ['dayweek' => 1, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoMonday) == 0) {

                            if(($monday[0] != ':00') && ($monday[1] != ':00')){
                                $workingHoursMonday = new WorkingHours();
                                $monday0 = DateTime::createFromFormat('H:i:s', $monday[0]);
                                $monday1 = DateTime::createFromFormat('H:i:s', $monday[1]);
                                $workingHoursMonday->setStarttime($monday0);
                                $workingHoursMonday->setEndtime($monday1);
                                $workingHoursMonday->setHumanresource($humanResource);
                                $workingHoursMonday->setDayweek($j);
                                $workingHoursRepository->add($workingHoursMonday, true);
                            }

                        }
                        else {
                            if(($monday[0] != ':00') && ($monday[1] != ':00')){
                                $workingHoursUpdateMonday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoMonday[0]->getId());
                                $monday0 = DateTime::createFromFormat('H:i:s', $monday[0]);
                                $monday1 = DateTime::createFromFormat('H:i:s', $monday[1]);
                                $workingHoursUpdateMonday->setStarttime($monday0);
                                $workingHoursUpdateMonday->setEndtime($monday1);
                                $workingHoursUpdateMonday->setHumanresource($humanResource);
                                $workingHoursUpdateMonday->setDayweek($j);
                                $em->flush();       
                            }
                            else {
                                $em=$doctrine->getManager();
                                $em->remove($workingHoursRepoMonday[0]);
                                $em->flush();
                            }
                        }
                        break;
                    case 2:
                        $workingHoursRepoTuesday = $workingHoursRepository->findBy(
                            ['dayweek' => 2, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoTuesday) == 0) {

                            if(($tuesday[0] != ':00') && ($tuesday[1] != ':00')){
                                $workingHoursTuesday = new WorkingHours();
                                $tuesday0 = DateTime::createFromFormat('H:i:s', $tuesday[0]);
                                $tuesday1 = DateTime::createFromFormat('H:i:s', $tuesday[1]);
                                $workingHoursTuesday->setStarttime($tuesday0);
                                $workingHoursTuesday->setEndtime($tuesday1);
                                $workingHoursTuesday->setHumanresource($humanResource);
                                $workingHoursTuesday->setDayweek($j);
                                $workingHoursRepository->add($workingHoursTuesday, true);
                            }
                        }
                        else {
                            if(($tuesday[0] != ':00') && ($tuesday[1] != ':00')){
                            $workingHoursUpdateTuesday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoTuesday[0]->getId());
                            $tuesday0 = DateTime::createFromFormat('H:i:s', $tuesday[0]);
                            $tuesday1 = DateTime::createFromFormat('H:i:s', $tuesday[1]);
                            $workingHoursUpdateTuesday->setStarttime($tuesday0);
                            $workingHoursUpdateTuesday->setEndtime($tuesday1);
                            $workingHoursUpdateTuesday->setHumanresource($humanResource);
                            $workingHoursUpdateTuesday->setDayweek($j);
                            $em->flush();       
                        }
                        else {
                            $em=$doctrine->getManager();
                            $em->remove($workingHoursRepoTuesday[0]);
                            $em->flush();
                        }
                    }
                    break;
                    case 3:
                        $workingHoursRepoWednesday = $workingHoursRepository->findBy(
                            ['dayweek' => 3, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoWednesday) == 0) {
                            if(($wednesday[0] != ':00') && ($wednesday[1] != ':00')){
                                $workingHoursWednesday = new WorkingHours();
                                $wednesday0 = DateTime::createFromFormat('H:i:s', $wednesday[0]);
                                $wednesday1 = DateTime::createFromFormat('H:i:s', $wednesday[1]);
                                $workingHoursWednesday->setStarttime($wednesday0);
                                $workingHoursWednesday->setEndtime($wednesday1);
                                $workingHoursWednesday->setHumanresource($humanResource);
                                $workingHoursWednesday->setDayweek($j);
                                $workingHoursRepository->add($workingHoursWednesday, true);
                            }

                        }
                        else {
                            if(($wednesday[0] != ':00') && ($wednesday[1] != ':00')){
                                $workingHoursUpdateWednesday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoWednesday[0]->getId());
                                $wednesday0 = DateTime::createFromFormat('H:i:s', $wednesday[0]);
                                $wednesday1 = DateTime::createFromFormat('H:i:s', $wednesday[1]);
                                $workingHoursUpdateWednesday->setStarttime($wednesday0);
                                $workingHoursUpdateWednesday->setEndtime($wednesday1);
                                $workingHoursUpdateWednesday->setHumanresource($humanResource);
                                $workingHoursUpdateWednesday->setDayweek($j);
                                $em->flush();       
                            }
                            else {
                                $em=$doctrine->getManager();
                                $em->remove($workingHoursRepoWednesday[0]);
                                $em->flush();
                            }
                        }
                        break;
                        case 4:
                        $workingHoursRepoThursday = $workingHoursRepository->findBy(
                            ['dayweek' => 4, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoThursday) == 0) {
                        if(($thursday[0] != ':00') && ($thursday[1] != ':00')){
                            $workingHoursThursday = new WorkingHours();
                            $thursday0 = DateTime::createFromFormat('H:i:s', $thursday[0]);
                            $thursday1 = DateTime::createFromFormat('H:i:s', $thursday[1]);
                            $workingHoursThursday->setStarttime($thursday0);
                            $workingHoursThursday->setEndtime($thursday1);
                            $workingHoursThursday->setHumanresource($humanResource);
                            $workingHoursThursday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursThursday, true);
                        }

                    }
                    else {
                        if(($thursday[0] != ':00') && ($thursday[1] != ':00')){
                        $workingHoursUpdateThursday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoThursday[0]->getId());
                        $thursday0 = DateTime::createFromFormat('H:i:s', $thursday[0]);
                        $thursday1 = DateTime::createFromFormat('H:i:s', $thursday[1]);
                        $workingHoursUpdateThursday->setStarttime($thursday0);
                        $workingHoursUpdateThursday->setEndtime($thursday1);
                        $workingHoursUpdateThursday->setHumanresource($humanResource);
                        $workingHoursUpdateThursday->setDayweek($j);
                        $em->flush();       
                    }
                    else {
                        $em=$doctrine->getManager();
                        $em->remove($workingHoursRepoThursday[0]);
                        $em->flush();
                    }
                    }
                        break;
                    case 5:
                        $workingHoursRepoFriday = $workingHoursRepository->findBy(
                            ['dayweek' => 5, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoFriday) == 0) {
                        if(($friday[0] != ':00') && ($friday[1] != ':00')){
                            $workingHoursFriday = new WorkingHours();
                            $friday0 = DateTime::createFromFormat('H:i:s', $friday[0]);
                            $friday1 = DateTime::createFromFormat('H:i:s', $friday[1]);
                            $workingHoursFriday->setStarttime($friday0);
                            $workingHoursFriday->setEndtime($friday1);
                            $workingHoursFriday->setHumanresource($humanResource);
                            $workingHoursFriday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursFriday, true);
                        }

                    }
                    else {
                        if(($friday[0] != ':00') && ($friday[1] != ':00')){
                        $workingHoursUpdateFriday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoFriday[0]->getId());
                        $friday0 = DateTime::createFromFormat('H:i:s', $friday[0]);
                        $friday1 = DateTime::createFromFormat('H:i:s', $friday[1]);
                        $workingHoursUpdateFriday->setStarttime($friday0);
                        $workingHoursUpdateFriday->setEndtime($friday1);
                        $workingHoursUpdateFriday->setHumanresource($humanResource);
                        $workingHoursUpdateFriday->setDayweek($j);
                        $em->flush();       
                    }
                    else {
                        $em=$doctrine->getManager();
                        $em->remove($workingHoursRepoFriday[0]);
                        $em->flush();
                    }
                    }
                        break;
                    case 6:
                        $workingHoursRepoSaturday = $workingHoursRepository->findBy(
                            ['dayweek' => 6, 
                            'humanresource' => $humanResource]
                        );
                        if(sizeof($workingHoursRepoSaturday) == 0) {
                        if(($saturday[0] != ':00') && ($saturday[1] != ':00')){
                            $workingHoursSaturday = new WorkingHours();
                            $saturday0 = DateTime::createFromFormat('H:i:s', $saturday[0]);
                            $saturday1 = DateTime::createFromFormat('H:i:s', $saturday[1]);
                            $workingHoursSaturday->setStarttime($saturday0);
                            $workingHoursSaturday->setEndtime($saturday1);
                            $workingHoursSaturday->setHumanresource($humanResource);
                            $workingHoursSaturday->setDayweek($j);
                            $workingHoursRepository->add($workingHoursSaturday, true);
                        }

                    }
                    else {
                        if(($saturday[0] != ':00') && ($saturday[1] != ':00')){
                        $workingHoursUpdateSaturday = $em->getRepository(WorkingHours::class)->find($workingHoursRepoSaturday[0]->getId());
                        $saturday0 = DateTime::createFromFormat('H:i:s', $saturday[0]);
                        $saturday1 = DateTime::createFromFormat('H:i:s', $saturday[1]);
                        $workingHoursUpdateSaturday->setStarttime($saturday0);
                        $workingHoursUpdateSaturday->setEndtime($saturday1);
                        $workingHoursUpdateSaturday->setHumanresource($humanResource);
                        $workingHoursUpdateSaturday->setDayweek($j);
                        $em->flush();       
                    }
                    else {
                        $em=$doctrine->getManager();
                        $em->remove($workingHoursRepoSaturday[0]);
                        $em->flush();
                    }
                    }
                        break;
                   
                }
            }
                

            if ($nbCategories != 0) {

                //Adding links between resource and their categories
                for($i = 0; $i < $nbCategories; $i++)
                {
                    $categOf = new CategoryOfHumanResource();
                    $categOf->setHumanresource($humanResource);
                    $categOf->setHumanresourcecategory($humanResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                    $categOfHumanResourceRepository->add($categOf, true);
                }
            }
            
            return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
            
        }
    }

    /*
      * @brief Allows to delete a human resource that is already in the database
     */

    /**
     * @Route("/{id}", name="app_human_resource_delete", methods={"POST"})
     */
    public function delete(HumanResource $humanResource, HumanResourceRepository $humanResourceRepository,ManagerRegistry $doctrine): Response
    {
        $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($doctrine);
        $workingHoursRepository = new WorkingHoursRepository($doctrine);
        $unavailabilitiesHumanRepository = new UnavailabilityHumanResourceRepository($doctrine);
        $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
        $scheduledHumanResourcesRepository = new HumanResourceScheduledRepository($doctrine);

        //Deleting links between categories and the deleted resource
        $em=$doctrine->getManager();
        $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResource]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }
        
        //Deleting the working hours of the deleted resource
        $workingHours = $workingHoursRepository->findBy(['humanresource' => $humanResource]);
        for($indexWorkingHour = 0; $indexWorkingHour < count($workingHours); $indexWorkingHour++) {
            $em->remove($workingHours[$indexWorkingHour]);
        }

        //Deleting the unavailabilities of the deleted resource
        $unavailabilitiesHuman = $unavailabilitiesHumanRepository->findBy(['humanresource' => $humanResource]);
        for ($indexUnavailabilityHuman = 0; $indexUnavailabilityHuman < count($unavailabilitiesHuman); $indexUnavailabilityHuman++){
            $unavailabilityToDelete = $unavailabilitiesRepository->findBy(['id' => $unavailabilitiesHuman[$indexUnavailabilityHuman]->getUnavailability()->getId()]);
            $unavailabilitiesRepository->remove($unavailabilityToDelete[0], true);
            $em->remove($unavailabilitiesHuman[$indexUnavailabilityHuman]);
        }
        
        //Deleting the scheduled things of the deleted resource
        $scheduledHumanResources = $scheduledHumanResourcesRepository->findBy(['humanresource' => $humanResource]);
        for ($indexScheduledHumanResource = 0; $indexScheduledHumanResource < count($scheduledHumanResources); $indexScheduledHumanResource++){
            $em->remove($scheduledHumanResources[$indexScheduledHumanResource]);
        }    

        $em->flush();
        //Deleting the basic object human resource
        $humanResourceRepository->remove($humanResource, true);
        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);

    }
    

    /*
      * @brief Allows to get data of human resources
     */
    public function getDataHumanResource(ManagerRegistry $doctrine)
    {
        if(isset($_POST["idHumanResource"])){
            if(isset($_POST["date"])){
                $categories = $this->getCategoryByHumanResourceId($_POST["idHumanResource"], $doctrine);
                $workingHours = $this->getWorkingHoursByHumanResourceId($_POST["idHumanResource"], $doctrine);
                $unavailability= $this->getUnavailabilityByHumanResourceId($_POST["idHumanResource"], $doctrine);
                $activities = $this->getActivities($_POST["idHumanResource"],$_POST["date"],$doctrine);
                $data = array(
                    "categories" => $categories,
                    "workingHours" => $workingHours,
                    "unavailability" => $unavailability,
                    "activities" => $activities
                );
                return new JsonResponse($data);
            }
        }
        if(isset($_POST["idHumanResourceCategory"])){
            $resources = $this->getResourceByHumanResourceCategoryId($_POST["idHumanResourceCategory"], $doctrine);
            return new JsonResponse($resources);
        }
    }

    /*
      * @brief Allows to get all categories of a specified resource
     */
    public function getCategoryByHumanResourceId($id, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getManager()->getRepository("App\Entity\CategoryOfHumanResource")->findAll();
        $categoryArray=[];
        foreach ($categories as $category) {
            if ($category->getHumanresource()->getId() == $id){
                $categoryArray[] = [
                    'humanresourcecategory' => $category->getHumanresourcecategory()->getCategoryname(),
                ];
            }
        }
        return $categoryArray;
    }
    /*
      * @brief Allows to get all resources of a specified category
     */
    public function getResourceByHumanResourceCategoryId($id, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getManager()->getRepository("App\Entity\CategoryOfHumanResource")->findHumanResourceByCategory($id);
        $resourceArray=[];
        foreach ($categories as $category) {
            $resourceArray[] = [
                'humanresource' => $category['humanresourcename']
            ];
        }
        return $resourceArray;
    }

    public function getActivitiesByHumanResourceCategoryId(ManagerRegistry $doctrine)
    {
        if(isset($_POST['idHumanResourceCategory'])){
            $id = $_POST['idHumanResourceCategory'];
            $activities = $doctrine->getManager()->getRepository("App\Entity\ActivityHumanResource")->findActivitiesByHumanResourceCategory($id);
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
      * @brief Allows to create an unavailability linked to a human resource that is already in the database
     */
    public function unavailability(Request $request,ManagerRegistry $doctrine) {

        $param = $request->request->all(); 
        $startTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-begin-unavailability'].":00"));
        $endTime = DateTime::createFromFormat('Y-m-d H:i:s', str_replace('T',' ',$param['datetime-end-unavailability'].":00"));
        $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
        $unavailabilities = $unavailabilitiesRepository->findAll();
        $unavailabilitiesHumanRepository = new UnavailabilityHumanResourceRepository($doctrine);
        $unavailabilitiesHuman = $unavailabilitiesHumanRepository->findAll();
        $humanResourcesRepository = new HumanResourceRepository($doctrine);
        $humanResource = $humanResourcesRepository->findBy(['id' => $param['id-human-resource-unavailability']]);

        
        $unavailability = new Unavailability();
        $unavailabilityHumanResource = new UnavailabilityHumanResource();
        $unavailability->setStartdatetime($startTime);
        $unavailability->setEnddatetime($endTime);
        $unavailabilitiesRepository->add($unavailability, true);
        $unavailabilityHumanResource->setHumanresource($humanResource[0]);
        $unavailabilityHumanResource->setUnavailability($unavailability);
        $unavailabilitiesHumanRepository->add($unavailabilityHumanResource, true);
        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }

    /*
      * Allows to delete an unavailability linked to a human resource that is already in the database
     */
    public function deleteUnavailability(Request $request, ManagerRegistry $doctrine)
    {
        if (isset($_POST['idHumanAvailability'])) {
            $idHumanAvailability = $_POST['idHumanAvailability'];

            if (isset($_POST['idUnavailability'])) {
                $idUnavailability = $_POST['idUnavailability'];

                $unavailabilitiesRepository = new UnavailabilityRepository($doctrine);
                $unavailabilitiesHumanRepository = new UnavailabilityHumanResourceRepository($doctrine);
                $unavailabilityToDelete = $unavailabilitiesRepository->findBy(['id' => $idUnavailability]);
                $unavailabilityHumanToDelete = $unavailabilitiesHumanRepository->findBy(['id' => $idHumanAvailability]);
        
                $em=$doctrine->getManager();
                $unavailabilitiesRepository->remove($unavailabilityToDelete[0], true);
                $unavailabilitiesHumanRepository->remove($unavailabilityHumanToDelete[0], true);
                $em->flush();

            }
        }
        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);

        
    }


    /*
      * @brief Allows to get working hours of a specified human resource
     */
    public function getWorkingHoursByHumanResourceId($id, ManagerRegistry $doctrine){
        $workingHours = $doctrine->getManager()->getRepository("App\Entity\WorkingHours")->findBy(['humanresource' => $id]);
        $workingHoursArray=[];
        foreach ($workingHours as $workingHour) {
                $workingHoursArray[] = [
                    'starttime' => $workingHour->getStarttime()->format('H:i:s'),
                    'endtime' => $workingHour->getEndtime()->format('H:i:s'),
                    'dayweek' => $workingHour->getDayweek(),
                ];
        }
        return $workingHoursArray;
    }

    /*
      * @brief Allows to get unavailabilities of a specified human resources
     */
    public function getUnavailabilityByHumanResourceId($id, ManagerRegistry $doctrine){
        $unavailability = $doctrine->getManager()->getRepository("App\Entity\UnavailabilityHumanResource")->findBy(['humanresource' => $id]);
        $unavailabilityArray=[];
        foreach ($unavailability as $unavail) {
                $unavailabilityArray[] = [
                    'starttime' => str_replace(" ","T",$unavail->getUnavailability()->getStartdatetime()->format('Y-m-d H:i:s')),
                    'endtime' =>  str_replace(" ","T",$unavail->getUnavailability()->getEnddatetime()->format('Y-m-d H:i:s')),
                ];
        }
        return $unavailabilityArray;
    }

    /*
      * @brief Allows to create an unavailability linked to a human resource that is already in the database
     */
    public function getActivities($id, $dateStr,ManagerRegistry $doctrine){
        $activities = $doctrine->getManager()->getRepository("App\Entity\HumanResourceScheduled")->findBy(['humanresource' => $id]);
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
                $activitiesArray[] = [
                    'dayappointment' => $activity->getScheduledActivity()->getAppointment()->getDayappointment()->format('Y-m-d'),
                    'activity' => $activity->getScheduledActivity()->getActivity()->getActivityname(),
                    'pathway' => $activity->getScheduledActivity()->getActivity()->getPathway()->getPathwayname(),
                    'starttime' => $activity->getScheduledActivity()->getStarttime()->format('H:i:s'),
                    'endtime' => $activity->getScheduledActivity()->getEndtime()->format('H:i:s'),
                    'patient' => $activity->getScheduledActivity()->getAppointment()->getPatient()->getLastname()." ".$activity->getScheduledActivity()->getAppointment()->getPatient()->getFirstname(),
                ];
            }
        }
        return $activitiesArray;
    }

    /*
      * @brief Allows to autocomplete human resources researches
     */
    public function autocompleteHR(Request $request, HumanResourceRepository $HRRepository, CategoryOfHumanResourceRepository $categoryOfHRRepository,HumanResourceCategoryRepository $humanResourceCategoryRepository){
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
        $results = array();
        $HRs = $HRRepository->findBy(array(), array('humanresourcename' => 'ASC'));
        foreach ($HRs as $HR) {
            $name = strtr(mb_strtolower($HR->getHumanresourcename(),'UTF-8'), $utf8);
            if (strpos($name, $term) !== false ){
                $categories=$categoryOfHRRepository->findBy(['humanresource' => $HR->getId()]);
                $categoriesArray=[];
                foreach($categories as $category){
                    $categoriesArray[] = [
                        'category' => $category->getHumanresourcecategory()->getCategoryname(),
                    ];
                }
                $results[] = [
                    'id' => $HR->getId(),
                    'value' => $HR->getHumanresourcename(),
                    'categories' => $categoriesArray,

                ];
            }
        }
        
        return new JsonResponse($results);
    }

    public function showCategory(HumanResourceRepository $humanResourceRepository,ManagerRegistry $doctrine,Request $request, PaginatorInterface $paginator){
        return $this->index($humanResourceRepository,$doctrine,$request, $paginator,"categories");
    }


    public function GetAppointmentFromHumanResourceId(ManagerRegistry $doctrine, int $id) {
        $HRSRepository= $doctrine->getManager()->getRepository("App\Entity\HumanResourceScheduled");
        $appointments = $HRSRepository->findAppointmentsByHumanResource($id, date('Y-m-d'));

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