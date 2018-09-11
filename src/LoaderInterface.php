<?php
namespace Lockr;

interface LoaderInterface
{
    /**
     * Creates a new model.
     *
     * @param array $data
     *
     * @returns ModelInterface
     */
    public function create(array $data);

    /**
     * Loads a model.
     *
     * @param string $type
     * @param string $id
     *
     * @returns ModelInterface
     */
    public function load($type, $id);

    /**
     * Loads a related model.
     *
     * @param ModelInterface $model
     * @param string $name
     *
     * @returns ModelInterface
     */
    public function loadRelated(ModelInterface $model, $name);
}

// ex: ts=4 sts=4 sw=4 et:
