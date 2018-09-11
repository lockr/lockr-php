<?php
namespace Lockr;

interface LoaderInterface
{
    /**
     * Creates a new model.
     *
     * @param array $data
     *
     * @returns mixed
     */
    public function create(array $data);

    /**
     * Loads a model.
     *
     * @param string $type
     * @param string $id
     *
     * @returns mixed
     */
    public function load($type, $id);
}

// ex: ts=4 sts=4 sw=4 et:
