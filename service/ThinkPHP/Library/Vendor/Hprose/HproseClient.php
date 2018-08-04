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
 * HproseClient.php                                       *
 *                                                        *
 * hprose client library for php5.                        *
 *                                                        *
 * LastModified: Nov 13, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

require_once('HproseCommon.php');
require_once('HproseIO.php');

abstract class HproseClient {
    protected $url;
    private $filter;
    private $simple;
    protected abstract function send($request);
    public function __construct($url = '') {
        $this->useService($url);
        $this->filter = NULL;
        $this->simple = false;
    }
    public function useService($url = '', $namespace = '') {
        if ($url) {
            $this->url = $url;
        }
        return new HproseProxy($this, $namespace);
    }
    public function invoke($functionName, &$arguments = array(), $byRef = false, $resultMode = HproseResultMode::Normal, $simple = NULL) {
        if ($simple === NULL) $simple = $this->simple;
        $stream = new HproseStringStream(HproseTags::TagCall);
        $hproseWriter = ($simple ? new HproseSimpleWriter($stream) : new HproseWriter($stream));
        $hproseWriter->writeString($functionName);
        if (count($arguments) > 0 || $byRef) {
            $hproseWriter->reset();
            $hproseWriter->writeList($arguments);
            if ($byRef) {
                $hproseWriter->writeBoolean(true);
            }
        }
        $stream->write(HproseTags::TagEnd);
        $request = $stream->toString();
        if ($this->filter) $request = $this->filter->outputFilter($request);
        $stream->close();
        $response = $this->send($request);
        if ($this->filter) $response = $this->filter->inputFilter($response);
        if ($resultMode == HproseResultMode::RawWithEndTag) {
            return $response;
        }
        if ($resultMode == HproseResultMode::Raw) {
            return substr($response, 0, -1);
        }
        $stream = new HproseStringStream($response);
        $hproseReader = new HproseReader($stream);
        $result = NULL;
        while (($tag = $hproseReader->checkTags(
            array(HproseTags::TagResult,
                  HproseTags::TagArgument,
                  HproseTags::TagError,
                  HproseTags::TagEnd))) !== HproseTags::TagEnd) {
            switch ($tag) {
                case HproseTags::TagResult:
                    if ($resultMode == HproseResultMode::Serialized) {
                        $result = $hproseReader->readRaw()->toString();
                    }
                    else {
                        $hproseReader->reset();
                        $result = &$hproseReader->unserialize();
                    }
                    break;
                case HproseTags::TagArgument:
                    $hproseReader->reset();
                    $args = &$hproseReader->readList(true);
                    for ($i = 0; $i < count($arguments); $i++) {
                        $arguments[$i] = &$args[$i];
                    }
                    break;
                case HproseTags::TagError:
                    $hproseReader->reset();
                    throw new HproseException($hproseReader->readString(true));
                    break;
            }
        }
        return $result;
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
    public function __call($function, $arguments) {
        return $this->invoke($function, $arguments);
    }
    public function __get($name) {
        return new HproseProxy($this, $name . '_');
    }
}

class HproseProxy {
    private $client;
    private $namespace;
    public function __construct($client, $namespace = '') {
        $this->client = $client;
        $this->namespace = $namespace;
    }
    public function __call($function, $arguments) {
        $function = $this->namespace . $function;
        return $this->client->invoke($function, $arguments);
    }
    public function __get($name) {
        return new HproseProxy($this->client, $this->namespace . $name . '_');
    }
}
?>