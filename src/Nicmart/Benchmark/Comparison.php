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


class Comparison
{
    private $a;
    private $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function ratio()
    {
        return $this->a / $this->b;
    }

    public function inverseRatio()
    {
        return $this->b / $this->a;
    }

    public function absoluteIncrease()
    {
        return $this->a - $this->b;
    }

    public function percentualIncrease()
    {
        return ($this->ratio() - 1) * 100;
    }
} 