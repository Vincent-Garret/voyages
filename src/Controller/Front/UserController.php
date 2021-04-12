<?php


namespace App\Controller\Front;

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

class UserController extends AbstractController{

    /**
     * @Route("/user/list", name="userList")
     */
    public function userList(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('Front/search_user.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/search", name="search_user")
     */
    public function searchUser(Request $request, UserRepository $userRepository, TripRepository $tripRepository)
    {
        $search = $request->query->get('search');
        $users = $userRepository->getByWordInUserName($search);
        $trips = $tripRepository->findBy([], ['id' => 'DESC'], 3, 0);

        return $this->render('Front/search_result.html.twig', [
            'search' => $search, 
            'users' => $users,
            'trips' => $trips
        ]);
    }
}