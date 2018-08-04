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
 * HproseHttpClient.php                                   *
 *                                                        *
 * hprose http client library for php5.                   *
 *                                                        *
 * LastModified: Nov 12, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

require_once('HproseCommon.php');
require_once('HproseIO.php');
require_once('HproseClient.php');

abstract class HproseBaseHttpClient extends HproseClient {
    protected $host;
    protected $path;
    protected $secure;
    protected $proxy;
    protected $header;
    protected $timeout;
    protected $keepAlive;
    protected $keepAliveTimeout;
    protected static $cookieManager = array();
    static function hproseKeepCookieInSession() {
        $_SESSION['HPROSE_COOKIE_MANAGER'] = self::$cookieManager;
    }
    public static function keepSession() {
        if (array_key_exists('HPROSE_COOKIE_MANAGER', $_SESSION)) {
            self::$cookieManager = $_SESSION['HPROSE_COOKIE_MANAGER'];
        }
        register_shutdown_function(array('HproseBaseHttpClient', 'hproseKeepCookieInSession'));
    }
    protected function setCookie($headers) {
        foreach ($headers as $header) {
            @list($name, $value) = explode(':', $header, 2);
            if (strtolower($name) == 'set-cookie' ||
                strtolower($name) == 'set-cookie2') {
                $cookies = explode(';', trim($value));
                $cookie = array();
                list($name, $value) = explode('=', trim($cookies[0]), 2);
                $cookie['name'] = $name;
                $cookie['value'] = $value;
                for ($i = 1; $i < count($cookies); $i++) {
                    list($name, $value) = explode('=', trim($cookies[$i]), 2);
                    $cookie[strtoupper($name)] = $value;
                }
                // Tomcat can return SetCookie2 with path wrapped in "
                if (array_key_exists('PATH', $cookie)) {
                    $cookie['PATH'] = trim($cookie['PATH'], '"');
                }
                else {
                    $cookie['PATH'] = '/';
                }
                if (array_key_exists('EXPIRES', $cookie)) {
                    $cookie['EXPIRES'] = strtotime($cookie['EXPIRES']);
                }
                if (array_key_exists('DOMAIN', $cookie)) {
                    $cookie['DOMAIN'] = strtolower($cookie['DOMAIN']);
                }
                else {
                    $cookie['DOMAIN'] = $this->host;
                }
                $cookie['SECURE'] = array_key_exists('SECURE', $cookie);
                if (!array_key_exists($cookie['DOMAIN'], self::$cookieManager)) {
                    self::$cookieManager[$cookie['DOMAIN']] = array();
                }
                self::$cookieManager[$cookie['DOMAIN']][$cookie['name']] = $cookie;
            }
        }
    }
    protected abstract function formatCookie($cookies);
    protected function getCookie() {
        $cookies = array();
        foreach (self::$cookieManager as $domain => $cookieList) {
            if (strpos($this->host, $domain) !== false) {
                $names = array();
                foreach ($cookieList as $cookie) {
                    if (array_key_exists('EXPIRES', $cookie) && (time() > $cookie['EXPIRES'])) {
                        $names[] = $cookie['name'];
                    }
                    elseif (strpos($this->path, $cookie['PATH']) === 0) {
                        if ((($this->secure && $cookie['SECURE']) ||
                             !$cookie['SECURE']) && !is_null($cookie['value'])) {
                            $cookies[] = $cookie['name'] . '=' . $cookie['value'];
                        }
                    }
                }
                foreach ($names as $name) {
                    unset(self::$cookieManager[$domain][$name]);
                }
            }
        }
        return $this->formatCookie($cookies);
    }
    public function __construct($url = '') {
        parent::__construct($url);
        $this->header = array('Content-type' => 'application/hprose');
    }
    public function useService($url = '', $namespace = '') {
        $serviceProxy = parent::useService($url, $namespace);
        if ($url) {
            $url = parse_url($url);
            $this->secure = (strtolower($url['scheme']) == 'https');
            $this->host = strtolower($url['host']);
            $this->path = $url['path'];
            $this->timeout = 30000;
            $this->keepAlive = false;
            $this->keepAliveTimeout = 300;
        }
        return $serviceProxy;
    }
    public function setHeader($name, $value) {
        $lname = strtolower($name);
        if ($lname != 'content-type' &&
            $lname != 'content-length' &&
            $lname != 'host') {
            if ($value) {
                $this->header[$name] = $value;
            }
            else {
                unset($this->header[$name]);
            }
        }
    }
    public function setProxy($proxy = NULL) {
        $this->proxy = $proxy;
    }
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }
    public function getTimeout() {
        return $this->timeout;
    }
    public function setKeepAlive($keepAlive = true) {
        $this->keepAlive = $keepAlive;
    }
    public function getKeepAlive() {
        return $this->keeepAlive;
    }
    public function setKeepAliveTimeout($timeout) {
        $this->keepAliveTimeout = $timeout;
    }
    public function getKeepAliveTimeout() {
        return $this->keepAliveTimeout;
    }
}

if (class_exists('SaeFetchurl')) {
    class HproseHttpClient extends HproseBaseHttpClient {
        protected function formatCookie($cookies) {
            if (count($cookies) > 0) {
                return implode('; ', $cookies);
            }
            return '';
        }
        protected function send($request) {
            $f = new SaeFetchurl();
            $cookie = $this->getCookie();
            if ($cookie != '') {
                $f->setHeader("Cookie", $cookie);
            }
            if ($this->keepAlive) {
                $f->setHeader("Connection", "keep-alive");
                $f->setHeader("Keep-Alive", $this->keepAliveTimeout);
            }
            else {
                $f->setHeader("Connection", "close");
            }
            foreach ($this->header as $name => $value) {
                $f->setHeader($name, $value);
            }
            $f->setMethod("post");
            $f->setPostData($request);
            $f->setConnectTimeout($this->timeout);        
            $f->setSendTimeout($this->timeout);
            $f->setReadTimeout($this->timeout);
            $response = $f->fetch($this->url);
            if ($f->errno()) {
                throw new HproseException($f->errno() . ": " . $f->errmsg());
            }
            $http_response_header = $f->responseHeaders(false);
            $this->setCookie($http_response_header);
            return $response;
        }
    }
}
elseif (function_exists('curl_init')) {
    class HproseHttpClient extends HproseBaseHttpClient {
        private $curl;
        protected function formatCookie($cookies) {
            if (count($cookies) > 0) {
                return "Cookie: " . implode('; ', $cookies);
            }
            return '';
        }
        public function __construct($url = '') {
            parent::__construct($url);
            $this->curl = curl_init();
        }
        protected function send($request) {
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
            curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($this->curl, CURLOPT_POST, TRUE);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request);
            $headers_array = array($this->getCookie(),
                                    "Content-Length: " . strlen($request));
            if ($this->keepAlive) {
                $headers_array[] = "Connection: keep-alive";
                $headers_array[] = "Keep-Alive: " . $this->keepAliveTimeout;
            }
            else {
                $headers_array[] = "Connection: close";
            }
            foreach ($this->header as $name => $value) {
                $headers_array[] = $name . ": " . $value;
            }
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers_array);
            if ($this->proxy) {
                curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy);
            }
            if (defined(CURLOPT_TIMEOUT_MS)) {
                curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, $this->timeout);
            }
            else {
                curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout / 1000);
            }
            $response = curl_exec($this->curl);
            $errno = curl_errno($this->curl);
            if ($errno) {
                throw new HproseException($errno . ": " . curl_error($this->curl));
            }
            do {
                list($response_headers, $response) = explode("\r\n\r\n", $response, 2); 
                $http_response_header = explode("\r\n", $response_headers);
                $http_response_firstline = array_shift($http_response_header); 
                if (preg_match('@^HTTP/[0-9]\.[0-9]\s([0-9]{3})\s(.*)@',
                               $http_response_firstline, $matches)) { 
                    $response_code = $matches[1];
                    $response_status = trim($matches[2]);
                }
                else {
                    $response_code = "500";
                    $response_status = "Unknown Error.";                
                }
            } while (substr($response_code, 0, 1) == "1");
            if ($response_code != '200') {
                throw new HproseException($response_code . ": " . $response_status);
            }
            $this->setCookie($http_response_header);
            return $response;
        }
        public function __destruct() {
            curl_close($this->curl);
        }
    }
}
else {
    class HproseHttpClient extends HproseBaseHttpClient {
        protected function formatCookie($cookies) {
            if (count($cookies) > 0) {
                return "Cookie: " . implode('; ', $cookies) . "\r\n";
            }
            return '';
        }
        public function __errorHandler($errno, $errstr, $errfile, $errline) {
            throw new Exception($errstr, $errno);
        }
        protected function send($request) {
            $opts = array (
                'http' => array (
                    'method' => 'POST',
                    'header'=> $this->getCookie() .
                               "Content-Length: " . strlen($request) . "\r\n" .
                               ($this->keepAlive ?
                               "Connection: keep-alive\r\n" .
                               "Keep-Alive: " . $this->keepAliveTimeout . "\r\n" :
                               "Connection: close\r\n"),
                    'content' => $request,
                    'timeout' => $this->timeout / 1000.0,
                ),
            );
            foreach ($this->header as $name => $value) {
                $opts['http']['header'] .= "$name: $value\r\n";
            }
            if ($this->proxy) {
                $opts['http']['proxy'] = $this->proxy;
                $opts['http']['request_fulluri'] = true;
            }
            $context = stream_context_create($opts);
            set_error_handler(array(&$this, '__errorHandler'));
            $response = file_get_contents($this->url, false, $context);
            restore_error_handler();
            $this->setCookie($http_response_header);
            return $response;
        }
    }
}

?>