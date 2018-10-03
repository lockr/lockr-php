<?php
namespace Lockr\Model;

use DateTime;

class SecretValue extends ModelBase
{
    /**
     * @return DateTime
     */
    public function getCreated()
    {
        $created = $this->getAttribute('created');
        return DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $created);
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->getAttribute('env');
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $value = $this->getAttribute('value');
        return base64_decode($value);
    }
}

// ex: ts=4 sts=4 sw=4 et:
