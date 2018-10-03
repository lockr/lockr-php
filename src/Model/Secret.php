<?php
namespace Lockr\Model;

use DateTime;

class Secret extends ModelBase
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
    public function getName()
    {
        return $this->getAttribute('name');
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
    public function getPolicy()
    {
        return $this->getAttribute('policy');
    }

    /**
     * @return string
     */
    public function getSovereignty()
    {
        return $this->getAttribute('sovereignty');
    }
}

// ex: ts=4 sts=4 sw=4 et:
