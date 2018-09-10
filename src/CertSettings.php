<?php
namespace Lockr;

class CertSettings extends AnonSettings
{
    /** @var string $certPath */
    private $certPath;

    /**
     * @param string $cert_path
     * @param string|null $sovereignty
     */
    public function __construct($cert_path, $sovereignty = null)
    {
        parent::__construct($sovereignty);
        $this->certPath = $cert_path;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return ['cert' => $this->certPath];
    }
}

// ex: ts=4 sts=4 sw=4 et:
