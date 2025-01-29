<?php
class ConfigHandler {
    private static $instance = null;
    private $mainConfig = null;
    private $reflectorConfig = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->loadConfigs();
    }
    
    private function loadConfigs() {
        $mainFile = '/etc/svxlink/svxlink.conf';
        $reflectorFile = '/etc/svxlink/svxlink.d/ReflectorLogic.conf';
        
        if (file_exists($mainFile)) {
            $this->mainConfig = parse_ini_file($mainFile, true, INI_SCANNER_RAW);
        }
        if (file_exists($reflectorFile)) {
            $this->reflectorConfig = parse_ini_file($reflectorFile, true, INI_SCANNER_RAW);
        }
    }
    
    public function getLogicModules() {
        return $this->mainConfig['GLOBAL']['LOGICS'] ?? '';
    }
    
    public function getActiveModules($logic) {
        $logicFile = '/etc/svxlink/svxlink.d/' . $logic . '.conf';
        if (file_exists($logicFile)) {
            $logicConfig = parse_ini_file($logicFile, true, INI_SCANNER_RAW);
            if (isset($logicConfig[$logic]['MODULES'])) {
                return explode(",", str_replace('Module', '', $logicConfig[$logic]['MODULES']));
            }
        }
        return [];
    }
    
    public function getCallsign() {
        return $this->reflectorConfig['ReflectorLogic']['CALLSIGN'] ?? 'NOCALL';
    }
    
    public function getFmNetwork() {
        return $this->reflectorConfig['ReflectorLogic']['HOSTS'] ?? 'not registered';
    }
}
