<?php

namespace App\Controller;

use App\Entity\HumanResource;
use App\Entity\CategoryOfHumanResource;
use App\Entity\WorkingHours;
use App\Form\HumanResourceType;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\CategoryOfHumanResourceRepository;
use App\Repository\WorkingHoursRepository;
use DateTimeInterface;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/human/resource")
 */
class HumanResourceController extends AbstractController
{
    /**
     * @Route("/", name="app_human_resource_index", methods={"GET"})
     */
    public function index(HumanResourceRepository $humanResourceRepository): Response
    {
        $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());
        $humanResourceCategories = $humanResourceCategoryRepository->findAll();

        $workingHours = $this->listWorkingHoursJSON();
        $categoriesByHumanResources = $this->listCategoriesByHumanResourcesJSON();
        return $this->render('human_resource/index.html.twig', [
            'human_resources' => $humanResourceRepository->findAll(),
            'human_resources_categories' => $humanResourceCategories,
            'workingHours' => $workingHours,
            'categoriesByHumanResources' => $categoriesByHumanResources
        ]); 
    }

    /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource humaine
     */
    public function listWorkingHoursJSON()
    {
        $workingHoursRepository = new WorkingHoursRepository($this->getDoctrine());
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
        //Conversion des données ressources en json
        $workingHoursArrayJson = new JsonResponse($workingHoursArray);
        return $workingHoursArrayJson;    
    }


        /**
     * Permet de créer un objet json a partir d'une liste de categorie de ressource humaine
     */
    public function listCategoriesByHumanResourcesJSON()
    {
        $categoriesByHumanResourcesRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
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
        //Conversion des données ressources en json
        $categoriesByHumanResourcesArrayJson = new JsonResponse($categoriesByHumanResourcesArray);
        return $categoriesByHumanResourcesArrayJson;    
    }

   

    

    /**
     * @Route("/new", name="app_human_resource_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HumanResourceRepository $humanResourceRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $humanResource = new HumanResource();
            $param = $request->request->all();            
            $name = $param['resourcename'];
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
            $humanResourceRepository = new HumanResourceRepository($this->getDoctrine());
            $humanResourceRepository->add($humanResource, true);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());

            // On récupère toutes les catégories
            $categoryOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
            $categories = $categoryOfHumanResourceRepository->findAll(); 

            // On récupère les working hours
            $workingHoursRepository = new WorkingHoursRepository($this->getDoctrine());
            
            // On récupère le nombre de catégories
            $nbCategory = $param['nbCategory'];
            //$activityArray = array();
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

    /**
     * @Route("/{id}", name="app_human_resource_show", methods={"GET"})
     */
    public function show(HumanResource $humanResource): Response
    {
        return $this->render('human_resource/show.html.twig', [
            'human_resource' => $humanResource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_human_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request) 
    { 
        // Méthode POST pour ajouter un circuit
        if ($request->getMethod() === 'POST' ) {

            // On recupere toutes les données de la requete
            $param = $request->request->all();

            // On récupère l'objet parcours que l'on souhaite modifier grace a son id
            $humanResourceRepository = new HumanResourceRepository($this->getDoctrine());
            $humanResource = $humanResourceRepository->findById($param['id'])[0];
            $humanResource->setHumanResourceName($param['resourcename']);
            //$pathway->setAvailable(true);
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
            // On ajoute le parcours a la bd
            $humanResourceRepository->add($humanResource, true);
            
            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $categOfHumanResource = $categOfHumanResourceRepository->findAll();
            $humanResourcesCategories = $humanResourceCategoryRepository->findAll();

            // On récupère les working hours
            $workingHoursRepository = new WorkingHoursRepository($this->getDoctrine());

            // On supprime toutes les activités et leurs successor
            $em=$this->getDoctrine()->getManager();
            $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResource]);
            for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
                $em->remove($categsOfResources[$indexCategOf]);
            }
            $em->flush();
        

            // On récupère le nombre de catégories
            $nbCategories = $param['nbcategory'];
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
                                $em=$this->getDoctrine()->getManager();
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
                                $em=$this->getDoctrine()->getManager();
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
                            $em=$this->getDoctrine()->getManager();
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
                                $em=$this->getDoctrine()->getManager();
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
                        $em=$this->getDoctrine()->getManager();
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
                        $em=$this->getDoctrine()->getManager();
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
                        $em=$this->getDoctrine()->getManager();
                        $em->remove($workingHoursRepoSaturday[0]);
                        $em->flush();
                    }
                    }
                        break;
                   
                }
            }
                

            //$activityArray = array();
            if ($nbCategories != 0) {
                /* $categOf_old = new CategoryOfHumanResource();      
                
                $categOf_old->setHumanresource($param["name-activity-0"]);
                $categOf_old->setHumanresourcecategory($param[ "duration-activity-0"]);

                $categOfHumanResourceRepository->add($categOf_old, true); */

                for($i = 0; $i < $nbCategories; $i++)
                {
                    $categOf = new CategoryOfHumanResource();
                    $categOf->setHumanresource($humanResource);
                    $categOf->setHumanresourcecategory($humanResourceCategoryRepository->findById($param['id-category-'.$i])[0]);
                    //dd($categOf);
                    $categOfHumanResourceRepository->add($categOf, true);
                    //dd($categOfHumanResourceRepository->findAll());
                   //}

                  //  $categOf_old = $categOfHumanResourceRepository->findById($humanResource->getId())[0];

                }
            }
            
            return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
            
        }
    }

    
    



    /**
     * @Route("/{id}", name="app_human_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, HumanResource $humanResource, HumanResourceRepository $humanResourceRepository): Response
    {
        $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
        $workingHoursRepository = new WorkingHoursRepository($this->getDoctrine());
        $em=$this->getDoctrine()->getManager();
        $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResource]);
        for ($indexCategOf = 0; $indexCategOf < count($categsOfResources); $indexCategOf++) {
            $em->remove($categsOfResources[$indexCategOf]);
        }
        $workingHours = $workingHoursRepository->findBy(['humanresource' => $humanResource]);
        for($indexWorkingHour = 0; $indexWorkingHour < count($workingHours); $indexWorkingHour++) {
            $em->remove($workingHours[$indexWorkingHour]);
        }
        $em->flush();
        $humanResourceRepository->remove($humanResource, true);
        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }

    public function getDataHumanResource(ManagerRegistry $doctrine)
    {
        if(isset($_POST["idHumanResource"])){
            $categories = $this->getCategoryByHumanResourceId($_POST["idHumanResource"], $doctrine);
            return new JsonResponse($categories);
        }
        if(isset($_POST["idHumanResourceCategory"])){
            $resources = $this->getResourceByHumanResourceCategoryId($_POST["idHumanResourceCategory"], $doctrine);
            return new JsonResponse($resources);
        }
    }

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
    public function getResourceByHumanResourceCategoryId($id, ManagerRegistry $doctrine)
    {
        $categories = $doctrine->getManager()->getRepository("App\Entity\CategoryOfHumanResource")->findAll();
        $resourceArray=[];
        foreach ($categories as $category) {
            if ($category->getHumanresourceCategory()->getId() == $id){
                $resourceArray[] = [
                    'humanresource' => $category->getHumanresource()->getHumanresourcename(),
                ];
            }
        }
        return $resourceArray;
    }
}
