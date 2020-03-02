<?php
/**
 * sjaakp/yii2-helpers
 * ----------
 * Various helpers for Yii2 PHP framework
 * Version 1.0.0
 * Copyright (c) 2020
 * Sjaak Priester, Amsterdam
 * MIT License
 * https://github.com/sjaakp/yii2-helpers
 * https://sjaakpriester.nl
 */

namespace sjaakp\helpers;

abstract class Roman
{
    public static function toInt($roman)
    {
        $acc = 0;
        $ms = [];
        preg_match('/^(M{0,3})(CM|CD|D)?(C{0,3})(XC|XL|L)?(X{0,3})(IX|IV|V)?(I{0,3})$/', $roman, $ms);
        if (count($ms)) {
            $syms = array_flip(self::$symbols);
            array_shift($ms);
            foreach ($ms as $match) {
                $len = strlen($match);
                if ($len == 2 && $match[0] != $match[1])  {
                    $acc += $syms[$match];
                }
                else if ($len) {
                    $acc += $len * $syms[$match[0]];
                }
            }
        }
        return $acc;
    }

    public static function toRoman($int)
    {
        $r = '';
        $acc = intval($int);
        $syms = self::$symbols;
        end($syms);
        while ($acc > 0)    {
            $k = key($syms);
            $div = intdiv($acc, $k);
            $acc = $acc % $k;
            if ($div > 0) $r .= str_repeat(current($syms), $div);
            prev($syms);
        }
        return $r;
    }

    protected static $symbols = [
        1 => 'I',
        4 => 'IV',
        5 => 'V',
        9 => 'IX',
        10 => 'X',
        40 => 'XL',
        50 => 'L',
        90 => 'XC',
        100 => 'C',
        400 => 'CD',
        500 => 'D',
        900 => 'CM',
        1000 => 'M',
    ];
}
