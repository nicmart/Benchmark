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

class Benchmark
{
    public $name;
    public $time;
    public $iterations;

    /** @var  BenchmarkResultsSet */
    public $set;

    /**
     * @param string $name        The name of the benchmark
     * @param float $time         The time
     * @param int $iterations     The number of iterations done
     */
    public function __construct($name, $time, $iterations)
    {
        $this->name = $name;
        $this->time = $time;
        $this->iterations = $iterations;
    }

    /**
     * Get the average execution time
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->time / $this->iterations;
    }

    /**
     * Get an array of comparisons with
     * @return Comparison[]
     */
    public function getComparisons()
    {
        $comparisons = array();

        foreach ($this->set->group->compareWith as $name) {
            $benchmark = $this->set->benchmarks[$name];
            $comparisons[$name] = new Comparison(
                $this->getAverage(), $benchmark->getAverage()
            );
        }

        return $comparisons;
    }

    /**
     * Get the title of the benchmark, picking it from the parent group
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->set->group->funcTitles[$this->name];
    }

    /**
     * Get the code of the sampling function
     *
     * @return string
     */
    public function getCode()
    {
        return $this->set->group->getCode($this->name);
    }

    /**
     * Get the input size
     *
     * @return null|int
     */
    public function getInputSize()
    {
        return $this->set->inputSize;
    }
}