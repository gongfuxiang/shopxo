<?php
/**********************************************************\
|                                                          |
|                          hprose                          |
|                                                          |
| Official WebSite: http://www.hprose.com/                 |
|                   http://www.hprose.net/                 |
|                   http://www.hprose.org/                 |
|                                                          |
\**********************************************************/

/**********************************************************\
 *                                                        *
 * HproseHttpServer.php                                   *
 *                                                        *
 * hprose http server library for php5.                   *
 *                                                        *
 * LastModified: Nov 13, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

require_once('HproseCommon.php');
require_once('HproseIO.php');

class HproseHttpServer {
    private $errorTable = array(E_ERROR => 'Error',
                                E_WARNING => 'Warning',
                                E_PARSE => 'Parse Error',
                                E_NOTICE => 'Notice',
                                E_CORE_ERROR => 'Core Error',
                                E_CORE_WARNING => 'Core Warning',
                                E_COMPILE_ERROR => 'Compile Error',
                                E_COMPILE_WARNING => 'Compile Warning',
                                E_USER_ERROR => 'User Error',
                                E_USER_WARNING => 'User Warning',
                                E_USER_NOTICE => 'User Notice',
                                E_STRICT => 'Run-time Notice',
                                E_RECOVERABLE_ERROR => 'Error');
    private $functions;
    private $funcNames;
    private $resultModes;
    private $simpleModes;
    private $debug;
    private $crossDomain;
    private $P3P;
    private $get;
    private $input;
    private $output;
    private $error;
    private $filter;
    private $simple;
    public $onBeforeInvoke;
    public $onAfterInvoke;
    public $onSendHeader;
    public $onSendError;
    public function __construct() {
        $this->functions = array();
        $this->funcNames = array();
        $this->resultModes = array();
        $this->simpleModes = array();
        $this->debug = false;
        $this->crossDomain = false;
        $this->P3P = false;
        $this->get = true;
        $this->filter = NULL;
        $this->simple = false;
        $this->error_types = E_ALL & ~E_NOTICE;
        $this->onBeforeInvoke = NULL;
        $this->onAfterInvoke = NULL;
        $this->onSendHeader = NULL;
        $this->onSendError = NULL;
    }
    /*
      __filterHandler & __errorHandler must be public,
      however we should never call them directly.
    */
    public function __filterHandler($data) {
        if (preg_match('/<b>.*? error<\/b>:(.*?)<br/', $data, $match)) {
            if ($this->debug) {
                $error = preg_replace('/<.*?>/', '', $match[1]);
            }
            else {
                $error = preg_replace('/ in <b>.*<\/b>$/', '', $match[1]);
            }
            $data = HproseTags::TagError .
                 HproseFormatter::serialize(trim($error), true) .
                 HproseTags::TagEnd;
        }
        if ($this->filter) $data = $this->filter->outputFilter($data);
        return $data;
    }
    public function __errorHandler($errno, $errstr, $errfile, $errline) {
        if ($this->debug) {
            $errstr .= " in $errfile on line $errline";
        }
        $this->error = $this->errorTable[$errno] . ": " . $errstr;
        $this->sendError();
        return true;
    }
    private function sendHeader() {
        if ($this->onSendHeader) {
            call_user_func($this->onSendHeader);
        }
        header("Content-Type: text/plain");
        if ($this->P3P) {
            header('P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi ' .
                   'CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL ' .
                   'UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"');
        }
        if ($this->crossDomain) {
            if (array_key_exists('HTTP_ORIGIN', $_SERVER) && $_SERVER['HTTP_ORIGIN'] != "null") {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
                header("Access-Control-Allow-Credentials: true");  
            }
            else {
                header('Access-Control-Allow-Origin: *');
            }
        }
    }
    private function sendError() {
        if ($this->onSendError) {
            call_user_func($this->onSendError, $this->error);
        }
        ob_clean();
        $this->output->write(HproseTags::TagError);
        $writer = new HproseSimpleWriter($this->output);
        $writer->writeString($this->error);
        $this->output->write(HproseTags::TagEnd);
        ob_end_flush();
    }
    private function doInvoke() {
        $simpleReader = new HproseSimpleReader($this->input);
        do {
            $functionName = $simpleReader->readString(true);
            $aliasName = strtolower($functionName);
            $resultMode = HproseResultMode::Normal;
            if (array_key_exists($aliasName, $this->functions)) {
                $function = $this->functions[$aliasName];
                $resultMode = $this->resultModes[$aliasName];
                $simple = $this->simpleModes[$aliasName];
            }
            elseif (array_key_exists('*', $this->functions)) {
                $function = $this->functions['*'];
                $resultMode = $this->resultModes['*'];
                $simple = $this->resultModes['*'];
            }
            else {
                throw new HproseException("Can't find this function " . $functionName . "().");
            }
            if ($simple === NULL) $simple = $this->simple;
            $writer = ($simple ? new HproseSimpleWriter($this->output) : new HproseWriter($this->output));
            $args = array();
            $byref = false;
            $tag = $simpleReader->checkTags(array(HproseTags::TagList,
                                            HproseTags::TagEnd,
                                            HproseTags::TagCall));
            if ($tag == HproseTags::TagList) {
                $reader = new HproseReader($this->input);
                $args = &$reader->readList();
                $tag = $reader->checkTags(array(HproseTags::TagTrue,
                                                HproseTags::TagEnd,
                                                HproseTags::TagCall));
                if ($tag == HproseTags::TagTrue) {
                    $byref = true;
                    $tag = $reader->checkTags(array(HproseTags::TagEnd,
                                                    HproseTags::TagCall));
                }
            }
            if ($this->onBeforeInvoke) {
                call_user_func($this->onBeforeInvoke, $functionName, $args, $byref);
            }
            if (array_key_exists('*', $this->functions) && ($function === $this->functions['*'])) {
                $arguments = array($functionName, &$args);
            }
            elseif ($byref) {
                $arguments = array();
                for ($i = 0; $i < count($args); $i++) {
                    $arguments[$i] = &$args[$i];
                }
            }
            else {
                $arguments = $args;
            }
            $result = call_user_func_array($function, $arguments);
            if ($this->onAfterInvoke) {
                call_user_func($this->onAfterInvoke, $functionName, $args, $byref, $result);
            }
            // some service functions/methods may echo content, we need clean it
            ob_clean();
            if ($resultMode == HproseResultMode::RawWithEndTag) {
                $this->output->write($result);
                return;
            }
            elseif ($resultMode == HproseResultMode::Raw) {
                $this->output->write($result);
            }
            else {
                $this->output->write(HproseTags::TagResult);
                if ($resultMode == HproseResultMode::Serialized) {
                    $this->output->write($result);
                }
                else {
                    $writer->reset();
                    $writer->serialize($result);
                }
                if ($byref) {
                    $this->output->write(HproseTags::TagArgument);
                    $writer->reset();
                    $writer->writeList($args);
                }
            }
        } while ($tag == HproseTags::TagCall);
        $this->output->write(HproseTags::TagEnd);
        ob_end_flush();
    }
    private function doFunctionList() {
        $functions = array_values($this->funcNames);
        $writer = new HproseSimpleWriter($this->output);
        $this->output->write(HproseTags::TagFunctions);
        $writer->writeList($functions);
        $this->output->write(HproseTags::TagEnd);
        ob_end_flush();
    }
    private function getDeclaredOnlyMethods($class) {
        $all = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $inherit = get_class_methods($parent_class);
            $result = array_diff($all, $inherit);
        }
        else {
            $result = $all;
        }
        return $result;
    }
    public function addMissingFunction($function, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        $this->addFunction($function, '*', $resultMode, $simple);
    }
    public function addFunction($function, $alias = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        if (is_callable($function)) {
            if ($alias === NULL) {
                if (is_string($function)) {
                    $alias = $function;
                }
                else {
                    $alias = $function[1];
                }
            }
            if (is_string($alias)) {
                $aliasName = strtolower($alias);
                $this->functions[$aliasName] = $function;
                $this->funcNames[$aliasName] = $alias;
                $this->resultModes[$aliasName] = $resultMode;
                $this->simpleModes[$aliasName] = $simple;
            }
            else {
                throw new HproseException('Argument alias is not a string');
            }
        }
        else {
            throw new HproseException('Argument function is not a callable variable');
        }
    }
    public function addFunctions($functions, $aliases = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        $aliases_is_null = ($aliases === NULL);
        $count = count($functions);
        if (!$aliases_is_null && $count != count($aliases)) {
            throw new HproseException('The count of functions is not matched with aliases');
        }
        for ($i = 0; $i < $count; $i++) {
            $function = $functions[$i];
            if ($aliases_is_null) {
                $this->addFunction($function, NULL, $resultMode, $simple);
            }
            else {
                $this->addFunction($function, $aliases[$i], $resultMode, $simple);
            }
        }
    }
    public function addMethod($methodname, $belongto, $alias = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        if ($alias === NULL) {
            $alias = $methodname;
        }
        if (is_string($belongto)) {
            $this->addFunction(array($belongto, $methodname), $alias, $resultMode, $simple);
        }
        else {
            $this->addFunction(array(&$belongto, $methodname), $alias, $resultMode, $simple);
        }
    }
    public function addMethods($methods, $belongto, $aliases = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        $aliases_is_null = ($aliases === NULL);
        $count = count($methods);
        if (is_string($aliases)) {
            $aliasPrefix = $aliases;
            $aliases = array();
            foreach ($methods as $name) {
                $aliases[] = $aliasPrefix . '_' . $name;
            }
        }
        if (!$aliases_is_null && $count != count($aliases)) {
            throw new HproseException('The count of methods is not matched with aliases');
        }
        for ($i = 0; $i < $count; $i++) {
            $method = $methods[$i];
            if (is_string($belongto)) {
                $function = array($belongto, $method);
            }
            else {
                $function = array(&$belongto, $method);
            }
            if ($aliases_is_null) {
                $this->addFunction($function, $method, $resultMode, $simple);
            }
            else {
                $this->addFunction($function, $aliases[$i], $resultMode, $simple);
            }
        }
    }
    public function addInstanceMethods($object, $class = NULL, $aliasPrefix = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        if ($class === NULL) $class = get_class($object);
        $this->addMethods($this->getDeclaredOnlyMethods($class), $object, $aliasPrefix, $resultMode, $simple);
    }
    public function addClassMethods($class, $execclass = NULL, $aliasPrefix = NULL, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        if ($execclass === NULL) $execclass = $class;
        $this->addMethods($this->getDeclaredOnlyMethods($class), $execclass, $aliasPrefix, $resultMode, $simple);
    }
    public function add() {
        $args_num = func_num_args();
        $args = func_get_args();
        switch ($args_num) {
            case 1: {
                if (is_callable($args[0])) {
                    return $this->addFunction($args[0]);
                }
                elseif (is_array($args[0])) {
                    return $this->addFunctions($args[0]);
                }
                elseif (is_object($args[0])) {
                    return $this->addInstanceMethods($args[0]);
                }
                elseif (is_string($args[0])) {
                    return $this->addClassMethods($args[0]);
                }
                break;
            }
            case 2: {
                if (is_callable($args[0]) && is_string($args[1])) {
                    return $this->addFunction($args[0], $args[1]);
                }
                elseif (is_string($args[0])) {
                    if (is_string($args[1]) && !is_callable(array($args[1], $args[0]))) {
                        if (class_exists($args[1])) {
                            return $this->addClassMethods($args[0], $args[1]);
                        }
                        else {
                            return $this->addClassMethods($args[0], NULL, $args[1]);
                        }
                    }
                    return $this->addMethod($args[0], $args[1]);
                }
                elseif (is_array($args[0])) {
                    if (is_array($args[1])) {
                        return $this->addFunctions($args[0], $args[1]);
                    }
                    else {
                        return $this->addMethods($args[0], $args[1]);
                    }
                }
                elseif (is_object($args[0])) {
                    return $this->addInstanceMethods($args[0], $args[1]);
                }
                break;
            }
            case 3: {
                if (is_callable($args[0]) && is_null($args[1]) && is_string($args[2])) {
                    return $this->addFunction($args[0], $args[2]);
                }
                elseif (is_string($args[0]) && is_string($args[2])) {
                    if (is_string($args[1]) && !is_callable(array($args[0], $args[1]))) {
                        return $this->addClassMethods($args[0], $args[1], $args[2]);
                    }
                    else {
                        return $this->addMethod($args[0], $args[1], $args[2]);
                    }
                }
                elseif (is_array($args[0])) {
                    if (is_null($args[1]) && is_array($args[2])) {
                        return $this->addFunctions($args[0], $args[2]);
                    }
                    else {
                        return $this->addMethods($args[0], $args[1], $args[2]);
                    }
                }
                elseif (is_object($args[0])) {
                    return $this->addInstanceMethods($args[0], $args[1], $args[2]);
                }
                break;
            }
            throw new HproseException('Wrong arguments');
        }
    }
    public function isDebugEnabled() {
        return $this->debug;
    }
    public function setDebugEnabled($enable = true) {
        $this->debug = $enable;
    }
    public function isCrossDomainEnabled() {
        return $this->crossDomain;
    }
    public function setCrossDomainEnabled($enable = true) {
        $this->crossDomain = $enable;
    }
    public function isP3PEnabled() {
        return $this->P3P;
    }
    public function setP3PEnabled($enable = true) {
        $this->P3P = $enable;
    }
    public function isGetEnabled() {
        return $this->get;
    }
    public function setGetEnabled($enable = true) {
        $this->get = $enable;
    }
    public function getFilter() {
        return $this->filter;
    }
    public function setFilter($filter) {
        $this->filter = $filter;
    }
    public function getSimpleMode() {
        return $this->simple;
    }
    public function setSimpleMode($simple = true) {
        $this->simple = $simple;
    }
    public function getErrorTypes() {
        return $this->error_types;
    }
    public function setErrorTypes($error_types) {
        $this->error_types = $error_types;
    }
    public function handle() {
        if (!isset($HTTP_RAW_POST_DATA)) $HTTP_RAW_POST_DATA = file_get_contents("php://input");
        if ($this->filter) $HTTP_RAW_POST_DATA = $this->filter->inputFilter($HTTP_RAW_POST_DATA);
        $this->input = new HproseStringStream($HTTP_RAW_POST_DATA);
        $this->output = new HproseFileStream(fopen('php://output', 'wb'));
        set_error_handler(array(&$this, '__errorHandler'), $this->error_types);
        ob_start(array(&$this, "__filterHandler"));
        ob_implicit_flush(0);
        ob_clean();
        $this->sendHeader();
        if (($_SERVER['REQUEST_METHOD'] == 'GET') and $this->get) {
            return $this->doFunctionList();
        }
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                switch ($this->input->getc()) {
                    case HproseTags::TagCall: return $this->doInvoke();
                    case HproseTags::TagEnd: return $this->doFunctionList();
                    default: throw new HproseException("Wrong Request: \r\n" . $HTTP_RAW_POST_DATA);
                }
            }
            catch (Exception $e) {
                $this->error = $e->getMessage();
                if ($this->debug) {
                    $this->error .= "\nfile: " . $e->getFile() .
                                    "\nline: " . $e->getLine() .
                                    "\ntrace: " . $e->getTraceAsString();
                }
                $this->sendError();
            }
        }
        $this->input->close();
        $this->output->close();
    }
    public function start() {
        $this->handle();
    }
}
?>