<?php
namespace Lockr\Model;

use DateTime;

class SecretValue extends ModelBase
{
    /**
     * @returns DateTime
     */
    public function getCreated()
    {
        $created = $this->getAttribute('created');
        return DateTime::createFromFormat(DateTime::RFC3339, $created);
    }

    /**
     * @returns string
     */
    public function getEnv()
    {
        return $this->getAttribute('env');
    }

    /**
     * @returns string
     */
    public function getValue()
    {
        $value = $this->getAttribute('value');
        return base64_decode($value);
    }
}

// ex: ts=4 sts=4 sw=4 et:
