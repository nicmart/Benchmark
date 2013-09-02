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

class BenchmarkResult
{
    public $name;
    public $time;

    /** @var  BenchmarkResultsSet */
    public $set;

    public function __construct($name, $time)
    {
        $this->name = $name;
        $this->time = $time;
    }

    public function getAverage()
    {
        return $this->time / $this->getIterations();
    }

    public function getComparisons()
    {
        $comparisons = array();

        foreach ($this->set->benchmarks as $name => $benchmark) {
            $comparisons[$name] = new Comparison(
                $this->getAverage(), $benchmark->getAverage()
            );
        }

        return $comparisons;
    }

    public function getTitle()
    {
        return $this->set->group->funcTitles[$this->name];
    }

    public function getCode()
    {
        return $this->set->group->getCode($this->name);
    }

    public function getIterations()
    {
        return max(1, (int) ($this->set->iterations / pow($this->set->inputSize, $this->set->group->iterationsCorrections[$this->name] - 1)));
    }

    public function getInputSize()
    {
        return $this->set->inputSize;
    }
}