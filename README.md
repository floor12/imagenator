
# floor12/imagenator

[![Build Status](https://travis-ci.org/floor12/imagenator.svg?branch=master)](https://travis-ci.org/floor12/imagenator.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/floor12/imagenator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/floor12/imagenator/?branch=master)

*Этот файл доступен на [русском языке](README_RU.md).*

## Description

PHP library for generate image with a article title to use it in OpenGraph. 
It takes PNG image is a background and put text on it. The text position, size, font, color and other
parameters. In addition, the library correctly processes "hanging prepositions": it wrap words shorter than 3 letters to
 the next line, if it exists.

For example:

![FileInputWidget](https://raw.githubusercontent.com/floor12/imagenator/master/tests/data/example1.png)

![FileInputWidget](https://raw.githubusercontent.com/floor12/imagenator/master/tests/data/example2.png)


## Installation


Add this library to your project

 ```bash
 $ composer require floor12/imagenator
 ```
or add this to the `require` section of your composer.json.
 ```json
 "floor12/imagenator": "dev-master"
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
    ->setFontSize(3)                        // Font size in percent of image height;
    ->setPositionX(5)                       // Horizontal text position in percent of image width;
    ->setPositionY(50)                      // Vertical text position in percent of image height;
    ->setRowHeight(7)                       // Row height in percent of image height;
    ->setWordsPerRow(4)                     // Words number per row;
    ->setText('This is an article title.'); // Text to put over the image;
```

After that, you can generate result PNG image:
```php
$imagenator->generate('/resultImage.png');
```

