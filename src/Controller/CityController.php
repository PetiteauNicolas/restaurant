<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/city', name: 'city_')]
class CityController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function addCity(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();
        $cityForm = $this->createForm(CityType::class, $city);

        $cityForm->handleRequest($request);

        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('succes-add', 'Ville ajoutée avec succès !');

            return $this->redirectToRoute('restaurant_list');
        }

        return $this->render('addCity.html.twig', [
            'cityForm' => $cityForm->createview(),
        ]);
    }
}
