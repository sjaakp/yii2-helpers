Yii2-helpers
============

This is a collection of various helpers for the 
[Yii 2.0](https://yiiframework.com/ "Yii") PHP Framework.
All of them are abstract classes having only static functions
in the `sjaakp\helpers` namespace.

## Installation ##

The preferred way to install **yii2-helpers** is through [Composer](https://getcomposer.org/). 
Either add the following to the require section of your `composer.json` file:

`"sjaakp/yii2-helpers": "*"` 

Or run:

`composer require sjaakp/yii2-helpers "*"` 

You can manually install **yii2-helpers** by
 [downloading the source in ZIP-format](https://github.com/sjaakp/yii2-helpers/archive/master.zip).

## Roman ##

Functions to convert an `int` into a [Roman numeral](https://en.wikipedia.org/wiki/Roman_numerals)
vice versa.

- **static function toInt($roman)** Converts Roman numeral string `$roman` 
    to `int`. Invalid `$roman` will be converted to `0`.
- **static function toRoman($int)** Converts `$int` to Roman numeral 
  (`1 <= $int <= 3999`). Invalid `$int` will be converted to empty string.
  
