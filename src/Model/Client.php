<?php
namespace Lockr\Model;

use DateTime;

class Client extends ModelBase
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
}

// ex: ts=4 sts=4 sw=4 et:
