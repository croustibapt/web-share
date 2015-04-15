<?php

class ShareException extends Exception {
    protected $statusCode;
    protected $validationErrors;

    public function __construct($statusCode = 0, $code = 0, $message = "", $validationErrors = NULL) {
        parent::__construct($message, $code);

        $this->statusCode = $statusCode;
        $this->validationErrors = $validationErrors;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getValidationErrors() {
        return $this->validationErrors;
    }
}