<?php
namespace Lockr\Exception;

use Exception;

class LockrApiException extends Exception
{
    /** @var array $errors */
    private $errors;

    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
        $msg = $this->buildMessage();
        parent::__construct($msg);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function buildMessage()
    {
        $msgs = [];
        foreach ($this->errors as $err) {
            $msgs[] = "[{$err->getTitle()}] {$err->getDetail()}";
        }
        return implode("\n", $msgs);
    }
}

// ex: ts=4 sts=4 sw=4 et:
