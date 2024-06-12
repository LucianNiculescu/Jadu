<?php

namespace App\Controller;

use App\Service\CheckerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for interacting with the CheckerService to validate words. Route: /{type}/validate
 */
class CheckerController extends AbstractController
{
    private CheckerService $checkerService;

    public function __construct(CheckerService $checkerService)
    {
        $this->checkerService = $checkerService;
    }

    /**
     * Validates if the provided word is a palindrome.
     * Request body should be a JSON object with a 'word' key.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \JsonException
     */
    #[Route('/validate/palindrome', name: 'check palindrome', methods: ['POST'])]
    public function palindrome(Request $request): JsonResponse
    {
        $jsonContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Check if the Content provided in the api call is valid and contains the 'word' index
        if ($jsonContent === null || !isset($jsonContent['word'])) {
            return $this->buildResponse("Please ensure you provide a valid JSON payload with a 'word' key.", false, JsonResponse::HTTP_BAD_REQUEST);
        }

        $word = $jsonContent['word'];

        if (!$this->checkerService->isPalindrome($jsonContent['word'])) {
            return $this->buildResponse("The word: '" . $word . "' is NOT a palindrome.", false, JsonResponse::HTTP_OK);
        }

        return $this->buildResponse("The word: '" . $word . "' is a palindrome.", true, JsonResponse::HTTP_OK);
    }

    /**
     * Builds a JsonResponse with a string as message, a boolean indicating if the parameter(s) matched the called check function and an integer determining the HTTP Status Code.
     *
     * @param string $message
     * @param bool $pass
     * @param int $status
     *
     * @return JsonResponse
     */
    private function buildResponse(string $message, bool $pass, int $status): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'success' => $pass
        ], $status);
    }
}
