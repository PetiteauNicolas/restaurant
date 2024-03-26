<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private RequestStack $requestStack)
    {
    }

    #[Route('/details/{id}', name: 'details')]
    public function detailsUser(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('userDetails.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addUser(UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plaintextPaswword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPaswword);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('addUser.html.twig', [
            'userForm' => $userForm->createview(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateUser(User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($this->requestStack->getCurrentRequest());

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plaintextPaswword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPaswword);

            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->render('userDetails.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('addUser.html.twig', [
            'userForm' => $userForm->createview(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteUser(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('restaurant_list');
    }
}
