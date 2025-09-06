<?php

namespace App\Controller;

use App\Entity\PollQuestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PollController extends AbstractController
{

    #[Route('/fetch-poll', name: 'poll_fetch', methods: ['GET'])]
    public function fetch(Request $request, EntityManagerInterface $em, HttpClientInterface $http, ParameterBagInterface $params): JsonResponse
    {
        $NUMBER_QUESTIONS = 50;

        $HOST = $params->get('GPT_HOST');; // 15.0.1.50
        $question = $request->request->get('question', '
        I am trader for a long time. Issues are I forget to take profit or I move my stop and a loss
become big loss, I enter the market then I realise I am wrong but still let the trade go, then
close for a big loss.
-create a POLL '.$NUMBER_QUESTIONS.'. QUESTIONS ABOUT getting  DISCIPLINEd trader get result in json format
"question": "",
  "answers": {
	list with zero index,could be 2 to 5 options to answer , always first answer is correct
  },
  "right_answer": id of the right answer,
  "explanation": explain why this is correct
        ');

        $pollNumber = (int)date('mdHi');

        try {
            $response = $http->request('POST', 'http://' . $HOST . ':5000/api/ask-gpt?model=gpt-4.1', [
                'json' => ['question' => $question],
            ]);

            $data = $response->toArray();
            $answerRaw = $data['answer'] ?? '';

            if (preg_match('/```json(.*?)```/s', $answerRaw, $matches)) {
                $jsonString = trim($matches[1]);
            } else {
                // fallback: try to extract first JSON-like array
                if (preg_match('/\[[\s\S]+\]/', $answerRaw, $matches)) {
                    $jsonString = $matches[0];
                } else {
                    throw new \RuntimeException("No JSON found in GPT response");
                }
            }

            $data = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

            // Save each question in DB
            $saved = [];
            foreach ($data as $idx => $item) {
                $pollQuestion = new PollQuestion();
                $pollQuestion->setPollNumber($pollNumber);
                $pollQuestion->setPollQuestionId($idx + 1);
                $pollQuestion->setJsonData(json_encode($item, JSON_PRETTY_PRINT));

                $pollQuestion->setAnswered(2); // default 2 (not answered)

                $em->persist($pollQuestion);
                $saved[] = $item;
            }
            $em->flush();

            return new JsonResponse(['success' => true, 'saved' => $saved]);

        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/poll', name: 'api_poll_unanswered', methods: ['GET'])]
    public function getUnanswered(EntityManagerInterface $em): JsonResponse
    {
        $poll = $em->getRepository(PollQuestion::class)->findOneBy(['answered' => 2]);

        if (!$poll) {
            return $this->json(['message' => 'No unanswered poll']);
        }

        return $this->json([
            'id' => $poll->getId(),
            'pollNumber' => $poll->getPollNumber(),
            'pollQuestionId' => $poll->getPollQuestionId(),
            'data' => json_decode($poll->getJsonData(), true),
        ]);
    }

    #[Route('/api/poll/{id}/answer', name: 'api_poll_answer', methods: ['POST'])]
    public function answer(int $id, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $poll = $em->getRepository(PollQuestion::class)->find($id);
        if (!$poll) {
            return $this->json(['error' => 'Poll not found'], 404);
        }

        $poll->setAnswered(1);
        $em->flush();

        return $this->json(['status' => 'ok']);
    }

    #[Route('/poll', name: 'poll_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('poll/index.html.twig', [

        ]);
    }
}
