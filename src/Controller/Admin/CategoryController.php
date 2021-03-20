<?php
namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Trip;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController{

    /**
     * @Route("/admin/categorylist", name="categoryList" )
     */
    public function category(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('Admin/category.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/insert/category", name="categoryInsert)
     */
    public function insertCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    )
    {
        //new category
        $category = new Category();
        //new form
        $categoryForm = $this->createForm(category::class, $category);
        //post request from form
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie bien enregistrée !');
            return $this->redirectToRoute('categoryList');
        }

        return $this->render('Admin/category/insertCategory.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }
    
}