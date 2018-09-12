<?php
namespace Lockr\Model;

use DateTime;

class Secret extends ModelBase
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
     * @returns DateTime
     */
    public function getModified()
    {
        $modified = $this->getAttribute('modified');
        return DateTime::createFromFormat(DateTime::RFC3339, $modified);
    }

    /**
     * @returns string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @returns string
     */
    public function getLabel()
    {
        return $this->getAttribute('label');
    }

    /**
     * @returns string
     */
    public function getPolicy()
    {
        return $this->getAttribute('policy');
    }

    /**
     * @returns string
     */
    public function getSovereignty()
    {
        return $this->getAttribute('sovereignty');
    }
}

// ex: ts=4 sts=4 sw=4 et:
