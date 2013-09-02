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
 * Class Benchmark
 */
abstract class AbstractBenchmark
{
    /** @var BenchmarkResultsGroup */
    protected $resultsGroup;

    protected $iterationCorrections = array();

    /**
     * @param string $title
     */
    public function __construct($title = "My Benchmark")
    {
        $this->resultsGroup = new BenchmarkResultsGroup($title);
    }

    abstract protected function getSamplersForInputSize($inputSize);

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
            $set->addBenchmark(new BenchmarkResult($name, $time, $actualIterations));
        }

        return $this;
    }

    public function progression($startIterations, $startSize, $numOfBenchmarks = 4, $base = 2)
    {
        $factor = 1;
        for ($i = 1; $i <= $numOfBenchmarks; $i++) {
            $this->benchmark((int) ($startIterations / $factor), $startSize * $factor);
            $factor *= $base;
        }
    }

    public function getActualIterations($name, $iterations, $inputSize)
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

    public function getResults()
    {
        return $this->resultsGroup;
    }

    public function flush()
    {
        $results = $this->getResults();
        $this->resultsGroup = new BenchmarkResultsGroup($this->resultsGroup->title);

        return $results;
    }
}