<?php

namespace App\Controller;

use App\Entity\HumanResource;
use App\Entity\CategoryOfHumanResource;
use App\Form\HumanResourceType;
use App\Repository\HumanResourceCategoryRepository;
use App\Repository\HumanResourceRepository;
use App\Repository\CategoryOfHumanResourceRepository;
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
        $humanResourceCategory = $humanResourceCategoryRepository->findAll();

        $nbHumanResource = count($humanResources);
        $nbCategBy = count($categOfHumanResource);
        $categoriesByResources = array();

        for($indexResource = 0; $indexResource < $nbHumanResource; $indexResource++) {
            $listCategOf = $categOfHumanResourceRepository->findBy(['humanresource' => $humanResources[$indexResource]]);
            $categoriesByResource = array();
            for($indexCategOf = 0; $indexCategOf < count($listCategOf); $indexCategOf++) {
                array_push($categoriesByResource, $humanResourceCategoryRepository->findBy(['id' => $humanResourceCategories[$indexCategOf]])[0]);
            }
            array_push($categoriesByResources, $categoriesByResource);
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
            $name = $param['categoryname'];
            $humanResource->setAvailable(true);
            $humanResource->setHumanresourcename($name);
            $humanResourceRepository = new HumanResourceRepository($this->getDoctrine());
            $humanResourceRepository->add($humanResource, true);
            $humanResourceCategoryRepository = new HumanResourceCategoryRepository($this->getDoctrine());


            // On récupère toutes les catégories
            $categoryOfHumanResourceRepository = new CategoryOfHumanResourceRepository($this->getDoctrine());
            $categories = $categoryOfHumanResourceRepository->findAll();            

            // On récupère le nombre de catégories
            $nbCategory = $param['nbCategory'];


            //$activityArray = array();


            for($i = 0; $i < $nbCategory; $i++)
            {
                $linkCategRes = new CategoryOfHumanResource();      

                $linkCategRes->setHumanresource($humanResource);
                $linkCategRes->setHumanResourcecategory($humanResourceCategoryRepository->findById($param['select-'.$i])[0]);
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
            //dd($param);
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
                    $categOf->setHumanresourcecategory($humanResourceCategoryRepository->findById($param['select-'.$i])[0]);
                    $categOfHumanResourceRepository->add($categOf, true);
                   //}

                  //  $categOf_old = $categOfHumanResourceRepository->findById($humanResource->getId())[0];

                }
            }
            
            return $this->redirectToRoute('Pathways', [], Response::HTTP_SEE_OTHER);
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
