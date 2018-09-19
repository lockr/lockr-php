<?php
namespace Lockr\Model;

use DateTime;

class Site extends ModelBase
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
     * @return DateTime
     */
    public function getModified()
    {
        $modified = $this->getAttribute('modified');
        return DateTime::createFromFormat(DateTime::RFC3339, $modified);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getAttribute('label');
    }
}

// ex: ts=4 sts=4 sw=4 et:
