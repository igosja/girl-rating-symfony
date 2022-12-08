<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SiteController
 * @package App\Admin\Controller
 *
 * @Route("/admin")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("", name="admin_home")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/site/index.html.twig');
    }
}
