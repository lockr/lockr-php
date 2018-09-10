<?php
namespace Lockr\Model;

class Site extends ModelBase
{
    /**
     * @returns string
     */
    public function getLabel()
    {
        return $this->getAttribute('label');
    }
}

// ex: ts=4 sts=4 sw=4 et:
