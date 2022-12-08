<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Girl;
use App\Entity\Vote;
use App\Interface\Executable;
use App\Repository\GirlRepository;
use App\Repository\VoteRepository;

/**
 * Class VoteCreateService
 * @package App\Service
 */
class VoteCreateService implements Executable
{
    /**
     * @var GirlRepository $girlRepository
     */
    private GirlRepository $girlRepository;

    /**
     * @var Girl[] $girls
     */
    private array $girls = [];

    /**
     * @var Vote|null $vote
     */
    private ?Vote $vote = null;

    /**
     * @var VoteRepository $voteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @param GirlRepository $girlRepository
     * @param VoteRepository $voteRepository
     */
    public function __construct(GirlRepository $girlRepository, VoteRepository $voteRepository)
    {
        $this->girlRepository = $girlRepository;
        $this->voteRepository = $voteRepository;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $this->loadGirls();
        $this->createVote();
        return true;
    }

    /**
     * @return int
     */
    public function getVoteId(): int
    {
        return $this->vote->getId();
    }

    /**
     * @return void
     */
    private function loadGirls(): void
    {
        $this->girls = $this->girlRepository->findAll();
        shuffle($this->girls);
        $this->girls = array_slice($this->girls, 0, 2);
    }

    /**
     * @return void
     */
    private function createVote(): void
    {
        $this->vote = new Vote();
        $this->vote->setCreatedAt(time());
        $this->vote->setGirlOne($this->girls[0]);
        $this->vote->setGirlTwo($this->girls[1]);
        $this->vote->setUpdatedAt(time());

        $this->voteRepository->save($this->vote, true);
    }
}