<?php
namespace Lockr;

class ApiError
{
    /** @var string $id */
    private $id = null;

    /** @var array $links */
    private $links = null;

    /** @var string $status */
    private $status = null;

    /** @var string $code */
    private $code = null;

    /** @var string $title */
    private $title = null;

    /** @var string $detail */
    private $detail = null;

    /** @var array $source */
    private $source = null;

    /** @var array $meta */
    private $meta = null;

    /**
     * @param array $error
     */
    public function __construct(array $error)
    {
        if (isset($error['id'])) {
            $this->id = $error['id'];
        }
        if (isset($error['links'])) {
            $this->links = $error['links'];
        }
        if (isset($error['code'])) {
            $this->code = $error['code'];
        }
        if (isset($error['status'])) {
            $this->status = $error['status'];
        }
        if (isset($error['title'])) {
            $this->title = $error['title'];
        }
        if (isset($error['detail'])) {
            $this->detail = $error['detail'];
        }
        if (isset($error['source'])) {
            $this->source = $error['source'];
        }
        if (isset($error['meta'])) {
            $this->meta = $error['meta'];
        }
    }

    /**
     * @returns string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @returns array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @returns string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @returns string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @returns string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @returns array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @returns array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}

// ex: ts=4 sts=4 sw=4 et:
