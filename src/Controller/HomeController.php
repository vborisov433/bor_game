<?php

namespace App\Controller;

use App\Entity\MarketSummary;
use App\Entity\PromptTemplate;
use App\Entity\Service;
use App\Repository\MarketSummaryRepository;
use App\Repository\NewsItemRepository;
use App\Repository\PromptTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {

        return $this->render('home/index.html.twig', []);
    }

}
