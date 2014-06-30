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

$bench = new \Nicmart\Benchmark\FixedSizeEngine("Arrays vs Classes");

$storage = array();
$storage2 = array();

class Test {
    public $field0;
    public $field1;
    public $field2;
    public $field3;
    public $field4;
    public $field5;
    public $field6;
    public $field7;
    public $field8;
    public $field9;
}

$bench
    ->register('plain array', 'Plain Array', function() {
        $a["field0"] = 0;
        $a["field1"] = 1;
        $a["field2"] = 2;
        $a["field3"] = 3;
        $a["field4"] = 4;
        $a["field5"] = 5;
        $a["field6"] = 6;
        $a["field7"] = 7;
        $a["field8"] = 8;
        $a["field9"] = 9;
    }, true)
    ->register('class', 'Class', function() {
        $a = new Test;
        $a->field0 = 0;
        $a->field1 = 1;
        $a->field2 = 2;
        $a->field3 = 3;
        $a->field4 = 4;
        $a->field5 = 5;
        $a->field6 = 6;
        $a->field7 = 7;
        $a->field8 = 8;
        $a->field9 = 9;
    }, true)
/*    ->register('plain array', 'Plain Array', function() use (&$storage) {
        $a["field0"] = 0;
        $a["field1"] = 1;
        $a["field2"] = 2;
        $a["field3"] = 3;
        $a["field4"] = 4;
        $a["field5"] = 5;
        $a["field6"] = 6;
        $a["field7"] = 7;
        $a["field8"] = 8;
        $a["field9"] = 9;
        $storage[] = $a;
    }, true)*/
    /*->register('class', 'Class', function() use (&$storage) {
        $a = new Test;
        $a->field0 = 0;
        $a->field1 = 1;
        $a->field2 = 2;
        $a->field3 = 3;
        $a->field4 = 4;
        $a->field5 = 5;
        $a->field6 = 6;
        $a->field7 = 7;
        $a->field8 = 8;
        $a->field9 = 9;
        $storage[] = $a;
    }, true)*/
;

$bench->benchmark(10000);

$groups[] = $bench->getResults();

$template = new \Nicmart\Benchmark\PHPTemplate;
echo $template->render(array('groups' => $groups));
