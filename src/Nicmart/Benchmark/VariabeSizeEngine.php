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
 * Class VariableSizeEngine
 */
class VariabeSizeEngine extends Engine
{
    private $functionals = array();

    /**
     * @param string $name                          The name of the sampling function
     * @param string $title                         The title of the sampling function
     * @param callable $functional                  A functional that returns other functions
     * @param bool $compare                         Include the sampling functions in comparisons?
     * @param int|callable $iterationCorrection     Correcting function
     *
     * @return $this
     */
    public function registerFunctional($name, $title, $functional, $compare = false, $iterationCorrection = 1)
    {
        $this->functionals[$name] = $functional;
        $this->resultsGroup->funcTitles[$name] = $title;

        if ($compare)
            $this->resultsGroup->compareWith[] = $name;

        $this->resultsGroup->funcs[$name] = $functional(1);
        $this->iterationCorrections[$name] = $iterationCorrection;
        $this->resultsGroup->iterationsCorrections[$name] = $iterationCorrection;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSamplersForInputSize($inputSize)
    {
        return array_map(function($functional) use($inputSize) {
            return $functional($inputSize);
        }, $this->functionals);
    }
}