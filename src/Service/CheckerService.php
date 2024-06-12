<?php

namespace App\Service;

use App\Service\CheckerInterface;

class CheckerService implements CheckerInterface
{
    private const ALPHABET = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    ];

    /**
     * A palindrome is a word, phrase, number, or other sequence of characters which reads the same backward or forward.
     *
     * @param string $word
     *
     * @return bool
     */
    public function isPalindrome(string $word): bool
    {
        $word = $this->validate($word);

        return $word === strrev($word);
    }

    /**
     * An anagram is the result of rearranging the letters of a word or phrase to produce a new word or phrase, using all the original letters exactly once.
     *
     * @param string $word
     * @param string $comparison
     *
     * @return bool
     */
    public function isAnagram(string $word, string $comparison): bool
    {
        $word = $this->validate($word);
        $comparison = $this->validate($comparison);

        return count_chars($word, 1) === count_chars($comparison, 1);
    }

    /**
     * A Pangram for a given alphabet is a sentence using every letter of the alphabet at least once.
     *
     * @param string $phrase
     *
     * @return bool
     */
    public function isPangram(string $phrase): bool
    {
        $phrase = $this->validate($phrase);
        $characterSet = str_split($phrase);
        $characterSet = array_unique($characterSet);
        $characterSet = array_intersect($characterSet, self::ALPHABET);

        return count($characterSet) === count(self::ALPHABET);
    }

    /**
     * Validates the string to ensure we do not have special characters
     *
     * @param string $string
     *
     * @return string
     */
    protected function validate(string $string): string
    {
        // Transform all the characters to lowercase
        $string = strtolower($string);

        // Remove all non-alphabetic characters from the string
        $validatedString = '';
        foreach(str_split($string) as $character) {
            if(in_array($character, self::ALPHABET, true)) {
                $validatedString .= $character;
            }
        }

        return $validatedString;
    }
}
