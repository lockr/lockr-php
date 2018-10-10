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
        return DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $created);
    }

    /**
     * @return DateTime
     */
    public function getModified()
    {
        $modified = $this->getAttribute('modified');
        return DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $modified);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getAttribute('label');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getAttribute('status');
    }
}

// ex: ts=4 sts=4 sw=4 et:
