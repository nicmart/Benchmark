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


class PHPTemplate
{
    private $filename;

    /**
     * @param null $filename
     */
    public function __construct($filename = null)
    {
        if (!isset($filename))
            $filename = __DIR__ . '/../../../templates/default.php';

        $this->filename = $filename;
    }

    /**
     * @param array $params
     * @return string
     */
    public function render(array $params)
    {
        extract($params);
        ob_start();

        require $this->filename;

        return ob_get_clean();
    }
} 