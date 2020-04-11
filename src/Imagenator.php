<?php

namespace floor12\imagenator;

use floor12\imagenator\exception\FontNotFoundException;
use floor12\imagenator\exception\ImageNotFoundException;
use floor12\imagenator\exception\InvalidFontSizeException as InvalidFontSizeExceptionAlias;
use floor12\imagenator\exception\InvalidHexColorException;
use floor12\imagenator\exception\InvalidPositionValueException;
use floor12\imagenator\exception\InvalidWordPerPageException;

class Imagenator
{
    const DEFAULT_IMAGE = __DIR__ . '/../assets/default.png';
    /**
     * @var string Path to TTF font file
     */
    protected $font = __DIR__ . '/../assets/Rubik.ttf';
    /**
     * @var string
     */
    protected $text = "Dont forget to put some text here :-)";
    /**
     * @var false|resource
     */
    protected $image;
    /**
     * @var int
     */
    protected $imageWidth;
    /**
     * @var int
     */
    protected $imageHeight;
    /**
     * @var int
     */
    protected $wordsPerPage = 7;
    /**
     * @var int
     */
    protected $stringHeight = 8;
    /**
     * @var int
     */
    protected $textPositionPercentY = 50;
    /**
     * @var int
     */
    protected $textPositionPercentX = 5;
    /**
     * @var int Default color is white
     */
    private $textColor = '16777215';
    /**
     * @var int
     */
    private $fontSizeInPercents = 5;

    /**
     * Imagenator constructor.
     * @param string $backgroundImagePath
     * @throws ImageNotFoundException
     */
    public function __construct(string $backgroundImagePath = self::DEFAULT_IMAGE)
    {
        $this->loadImage($backgroundImagePath);
    }

    /**
     * @param string $backgroundImagePath
     * @throws ImageNotFoundException
     */
    protected function loadImage(string $backgroundImagePath)
    {
        if (!file_exists($backgroundImagePath))
            throw new ImageNotFoundException();
        $this->image = imagecreatefrompng($backgroundImagePath);
        list($this->imageWidth, $this->imageHeight) = getimagesize($backgroundImagePath);
    }

    /**
     * @param string $resultImagePath
     * @return bool
     * @throws FontNotFoundException
     */
    public function generate(string $resultImagePath): bool
    {
        $this->putTextToImage();
        return $this->saveImage($resultImagePath);
    }

    /**
     * @param string $pathToSave
     * @return bool
     */
    protected function saveImage(string $pathToSave): bool
    {
        return imagepng($this->image, $pathToSave) && imagedestroy($this->image);
    }

    /**
     * @throws FontNotFoundException
     */
    protected function putTextToImage(): void
    {
        if (!file_exists($this->font))
            throw new FontNotFoundException();

        $wordsArray = explode(' ', $this->text);
        $strings = array_chunk($wordsArray, $this->wordsPerPage);
        $positionStartY = $this->imageHeight / 100 * $this->textPositionPercentY;
        $stringHeightInPx = $this->imageHeight / 100 * $this->stringHeight;
        $fontSizeInPx = $this->imageHeight / 100 * $this->fontSizeInPercents;
        $positionX = $this->imageWidth / 100 * $this->textPositionPercentX;
        foreach ($strings as $stringNumber => $wordsArray) {
            $string = implode(' ', $wordsArray);
            $positionY = $positionStartY + $stringNumber * $stringHeightInPx;
            imagettftext($this->image, $fontSizeInPx, 0, $positionX, $positionY, $this->textColor, $this->font, $string);
        }

    }

    /**
     * @param string $text
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $font
     * @return self
     */
    public function setFont(string $font): self
    {
        $this->font = $font;
        return $this;
    }

    /**
     * @param string $colorInHex
     * @return self
     * @throws InvalidHexColorException
     */
    public function setColor(string $colorInHex): self
    {
        $colorInHex = str_replace('#', '', $colorInHex);

        if (!ctype_xdigit($colorInHex) || strlen($colorInHex) !== 6)
            throw new InvalidHexColorException();

        $red = hexdec(substr($colorInHex, 0, 2));
        $green = hexdec(substr($colorInHex, 2, 2));
        $blue = hexdec(substr($colorInHex, 4, 2));
        $this->textColor = imagecolorallocate($this->image, $red, $green, $blue);
        return $this;
    }

    /**
     * @param int $size
     * @return Imagenator
     * @throws InvalidFontSizeExceptionAlias
     */
    public function setFontSize(int $size)
    {
        if ($size < 1 || $size > 20)
            throw new InvalidFontSizeExceptionAlias();
        $this->fontSizeInPercents = $size;
        return $this;
    }

    /**
     * @param int $wordsPerPage
     * @return Imagenator
     * @throws InvalidWordPerPageException
     */
    public function setWordsPerPage(int $wordsPerPage)
    {
        if ($wordsPerPage < 1 || $wordsPerPage > 30)
            throw new InvalidWordPerPageException();
        $this->wordsPerPage = $wordsPerPage;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidPositionValueException
     */
    public function setPositionX(int $percent)
    {
        if ($percent < 1 || $percent > 100)
            throw new InvalidPositionValueException();
        $this->textPositionPercentX = $percent;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidPositionValueException
     */
    public function setPositionY(int $percent)
    {
        if ($percent < 1 || $percent > 100)
            throw new InvalidPositionValueException();
        $this->textPositionPercentY = $percent;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidRowHeightException
     */
    public function setRowHeight(int $percent)
    {
        if ($percent < 1 || $percent > 30)
            throw new InvalidRowHeightException();
        $this->rowHeight = $percent;
        return $this;
    }
}
