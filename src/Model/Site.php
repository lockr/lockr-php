<?php
namespace Lockr\Model;

class Site
{
    /** @var string $id */
    private $id;

    /** @var array $attrs */
    private $attrs;

    /** @var array $rels */
    private $rels;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->attrs = isset($data['attributes']) ? $data['attributes'] : [];
        $this->rels = isset($data['relationships'])
            ? $data['relationships']
            : [];
    }

    /**
     * @returns string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @returns string
     */
    public function getlabel()
    {
        return $this->attrs['label'];
    }
}

// ex: ts=4 sts=4 sw=4 et:
