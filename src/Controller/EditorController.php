<?php

namespace App\Controller;

use App\Entity\EditorEntity;
use App\Form\EditorCreate;
use App\Repository\{EditorEntityRepository, GameEntityRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EditorController extends AbstractController
{
    private $editorEntityRepository;
    private $gameEntityRepository;
    private $entityManager;

    public function __construct(
        EditorEntityRepository $editorEntityRepository,
        GameEntityRepository $gameEntityRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->editorEntityRepository = $editorEntityRepository;
        $this->gameEntityRepository = $gameEntityRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/editor", name="editor")
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        $editor = $this->editorEntityRepository->findAll();

        return $this->render('editor/index.html.twig', [
            'controller_name' => 'EditorController',
            'editor' => $editor
        ]);
    }

    /**
     * @Route("/editor/detail/{id}", name="editorDetail")
     * @ParamConverter("editorId", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_USER")
     */
    public function detail(EditorEntity $editorId, EntityManagerInterface $entityManager)
    {
        $editor = $this->editorEntityRepository->find($editorId);
        $game = $this->gameEntityRepository->findBy(['editor' => $editorId]);

        return $this->render('editor/detail.html.twig', [
            'controller_name' => 'EditorController',
            'editor' => $editor,
            'game' => $game
        ]);
    }

    /**
     * @Route("/editor/create", name="editorCreate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request)
    {
        $newEditor = new EditorEntity();

        $form = $this->createForm(EditorCreate::class, $newEditor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($newEditor);
            $this->entityManager->flush();
            $this->addFlash('notice', "L'éditeur a bien été ajouté");

            return $this->redirectToRoute('editor');
        }

        return $this->render('editor/create.html.twig', [
            'createEditorForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editor/edit/{id}", name="editorEdit")
     * @ParamConverter("editorID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, EditorEntity $editorId)
    {
        $form = $this->createForm(EditorCreate::class, $editorId);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($editorId);
            $this->entityManager->flush();
            $this->addFlash('notice', "L'éditeur a bien été mis à jour");

            return $this->redirectToRoute('editor');
        }

        return $this->render('editor/edit.html.twig', [
            'editEditorForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editor/delete/{id}", name="editorDelete")
     * @ParamConverter("editor", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(EditorEntity $editor, EntityManagerInterface $entityManager)
    {
        $game = $this->gameEntityRepository->findBy(['editor' => $editor]);

        foreach ($game as $key) {
            $key->setEditorToNull();
        }

        $entityManager->remove($editor);
        $entityManager->flush();
        $this->addFlash('notice', "L'éditeur a bien été supprimé");

        return $this->redirectToRoute('editor');
    }
}
