<?php

namespace floor12\imagenator\tests;

use floor12\imagenator\exception\FontNotFoundException;
use floor12\imagenator\exception\InvalidFontSizeException;
use floor12\imagenator\exception\InvalidHexColorException;
use floor12\imagenator\exception\InvalidPositionValueException;
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


    public function testSetWordsPerPage()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setWordsPerPage(2);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidWordsPerPage()
    {
        $this->expectException(InvalidWordPerPageException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setWordsPerPage(200);
        $this->imaginator->generate($this->outputImage);
    }

    public function testSetPosition()
    {
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setPositionX(70)
            ->setPositionY(70);
        $this->imaginator->generate($this->outputImage);
        $this->checkFileGenerated();
    }


    public function testInvalidPosition()
    {
        $this->expectException(InvalidPositionValueException::class);
        $this->unlinkTestOutputImage();
        $this->imaginator = new Imagenator();
        $this->imaginator
            ->setText($this->testText)
            ->setPositionX(101)
            ->setPositionY(70);
        $this->imaginator->generate($this->outputImage);
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
