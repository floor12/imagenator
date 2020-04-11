
# floor12/imaginator


[![Build Status](https://travis-ci.org/floor12/imagenator.svg?branch=master)](https://travis-ci.org/floor12/imagenator.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/floor12/imagenator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/floor12/imagenator/?branch=master)

## Description

PHP library for generate image with a article title to use it in OpenGraph. 
It takes PNG image is a background and put text on it. The text position, size, font, color and other
parameters 

## Installation

Add this library to your project

 ```bash
 $ composer require floor12/imaginator
 ```
or add this to the `require` section of your composer.json.
 ```json
 "floor12/imaginator": "dev-master"
 ```

## Using

Pass background PNG file path to the class constructor:
```php
use floor12\imagenator\Imagenator;

$imagenator = new Imagenator('/project/images/image.png');
```

Then, you can setup any of additional parameters using some setters:
```php
$imagenator
    ->setColor('FF04AB')                    // Font color in HEX format;
    ->setFont('/fonts/SomeFont.ttf')        // Path to custom font;
    ->setFontSize('3')                      // Font size in percent of image height;
    ->setPositionX(5)                       // Horizontal text position in percent of image width;
    ->setPositionY(50)                      // Vertical text position in percent of image height;
    ->setRowHeight(7)                       // Row height in percent of image height;
    ->setText('This is an article title.')  // Text to put over the image;
    ->setWordsPerRow(4);                    // Words number per row;
```

After that, you can generate result PNG image:
```php
$imagenator->generate('/resultImage.png');
```

