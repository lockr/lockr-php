<?php
namespace Lockr\KeyWrapper;

interface KeyWrapperInterface
{
    /**
     * @returns bool
     */
    public static function enabled();

    /**
     * Encrypt the given plaintext.
     *
     * @param string $plaintext
     *
     * @returns array
     */
    public static function encrypt($plaintext);

    /**
     * Encrypt the given plaintext reusing state.
     *
     * @param string $plaintext
     * @param string $wrapping_key
     *
     * @returns array
     */
    public static function reencrypt($plaintext, $wrapping_key);

    /**
     * Decrypt the given ciphertext.
     *
     * @param string $ciphertext
     * @param string $wrapping_key
     *
     * @returns string|bool
     */
    public static function decrypt($ciphertext, $wrapping_key);
}

// ex: ts=4 sts=4 sw=4 et:
