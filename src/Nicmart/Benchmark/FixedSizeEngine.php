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
use Jeremeamia\SuperClosure\ClosureParser;

/**
 * Class FixedSizeEngine
 */
class FixedSizeEngine extends Engine
{
    private $functions = array();

    /**
     * @param string $name      The name of the function
     * @param string $title     The title
     * @param callable $func    The sampling function
     * @param bool $compare     Include in comparisons?
     * @return $this
     */
    public function register($name, $title, $func, $compare = false)
    {
        $this->resultsGroup->funcs[$name]
            = $this->functions[$name] = $func;
        $this->resultsGroup->funcTitles[$name] = $title;

        if ($compare)
            $this->resultsGroup->compareWith[] = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSamplersForInputSize($inputSize)
    {
        return $this->functions;
    }
}