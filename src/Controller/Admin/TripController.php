<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Trip;
use App\Form\TripFormType;
use App\Form\TripType;
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

class TripController extends AbstractController{
    /**
     * @Route("/admin/trip", name="tripList")
     */
    public function trip(TripRepository $tripRepository){
        $trips = $tripRepository->findAll();
        

        return $this->render('Admin/trip.html.twig', [
            'trips' => $trips
        ]);
    }

    /**
     * @Route("/admin/insert/trip", name="tripInsert")
     */
    public function insertTrip(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ){
        $trip = new Trip();
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
                $entityManager->persist($trip);
                $entityManager->flush();
            }
            $entityManager->persist($trip);
            $entityManager->flush();
            $this->addFlash('Success', 'Votre voyage a été crée !');
            return $this->redirectToRoute('home');
        }
        return $this->render('Admin/insertTrip.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);
    }

    /**
     * @Route("admin/delete/trip/{id}", name="tripDelete")
     */
    public function deleteTrip(
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager,
        $id
    ){
        $trip = $tripRepository->find($id);
        $entityManager->remove($trip);
        $entityManager->flush();

        $this->addFlash('success', 'Votre voyage à bien été supprimé !');
        return $this->redirectToRoute('tripList');
    }

    /**
     * @Route("admin/update/trip/{id}", name="tripUpdate")
     */
    public function updateTrip(
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        $id
    ){
        $trip = $tripRepository->find($id);
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        if($tripForm->isSubmitted() && $tripForm->isValid()){
            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'Votre voyage a bien été modifié !');
        }
        return $this->render('Admin/updateTrip.html.twig', [
            'tripForm'=> $tripForm->createView()
        ]);
    }
}