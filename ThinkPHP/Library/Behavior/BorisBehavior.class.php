<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Behavior;
use Think\Think;
/**
 * Boris行为扩展
 */
class BorisBehavior {
    public function run(&$params) {
        if(IS_CLI){
            if(!function_exists('pcntl_signal'))
                E("pcntl_signal not working.\nRepl mode based on Linux OS or PHP for OS X(http://php-osx.liip.ch/)\n");
            Think::addMap(array(
                'Boris\Boris'               => VENDOR_PATH . 'Boris/Boris.php',
                'Boris\Config'              => VENDOR_PATH . 'Boris/Config.php',
                'Boris\CLIOptionsHandler'   => VENDOR_PATH . 'Boris/CLIOptionsHandler.php',
                'Boris\ColoredInspector'    => VENDOR_PATH . 'Boris/ColoredInspector.php',
                'Boris\DumpInspector'       => VENDOR_PATH . 'Boris/DumpInspector.php',
                'Boris\EvalWorker'          => VENDOR_PATH . 'Boris/EvalWorker.php',    
                'Boris\ExportInspector'     => VENDOR_PATH . 'Boris/ExportInspector.php',
                'Boris\Inspector'           => VENDOR_PATH . 'Boris/Inspector.php',
                'Boris\ReadlineClient'      => VENDOR_PATH . 'Boris/ReadlineClient.php',
                'Boris\ShallowParser'       => VENDOR_PATH . 'Boris/ShallowParser.php',
            ));
            $boris      =   new \Boris\Boris(">>> ");
            $config     =   new \Boris\Config();
            $config->apply($boris, true);
            $options    =   new \Boris\CLIOptionsHandler();
            $options->handle($boris);
            $boris->onStart(sprintf("echo 'REPL MODE FOR THINKPHP \nTHINKPHP_VERSION: %s, PHP_VERSION: %s, BORIS_VERSION: %s\n';", THINK_VERSION, PHP_VERSION, $boris::VERSION));
            $boris->start();
        }
    }
}
