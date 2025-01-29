<?php
class ConfigHandler {
    private static $instance = null;
    private $reflectorConfig = null;
    private $mainConfig = null;
    
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
        $reflectorFile = '/etc/svxlink/svxlink.d/ReflectorLogic.conf';
        $mainFile = '/etc/svxlink/svxlink.conf';
        
        if (file_exists($reflectorFile)) {
            $this->reflectorConfig = parse_ini_file($reflectorFile, true, INI_SCANNER_RAW);
        }
        
        if (file_exists($mainFile)) {
            $this->mainConfig = parse_ini_file($mainFile, true, INI_SCANNER_RAW);
        }
    }
    
    public function getReflectorConfig() {
        return $this->reflectorConfig;
    }
    
    public function getMainConfig() {
        return $this->mainConfig;
    }
    
    public function getCallsign() {
        return $this->reflectorConfig['ReflectorLogic']['CALLSIGN'] ?? 'NOCALL';
    }
    
    public function getFmNetwork() {
        return $this->reflectorConfig['ReflectorLogic']['HOSTS'] ?? 'not registered';
    }

    public function getLogicModules() {
        return $this->mainConfig['GLOBAL']['LOGICS'] ?? '';
    }

    public function getActiveModules($logic) {
        $modules = [];
        if (isset($this->mainConfig[$logic]['MODULES'])) {
            $modules = explode(",", str_replace('Module', '', $this->mainConfig[$logic]['MODULES']));
        }
        return $modules;
    }
}
