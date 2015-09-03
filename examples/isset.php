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

$bench = new \Nicmart\Benchmark\FixedSizeEngine('Isset versus null');
$args = array('a', null);

$bench
    ->register('isset', 'isset', function() use ($args) {
        $a = isset($args[0]); $b = isset($args[1]);
    }, true)
    ->register('null', 'null', function() use ($args) {
        $a = ($args[0] === null); $b = ($args[1] === null);
    }, true)
    ->register('is_null', 'is_null', function() use ($args) {
        $a = is_null($args[0]); $b = is_null($args[1]);
    })
    ->register('array_key_exists', 'array_key_exists', function() use ($args) {
        $a = array_key_exists(0, $args); $b = array_key_exists(1, $args);
    })
;

$bench->benchmark(50000);

$groups[] = $bench->getResults();

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => $groups));
