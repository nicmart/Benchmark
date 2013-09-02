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

/**
 * Class BenchmarkResultsSet
 *
 * A set of benchmarks done in same conditions (iterations and input size)
 *
 * @package Nicmart\Benchmark
 */
class BenchmarkResultsSet
{
    /** @var Benchmark[]  */
    public $benchmarks = array();
    public $iterations;
    public $inputSize;

    /** @var  BenchmarkResultsGroup */
    public $group;

    public function __construct($iterations, $inputSize = null)
    {
        $this->iterations = $iterations;
        $this->inputSize = $inputSize;
    }

    public function addBenchmark(Benchmark $benchmark)
    {
        $this->benchmarks[$benchmark->name] = $benchmark;
        $benchmark->set = $this;

        return $this;
    }
} 