<?php


namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TripType;
use App\Entity\Trip;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;


class TripController extends AbstractController
{

    /**
     * @Route("/user/trip", name="trips")
     */
    public function trips(
        TripRepository $tripRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository,
        paginatorInterface $paginator,
        Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tripRepository = $em->getRepository(Trip::class);
        $allTripsQuery = $tripRepository->findBy([], ['id' => 'DESC']);
        $categories = $categoryRepository->findAll();
        $trips = $paginator->paginate(
            $allTripsQuery,
            $request->query->getInt('page', 1),
            8
        );
        $users = $userRepository->findAll();

        return $this->render('Front/home_user.html.twig', [
            'categories' => $categories,
            'trips' => $trips,
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/insert/trip", name="insert")
     */
    public function insertTrip(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ){
        $trip = new Trip();
        $userId = $this->getUser();
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $image = $tripForm->get('image')->getData();
            if($image){
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('img'),
                    $newFileName
                );
                
                $trip->setImage($newFileName);
                $trip->setUser($userId);
                $entityManager->persist($trip);
                $entityManager->flush();
            }
            $entityManager->persist($trip);
            $entityManager->flush();
            $this->addFlash('Success', 'Votre voyage a ??t?? cr??e !');
            return $this->redirectToRoute('trips');
        }
        return $this->render('Front/tripInsert.html.twig', [
            'tripForm' => $tripForm->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/trip/{id}", name="delete")
     */
    public function deleteTrip(
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager,
        $id
    ){
        $trip = $tripRepository->find($id);
        
        $entityManager->remove($trip);
        $entityManager->flush();

        $this->addFlash('Success', 'Votre voyage a bien ??t?? supprim??');
        return $this->redirectToRoute('trips');
    }

    /**
     * @Route("/user/update/trip/{id}", name="update")
     */
    public function updateTrip(
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $id,
        SluggerInterface $slugger
    ){
        $trip = $tripRepository->find($id);
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() &&$tripForm->isValid()){
            $image = $tripForm->get('image')->getData();
            if($image){
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('img'),
                    $newFileName
                    );
                $trip->setImage($newFileName);
            }

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'Votre voyage ?? bien ??t?? modifi??');
            return $this->redirectToRoute('trips');
        }
        return $this->render('Front/tripUpdate.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);
    }

    /**
     *@Route("/user/trips/{id}", name="tripsByUser") 
     */
    public function tripsByUser(
        UserRepository $userRepository,
        $id)
    {
        $user = $userRepository->find($id);

        return $this->render('Front/tripsByUser.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/category/{id}", name="tripsByCategory")
     */
    public function tripsByCategory(
        CategoryRepository $categoryRepository,
        $id
        )
    {
        $category = $categoryRepository->find($id);
        

        return $this->render('Front/tripsByCategory.html.twig', [
            'category' => $category
        ]);
    }
}