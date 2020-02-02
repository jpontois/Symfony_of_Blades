<?php

namespace App\Controller;

use App\Entity\UserEntity;
use App\Form\{UserCreate, UserEdit};
use App\Event\UserCreatedEvent;
use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserController extends AbstractController
{
    private $userEntityRepository;
    private $eventDispatcher;
    private $entityManager;

    public function __construct(
        userEntityRepository $userEntityRepository,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager
    )
    {
        $this->userEntityRepository = $userEntityRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/user", name="user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function list()
    {
        $user = $this->userEntityRepository->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/signin", name="signin")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $newUser = new UserEntity();

        $form = $this->createForm(UserCreate::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($password);

            $newUser->setCreationDate(new \DateTime());

            $entityManager->persist($newUser);
            $entityManager->flush();

            $this->addFlash('notice', "Le profil a bien été crée");

            $userCreatedEvent = new UserCreatedEvent($newUser);
            $this->eventDispatcher->dispatch($userCreatedEvent);

            return $this->redirectToRoute('home');
        }

        return $this->render('user/create.html.twig', [
            'newUserForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEdit::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('notice', "Le profil a bien été mis à jour");

            return $this->redirectToRoute('profil');
        }

        return $this->render('user/edit.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="userEdit")
     * @ParamConverter("userID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, UserEntity $userId)
    {
        $form = $this->createForm(UserEdit::class, $userId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($userId);
            $this->entityManager->flush();
            $this->addFlash('notice', "Le profil a bien été mis à jour");

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'editUserForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="userDelete")
     * @ParamConverter("user", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(UserEntity $user, EntityManagerInterface $entityManager)
    {
        $currentUser = $this->getUser();

        if ($currentUser == $user) {
            $this->addFlash('notice', "Vous ne pouvez pas supprimer le profil sur lequel vous êtes connecté");
        } else {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('notice', "Le profil a bien été supprimé");
        }

        return $this->redirectToRoute('user');
    }
}