<?php

namespace App\Controller;

use App\Entity\MarketSummary;
use App\Entity\PromptTemplate;
use App\Entity\Service;
use App\Entity\UploadedFile;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $lastPicLongTerm = $em->getRepository(\App\Entity\UploadedFile::class)
            ->findOneBy(['groupType'=>1], ['createdAt' => 'DESC']);

        $lastPicRight = $em->getRepository(\App\Entity\UploadedFile::class)
            ->findOneBy(['groupType'=>2], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'lastPicLongTerm' => $lastPicLongTerm,
            'lastPicRight' => $lastPicRight,
        ]);
    }

    #[Route('/upload', name: 'app_upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $uploadedFile = $request->files->get('picture');
            $groupType = $request->request->get('groupType', 2);

            if ($uploadedFile) {
                $today = (new \DateTime())->format('Y-m-d'); // e.g. 2025-09-03
                $uploadBaseDir = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $today;

                // Ensure the date folder exists
                if (!is_dir($uploadBaseDir)) {
                    mkdir($uploadBaseDir, 0775, true);
                }

                // Find the next counter (e.g. 001, 002, 003)
                $files = glob($uploadBaseDir . '/*');
                $counter = str_pad(count($files) + 1, 3, '0', STR_PAD_LEFT);

                // Generate safe file name
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $counter . '.' . $uploadedFile->guessExtension();

                try {
                    $uploadedFile->move($uploadBaseDir, $newFilename);

                    $uploaded = new UploadedFile();
                    $uploaded->setPath('uploads/' . $today . '/' . $newFilename);
                    $uploaded->setGroupType($groupType);

                    $em->persist($uploaded);
                    $em->flush();
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Upload failed: ' . $e->getMessage());
                    return $this->redirectToRoute('app_upload');
                }

//                $this->addFlash('success', 'Picture uploaded to /uploads/' . $today . '/' . $newFilename);
                return $this->redirectToRoute('app_home');
            }

            $this->addFlash('warning', 'No file selected.');
        }

        return $this->render('home/upload.html.twig');
    }

}
