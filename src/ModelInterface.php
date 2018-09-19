<?php
namespace Lockr;

interface ModelInterface
{
    /**
     * Gets the ID of this model.
     *
     * @return string
     */
    public function getId();

    /**
     * Gets an attribute of this model.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * Gets a relationship of this model.
     *
     * @param string $name
     *
     * @return array
     */
    public function getRelationship($name);
}

// ex: ts=4 sts=4 sw=4 et:
