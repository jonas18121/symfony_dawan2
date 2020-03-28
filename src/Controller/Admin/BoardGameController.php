<?php

namespace App\Controller\Admin;

use App\Entity\BoardGame;
use App\Form\BoardGameType;
use App\Repository\BoardGameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/adimn/board-game")
 */
class BoardGameController extends AbstractController
{
    /**
     * @Route("/new", 
     *      methods={"GET", "POST"},
     *      name="board_game_add"
     * )
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $game = new BoardGame();

        $form = $this->createForm(BoardGameType::class, $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($game);
            $manager->flush();

            $this->addFlash('success', 'Nouveau jeu créé');
            return $this->redirectToRoute('board_game_show', [
                'id' => $game->getId(),
            ]);
        }

        return $this->render('board_game/new.html.twig', [
            'new_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", 
     *      methods={"GET", "PUT"},
     *      name="board_game_edit"
     * )
     */
    public function edit(
        BoardGame $game,
        Request $request,
        EntityManagerInterface $manager
    ) 
    {
        $form = $this->createForm(BoardGameType::class, $game, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', $game->getName().' mis à jour');
            return $this->redirectToRoute('board_game_show', [
                'id' => $game->getId(),
            ]);
        }

        return $this->render('board_game/edit.html.twig', [
            'game' => $game,
            'edit_form' => $form->createView(),
        ]);
    }
}
