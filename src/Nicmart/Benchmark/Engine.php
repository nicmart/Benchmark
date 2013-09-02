<?php
/*
 * This file is part of Benchmark.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nicmart\Benchmark;

/**
 * Class Engine
 */
abstract class Engine
{
    /** @var BenchmarkResultsGroup */
    protected $resultsGroup;

    /**
     * @var callable[]
     */
    protected $iterationCorrections = array();

    /**
     * @param string $title
     */
    public function __construct($title = "My Benchmark")
    {
        $this->resultsGroup = new BenchmarkResultsGroup($title);
    }

    /**
     * This will return a set of callables for the given input size.
     *
     * @param int|null $inputSize
     * @return callable[]
     */
    abstract protected function getSamplersForInputSize($inputSize);

    /**
     * @return BenchmarkResultsGroup
     */
    public function getResults()
    {
        return $this->resultsGroup;
    }

    /**
     * Perform a set of benchmarks.
     *
     * @param int $iterations       The number of iterations. Cam be corrected by $iterationCorrections
     * @param int|null $inputSize   The size of the input. Null for unspecified size.
     *
     * @return $this
     */
    public function benchmark($iterations = 10000, $inputSize = null)
    {
        $set = new BenchmarkResultsSet($iterations, $inputSize);
        $this->resultsGroup->addSet($set);

        foreach ($this->getSamplersForInputSize($inputSize) as $name => $func) {
            $actualIterations = $this->getActualIterations($name, $iterations, $inputSize);

            $start = microtime(true);
            for ($i = 0; $i < $actualIterations; $i++)
                $func();
            $time = microtime(true) - $start;

            $set->addBenchmark(new Benchmark($name, $time, $actualIterations));
        }

        return $this;
    }

    /**
     * The aim of this function is to perform a multiple set of benchmarks with different iterations and
     * input sizes, but trying to keep the same execution time for each set.
     *
     * For each iteration input size is multiplied by $base and $iterations are devided by $base
     * (in respect of the previous benchmark)
     *
     * @param int $startIterations  This is intended as the number of iterations of a linear-time algorithm
     * @param int $startSize        The input size to start with
     * @param int $numOfBenchmarks  The total number of benchmarks to launch.
     * @param int $base             The base to multiply $startSize with
     */
    public function progression($startIterations, $startSize, $numOfBenchmarks = 4, $base = 2)
    {
        $factor = 1;
        for ($i = 1; $i <= $numOfBenchmarks; $i++) {
            $this->benchmark((int) ($startIterations / $factor), $startSize * $factor);
            $factor *= $base;
        }
    }

    /**
     * @param string $name          The name of the function
     * @param int $iterations       The iteration number
     * @param int|null $inputSize   The input size
     * @return mixed
     */
    private function getActualIterations($name, $iterations, $inputSize)
    {
        if (!isset($this->iterationCorrections[$name]) || !isset($inputSize) || $this->iterationCorrections[$name] === 1)
            return $iterations;

        $correction = $this->iterationCorrections[$name];

        if (!is_callable($correction)) {
            $correction = function ($n) use ($correction) {
                return pow($n, $correction);
            };
        }

        return max(1, (int) ($inputSize / $correction($inputSize) * $iterations));
    }
}