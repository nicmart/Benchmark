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
 * Class Benchmark
 */
class Benchmark
{
    private $functions = array();
    private $title;
    private $compareWith = array();
    private $results = array();

    /**
     * @param string $title
     */
    public function __construct($title = "My Benchmark")
    {
        $this->title = $title;
    }

    /**
     * @param $name
     * @param $title
     * @param $func
     * @param bool $compare
     * @return $this
     */
    public function register($name, $title, $func, $compare = false)
    {
        $this->functions[$name] = array(
            'title' => $title,
            'func' => $func,
            'compare' => $compare
        );

        if ($compare)
            $this->compareWith[] = $name;

        return $this;
    }

    /**
     * @param int $iterations
     * @return $this
     */
    public function benchmark($iterations = 10000)
    {
        $result = array(
            'iterations' => $iterations,
            'times' => array(),
        );

        foreach ($this->functions as $name => $data) {
            $func = $this->functions[$name]['func'];
            $start = microtime(true);

            $result['midtimes'][$name] = microtime(true) - $start;

            for ($i = 0; $i < $iterations; $i++)
                $func();

            $result['times'][$name] = microtime(true) - $start;
        }

        $this->results[] = $result;

        return $this;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        $parsedResults = array();

        foreach ($this->results as $result)
        {
            $parsedResult['iterations']  = $result['iterations'];
            $parsedResult['compareWith']  = $this->getCompareWithColumns();
            $parsedResult['rows']  = array();

            foreach ($result['times'] as $name => $time)
                $parsedResult['rows'][] = $this->parseResult($name, $result['iterations'], $result['times']);

            $parsedResults[] = $parsedResult;
        }

        return $parsedResults;
    }

    /**
     * @param PHPTemplate $template
     * @return string
     */
    public function renderResults(PHPTemplate $template = null)
    {
        if (!isset($template))
            $template = new PHPTemplate;

        return $template->render(array(
            'title' => $this->title,
            'benchmarks' => $this->getResults()
        ));
    }

    private function getCompareWithColumns()
    {
        $funcs = $this->functions;
        return array_map(function($name) use ($funcs) {
            return array(
                'name' => $name,
                'title' => $funcs[$name]['title']
            );
        }, $this->compareWith);
    }

    /**
     * @param $name
     * @param array $times
     * @return array
     */
    private function parseResult($name, $iterations, array $times)
    {
        $parsedResult = array(
            'name' => $name,
            'title' => $this->functions[$name]['title'],
            'comparated' => $this->functions[$name]['compare'],
            'time' => $times[$name],
            'avg' => $times[$name] / $iterations,
            'comparisons' => array(),
            'code' => $this->getCode($name)
        );

        foreach ($this->compareWith as $comparedName) {
            $ratio = $times[$name] / $times[$comparedName];
            $inverse = $times[$comparedName] / $times[$name];
            $parsedResult['comparisons'][$comparedName] = array(
                'ratio' => $ratio,
                'inversedRatio' => $inverse,
                'percentIncrease' => ($ratio - 1) * 100,
            );
        }

        return $parsedResult;
    }

    private function getCode($name)
    {
        $parser = ClosureParser::fromClosure($this->functions[$name]['func']);

        return $parser->getCode();
    }
}