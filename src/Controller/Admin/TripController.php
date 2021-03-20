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

    public function insertTrip(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ){
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $entityManager->persist($trip);
            $entityManager->flush();
            $this->addFlash('Success', 'Votre voyage a été crée !');
            return $this->redirectToRoute('tripList');
        }
        return $this->render('Admin/insertTrip.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);
    }
}