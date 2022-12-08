<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Girl;
use App\Entity\User;
use App\Entity\Vote;
use App\Form\GirlForm;
use App\Repository\GirlRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VotesController
 * @package App\Admin\Controller
 *
 * @Route("/admin/votes")
 */
class VotesController extends AbstractController
{
    /**
     * @Route("", name="admin_votes")
     *
     * @param Request $request
     * @param VoteRepository $voteRepository
     * @return Response
     */
    public function index(Request $request, VoteRepository $voteRepository): Response
    {
        $filterUrl = $this->getFilterUrl('admin_votes', $request);
        $votes = $voteRepository->findByFilters(
            $request->query->all(),
            $this->getSortingCriteria($request),
            $this->itemsPerPage,
            $this->getOffset($request)
        );
        $totalCount = count(
            $voteRepository->findByFilters($request->query->all(), $this->getSortingCriteria($request))
        );
        $pages = $this->getPaginationPages($request, $totalCount);
        $paginationUrl = $this->getPaginationUrl('admin_votes', $request);

        return $this->render('admin/votes/index.html.twig', [
            'filter_url' => $filterUrl,
            'votes' => $votes,
            'pages' => $pages,
            'pagination_url' => $paginationUrl,
            'total_count' => $totalCount,
        ]);
    }

    /**
     * @Route("/view/{id}", name="admin_vote_view")
     *
     * @param Vote $vote
     * @return Response
     */
    public function view(Vote $vote): Response
    {
        return $this->render('admin/votes/view.html.twig', [
            'vote' => $vote,
        ]);
    }
}
