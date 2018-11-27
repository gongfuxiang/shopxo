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
 * HproseReader.php                                       *
 *                                                        *
 * hprose reader library for php5.                        *
 *                                                        *
 * LastModified: Nov 12, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

require_once('HproseCommon.php');
require_once('HproseTags.php');
require_once('HproseClassManager.php');

class HproseRawReader {
    public $stream;
    function __construct(&$stream) {
        $this->stream = &$stream;
    }
    public function readRaw($ostream = NULL, $tag = NULL) {
        if (is_null($ostream)) {
            $ostream = new HproseStringStream();
        }
        if (is_null($tag)) {
            $tag = $this->stream->getc();
        }
        switch ($tag) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
            case HproseTags::TagNull:
            case HproseTags::TagEmpty:
            case HproseTags::TagTrue:
            case HproseTags::TagFalse:
            case HproseTags::TagNaN:
                $ostream->write($tag);
                break;
            case HproseTags::TagInfinity:
                $ostream->write($tag);
                $ostream->write($this->stream->getc());
                break;
            case HproseTags::TagInteger:
            case HproseTags::TagLong:
            case HproseTags::TagDouble:
            case HproseTags::TagRef:
                $this->readNumberRaw($ostream, $tag);
                break;
            case HproseTags::TagDate:
            case HproseTags::TagTime:
                $this->readDateTimeRaw($ostream, $tag);
                break;
            case HproseTags::TagUTF8Char:
                $this->readUTF8CharRaw($ostream, $tag);
                break;
            case HproseTags::TagBytes:
                $this->readBytesRaw($ostream, $tag);
                break;
            case HproseTags::TagString:
                $this->readStringRaw($ostream, $tag);
                break;
            case HproseTags::TagGuid:
                $this->readGuidRaw($ostream, $tag);
                break;
            case HproseTags::TagList:
            case HproseTags::TagMap:
            case HproseTags::TagObject:
                $this->readComplexRaw($ostream, $tag);
                break;
            case HproseTags::TagClass:
                $this->readComplexRaw($ostream, $tag);
                $this->readRaw($ostream);
                break;
            case HproseTags::TagError:
                $ostream->write($tag);
                $this->readRaw($ostream);
                break;
            case false:
                throw new HproseException("No byte found in stream");
            default:
                throw new HproseException("Unexpected serialize tag '" + $tag + "' in stream");
        }
    	return $ostream;
    }

    private function readNumberRaw($ostream, $tag) {
        $s = $tag .
             $this->stream->readuntil(HproseTags::TagSemicolon) .
             HproseTags::TagSemicolon;
        $ostream->write($s);
    }

    private function readDateTimeRaw($ostream, $tag) {
        $s = $tag;
        do {
            $tag = $this->stream->getc();
            $s .= $tag;
        } while ($tag != HproseTags::TagSemicolon &&
                 $tag != HproseTags::TagUTC);
        $ostream->write($s);
    }

    private function readUTF8CharRaw($ostream, $tag) {
        $s = $tag;
        $tag = $this->stream->getc();
        $s .= $tag;
        $a = ord($tag);
        if (($a & 0xE0) == 0xC0) {
            $s .= $this->stream->getc();
        }
        elseif (($a & 0xF0) == 0xE0) {
            $s .= $this->stream->read(2);
        }
        elseif ($a > 0x7F) {
            throw new HproseException("bad utf-8 encoding");
        }
        $ostream->write($s);
    }

    private function readBytesRaw($ostream, $tag) {
        $len = $this->stream->readuntil(HproseTags::TagQuote);
        $s = $tag . $len . HproseTags::TagQuote . $this->stream->read((int)$len) . HproseTags::TagQuote;
        $this->stream->skip(1);
        $ostream->write($s);
    }

    private function readStringRaw($ostream, $tag) {
        $len = $this->stream->readuntil(HproseTags::TagQuote);
        $s = $tag . $len . HproseTags::TagQuote;
        $len = (int)$len;
        $this->stream->mark();
        $utf8len = 0;
        for ($i = 0; $i < $len; ++$i) {
            switch (ord($this->stream->getc()) >> 4) {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7: {
                    // 0xxx xxxx
                    $utf8len++;
                    break;
                }
                case 12:
                case 13: {
                    // 110x xxxx   10xx xxxx
                    $this->stream->skip(1);
                    $utf8len += 2;
                    break;
                }
                case 14: {
                    // 1110 xxxx  10xx xxxx  10xx xxxx
                    $this->stream->skip(2);
                    $utf8len += 3;
                    break;
                }
                case 15: {
                    // 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
                    $this->stream->skip(3);
                    $utf8len += 4;
                    ++$i;
                    break;
                }
                default: {
                    throw new HproseException('bad utf-8 encoding');
                }
            }
        }
        $this->stream->reset();
        $this->stream->unmark();
        $s .= $this->stream->read($utf8len) . HproseTags::TagQuote;
        $this->stream->skip(1);
        $ostream->write($s);
    }

    private function readGuidRaw($ostream, $tag) {
        $s = $tag . $this->stream->read(38);
        $ostream->write($s);
    }

    private function readComplexRaw($ostream, $tag) {
        $s = $tag .
             $this->stream->readuntil(HproseTags::TagOpenbrace) .
             HproseTags::TagOpenbrace;
        $ostream->write($s);
        while (($tag = $this->stream->getc()) != HproseTags::TagClosebrace) {
            $this->readRaw($ostream, $tag);
        }
        $ostream->write($tag);
    }
}

class HproseSimpleReader extends HproseRawReader {
    private $classref;
    function __construct(&$stream) {
        parent::__construct($stream);
        $this->classref = array();
    }
    public function &unserialize($tag = NULL) {
        if (is_null($tag)) {
            $tag = $this->stream->getc();
        }
        $result = NULL;
        switch ($tag) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                $result = (int)$tag; break;
            case HproseTags::TagInteger: $result = $this->readInteger(); break;
            case HproseTags::TagLong: $result = $this->readLong(); break;
            case HproseTags::TagDouble: $result = $this->readDouble(); break;
            case HproseTags::TagNull: break;
            case HproseTags::TagEmpty: $result = ''; break;
            case HproseTags::TagTrue: $result = true; break;
            case HproseTags::TagFalse: $result = false; break;
            case HproseTags::TagNaN: $result = log(-1); break;
            case HproseTags::TagInfinity: $result = $this->readInfinity(); break;
            case HproseTags::TagDate: $result = $this->readDate(); break;
            case HproseTags::TagTime: $result = $this->readTime(); break;
            case HproseTags::TagBytes: $result = $this->readBytes(); break;
            case HproseTags::TagUTF8Char: $result = $this->readUTF8Char(); break;            
            case HproseTags::TagString: $result = $this->readString(); break;
            case HproseTags::TagGuid: $result = $this->readGuid(); break;
            case HproseTags::TagList: $result = &$this->readList(); break;
            case HproseTags::TagMap: $result = &$this->readMap(); break;
            case HproseTags::TagClass: $this->readClass(); $result = &$this->unserialize(); break;
            case HproseTags::TagObject: $result = $this->readObject(); break;
            case HproseTags::TagError: throw new HproseException($this->readString(true));
            case false: throw new HproseException('No byte found in stream');
            default: throw new HproseException("Unexpected serialize tag '$tag' in stream");
        }
        return $result;
    }
    public function checkTag($expectTag, $tag = NULL) {
        if (is_null($tag)) $tag = $this->stream->getc();
        if ($tag != $expectTag) {
            throw new HproseException("Tag '$expectTag' expected, but '$tag' found in stream");
        }
    }
    public function checkTags($expectTags, $tag = NULL) {
        if (is_null($tag)) $tag = $this->stream->getc();
        if (!in_array($tag, $expectTags)) {
            $expectTags = implode('', $expectTags);
            throw new HproseException("Tag '$expectTags' expected, but '$tag' found in stream");
        }
        return $tag;
    }
    public function readInteger($includeTag = false) {
        if ($includeTag) {
            $tag = $this->stream->getc();
            if (($tag >= '0') && ($tag <= '9')) {
                return (int)$tag;
            }
            $this->checkTag(HproseTags::TagInteger, $tag);
        }
        return (int)($this->stream->readuntil(HproseTags::TagSemicolon));
    }
    public function readLong($includeTag = false) {
        if ($includeTag) {
            $tag = $this->stream->getc();
            if (($tag >= '0') && ($tag <= '9')) {
                return $tag;
            }
            $this->checkTag(HproseTags::TagLong, $tag);
        }
        return $this->stream->readuntil(HproseTags::TagSemicolon);
    }
    public function readDouble($includeTag = false) {
        if ($includeTag) {
            $tag = $this->stream->getc();
            if (($tag >= '0') && ($tag <= '9')) {
                return (double)$tag;
            }
            $this->checkTag(HproseTags::TagDouble, $tag);
        }
        return (double)($this->stream->readuntil(HproseTags::TagSemicolon));
    }
    public function readNaN() {
        $this->checkTag(HproseTags::TagNaN);
        return log(-1);
    }
    public function readInfinity($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagInfinity);
        return (($this->stream->getc() == HproseTags::TagNeg) ? log(0) : -log(0));
    }
    public function readNull() {
        $this->checkTag(HproseTags::TagNull);
        return NULL;
    }
    public function readEmpty() {
        $this->checkTag(HproseTags::TagEmpty);
        return '';
    }
    public function readBoolean() {
        $tag = $this->checkTags(array(HproseTags::TagTrue, HproseTags::TagFalse));
        return ($tag == HproseTags::TagTrue);
    }
    public function readDate($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagDate);
        $year = (int)($this->stream->read(4));
        $month = (int)($this->stream->read(2));
        $day = (int)($this->stream->read(2));
        $tag = $this->stream->getc();
        if ($tag == HproseTags::TagTime) {
            $hour = (int)($this->stream->read(2));
            $minute = (int)($this->stream->read(2));
            $second = (int)($this->stream->read(2));
            $microsecond = 0;
            $tag = $this->stream->getc();
            if ($tag == HproseTags::TagPoint) {
                $microsecond = (int)($this->stream->read(3)) * 1000;
                $tag = $this->stream->getc();
                if (($tag >= '0') && ($tag <= '9')) {
                    $microsecond += (int)($tag) * 100 + (int)($this->stream->read(2));
                    $tag = $this->stream->getc();
                    if (($tag >= '0') && ($tag <= '9')) {
                        $this->stream->skip(2);
                        $tag = $this->stream->getc();
                    }
                }
            }
            if ($tag == HproseTags::TagUTC) {
                $date = new HproseDateTime($year, $month, $day,
                                            $hour, $minute, $second,
                                            $microsecond, true);
            }
            else {
                $date = new HproseDateTime($year, $month, $day,
                                            $hour, $minute, $second,
                                            $microsecond);
            }
        }
        elseif ($tag == HproseTags::TagUTC) {
            $date = new HproseDate($year, $month, $day, true);            
        }
        else {
            $date = new HproseDate($year, $month, $day);
        }
        return $date;
    }
    public function readTime($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagTime);
        $hour = (int)($this->stream->read(2));
        $minute = (int)($this->stream->read(2));
        $second = (int)($this->stream->read(2));
        $microsecond = 0;
        $tag = $this->stream->getc();
        if ($tag == HproseTags::TagPoint) {
            $microsecond = (int)($this->stream->read(3)) * 1000;
            $tag = $this->stream->getc();
            if (($tag >= '0') && ($tag <= '9')) {
                $microsecond += (int)($tag) * 100 + (int)($this->stream->read(2));
                $tag = $this->stream->getc();
                if (($tag >= '0') && ($tag <= '9')) {
                    $this->stream->skip(2);
                    $tag = $this->stream->getc();
                }
            }
        }
        if ($tag == HproseTags::TagUTC) {
            $time = new HproseTime($hour, $minute, $second, $microsecond, true);
        }
        else {
            $time = new HproseTime($hour, $minute, $second, $microsecond);
        }
        return $time;
    }
    public function readBytes($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagBytes);
        $count = (int)($this->stream->readuntil(HproseTags::TagQuote));
        $bytes = $this->stream->read($count);
        $this->stream->skip(1);
        return $bytes;
    }
    public function readUTF8Char($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagUTF8Char);
        $c = $this->stream->getc();
        $s = $c;
        $a = ord($c);
        if (($a & 0xE0) == 0xC0) {
            $s .= $this->stream->getc();
        }
        elseif (($a & 0xF0) == 0xE0) {
            $s .= $this->stream->read(2);
        }
        elseif ($a > 0x7F) {
            throw new HproseException("bad utf-8 encoding");
        }
        return $s;
    }
    public function readString($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagString);
        $len = (int)$this->stream->readuntil(HproseTags::TagQuote);
        $this->stream->mark();
        $utf8len = 0;
        for ($i = 0; $i < $len; ++$i) {
            switch (ord($this->stream->getc()) >> 4) {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7: {
                    // 0xxx xxxx
                    $utf8len++;
                    break;
                }
                case 12:
                case 13: {
                    // 110x xxxx   10xx xxxx
                    $this->stream->skip(1);
                    $utf8len += 2;
                    break;
                }
                case 14: {
                    // 1110 xxxx  10xx xxxx  10xx xxxx
                    $this->stream->skip(2);
                    $utf8len += 3;
                    break;
                }
                case 15: {
                    // 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
                    $this->stream->skip(3);
                    $utf8len += 4;
                    ++$i;
                    break;
                }
                default: {
                    throw new HproseException('bad utf-8 encoding');
                }
            }
        }
        $this->stream->reset();
        $this->stream->unmark();
        $s = $this->stream->read($utf8len);
        $this->stream->skip(1);
        return $s;
    }
    public function readGuid($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagGuid);
        $this->stream->skip(1);
        $s = $this->stream->read(36);
        $this->stream->skip(1);
        return $s;
    }
    protected function &readListBegin() {
        $list = array();
        return $list;
    }
    protected function &readListEnd(&$list) {
        $count = (int)$this->stream->readuntil(HproseTags::TagOpenbrace);
        for ($i = 0; $i < $count; ++$i) {
            $list[] = &$this->unserialize();
        }
        $this->stream->skip(1);
        return $list;
    }
    public function &readList($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagList);
        $list = &$this->readListBegin();
        return $this->readListEnd($list);
    }
    protected function &readMapBegin() {
        $map = array();
        return $map;
    }
    protected function &readMapEnd(&$map) {
        $count = (int)$this->stream->readuntil(HproseTags::TagOpenbrace);
        for ($i = 0; $i < $count; ++$i) {
            $key = &$this->unserialize();
            $map[$key] = &$this->unserialize();
        }
        $this->stream->skip(1);
        return $map;
    }
    public function &readMap($includeTag = false) {
        if ($includeTag) $this->checkTag(HproseTags::TagMap);
        $map = &$this->readMapBegin();
        return $this->readMapEnd($map);
    }
    protected function readObjectBegin() {
        list($classname, $fields) = $this->classref[(int)$this->stream->readuntil(HproseTags::TagOpenbrace)];
        $object = new $classname;
        return array($object, $fields);
    }
    protected function readObjectEnd($object, $fields) {
        $count = count($fields);
        if (class_exists('ReflectionClass')) {
            $reflector = new ReflectionClass($object);
            for ($i = 0; $i < $count; ++$i) {
                $field = $fields[$i];
                if ($reflector->hasProperty($field)) {
                    $property = $reflector->getProperty($field);
                    $property->setAccessible(true);
                    $property->setValue($object, $this->unserialize());
                }
                else {
                    $object->$field = &$this->unserialize();
                }
            }
        }
        else {
            for ($i = 0; $i < $count; ++$i) {
                $object->$fields[$i] = &$this->unserialize();
            }
        }
        $this->stream->skip(1);
        return $object;
    }
    public function readObject($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagClass, HproseTags::TagObject));
            if ($tag == HproseTags::TagClass) {
                $this->readClass();
                return $this->readObject(true);
            }
        }
        list($object, $fields) = $this->readObjectBegin();
        return $this->readObjectEnd($object, $fields);
    }
    protected function readClass() {
        $classname = HproseClassManager::getClass(self::readString());
        $count = (int)$this->stream->readuntil(HproseTags::TagOpenbrace);
        $fields = array();
        for ($i = 0; $i < $count; ++$i) {
            $fields[] = $this->readString(true);
        }
        $this->stream->skip(1);
        $this->classref[] = array($classname, $fields);
    }
    public function reset() {
        $this->classref = array();
    }
}

class HproseReader extends HproseSimpleReader {
    private $ref;
    function __construct(&$stream) {
        parent::__construct($stream);
        $this->ref = array();
    }
    public function &unserialize($tag = NULL) {
        if (is_null($tag)) {
            $tag = $this->stream->getc();
        }
        if ($tag == HproseTags::TagRef) {
            return $this->readRef();
        }
        return parent::unserialize($tag);
    }
    public function readDate($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagDate, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $date = parent::readDate();
        $this->ref[] = $date;
        return $date;
    }
    public function readTime($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagTime, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $time = parent::readTime();
        $this->ref[] = $time;
        return $time;
    }
    public function readBytes($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagBytes, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $bytes = parent::readBytes();
        $this->ref[] = $bytes;
        return $bytes;
    }
    public function readString($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagString, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $str = parent::readString();
        $this->ref[] = $str;
        return $str;
    }
    public function readGuid($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagGuid, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $guid = parent::readGuid();
        $this->ref[] = $guid;
        return $guid;
    }
    public function &readList($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagList, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $list = &$this->readListBegin();
        $this->ref[] = &$list;
        return $this->readListEnd($list);
    }
    public function &readMap($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagMap, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
        }
        $map = &$this->readMapBegin();
        $this->ref[] = &$map;
        return $this->readMapEnd($map);
    }
    public function readObject($includeTag = false) {
        if ($includeTag) {
            $tag = $this->checkTags(array(HproseTags::TagClass, HproseTags::TagObject, HproseTags::TagRef));
            if ($tag == HproseTags::TagRef) return $this->readRef();
            if ($tag == HproseTags::TagClass) {
                $this->readClass();
                return $this->readObject(true);
            }
        }
        list($object, $fields) = $this->readObjectBegin();
        $this->ref[] = $object;
        return $this->readObjectEnd($object, $fields);
    }
    private function &readRef() {
        $ref = &$this->ref[(int)$this->stream->readuntil(HproseTags::TagSemicolon)];
        if (gettype($ref) == 'array') {
            $result = &$ref;
        }
        else {
            $result = $ref;
        }
        return $result;
    }
    public function reset() {
        parent::reset();
        $this->ref = array();
    }
}
?>