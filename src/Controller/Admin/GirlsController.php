<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Girl;
use App\Entity\User;
use App\Form\GirlForm;
use App\Repository\GirlRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GirlsController
 * @package App\Admin\Controller
 *
 * @Route("/admin/girls")
 */
class GirlsController extends AbstractController
{
    /**
     * @Route("", name="admin_girls")
     *
     * @param Request $request
     * @param GirlRepository $girlRepository
     * @return Response
     */
    public function index(Request $request, GirlRepository $girlRepository): Response
    {
        $filterUrl = $this->getFilterUrl('admin_girls', $request);
        $girls = $girlRepository->findByFilters(
            $request->query->all(),
            $this->getSortingCriteria($request),
            $this->itemsPerPage,
            $this->getOffset($request)
        );
        $totalCount = count(
            $girlRepository->findByFilters($request->query->all(), $this->getSortingCriteria($request))
        );
        $pages = $this->getPaginationPages($request, $totalCount);
        $paginationUrl = $this->getPaginationUrl('admin_girls', $request);

        return $this->render('admin/girls/index.html.twig', [
            'filter_url' => $filterUrl,
            'girls' => $girls,
            'pages' => $pages,
            'pagination_url' => $paginationUrl,
            'total_count' => $totalCount,
        ]);
    }

    /**
     * @Route("/view/{id}", name="admin_girl_view")
     *
     * @param Girl $girl
     * @return Response
     */
    public function view(Girl $girl): Response
    {
        return $this->render('admin/girls/view.html.twig', [
            'girl' => $girl,
        ]);
    }

    /**
     * @Route("/create", name="admin_girl_create")
     *
     * @param Request $request
     * @param GirlRepository $girlRepository
     * @return Response
     */
    public function create(Request $request, GirlRepository $girlRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $girl = new Girl();
        $girl->setCreatedAt(time());
        $girl->setCreatedBy($user);
        $girl->setRating(1000);
        $girl->setUpdatedAt(time());
        $girl->setVotes(0);

        $form = $this->createForm(GirlForm::class, $girl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $girlRepository->save($girl, true);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFile->move(
                    $this->getParameter('uploads'),
                    $girl->getId() . '.jpg'
                );
            }

            return $this->redirectToRoute('admin_girl_view', ['id' => $girl->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/girls/create.html.twig', [
            'girl' => $girl,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_girl_update")
     *
     * @param Request $request
     * @param Girl $girl
     * @param GirlRepository $girlRepository
     * @return Response
     */
    public function update(Request $request, Girl $girl, GirlRepository $girlRepository): Response
    {
        $girl->setUpdatedAt(time());
        $form = $this->createForm(GirlForm::class, $girl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $girlRepository->save($girl, true);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                if (file_exists($girl->getFilePath())) {
                    unlink($girl->getFilePath());
                }

                $imageFile->move(
                    $this->getParameter('uploads'),
                    $girl->getId() . '.jpg'
                );
            }

            return $this->redirectToRoute('admin_girl_view', ['id' => $girl->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/girls/update.html.twig', [
            'girl' => $girl,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_girl_delete")
     *
     * @param Girl $girl
     * @param GirlRepository $girlRepository
     * @return Response
     */
    public function delete(Girl $girl, GirlRepository $girlRepository): Response
    {
        if (file_exists($girl->getFilePath())) {
            unlink($girl->getFilePath());
        }

        $girlRepository->remove($girl, true);

        return $this->redirectToRoute('admin_girls', [], Response::HTTP_SEE_OTHER);
    }
}
