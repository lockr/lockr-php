<?php
namespace Lockr;

interface SettingsInterface
{
    /**
     * Gets the Lockr host to connect to.
     *
     * @returns string
     */
    public function getHostname();

    /**
     * Gets the Guzzle client options to apply.
     *
     * @returns array
     */
    public function getOptions();
}

// ex: ts=4 sts=4 sw=4 et:
