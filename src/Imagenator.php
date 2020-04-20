<?php

namespace floor12\imagenator;

use floor12\imagenator\exception\FontNotFoundException;
use floor12\imagenator\exception\ImageNotFoundException;
use floor12\imagenator\exception\InvalidFontSizeException;
use floor12\imagenator\exception\InvalidHexColorException;
use floor12\imagenator\exception\InvalidPositionValueException;
use floor12\imagenator\exception\InvalidRowHeightException;

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
    protected $rowHeight = 8;
    /**
     * @var int
     */
    protected $marginTopInPercents = 50;
    /**
     * @var int
     */
    protected $paddingLeftRightInPercents = 5;
    /**
     * @var int Default color is white
     */
    private $textColor = '16777215';
    /**
     * @var int
     */
    private $fontSizeInPercents = 5;
    /**
     * @var array
     */
    private $rows = [];
    /**
     * @var float|int
     */
    private $positionX;
    /**
     * @var float|int
     */
    private $positionStartY;
    /**
     * @var float|int
     */
    private $rowHeightInPx;
    /**
     * @var float|int
     */
    private $fontSizeInPx;

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
        $this->calculateParams();
        $this->prepareRows();
        $this->putRowsToImage();
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

    protected function calculateParams()
    {
        $this->positionStartY = (int)($this->imageHeight / 100 * $this->marginTopInPercents);
        $this->rowHeightInPx = (int)($this->imageHeight / 100 * $this->rowHeight);
        $this->fontSizeInPx = (int)($this->imageHeight / 100 * $this->fontSizeInPercents);
        $this->positionX = (int)($this->imageWidth / 100 * $this->paddingLeftRightInPercents);
    }

    /**
     * @throws FontNotFoundException
     */
    protected function prepareRows(): void
    {
        if (!file_exists($this->font))
            throw new FontNotFoundException();

        $wordsArray = explode(' ', $this->text);
        $validRowWidth = $this->imageWidth - $this->positionX * 2;
        $currentRow = 0;

        foreach ($wordsArray as $word) {
            $testRow = isset($this->rows[$currentRow]) ? array_merge($this->rows[$currentRow], [$word]) : [$word];
            $imageBox = imagettfbbox($this->fontSizeInPx, 0, $this->font, implode('', $testRow));
            if ($imageBox[2] >= $validRowWidth) {
                $lastWord = end($this->rows[$currentRow]);
                if (mb_strlen($lastWord) <= 2) {
                    $this->rows[$currentRow + 1][] = array_pop($this->rows[$currentRow]);
                }
                $this->rows[++$currentRow][] = $word;
            } else {
                $this->rows[$currentRow][] = $word;
            }
        }
    }

    /**
     *
     */
    protected function putRowsToImage()
    {
        foreach ($this->rows as $rowNumber => $row) {
            $string = implode(' ', $row);
            $positionY = (int)$this->positionStartY + ($rowNumber * $this->rowHeightInPx);
            imagettftext($this->image, $this->fontSizeInPx, 0, $this->positionX, $positionY, $this->textColor, $this->font, $string);
        }
    }

    /**
     * @param string $text
     * @return self
     */
    public
    function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $font
     * @return self
     */
    public
    function setFont(string $font): self
    {
        $this->font = $font;
        return $this;
    }

    /**
     * @param string $colorInHex
     * @return self
     * @throws InvalidHexColorException
     */
    public
    function setColor(string $colorInHex): self
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
     * @throws InvalidFontSizeException
     */
    public
    function setFontSize(int $size)
    {
        if ($size < 1 || $size > 20)
            throw new InvalidFontSizeException();
        $this->fontSizeInPercents = $size;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidPositionValueException
     */
    public
    function setPadding(int $percent)
    {
        if ($percent < 1 || $percent > 100)
            throw new InvalidPositionValueException();
        $this->paddingLeftRightInPercents = $percent;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidPositionValueException
     */
    public function setMarginTopInPercents(int $percent)
    {
        if ($percent < 1 || $percent > 100)
            throw new InvalidPositionValueException();
        $this->marginTopInPercents = $percent;
        return $this;
    }

    /**
     * @param int $percent
     * @return Imagenator
     * @throws InvalidRowHeightException
     */
    public
    function setRowHeight(int $percent)
    {
        if ($percent < 1 || $percent > 30)
            throw new InvalidRowHeightException();
        $this->rowHeight = $percent;
        return $this;
    }
}
