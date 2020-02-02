<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\{GameEntityRepository};
use Doctrine\ORM\EntityManagerInterface;
use App\Form\GameCreate;
use App\Entity\{GameEntity};
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class GameController extends AbstractController
{
    private $gameEntityRepository;
    private $entityManager;

    public function __construct(
        GameEntityRepository $gameEntityRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->gameEntityRepository = $gameEntityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $game = $this->gameEntityRepository->findAll();

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'game' => $game
        ]);
    }

    /**
     * @Route("/game/detail/{id}", name="gameDetail")
     * @ParamConverter("gameId", options={"mapping"={"id"="id"}})
     */
    public function detail(GameEntity $gameId, EntityManagerInterface $entityManager)
    {
        $game = $this->gameEntityRepository->find($gameId);

        return $this->render('game/detail.html.twig', [
            'controller_name' => 'gameController',
            'game' => $game
        ]);
    }

    /**
     * @Route("/game/create", name="gameCreate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request)
    {
        $newGame = new GameEntity();

        $form = $this->createForm(GameCreate::class, $newGame);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($newGame);
            $this->entityManager->flush();
            $this->addFlash('notice', "Le jeu a bien été ajouté");

            return $this->redirectToRoute('home');
        }

        return $this->render('game/create.html.twig', [
            'createGameForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/game/edit/{id}", name="gameEdit")
     * @ParamConverter("gameID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, GameEntity $gameId)
    {
        $form = $this->createForm(GameCreate::class, $gameId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($gameId);
            $this->entityManager->flush();
            $this->addFlash('notice', "Le jeu a bien été mis à jour");

            return $this->redirectToRoute('home');
        }

        return $this->render('game/edit.html.twig', [
            'editGameForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/game/delete/{id}", name="gameDelete")
     * @ParamConverter("gameID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(GameEntity $gameID, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($gameID);
        $entityManager->flush();
        $this->addFlash('notice', "Le jeu a bien été supprimé");

        return $this->redirectToRoute('home');
    }
}
