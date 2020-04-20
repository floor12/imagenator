
# floor12/imagenator


[![Build Status](https://travis-ci.org/floor12/imagenator.svg?branch=master)](https://travis-ci.org/floor12/imagenator.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/floor12/imagenator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/floor12/imagenator/?branch=master)

## Описание

PHP библиотека для генерации изображений с заголовком статьи и брендированным фоном для использования в open graph тегах.
Библиотека принимает png изображение в качестве фона и накладывает на него текст с заданными параметрам:
Положение текста, шрифт, цвет, количество слов в строке и междстрочный интервал. Кроме того, библиотека корректно обрабатывает
"висячие предлоги" - то есть переносит слова короче 3х букв на следующую строку, если она существует.

Для примера:

![FileInputWidget](https://raw.githubusercontent.com/floor12/imagenator/master/tests/data/example3.png)

![FileInputWidget](https://raw.githubusercontent.com/floor12/imagenator/master/tests/data/example4.png)

## Установка

Добавьте библиотеку  в ваш проект:

 ```bash
 $ composer require floor12/imagenator
 ```
или добавьте библиотеку в секуцию `require` вашего composer.json.
 ```json
 "floor12/imagenator": "dev-master"
 ```

## Использование

Передайте фоновое изображение в формате PNG в конструктор класса:
```php
use floor12\imagenator\Imagenator;

$imagenator = new Imagenator('/project/images/image.png');
```

После этого, вы можете задать необходимые параметры используя сеттеры:
```php
$imagenator
    ->setColor('FF04AB')                    // Цвет текста в HEX;
    ->setFont('/fonts/SomeFont.ttf')        // Путь к шрифту;
    ->setFontSize(3)                        // Размер шрифта в процентах от высото изображения;
    ->setPadding(5)                         // Отсутпы от края картинки в процентах по оси X в процентах от ширины изображения;
    ->setMarginTopInPercents(50)            // Отступ от верхнего края в процентах от высоты изображения;
    ->setRowHeight(7)                       // Высота строк в процентах от высото изображения;
    ->setText('This is an article title.'); // Текст, который необходимо поместить на изображения
```
Далее можно сохранить результат в виде PNG изображения:
```php
$imagenator->generate('/resultImage.png');
```

