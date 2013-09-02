<?php
/**
 * This file is part of Benchmark
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Nicolò Martini <nicmartnic@gmail.com>
 */

include '../vendor/autoload.php';
ini_set('xdebug.var_display_max_depth', '10');

function func() {}

$func = 'func';

$bench = new \Nicmart\Benchmark\SizeBenchmark;
$args = array_fill(0, 5, null);;

$bench
    ->registerFunctional('strtolower', 'strtolower', function($n) {
        $string = str_repeat("a", $n);
        return function() use ($string) { return strtolower($string); };
    }, true)
    ->registerFunctional('mb_strtolower', 'mb_strtolower', function($n) {
        $string = str_repeat("a", $n);
        return function() use ($string) { return mb_strtolower($string); };
    }, true)
;

$bench->benchmark(100000, 64);
$bench->benchmark(100000, 128);
$bench->benchmark(100000, 256);

//var_dump($bench->getResults());

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => array($bench->getResults())));