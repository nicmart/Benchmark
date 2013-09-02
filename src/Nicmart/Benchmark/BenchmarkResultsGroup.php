<?php
/**
 * This file is part of Benchmark
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Nicolò Martini <nicmartnic@gmail.com>
 */

namespace Nicmart\Benchmark;
use Jeremeamia\SuperClosure\ClosureParser;

/**
 * Class BenchmarkResultsGroup
 *
 * A group of BenchmarkResultsSet
 *
 * @package Nicmart\Benchmark
 */
class BenchmarkResultsGroup
{
    public $title;
    /** @var BenchmarkResultsSet[]  */
    public $sets = array();
    public $compareWith = array();
    public $funcs = array();
    public $funcTitles = array();
    public $iterationsCorrections = array();

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function addSet(BenchmarkResultsSet $benchmarkSet)
    {
        $this->sets[] = $benchmarkSet;
        $benchmarkSet->group = $this;

        return $this;
    }

    public function getCode($name)
    {
        $parser = ClosureParser::fromClosure($this->funcs[$name]);

        return $parser->getCode();
    }

    public function orderOfGrowth($name)
    {
        $ratios = array();
        for ($i = 1; $i < count($this->sets); $i++) {
            $bench1 = $this->sets[$i]->benchmarks[$name];
            $bench2 = $this->sets[$i-1]->benchmarks[$name];

            if ($bench1->getInputSize() / $bench2->getInputSize() > 1.1)
                $ratios[] = log(
                    $bench1->getAverage() / $bench2->getAverage(),
                    $bench1->getInputSize() / $bench2->getInputSize()
                );
        }

        return array_sum($ratios) / count($ratios);
    }

    public function ordersOfGrowth()
    {
        $orders = array();

        foreach ($this->funcs as $name => $func) {
            $orders[$name] = $this->orderOfGrowth($name);
        }

        return $orders;
    }
} 