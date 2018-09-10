<?php
namespace Lockr\Model;

use Exception;

use Lockr\ModelInterface;

abstract class ModelBase implements ModelInterface
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
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name)
    {
        if (!isset($this->attrs[$name])) {
            throw new Exception("unknown attribute {$name}");
        }
        return $this->attrs[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationship($name)
    {
        if (!isset($this->rels[$name])) {
            throw new Exception("unknown relationship {$name}");
        }
        return $this->rels[$name];
    }
}

// ex: ts=4 sts=4 sw=4 et:
