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
            $start = microtime(true);
            for ($i = 0; $i < $iterations; $i++)
                $func();

            $time = microtime(true) - $start;
            $set->addBenchmark(new BenchmarkResult($name, $time));
        }

        return $this;
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