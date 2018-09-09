<?php
namespace Lockr;

class AnonSettings implements SettingsInterface
{
    /** @var string|null $sovereignty */
    private $sovereignty = null;

    /**
     * @param string|null $sovereignty;
     */
    public function __construct($sovereignty = null)
    {
        $this->sovereignty = $sovereignty;
    }

    /**
     * {@inheritdoc}
     */
    public function getHostname()
    {
        if ($this->sovereignty) {
            return "{$this->sovereignty}.api.lockr.io";
        }
        return 'api.lockr.io';
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return [];
    }
}

// ex: ts=4 sts=4 sw=4 et:
