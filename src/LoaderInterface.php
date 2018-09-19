<?php
namespace Lockr;

interface LoaderInterface
{
    /**
     * Creates a new model.
     *
     * @param array $data
     *
     * @return ModelInterface
     */
    public function create(array $data);

    /**
     * Loads a model.
     *
     * @param string $type
     * @param string $id
     * @param array $include
     *
     * @return ModelInterface
     */
    public function load($type, $id, array $include = null);

    /**
     * Loads a collection.
     *
     * @param string $type
     *
     * @return ModelInterface[]
     */
    public function loadCollection($type);

    /**
     * Loads a related model.
     *
     * @param ModelInterface $model
     * @param string $name
     *
     * @return ModelInterface
     */
    public function loadRelated(ModelInterface $model, $name);
}

// ex: ts=4 sts=4 sw=4 et:
