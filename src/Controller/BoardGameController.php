<?php

namespace App\Controller;

use App\Entity\BoardGame;
use App\Form\BoardGameType;
use App\SearchQuery\BoardGameQuery;
use App\Repository\BoardGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/board-game")
 */
class BoardGameController extends AbstractController
{

    /**
     * va chercher des mots
     * 
     * @Route("/search/{query}", methods="GET")
     * 
     */
    public function search(string $query, BoardGameQuery $searchQuery)
    {
        $games = $searchQuery->createCriteria($query);

        return $this->json($games , Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'categories',
                //'authoreBy',
            ]
        ]);
    }

    /**
     * @Route("",
     *      methods="GET",
     *      name="board_game_index"
     * )
     * 
     * @Cache(public=true, maxage=600, smaxage=600) géré le proxy, vider le cache
     */
    public function index(BoardGameRepository $repository)
    {
        $boardGames = $repository->findWithCategories();
        return $this->render('board_game/index.html.twig',[
            'board_games' => $boardGames,
        ]);
    }

    /**
     * @Route("/{id}", 
     *      requirements={"id": "\d+"},
     *      name="board_game_show"  
     * )
     *
     * Composant ParamConverter est capable de traduire un paramètre de route en:
     * - Entité
     * - \DateTime
     */
    public function show(BoardGame $boardGame, ValidatorInterface $validator)
    {
        // pas utile ici, juste pour un exemple de validation hors formulaire
        $errors = $validator->validate($boardGame);
        return $this->render('board_game/show.html.twig', [
            'board_game' => $boardGame,
        ]);
    }

    /**
     * @Route("/new", 
     *      methods={"GET", "POST"},
     *      name="board_game_add"
     * )
     */
    /*public function new(Request $request, EntityManagerInterface $manager)
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
    */

    /**
     * @Route("/{id}/edit", 
     *      methods={"GET", "PUT"},
     *      name="board_game_edit"
     * )
     */
    /*public function edit(
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
    */
}
