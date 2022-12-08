<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Girl;
use App\Entity\Vote;
use App\Repository\VoteRepository;
use App\Service\VoteCreateService;
use App\Service\VoteService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VotesController
 * @package App\Controller
 *
 * @Route("/votes")
 */
class VotesController extends AbstractController
{
    /**
     * @Route("", name="votes")
     * @throws NonUniqueResultException
     */
    public function index(VoteRepository $voteRepository, VoteCreateService $voteCreateService): RedirectResponse
    {
        $vote = $voteRepository
            ->createQueryBuilder('v')
            ->andWhere('v.girl_winner IS NULL')
            ->andWhere('v.created_at < ' . time() - 24 * 60 * 60)
            ->getQuery()
            ->getOneOrNullResult();
        if ($vote) {
            return $this->redirectToRoute('vote_view', ['id' => $vote->getId()]);
        }

        $voteCreateService->execute();

        return $this->redirectToRoute('vote_view', ['id' => $voteCreateService->getVoteId()]);
    }

    /**
     * @Route("/view/{id}", name="vote_view")
     */
    public function view(Vote $vote): RedirectResponse|Response
    {
        if ($vote->getGirlWinner()) {
            return $this->redirectToRoute('votes');
        }

        return $this->render('votes/view.html.twig', [
            'vote' => $vote,
        ]);
    }

    /**
     * @Route("/vote/{id}/{girl}", name="vote_vote")
     */
    public function vote(Vote $vote, Girl $girl, VoteService $voteService): RedirectResponse
    {
        if ($vote->getGirlWinner()) {
            return $this->redirectToRoute('votes');
        }

        $voteService->setGirl($girl);
        $voteService->setVote($vote);

        if (!$voteService->execute()) {
            return $this->redirectToRoute('vote_view', ['id' => $vote->getId()]);
        }

        return $this->redirectToRoute('votes');
    }
}
