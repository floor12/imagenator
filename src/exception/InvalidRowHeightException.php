<?php


namespace floor12\imagenator\exception;


class InvalidRowHeightException extends ImagenatorException
{
    protected $message = 'Row height must be between 1 and 30.';
}
