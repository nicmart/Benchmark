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
$bench = new \Nicmart\Benchmark\VariabeSizeEngine('String interpolation vs concatenation');
$bench
    ->registerFunctional('interpolation', 'Interpolation', function($n) {
        $a = $b = str_repeat('x', $n);
        return function() use ($a, $b) {
            return "xxx $a$b xxx";
        };
    }, true)
    ->registerFunctional('concatenation', 'Concatenation', function($n) {
        $a = $b = str_repeat('x', $n);
       return function() use ($a, $b) {
           return "xxx" . $a . $b . " xxx";
       };
    }, true)
    ->registerFunctional('sprintf', 'Sprintf', function($n) {
        $a = $b = str_repeat('x', $n);
       return function() use ($a, $b) {
           return sprintf("xxx %s%s xxx", $a, $b);
       };
    }, true)
;

//$bench->benchmark(10000, 100);
$bench->progression(1000, 8, 4);
$groups[] = $bench->getResults();

//$bench->benchmark(10000, 100);
//$bench->progression(10000, 3000, 2);
//$groups[] = $bench->getResults();

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => $groups));