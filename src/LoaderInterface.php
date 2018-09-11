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
}

// ex: ts=4 sts=4 sw=4 et:
