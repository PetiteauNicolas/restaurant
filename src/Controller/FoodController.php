<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/food', name: 'food_')]
class FoodController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function addFood(Request $request, EntityManagerInterface $entityManager): Response
    {
        $food = new Food();
        $foodForm = $this->createForm(FoodType::class, $food);

        $foodForm->handleRequest($request);

        if ($foodForm->isSubmitted() && $foodForm->isValid()) {
            $entityManager->persist($food);
            $entityManager->flush();

            $this->addFlash('succes-add', 'Type de cuisine ajouté avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addFood.html.twig', [
            'foodForm' => $foodForm->createview(),
        ]);
    }
}
