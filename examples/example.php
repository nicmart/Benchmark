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

$bench = new \Nicmart\Benchmark\Benchmark;
$args = array_fill(0, 5, null);;

$bench
    ->register('direct', 'Direct call', function() use ($args) {
        func($args[0], $args[1], $args[2], $args[3], $args[4]);
    }, true)
    ->register('cuf', 'call_user_func', function() use ($args) {
        call_user_func('func', $args[0], $args[1], $args[2], $args[3], $args[4]);
    }, true)
    ->register('cufa', 'call_user_func_array', function() use ($args) {
        call_user_func_array('func', $args);
    })
;

$bench->benchmark(100);
$bench->benchmark(1000);

echo $bench->renderResults();
