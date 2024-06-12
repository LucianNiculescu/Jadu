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
     */
    #[Route('/validate/palindrome', name: 'validate palindrome', methods: ['POST'])]
    public function palindrome(Request $request): JsonResponse
    {
        try {
            $jsonContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            //We can also log the error here
            return $this->buildResponse("Please ensure you provide a valid JSON payload. Failed with error {$exception->getMessage()}", false, JsonResponse::HTTP_BAD_REQUEST);
        }

        // Check if the Content provided in the api call is valid and contains the 'word' index
        if (!isset($jsonContent['word'])) {
            return $this->buildResponse("Please ensure you provide a 'word' key index.", false, JsonResponse::HTTP_BAD_REQUEST);
        }

        $word = $jsonContent['word'];

        if (!$this->checkerService->isPalindrome($jsonContent['word'])) {
            return $this->buildResponse("The word: '" . $word . "' is NOT a palindrome.", false, JsonResponse::HTTP_OK);
        }

        return $this->buildResponse("The word: '" . $word . "' is a palindrome.", true, JsonResponse::HTTP_OK);
    }

    /**
     * Validates if the provided word is an Anagram of the comparison word.
     * Request body should be a JSON object with a 'word' and 'comparison' indexes.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/validate/anagram', name: 'validate anagram', methods: ['POST'])]
    public function anagram(Request $request): JsonResponse
    {
        try {
            $jsonContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            //We can also log the error here
            return $this->buildResponse("Please ensure you provide a valid JSON payload. Failed with error {$exception->getMessage()}", false, JsonResponse::HTTP_BAD_REQUEST);
        }

        // Check if the Content provided in the api call is valid and contains the 'word' and 'comparison' indexes
        if (!isset($jsonContent['word'])) {
            return $this->buildResponse("Please ensure you provide a 'word' and 'comparison' key indexes.", false, JsonResponse::HTTP_BAD_REQUEST);
        }

        $word = $jsonContent['word'];
        $comparison = $jsonContent['comparison'];

        if (!$this->checkerService->isAnagram($word, $comparison)) {
            return $this->buildResponse("The word: '" . $word . "' is NOT an anagram of {$comparison}.", false, JsonResponse::HTTP_OK);
        }

        return $this->buildResponse("The word: '" . $word . "' is an anagram of {$comparison}.", true, JsonResponse::HTTP_OK);
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
