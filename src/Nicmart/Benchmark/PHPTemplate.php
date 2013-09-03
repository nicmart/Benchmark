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

    public function scientific($number, $significant = 3)
    {
        if ($number == 0)
            return 0;
        if (abs($number) >= 0.01 && abs($number) < 10000) {
            return $this->thousands($this->round($number, $significant));
        }

        $exp = $this->exponent($number);
        $mantissa = $this->thousands($this->round(pow(10, -$exp) * $number, $significant));

        return sprintf("%s &times; 10<sup>%s</sup>", $mantissa, $exp);
    }

    public function thousands($number)
    {
        $integerPart = (int) $number;

        $fracPart = '';
        if (($decPos = strpos($number, '.')) !== false)
            $fracPart = trim(substr((string) $number, $decPos), '0');

        return number_format($integerPart, 0) . $fracPart;
        ;
    }

    /**
     * round a number specifying the desired number of meaningful digits
     * @param $number
     * @param int $significant
     * @param bool $mantainIntegerPart
     * @return string
     */
    public function round($number, $significant = 2, $mantainIntegerPart = false)
    {
        $firstPosition = $this->exponent($number);
        $precision = $mantainIntegerPart ? max($significant - $firstPosition, 0) : $significant - $firstPosition;
        return round($number, $precision - 1);
        return rtrim(preg_replace("/(\\..*?)0+/", "$1", number_format($number, $significant)), '.');
    }

    private function exponent($number)
    {
        if ($number == 0)
            return 0;
        $log = log10(abs($number));
        return (int) $log + ($log < 0 ? -1 : 0);
    }

    public function modal(Benchmark $benchmark)
    {
        return $this->renderFile('modal.php', array('benchmark' => $benchmark));
    }

    private function renderFile($file, array $params = array())
    {
        extract($params);
        ob_start();
        require $this->baseDir . $file;
        return ob_get_clean();
    }
} 