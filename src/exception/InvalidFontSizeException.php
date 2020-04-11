<?php


namespace floor12\imagenator\exception;


class InvalidFontSizeException extends ImagenatorException
{
    protected $message = 'Font size must be between 1 and 20 percents.';
}
