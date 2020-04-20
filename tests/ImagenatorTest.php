<?php

namespace floor12\imagenator\tests;

use floor12\imagenator\exception\FontNotFoundException;
use floor12\imagenator\exception\InvalidFontSizeException;
use floor12\imagenator\exception\InvalidHexColorException;
use floor12\imagenator\exception\InvalidPositionValueException;
use floor12\imagenator\exception\InvalidRowHeightException;
use floor12\imagenator\exception\InvalidWordPerPageException;
use floor12\imagenator\Imagenator;
use PHPUnit\Framework\TestCase;

class ImagenatorTest extends TestCase
{
    public $testText = 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500.';
    public $outputImage = __DIR__ . '/output/out.png';
    public $imaginator;


    public function testDefaultCreation()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator->setText($this->testText);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }

    public function testCustomImage()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator(__DIR__ . '/data/image1.png');
        $this->imaginator->setText($this->testText);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }

    public function testCustomColor()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setColor('#FF0000');
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidColor()
    {
        $this->expectException(InvalidHexColorException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setColor('#FF0000DP');
        $this->imaginator->generate($this->outputImage);
    }

    public function testCustomFont()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setFont(__DIR__ . '/../assets/Pacifico.ttf');
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidFont()
    {
        $this->expectException(FontNotFoundException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setFont('not-not-exist.ttf');
        $this->imaginator->generate($this->outputImage);
    }

    public function testFontSize()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setFontSize(2);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidFontSize()
    {
        $this->expectException(InvalidFontSizeException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setFontSize(200);
        $this->imaginator->generate($this->outputImage);
    }

    public function testSetPosition()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setMarginTopInPercents(20)
            ->setPadding(10);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidPadding()
    {
        $this->expectException(InvalidPositionValueException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setPadding(101);
        $this->imaginator->generate($this->outputImage);
    }

    public function testInvalidTopMargin()
    {
        $this->expectException(InvalidPositionValueException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setMarginTopInPercents(120);
        $this->imaginator->generate($this->outputImage);
    }

    public function testSetRowHeight()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setRowHeight(20);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }

    public function testInvalidRowHeight()
    {
        $this->expectException(InvalidRowHeightException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setRowHeight(100);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }

    public function testSbc()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText('Можно написать текст красивым по шрифтом, это будет выглядеть интересно!')
            ->setFontSize(7)
            ->setRowHeight(12)
            ->setFont(__DIR__ . '/../assets/Pacifico.ttf');
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    protected function unlinkTestOutputImage()
    {
        @unlink($this->outputImage);
    }

    protected function checkFileGenerated()
    {
        $this->assertFileExists($this->outputImage);
    }
}
