<?php


namespace floor12\imagenator\exception;


class InvalidWordPerPageException extends ImagenatorException
{
    protected $message = 'Words per page must be between 1 and 30.';
}
