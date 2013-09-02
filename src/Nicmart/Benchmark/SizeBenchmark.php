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
class SizeBenchmark extends AbstractBenchmark
{
    private $functionals = array();

    /**
     * @param $name
     * @param $title
     * @param $functional
     * @param bool $compare
     * @return $this
     */
    public function registerFunctional($name, $title, $functional, $compare = false)
    {
        $this->functionals[$name] = $functional;
        $this->resultsGroup->funcTitles[$name] = $title;

        if ($compare)
            $this->resultsGroup->compareWith[] = $name;

        $this->resultsGroup->funcs[$name] = $functional(1);

        return $this;
    }

    protected function getSamplersForInputSize($inputSize)
    {
        return array_map(function($functional) use($inputSize) {
            return $functional($inputSize);
        }, $this->functionals);
    }
}