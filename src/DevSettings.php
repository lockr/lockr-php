<?php
namespace Lockr;

class DevSettings implements SettingsInterface
{
    /** @var string $certPath */
    private $certPath;

    /**
     * @param string|null $cert_path
     */
    public function __construct($cert_path = null)
    {
        $this->certPath = $cert_path;
    }

    /**
     * {@inheritdoc}
     */
    public function getHostname()
    {
        return 'host.docker.internal:8443';
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $options = ['verify' => false];
        if ($this->certPath) {
            $options['cert'] = $this->certPath;
        }
        return $options;
    }
}

// ex: ts=4 sts=4 sw=4 et:
