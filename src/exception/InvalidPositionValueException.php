<?php


namespace floor12\imagenator\exception;


class InvalidPositionValueException extends ImagenatorException
{
    protected $message = 'Position percent value must be between 1 and 100.';
}
