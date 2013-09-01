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

$bench = new \Nicmart\Benchmark\Benchmark;
$args = array('This Is The string To be Converted');

$bench
    ->register('direct', 'Direct call', function() use ($args) {
        strtoupper($args[0]);
    }, true)
    ->register('cuf', 'call_user_func', function() use ($args) {
        call_user_func('strtoupper', $args[0]);
    }, true)
    ->register('cufa', 'call_user_func_array', function() use ($args) {
        call_user_func_array('strtoupper', $args);
    })
;

$bench->benchmark(10);
$bench->benchmark(100);
$bench->benchmark(1000);

echo $bench->renderResults();
