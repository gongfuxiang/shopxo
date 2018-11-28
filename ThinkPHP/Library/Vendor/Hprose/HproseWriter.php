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
 * HproseWriter.php                                       *
 *                                                        *
 * hprose writer library for php5.                        *
 *                                                        *
 * LastModified: Nov 13, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

require_once('HproseCommon.php');
require_once('HproseTags.php');
require_once('HproseClassManager.php');

class HproseSimpleWriter {
    public $stream;
    private $classref;
    private $fieldsref;
    function __construct(&$stream) {
        $this->stream = &$stream;
        $this->classref = array();
        $this->fieldsref = array();
    }
    public function serialize(&$var) {
        if ((!isset($var)) || ($var === NULL)) {
            $this->writeNull();
        }
        elseif (is_scalar($var)) {
            if (is_int($var)) {
                $this->writeInteger($var);
            }
            elseif (is_bool($var)) {
                $this->writeBoolean($var);
            }
            elseif (is_float($var)) {
                $this->writeDouble($var);
            }
            elseif (is_string($var)) {
                if ($var === '') {
                    $this->writeEmpty();
                }
                elseif ((strlen($var) < 4) && is_utf8($var) && (ustrlen($var) == 1)) {
                    $this->writeUTF8Char($var);
                }
                elseif (is_utf8($var)) {
                    $this->writeString($var, true);
                }
                else {
                    $this->writeBytes($var, true);
                }
            }
        }
        elseif (is_array($var)) {
            if (is_list($var)) {
                $this->writeList($var, true);
            }
            else {
               $this->writeMap($var, true);
            }
        }
        elseif (is_object($var)) {
            if ($var instanceof stdClass) {
                $this->writeStdObject($var, true);
            }
            elseif (($var instanceof HproseDate) || ($var instanceof HproseDateTime)) {
                $this->writeDate($var, true);
            }
            elseif ($var instanceof HproseTime) {
                $this->writeTime($var, true);
            }
            else {
                $this->writeObject($var, true);
            }
        }
        else {
            throw new HproseException('Not support to serialize this data');
        }
    }
    public function writeInteger($integer) {
        if ($integer >= 0 && $integer <= 9) {
            $this->stream->write((string)$integer);
        }
        else {
            $this->stream->write(HproseTags::TagInteger . $integer . HproseTags::TagSemicolon);
        }
    }
    public function writeLong($long) {
        if ($long >= '0' && $long <= '9') {
            $this->stream->write($long);
        }
        else {
            $this->stream->write(HproseTags::TagLong . $long . HproseTags::TagSemicolon);
        }
    }
    public function writeDouble($double) {
        if (is_nan($double)) {
            $this->writeNaN();
        }
        elseif (is_infinite($double)) {
            $this->writeInfinity($double > 0);
        }
        else {
            $this->stream->write(HproseTags::TagDouble . $double . HproseTags::TagSemicolon);
        }
    }
    public function writeNaN() {
        $this->stream->write(HproseTags::TagNaN);
    }
    public function writeInfinity($positive = true) {
        $this->stream->write(HproseTags::TagInfinity . ($positive ? HproseTags::TagPos : HproseTags::TagNeg));
    }
    public function writeNull() {
        $this->stream->write(HproseTags::TagNull);
    }
    public function writeEmpty() {
        $this->stream->write(HproseTags::TagEmpty);
    }
    public function writeBoolean($bool) {
        $this->stream->write($bool ? HproseTags::TagTrue : HproseTags::TagFalse);
    }
    public function writeDate($date, $checkRef = false) {
        if ($date->utc) {
            $this->stream->write(HproseTags::TagDate . $date->toString(false));
        }
        else {
            $this->stream->write(HproseTags::TagDate . $date->toString(false) . HproseTags::TagSemicolon);
        }
    }
    public function writeTime($time, $checkRef = false) {
        if ($time->utc) {
            $this->stream->write(HproseTags::TagTime . $time->toString(false));
        }
        else {
            $this->stream->write(HproseTags::TagTime . $time->toString(false) . HproseTags::TagSemicolon);
        }
    }
    public function writeBytes($bytes, $checkRef = false) {
        $len = strlen($bytes);
        $this->stream->write(HproseTags::TagBytes);
        if ($len > 0) $this->stream->write((string)$len);
        $this->stream->write(HproseTags::TagQuote . $bytes . HproseTags::TagQuote);
    }
    public function writeUTF8Char($char) {
        $this->stream->write(HproseTags::TagUTF8Char . $char);
    }
    public function writeString($str, $checkRef = false) {
        $len = ustrlen($str);
        $this->stream->write(HproseTags::TagString);
        if ($len > 0) $this->stream->write((string)$len);
        $this->stream->write(HproseTags::TagQuote . $str . HproseTags::TagQuote);
    }
    public function writeList(&$list, $checkRef = false) {
        $count = count($list);
        $this->stream->write(HproseTags::TagList);
        if ($count > 0) $this->stream->write((string)$count); 
        $this->stream->write(HproseTags::TagOpenbrace);
        for ($i = 0; $i < $count; ++$i) {
            $this->serialize($list[$i]);
        }
        $this->stream->write(HproseTags::TagClosebrace);
    }
    public function writeMap(&$map, $checkRef = false) {
        $count = count($map);
        $this->stream->write(HproseTags::TagMap);
        if ($count > 0) $this->stream->write((string)$count); 
        $this->stream->write(HproseTags::TagOpenbrace);
        foreach ($map as $key => &$value) {
            $this->serialize($key);
            $this->serialize($value);
        }
        $this->stream->write(HproseTags::TagClosebrace);
    }
    public function writeStdObject($obj, $checkRef = false) {
        $map = (array)$obj;
        self::writeMap($map);
    }
    protected function writeObjectBegin($obj) {
        $class = get_class($obj);
        $alias = HproseClassManager::getClassAlias($class);
        $fields = array_keys((array)$obj);
        if (array_key_exists($alias, $this->classref)) {
            $index = $this->classref[$alias];
        }
        else {
            $index = $this->writeClass($alias, $fields);
        }
        return $index;
    }
    protected function writeObjectEnd($obj, $index) {
            $fields = $this->fieldsref[$index];
            $count = count($fields);
            $this->stream->write(HproseTags::TagObject . $index . HproseTags::TagOpenbrace);
            $array = (array)$obj;
            for ($i = 0; $i < $count; ++$i) {
                $this->serialize($array[$fields[$i]]);
            }
            $this->stream->write(HproseTags::TagClosebrace);
    }
    public function writeObject($obj, $checkRef = false) {
        $this->writeObjectEnd($obj, $this->writeObjectBegin($obj));
    }
    protected function writeClass($alias, $fields) {
        $len = ustrlen($alias);
        $this->stream->write(HproseTags::TagClass . $len .
                             HproseTags::TagQuote . $alias . HproseTags::TagQuote);
        $count = count($fields);
        if ($count > 0) $this->stream->write((string)$count);
        $this->stream->write(HproseTags::TagOpenbrace);
        for ($i = 0; $i < $count; ++$i) {
            $field = $fields[$i];
            if ($field{0} === "\0") {
                $field = substr($field, strpos($field, "\0", 1) + 1);
            }
            $this->writeString($field);
        }
        $this->stream->write(HproseTags::TagClosebrace);
        $index = count($this->fieldsref);
        $this->classref[$alias] = $index;
        $this->fieldsref[$index] = $fields;
        return $index;
    }
    public function reset() {
        $this->classref = array();
        $this->fieldsref = array();
    }
}
class HproseWriter extends HproseSimpleWriter {
    private $ref;
    private $arrayref;
    function __construct(&$stream) {
        parent::__construct($stream);
        $this->ref = array();
        $this->arrayref = array();
    }
    private function writeRef(&$obj, $checkRef, $writeBegin, $writeEnd) {
        if (is_string($obj)) {
            $key = 's_' . $obj;
        }
        elseif (is_array($obj)) {
            if (($i = array_ref_search($obj, $this->arrayref)) === false) {
                $i = count($this->arrayref);
                $this->arrayref[$i] = &$obj;
            }
            $key = 'a_' . $i;
        }
        else {
            $key = 'o_' . spl_object_hash($obj);
        }
        if ($checkRef && array_key_exists($key, $this->ref)) {
            $this->stream->write(HproseTags::TagRef . $this->ref[$key] . HproseTags::TagSemicolon);
        }
        else {
            $result = $writeBegin ? call_user_func_array($writeBegin, array(&$obj)) : false;
            $index = count($this->ref);
            $this->ref[$key] = $index;
            call_user_func_array($writeEnd, array(&$obj, $result));
        }
    }
    public function writeDate($date, $checkRef = false) {
        $this->writeRef($date, $checkRef, NULL, array(&$this, 'parent::writeDate'));
    }
    public function writeTime($time, $checkRef = false) {
        $this->writeRef($time, $checkRef, NULL, array(&$this, 'parent::writeTime'));
    }
    public function writeBytes($bytes, $checkRef = false) {
        $this->writeRef($bytes, $checkRef, NULL, array(&$this, 'parent::writeBytes'));
    }
    public function writeString($str, $checkRef = false) {
        $this->writeRef($str, $checkRef, NULL, array(&$this, 'parent::writeString'));
    }
    public function writeList(&$list, $checkRef = false) {
        $this->writeRef($list, $checkRef, NULL, array(&$this, 'parent::writeList'));
    }
    public function writeMap(&$map, $checkRef = false) {
        $this->writeRef($map, $checkRef, NULL, array(&$this, 'parent::writeMap'));
    }
    public function writeStdObject($obj, $checkRef = false) {
        $this->writeRef($obj, $checkRef, NULL, array(&$this, 'parent::writeStdObject'));
    }
    public function writeObject($obj, $checkRef = false) {
        $this->writeRef($obj, $checkRef, array(&$this, 'writeObjectBegin'), array(&$this, 'writeObjectEnd'));
    }
    public function reset() {
        parent::reset();
        $this->ref = array();
        $this->arrayref = array();
    }
}
?>