<?php

class Logger {
    
    const EMERGENCY = LOG_EMERG;
    const ALERT = LOG_ALERT;
    const CRITICAL = LOG_CRIT;
    const ERROR = LOG_ERR;
    const WARNING = LOG_WARNING;
    const NOTICE = LOG_NOTICE;
    const INFOS = LOG_INFO;
    const DEBUG = LOG_DEBUG;

    private static $_instance;
    private $level;

    public static function init($config){
        Logger::$_instance = new Logger();
        if($config->level){
            Logger::$_instance->level =  static::getLevel($config->level);
        }   
        
        static::_init();
        openlog("BO", LOG_PID | LOG_PERROR, LOG_USER);
    }
    
    
    public static function log($message, $level = LOG_INFO){
        syslog($level, $message);
    }
    
    public static function debug($message){
        if(static::$_instance->level <= static::DEBUG) {
            syslog(static::DEBUG, $message);
        }
    }

    public static function info($message){
        if(static::$_instance->level <= static::INFOS) {
            syslog(static::INFOS, $message);
        }
    }

    public static function notice($message){
        if(static::$_instance->level <= static::NOTICE) {
            syslog(static::NOTICE, $message);
        }
    }

    public static function warning($message){
        if(static::$_instance->level <= static::WARNING) {
            syslog(static::WARNING, $message);
        }
    }

    public static function error($message){
        if(static::$_instance->level <= static::ERROR) {
            syslog(static::ERROR, $message);
        }
    }

    public static function alert($message){
        if(static::$_instance->level <= static::ALERT) {
            syslog(static::ALERT, $message);
        }
    }

    public static function critical($message){
        if(static::$_instance->level <= static::CRITICAL) {
            syslog(static::CRITICAL, $message);
        }
    }

    public static function emergency($message){
        if(static::$_instance->level <= static::EMERGENCY) {
            syslog(static::EMERGENCY, $message);
        }
    }

    
    private static function _init(){
    
    }
    
    private static function getLevel($type){
        switch ($type) {
            case "EMERGENCY":
                return static::EMERGENCY;

            case "ALERT":
                return static::ALERT ;

            case "INFO":
                return static::INFOS;

            case "CRITICAL":
                return static::CRITICAL;

            case "NOTICE":
                return static::NOTICE;

            case "DEBUG":
                return static::DEBUG;

            case "WARNING":
                return static::WARNING;

            case "ERROR":
                return static::ERROR;
            
            default:
                return static::EMERGENCY;
        }
    }
    
    
}
    

