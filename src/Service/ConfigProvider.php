<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-05-18
 * Time: 12:11
 */

namespace Service;


use \Exception;

class ConfigProvider
{
    private $configFile;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }
    public function getParameter($parameter){
        $config = $this->parceConfig();
        if (!isset($config[$parameter])){
            throw new Exception(sprintf('Config parameter %s does not exist', $parameter));
        }
        return $config[$parameter];
    }

    private function parceConfig(){
        return json_decode(file_get_contents($this->configFile), true);
    }
}