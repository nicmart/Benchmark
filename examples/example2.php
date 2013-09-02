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

$bench->benchmark(1000, 64);
$bench->benchmark(1000, 128);
$bench->benchmark(1000, 256);

//var_dump($bench->getResults());
$groups = array($bench->flush());

$bench = new \Nicmart\Benchmark\SizeBenchmark;
$bench
    ->registerFunctional('linear', 'Linear loop', function($n) {
        return function() use ($n) {
            for ($i = 0; $i < $n; $i++) {}
        };
    }, true)
    ->registerFunctional('square', 'Square loop', function($n) {
        return function() use ($n) {
            for ($i = 0; $i < $n; $i++)
                for ($j = 0; $j < $n; $j++) {}
        };
    }, true, 0.5)
    ->registerFunctional('cubic', 'Cubic loop', function($n) {
        return function() use ($n) {
            for ($i = 0; $i < $n; $i++)
                for ($j = 0; $j < $n; $j++)
                    for ($h = 0; $h < $n; $h++) {}
        };
    }, true, 0.333)
;

//$bench->benchmark(100000, 32);
$bench->benchmark(1000, 64);
$bench->benchmark(1000, 128);
$bench->benchmark(1000, 256);
#$bench->benchmark(10000, 256);
//$bench->benchmark(1, 4 * 1024);

$groups[] = $bench->flush();

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => $groups));