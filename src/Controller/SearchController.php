<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private PaginatedFinderInterface $finder;

    /**
     * @param PaginatedFinderInterface $finder
     */
    public function __construct(PaginatedFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    #[Route('/{_locale<%app.supported_locales%>}/search', name: 'app_search')]
    public function index(Request $request): Response
    {
        $query = $request->query->filter("find");
        $results = [];
        if (!empty($query)) {
            $results = $this->finder->find($query);
        }
        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
