<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Restaurant;
use App\Form\RatingType;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rating', name: 'rating_')]
class RatingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private RequestStack $requestStack)
    {
    }

    #[Route('/add/{id}', name: 'add')]
    public function addRating(Restaurant $restaurant): Response
    {
        $user = $this->getUser();
        $rating = new Rating();
        $rating->setRestaurant($restaurant);
        $rating->setUsers($user);

        $ratingForm = $this->createForm(RatingType::class, $rating);
        $ratingForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $this->entityManager->persist($rating);
            $this->entityManager->flush();

            $this->addFlash('succes-add', 'Votre note a été ajoutée avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addRating.html.twig', [
            'restaurant' => $restaurant,
            'ratingForm' => $ratingForm->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateRating(string $id, RatingRepository $ratingRepository, Restaurant $restaurant): Response
    {
        $user = $this->getUser();
        $rating = $ratingRepository->findOneBy([
            'restaurant' => $id,
            'users' => $user,
        ]);

        if (null === $rating) {
            echo 'Vous ne pouvez pas modifier une note inexistante !';
        }

        $ratingForm = $this->createForm(RatingType::class, $rating);
        $ratingForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('succes-add', 'Votre note a été modifiée avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addRating.html.twig', [
            'restaurant' => $restaurant,
            'ratingForm' => $ratingForm->createView(),
        ]);
    }

    #[Route('/average{id}', name: 'average')]
    public function averageRating()
    {
        $this->render('listRestaurant.html.twig');
    }
}
