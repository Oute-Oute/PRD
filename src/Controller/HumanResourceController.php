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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
        $humanResourceCategories = $humanResourceCategoryRepository->findAll();
        $humanResources = $humanResourceRepository->findAll();
        $categOfHumanResource = $categOfHumanResourceRepository->findAll();
        $nbHumanResource = count($humanResources);
        $nbCategBy = count($categOfHumanResource);
        $categoriesByResources = array();

        for($indexResource = 0; $indexResource < $nbHumanResource; $indexResource++) {
            if ($humanResources[$indexResource]->isAvailable()) {
                $listCategOf = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResources[$indexResource]]);
            
                $categoriesByResource = array();
                for($indexCategOf = 0; $indexCategOf < count($listCategOf); $indexCategOf++) {
                    //dd($humanResourceCategories[$indexCategOf]->getCategoryname());
                    //dd( $humanResourceCategoryRepository->findBy(['id' => $humanResourceCategories[$indexCategOf]]));
                    //array_push($categoriesByResource, $humanResourceCategoryRepository->findBy(['id' => $humanResourceCategories[$indexCategOf]])[0]);
                    //dd($listCategOf[$indexCategOf]);
    
                    $humanResourceCategoriesBy =  $humanResourceCategoryRepository->findBy(['id' => $listCategOf[$indexCategOf]->getHumanresourcecategory()->getId()]);
                    //dd($materialResourceCategories);
                    if($humanResourceCategoriesBy != null){
                        array_push($categoriesByResource,$humanResourceCategoriesBy[0]);
                    }
                    //array_push($categoriesByResource, $humanResourceCategoryRepository->findBy(['id' => $listCategOf[$indexCategOf]->getHumanresourcecategory()->getId()])[0]);
                    
                }
                array_push($categoriesByResources, $categoriesByResource);
            }
        }
        //dd($categoriesByResources);
        return $this->render('human_resource/index.html.twig', [
            'human_resources' => $humanResourceRepository->findBy(['available' => true]),
            'human_resources_categories' => $humanResourceCategories,
            'categoriesByResources' => $categoriesByResources
        ]); 
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
            $humanResource->setAvailable(true);
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

            // On ajoute le parcours a la bd
            $humanResourceRepository->add($humanResource, true);

            // On s'occupe ensuite ds liens entre le parcours et les activités :

            // On récupère toutes les activités
            $categOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());
            $categOfHumanResource = $categOfHumanResourceRepository->findAll();
            $humanResourcesCategories = $humanResourceCategoryRepository->findAll();

            // On supprime toutes les activités et leurs successor
            $em=$this->getDoctrine()->getManager();
            $categsOfResources = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResource]);
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



    /**
     * @Route("/{id}", name="app_human_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, HumanResource $humanResource, HumanResourceRepository $humanResourceRepository): Response
    {
            if($humanResource->isAvailable() == true) {
                $humanResource->setAvailable(false);
            }
            else {
                $humanResource->setAvailable(true);
            }

        $humanResourceRepository->add($humanResource, true);
        return $this->redirectToRoute('index_human_resources', [], Response::HTTP_SEE_OTHER);
    }
}
