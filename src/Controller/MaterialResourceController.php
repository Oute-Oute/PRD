<?php

namespace App\Controller;

use App\Entity\MaterialResource;
use App\Entity\CategoryOfMaterialResource;
use App\Repository\MaterialResourceRepository;
use App\Repository\MaterialResourceCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(MaterialResourceRepository $materialResourceRepository): Response
    {
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($this->getDoctrine());
        $materialResourceCategories = $materialResourceCategoryRepository->findAll();
        $materialResources = $materialResourceRepository->findAll();
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $nbMaterialResource = count($materialResources);
        $nbCategBy = count($categOfMaterialResource);
        $categoriesByResources = array();

        for($indexResource = 0; $indexResource < $nbMaterialResource; $indexResource++) {
            if ($materialResources[$indexResource]->isAvailable()) {
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
        }
        //dd($categoriesByResources);
        return $this->render('material_resource/index.html.twig', [
            'material_resources' => $materialResourceRepository->findBy(['available' => true]),
            'material_resources_categories' => $materialResourceCategories,
            'categoriesByResources' => $categoriesByResources
        ]); 
    }

    /**
     * @Route("/new", name="app_material_resource_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MaterialResourceRepository $materialResourceRepository): Response
    {
        if ($request->getMethod() === 'POST') {
            $materialResource = new MaterialResource();
            $param = $request->request->all();
            $name = $param['categoryname'];
            $materialResource->setAvailable(true);
            $materialResource->setMaterialresourcename($name);
            $materialResourceRepository = new MaterialResourceRepository($this->getDoctrine());
            $materialResourceRepository->add($materialResource, true);
            $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());


            // On récupère toutes les catégories
            $categoryOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($this->getDoctrine());
            $categories = $categoryOfMaterialResourceRepository->findAll();            

            // On récupère le nombre de catégories
            $nbCategory = $param['nbCategory'];


            //$activityArray = array();


            for($i = 0; $i < $nbCategory; $i++)
            {
                $linkCategRes = new CategoryOfMaterialResource();      

                $linkCategRes->setMaterialresource($materialResource);
                $linkCategRes->setMaterialResourcecategory($materialResourceCategoryRepository->findById($param['select-'.$i])[0]);
                $categoryOfMaterialResourceRepository->add($linkCategRes, true);
            }
            return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
        }
    
}

    /**
     * @Route("/{id}/edit", name="app_material_resource_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request)
    {
       // Méthode POST pour ajouter un circuit
       if ($request->getMethod() === 'POST' ) {
            
        // On recupere toutes les données de la requete
        $param = $request->request->all();
        // On récupère l'objet parcours que l'on souhaite modifier grace a son id
        $materialResourceRepository = new MaterialResourceRepository($this->getDoctrine());
        $materialResource = $materialResourceRepository->findById($param['id'])[0];
        $materialResource->setMaterialResourceName($param['resourcename']);
        //$pathway->setAvailable(true);

        // On ajoute le parcours a la bd
        $materialResourceRepository->add($materialResource, true);

        // On s'occupe ensuite ds liens entre le parcours et les activités :

        // On récupère toutes les activités
        $categOfMaterialResourceRepository = new CategoryOfMaterialResourceRepository($this->getDoctrine());
        $materialResourceCategoryRepository = new MaterialResourceCategoryRepository($this->getDoctrine());
        $categOfMaterialResource = $categOfMaterialResourceRepository->findAll();
        $materialResourcesCategories = $materialResourceCategoryRepository->findAll();

        // On supprime toutes les activités et leurs successor
        $em=$this->getDoctrine()->getManager();
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
    /**
     * @Route("/{id}", name="app_material_resource_delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, MaterialResource $materialResource, CategoryOfMaterialResourceRepository $categoryOfMaterialResourceRepository, MaterialResourceScheduledRepository $materialResourceScheduledRepository, UnavailabilityMaterialResourceRepository $unavailabilityMaterialResourceRepository, MaterialResourceRepository $materialResourceRepository): Response
    {
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

            $listUnavailabilityMaterialResource = $unavailabilityMaterialResourceRepository->findBy(['materialresource' => $materialResource]);

            foreach($listUnavailabilityMaterialResource as $unavailabilityMaterialResource)
            {
                $unavailability = $unavailabilityMaterialResource->getUnavailability();
                $unavailabilityMaterialResourceRepository->remove($unavailabilityMaterialResource, true);

                $entityManager->persist($unavailability);
                $entityManager->flush();
            }

            $materialResourceRepository->remove($materialResource, true);
        }

        return $this->redirectToRoute('index_material_resources', [], Response::HTTP_SEE_OTHER);
    }
}
