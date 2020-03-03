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

## Fragment ##

Distill one or more relevant fragments from a string. A fragment is considered 
relevant if it contains a part that matches a regex pattern.

- **static function fragment($subject, $pattern, $radius = `50`, $affix = `'&hellip;'`, $highlightOptions = `[ ]`)**
    - $subject: `string`
    - $pattern:  PHP [regex pattern](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php).
    - $radius: `int` Tentative number of characters before and after match.
    Size of the fragments will be `2 * $radius`, plus the length of the matched part, if possible.
    - $affix: `string` Text before and after fragments, if appropriate.
    - $highlightOptions: `array|false` HTML options for the highlight tag.
    Key `'tag'` defines tag type; if not set, the tag type will be `'mark'`. 
    If false, no highlighting occurs.
    - Return: `string`.

- **public static function phpPattern($lucenePattern)**
Convert a [Zend Lucene query string](https://framework.zend.com/manual/1.12/en/zend.search.lucene.query-api.html)
to an acceptable PHP query string.


## Roman ##

Functions to convert an `int` into a [Roman numeral](https://en.wikipedia.org/wiki/Roman_numerals)
vice versa.

- **static function toInt($roman)** Converts Roman numeral string `$roman` 
    to `int`. Invalid `$roman` will be converted to `0`.
- **static function toRoman($int)** Converts `$int` to Roman numeral 
  (`1 <= $int <= 3999`). Invalid `$int` will be converted to empty string.
  
