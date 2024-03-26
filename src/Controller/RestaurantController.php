<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\City;
use App\Entity\Food;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Form\SearchType;
use App\Repository\RestaurantRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/restaurant', name: 'restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private RequestStack $requestStack)
    {
    }

    #[Route('/list', name: 'list')]
    public function listRestaurant(EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository, Request $request): Response
    {
        $cities = $entityManager->getRepository(City::class)->findAll();
        $food = $entityManager->getRepository(Food::class)->findAll();

        $SearchData = new SearchData();
        $form = $this->createForm(SearchType::class, $SearchData);
        $form->handleRequest($request);

        $searchTerm = $request->query->get('q');

        $restaurants = $restaurantRepository->sort($searchTerm, $SearchData);

        $restaurantsList = [];

        foreach ($restaurants as $result) {
            $restaurant = $result[0];
            $avgRating = $result['avgRating'];

            $restaurantData = [
                'id' => $restaurant->getId(),
                'name' => $restaurant->getName(),
                'address' => $restaurant->getAddress(),
                'description' => $restaurant->getDescription(),
                'picture' => $restaurant->getPicture(),
                'price' => $restaurant->getPrice(),
                'website' => $restaurant->getWebsite(),
                'alreadyDone' => $restaurant->getAlreadyDone(),
                'phoneNumber' => $restaurant->getPhoneNumber(),
                'avgRating' => $avgRating,
                'food' => $restaurant->getFood(),
                'city' => $restaurant->getCity(),
                'ratings' => $restaurant->getRatings(),
                'averageRatings' => $restaurant->getAverageRatings(),
            ];

            $restaurantsList[] = $restaurantData;
        }

        $request->query->get('q');

        return $this->render('listRestaurant.html.twig', [
            'restaurants' => $restaurantsList,
            'cities' => $cities,
            'foods' => $food,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addRestaurant(SluggerInterface $slugger, FileUploader $fileUploader): Response
    {
        $restaurant = new Restaurant();
        $restaurantForm = $this->createForm(RestaurantType::class, $restaurant);
        $restaurantForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($restaurantForm->isSubmitted() && $restaurantForm->isValid()) {
            $pictureFile = $restaurantForm->get('picture')->getData();
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $restaurant->setPicture($pictureFileName);
            }

            $this->entityManager->persist($restaurant);
            $this->entityManager->flush();

            $this->addFlash('succes-add', 'Restaurant ajouté avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addRestaurant.html.twig', [
            'restaurantForm' => $restaurantForm->createview(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateRestaurant(Restaurant $restaurant, FileUploader $fileUploader): Response
    {
        $restaurantForm = $this->createForm(RestaurantType::class, $restaurant);
        $restaurantForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($restaurantForm->isSubmitted() && $restaurantForm->isValid()) {
            $pictureFile = $restaurantForm->get('picture')->getData();
            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $restaurant->setPicture($pictureFileName);
            }
            $this->entityManager->flush();

            $this->addFlash('succes-update', 'Restaurant modifié avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addRestaurant.html.twig', [
            'restaurantForm' => $restaurantForm->createview(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteRestaurant(Restaurant $restaurant)
    {
        $picture = $restaurant->getPicture();

        if ($picture) {
            $picturePath = $this->getParameter('pictures_directory').'/'.$picture;

            if (file_exists($picturePath)) {
                unlink($picturePath);
            }
        }

        $this->entityManager->remove($restaurant);
        $this->entityManager->flush();

        $this->addFlash('succes-delete', 'Restaurant supprimé avec succès !');

        return $this->redirectToRoute('restaurant_list');
    }

    #[Route('/random', name: 'random')]
    public function selectRandomRestaurant(RestaurantRepository $restaurantRepository)
    {
        $results = $restaurantRepository->randomRestaurant();

        return $this->render('randomRestaurant.html.twig', [
            'results' => $results,
        ]);
    }
}
