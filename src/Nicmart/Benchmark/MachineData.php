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


class MachineData
{
    public static $opcacheExtenstions = array(
        'apc' => array('Alternative PHP Cache (APC)', 'apc.enabled'),
        'eaccelerator' => array('eAccelerator', 'eaccelerator.enabled'),
        'xcache' => array('XCache', null),
        'Zend OPcache' => array('Zend OPcache', 'opcache.enable')
    );

    public function phpVersion()
    {
        return phpversion();
    }

    /**
     * @return array
     */
    public function opcodeCacheData()
    {
        $cacheData = array(
        );

        foreach (static::$opcacheExtenstions as $name => $data)
        {
            list($title, $iniSetting) = $data;
            if ($this->hasCache($name, $iniSetting)) {
                $cacheData['title'] = $title;
                $cacheData['name'] = $name;
                $ref = new \ReflectionExtension($name);

                $cacheData['version'] = $ref->getVersion();
                $cacheData['settings'] = $ref->getINIEntries();

                break;
            }
        }

        return $cacheData;
    }

    private function hasCache($name, $iniSetting)
    {
        return extension_loaded($name) && ($iniSetting === null || ini_get($iniSetting));
    }
} 