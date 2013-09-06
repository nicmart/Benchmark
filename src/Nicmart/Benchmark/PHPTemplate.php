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


use Numbers\Number;

class PHPTemplate
{
    private $filename;
    private $baseDir;

    /**
     * @param null $baseDir
     * @param null $filename
     */
    public function __construct($baseDir = null, $filename = null)
    {
        if (!isset($baseDir))
            $this->baseDir = __DIR__ . '/../../../templates/';

        if (!isset($filename))
            $filename = 'default.php';

        $this->filename = $filename;
    }

    /**
     * @param array $params
     * @return string
     */
    public function render(array $params)
    {
        return $this->renderFile($this->filename, $params);
    }

    public function modal(Benchmark $benchmark)
    {
        return $this->renderFile('modal.php', array('benchmark' => $benchmark));
    }

    /**
     * @param $number
     * @return Number
     */
    public function n($number)
    {
        return new Number($number);
    }

    private function renderFile($file, array $params = array())
    {
        extract($params);
        ob_start();
        require $this->baseDir . $file;
        return ob_get_clean();
    }
} 