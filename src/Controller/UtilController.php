<?php

namespace App\Controller;

use App\Entity\UtilService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UtilController extends AbstractController
{
    #[Route('/set-timeframe', name: 'app_set_timeframe', methods: ['POST'])]
    public function setTimeframe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $hours = (int)$request->request->get('timeframe', 2); // default 2h

        $utilService = $em->getRepository(UtilService::class)->findOneBy(['name' => 'menu']);
        if (!$utilService) {
            $utilService = new UtilService();
            $utilService->setName('menu');
        }

        $data = $utilService->getData();
        $data['last_selected_timeframe'] = $hours;
        $utilService->setData($data);
        $utilService->setLastUpdated();

        $em->persist($utilService);
        $em->flush();

        return new JsonResponse(['success' => true, 'timeframe' => $hours]);
    }

    #[Route('/test-endpoint', name: 'app_test_endpoint', methods: ['GET'])]
    public function testEndpoint(): Response
    {
        return $this->render('util/test.html.twig', [
            'message' => 'This is a test endpoint',
        ]);
    }

}
