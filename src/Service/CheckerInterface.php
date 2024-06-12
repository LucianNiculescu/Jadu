<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Interface for checking if words are a Palindrome, Anagram and Pangram
 */
interface CheckerInterface
{
    /**
     * A palindrome is a word, phrase, number, or other sequence of characters which reads the same backward or forward.
     *
     * @param string $word
     * @return bool
     */
    public function isPalindrome(string $word): bool;

    /**
     * An anagram is the result of rearranging the letters of a word or phrase to produce a new word or phrase, using all the original letters exactly once.
     *
     * @param string $word
     * @param string $comparison
     * @return bool
     */
    public function isAnagram(string $word, string $comparison): bool;

    /**
     * A Pangram for a given alphabet is a sentence using every letter of the alphabet at least once.
     *
     *
     * @param string $phrase
     * @return bool
     */
    public function isPangram(string $phrase): bool;
}
