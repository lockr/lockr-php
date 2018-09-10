<?php
namespace Lockr;

interface LoaderInterface
{
    /**
     * Creates a new model.
     *
     * @param string $collection
     * @param array $data
     *
     * @returns mixed
     */
    public function create($collection, array $data);
}

// ex: ts=4 sts=4 sw=4 et:
