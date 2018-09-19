<?php
namespace Lockr\Model;

use DateTime;

class Cert extends ModelBase
{
    /**
     * @return DateTime
     */
    public function getCreated()
    {
        $created = $this->getAttribute('created');
        return DateTime::createFromFormat(DateTime::RFC3339, $created);
    }

    /**
     * @return string
     */
    public function getCsrText()
    {
        return $this->getAttribute('csr_text');
    }

    /**
     * @return string
     */
    public function getCertText()
    {
        return $this->getAttribute('cert_text');
    }

    /**
     * @return DateTime
     */
    public function getExpiration()
    {
        $expiration = $this->getAttribute('expiration');
        return DateTime::createFromFormat(DateTime::RFC3339, $expiration);
    }
}

// ex: ts=4 sts=4 sw=4 et:
