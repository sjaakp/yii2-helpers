<?php
/**
 * sjaakp/yii2-helpers
 * ----------
 * Various helpers for Yii2 PHP framework
 * Version 1.1.0
 * Copyright (c) 2015-2020
 * Sjaak Priester, Amsterdam
 * MIT License
 * https://github.com/sjaakp/yii2-helpers
 * https://sjaakpriester.nl
 */

namespace sjaakp\helpers;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

abstract class Fragment    {  // declare abstract, we don't want instances (trick from Zend)

    /**
     * @param string $subject
     * @param string $pattern, PHP regex pattern
     * @param int $radius, tentative number of characters before and after match
     * @param string $affix, text before and after fragments, if appropriate
     * @param array|false $highlightOptions, HTML options for the highlight <mark> tag
     *          if false, no highlighting occurs
     * @return string
     * Distills one or more relevant fragments from $subject. A fragment is considered relevant if it
     *      contains a part that matches $pattern.
     * Size of the fragments will be 2 * $radius, plus the length of the matched part, if possible.
     */
    public static function fragment($subject, $pattern, $radius = 50, $affix = '&hellip;', $highlightOptions = []) {
        $stringLength = strlen($subject);
        $parts = preg_split($pattern, $subject, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
        $partsCount = count($parts);
        if ($partsCount == 0) return '';

        $baseFrags = [];
        $iStart = 1 - preg_match($pattern, $parts[0][0]);

        for ($i = $iStart; $i < $partsCount; $i += 2)   {
            $fragBegin = 0;                 // defaults
            $fragEnd = $stringLength - 1;

            $match = $parts[$i][0];
            $matchLength = strlen($match);

            if ($parts[$i][1] > $stringLength / 2)  {   // second half of string, build fragment first to the right
                if ($i < $partsCount - 1)   {           // skip if this is the last part
                    $offset = $parts[$i + 1][1] + $radius;  // start searching from beginning of next part + $radius

                    if ($offset < $stringLength)    {
                        $found = strpos($subject, ' ', $offset); // search space
                        if ($found !== false) $fragEnd = $found;
                    }
                }
                if ($i >= 0)    {       // skip if this the first part
                    $offset = $fragEnd - 2 * $radius - $matchLength;
                    if ($offset > 0)    { // no negative offset; means offset from end

                        // read User Contributed Notes for strrpos documentation!
                        /* @link http://php.net/manual/en/function.strrpos.php */
                        $found = strrpos($subject, ' ', $offset - $stringLength); // search space backwards
                        if ($found !== false) $fragBegin = $found;
                    }
                }
            }
            else    {   // first half of string

                if ($i >= 0) {       // skip if this the first part
                    $offset = $parts[$i][1] - $radius; // start searching from beginning of this part - $radius
                    if ($offset > 0)    {   // no negative offset
                        $found = strrpos($subject, ' ', $offset - $stringLength); // search space backwards
                        if ($found !== false) $fragBegin = $found;
                    }
                }
                if ($i < $partsCount - 1) {           // skip if this is the last part
                    $offset = $fragBegin + 2 * $radius + $matchLength;
                    if ($offset < $stringLength) {
                        $found = strpos($subject, ' ', $offset); // search space
                        if ($found !== false) $fragEnd = $found;
                    }
                }
            }
            $baseFrags[] = [
                'start' => $fragBegin,
                'end' => $fragEnd
            ];
        }

        // base fragments may overlap; accumulate them to final fragments
        $fragments = [];
        $fragStart = $fragEnd = -1;
        $prefix = $suffix = $affix;

        foreach ($baseFrags as $baseFrag)   {
            $bfStart = $baseFrag['start'];
            $bfEnd = $baseFrag['end'];
            if ($fragStart < 0)    {   // first iteration
                $fragStart = $bfStart;
                $fragEnd = $bfEnd;

                if ($bfStart == 0) $prefix = '';
            }
            else    {
                if ($bfStart > $fragEnd) {   // no overlap
                    $fragments[] = [        // so push accumulated fragment
                        'start' => $fragStart,
                        'end' => $fragEnd
                    ];
                    $fragStart = $bfStart;    // and start new accumulation
                }
                $fragEnd = $bfEnd;
            }
        }
        if ($fragStart >= 0)   {
            $fragments[] = [        // still an accumulated fragment to push
                'start' => $fragStart,
                'end' => $fragEnd
            ];
            if ($fragEnd >= $stringLength - 1) $suffix = '';
        }

        $fragments = array_map(function($v) use ($subject) {
            $len = $v['end'] - $v['start'] + 1;
            return substr($subject, $v['start'], $len);
        }, $fragments);

        $r = $prefix . implode($affix, $fragments) . $suffix;
        if ($highlightOptions !== false) $r = self::highlight($r, $pattern, $highlightOptions);
        return $r;
    }

    /**
     * @param string $subject
     * @param string $pattern, PHP regex pattern
     * @param array $options, HTML options for the <mark> tag
     * @return string
     */
    public static function highlight($subject, $pattern, $options = [])    {
        return preg_replace_callback($pattern, function($m) use ($options) {
            $tag = ArrayHelper::remove($options, 'tag', 'mark');
            return Html::tag($tag, $m[0], $options);
        }, $subject);
    }

    /**
     * @param string $lucenePattern, Lucene query string
     * @return string PHP regex pattern
     * This doesn't solve all problems, but quite a lot!
     */
    public static function phpPattern($lucenePattern)  {
        $lucenePattern = trim($lucenePattern);
        if (strpos($lucenePattern, '"') !== false) {
            $lucenePattern = trim($lucenePattern, '"');
        }
        else {
            $lucenePattern = str_replace([
                ' AND ',
                ' ',
                '?',
                '*',
            ], [
                '|',
                '|',
                '\w',
                '\w*',

            ], $lucenePattern);
        }

        return "/($lucenePattern)/i";
    }

}
