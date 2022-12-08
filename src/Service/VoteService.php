<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Girl;
use App\Entity\Vote;
use App\Interface\Executable;
use App\Repository\GirlRepository;
use App\Repository\VoteRepository;

/**
 * Class VoteService
 * @package App\Service
 */
class VoteService implements Executable
{
    private const COEFFICIENT_JUNIOR = 25;
    private const COEFFICIENT_MIDDLE = 15;
    private const COEFFICIENT_SENIOR = 10;

    private const JUNIOR_LIMIT = 15;
    private const MIDDLE_LIMIT = 2400;

    /**
     * @var GirlRepository $girlRepository
     */
    private GirlRepository $girlRepository;

    /**
     * @var VoteRepository $voteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @var Vote $vote
     */
    private Vote $vote;

    /**
     * @var Girl $girl
     */
    private Girl $girl;

    /**
     * @param VoteRepository $voteRepository
     * @param GirlRepository $girlRepository
     */
    public function __construct(VoteRepository $voteRepository, GirlRepository $girlRepository)
    {
        $this->voteRepository = $voteRepository;
        $this->girlRepository = $girlRepository;
    }

    /**
     * @param Vote $vote
     * @return void
     */
    public function setVote(Vote $vote): void
    {
        $this->vote = $vote;
    }

    /**
     * @param Girl $girl
     * @return void
     */
    public function setGirl(Girl $girl): void
    {
        $this->girl = $girl;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        if ($this->vote->getGirlWinner()) {
            return false;
        }

        if (!in_array($this->girl->getId(), [$this->vote->getGirlOne()->getId(), $this->vote->getGirlTwo()->getId()])) {
            return false;
        }

        $this->updateVote();
        $this->updateGirls();

        return true;
    }

    /**
     * @return void
     */
    private function updateVote(): void
    {
        $this->vote->setGirlWinner($this->girl);
        $this->vote->setUpdatedAt(time());
        $this->voteRepository->save($this->vote, true);
    }

    /**
     * @return void
     */
    private function updateGirls(): void
    {
        $girlOneNewRating = $this->getNewRating($this->vote->getGirlOne());
        $girlTwoNewRating = $this->getNewRating($this->vote->getGirlTwo());

        $this->vote->getGirlOne()->setRating($girlOneNewRating);
        $this->vote->getGirlOne()->setVotes($this->vote->getGirlOne()->getVotes() + 1);
        $this->vote->getGirlOne()->setUpdatedAt(time());
        $this->girlRepository->save($this->vote->getGirlOne(), true);

        $this->vote->getGirlTwo()->setRating($girlTwoNewRating);
        $this->vote->getGirlTwo()->setVotes($this->vote->getGirlTwo()->getVotes() + 1);
        $this->vote->getGirlTwo()->setUpdatedAt(time());
        $this->girlRepository->save($this->vote->getGirlTwo(), true);
    }

    /**
     * @param Girl $girl
     * @return int
     */
    private function getNewRating(Girl $girl): int
    {
        return (int) round(
            $girl->getRating()
            + $this->getCoefficient($girl)
            * ($this->getScoredPoints($girl) - $this->getExpectedPoints($girl))
        );
    }

    /**
     * @param Girl $girl
     * @return int
     */
    private function getCoefficient(Girl $girl): int
    {
        if ($girl->getVotes() <= self::JUNIOR_LIMIT) {
            return self::COEFFICIENT_JUNIOR;
        }

        if ($girl->getRating() <= self::MIDDLE_LIMIT) {
            return self::COEFFICIENT_MIDDLE;
        }

        return self::COEFFICIENT_SENIOR;
    }

    /**
     * @param Girl $girl
     * @return int
     */
    private function getScoredPoints(Girl $girl): int
    {
        if ($this->vote->getGirlWinner()->getId() === $girl->getId()) {
            return 1;
        }
        return 0;
    }

    /**
     * @param Girl $girl
     * @return float
     */
    private function getExpectedPoints(Girl $girl): float
    {
        return 1 / (1 + 10 ** (($girl->getRating() - $this->getOpponentRating($girl)) / 400));
    }

    /**
     * @param Girl $girl
     * @return int
     */
    private function getOpponentRating(Girl $girl): int
    {
        $opponent = $this->vote->getGirlOne();
        if ($this->vote->getGirlOne()->getId() === $girl->getId()) {
            $opponent = $this->vote->getGirlTwo();
        }

        return $opponent->getRating();
    }
}