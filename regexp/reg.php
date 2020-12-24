<?php
$re = '/^([a-z-._0-9]+)@([a-z0-9]+[.]?)*([a-z0-9])(\.[a-z]{2,4})$/m';
$str = 'ahaloua@stud.ent.1337.ma
ali.haloua@01.com
ahaloua-yes@gmail.com
12...@1337.ma
ali_@about.ma';

preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

// Print the entire match result
var_dump($matches);

/**
 * regexp explaintion [Email validation];
 */

// Group 1
/**
 * ([a-z-._0-9]+) => [
	  means any char from `a` to `z` or `.` or `-` or `_` or any num from `0` to `9` => one or more;
  ]
 */

// Group 2
/**
 *  ([a-z0-9]+[.]?)* =>[
	  means any char from `a` to `z` or any num from `0` to `9` one or more
	  AND
	  a `.` optional (Zero or one) => All of that `*` Zero or more
  ]
 */

// Group 3
/**
 * ([a-z0-9]) only one char or num
 * ensure that the string ends with a char or a num
 */

// Group 4
/**
 * (\.[a-z]{2,4})$ => [
	  means check the domaine extention if its ends with a `.` and a word's lentgh between 2 and 4
	  in the end of the string. 
  ]
 */
