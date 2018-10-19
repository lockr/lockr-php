<?php
namespace Lockr;

class LockrSettings implements SettingsInterface
{
    /** @var string $certPath */
    private $certPath;

    /** @var string $host */
    private $host;

    /**
     * @param string|null $cert_path
     * @param string|null $host
     */
    public function __construct($cert_path = null, $host = null)
    {
        $this->certPath = $cert_path;
        $this->host = $host;
    }

    /**
     * {@inheritdoc}
     */
    public function getHostname()
    {
        return $this->host ?: 'api.lockr.io';
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->certPath ? ['cert' => $this->certPath] : [];
    }
}

// ex: ts=4 sts=4 sw=4 et:
