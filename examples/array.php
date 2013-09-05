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
$groups = array();
$bench = new \Nicmart\Benchmark\VariabeSizeEngine('Array versus SplFixedArray - Fill up');
$bench
    ->registerFunctional('array', 'array', function($n) {
        return function() use ($n) {
            $ary = array();
            for ($i = 0; $i < $n; $i++)
                $ary[$i] = null;
        };
    }, true)
    ->registerFunctional('splfixed', 'SplFixedArray', function($n) {
        return function() use ($n) {
            $ary = new SplFixedArray($n);
            for ($i = 0; $i < $n; $i++)
                $ary[$i] = null;
        };
    }, true)
;

//$bench->benchmark(10000, 100);
$bench->progression(10000, 400, 2);
$groups[] = $bench->getResults();

$bench = new \Nicmart\Benchmark\VariabeSizeEngine('Array versus SplFixedArray - Access items');
$bench
    ->registerFunctional('array', 'array', function($n) {
        $ary = array();
        for ($i = 0; $i < $n; $i++)
            $ary[$i] = $i;

        return function() use ($ary) {
            for ($i = 0; $i < count($ary); $i++)
                $j = $ary[$i];
        };
    }, true)
    ->registerFunctional('splfixed', 'SplFixedArray', function($n) {
        $ary = new SplFixedArray($n);
        return function() use ($ary) {
            for ($i = 0; $i < count($ary); $i++)
                $j = $ary[$i];
        };
    }, true)
;

//$bench->benchmark(10000, 100);
//$bench->progression(10000, 3000, 2);
//$groups[] = $bench->getResults();

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => $groups));