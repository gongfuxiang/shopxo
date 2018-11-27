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
 * HproseIOStream.php                                     *
 *                                                        *
 * hprose io stream library for php5.                     *
 *                                                        *
 * LastModified: Nov 12, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

abstract class HproseAbstractStream {
    public abstract function close();
    public abstract function getc();
    public abstract function read($length);
    public abstract function readuntil($char);
    public abstract function seek($offset, $whence = SEEK_SET);
    public abstract function mark();
    public abstract function unmark();
    public abstract function reset();    
    public abstract function skip($n);
    public abstract function eof();
    public abstract function write($string, $length = -1);
}

class HproseStringStream extends HproseAbstractStream {
    protected $buffer;
    protected $pos;
    protected $mark;
    protected $length;
    public function __construct($string = '') {
        $this->buffer = $string;
        $this->pos = 0;
        $this->mark = -1;
        $this->length = strlen($string);
    }
    public function close() {
        $this->buffer = NULL;
        $this->pos = 0;
        $this->mark = -1;
        $this->length = 0;
    }
    public function length() {
        return $this->length;
    }
    public function getc() {
        return $this->buffer{$this->pos++};
    }
    public function read($length) {
        $s = substr($this->buffer, $this->pos, $length);
        $this->skip($length);
        return $s;
    }
    public function readuntil($tag) {
        $pos = strpos($this->buffer, $tag, $this->pos);
        if ($pos !== false) {
            $s = substr($this->buffer, $this->pos, $pos - $this->pos);
            $this->pos = $pos + strlen($tag);
        }
        else {
            $s = substr($this->buffer, $this->pos);
            $this->pos = $this->length;
        }
        return $s;
    }
    public function seek($offset, $whence = SEEK_SET) {
        switch ($whence) {
            case SEEK_SET:
                $this->pos = $offset;
                break;
            case SEEK_CUR:
                $this->pos += $offset;
                break;
            case SEEK_END:
                $this->pos = $this->length + $offset;
                break;
        }
        $this->mark = -1;
        return 0;
    }
    public function mark() {
        $this->mark = $this->pos;
    }
    public function unmark() {
        $this->mark = -1;
    }
    public function reset() {
        if ($this->mark != -1) {
            $this->pos = $this->mark;
        }
    }
    public function skip($n) {
        $this->pos += $n;
    }
    public function eof() {
        return ($this->pos >= $this->length);
    }
    public function write($string, $length = -1) {
        if ($length == -1) {
            $this->buffer .= $string;
            $length = strlen($string);
        }
        else {
            $this->buffer .= substr($string, 0, $length);
        }
        $this->length += $length;
    }
    public function toString() {
        return $this->buffer;
    }
}

class HproseFileStream extends HproseAbstractStream {
    protected $fp;
    protected $buf;
    protected $unmark;    
    protected $pos;
    protected $length;
    public function __construct($fp) {
        $this->fp = $fp;
        $this->buf = "";
        $this->unmark = true;
        $this->pos = -1;
        $this->length = 0;
    }
    public function close() {
        return fclose($this->fp);
    }
    public function getc() {
        if ($this->pos == -1) {
            return fgetc($this->fp);
        }
        elseif ($this->pos < $this->length) {
            return $this->buf{$this->pos++};
        }
        elseif ($this->unmark) {
            $this->buf = "";        
            $this->pos = -1;
            $this->length = 0;
            return fgetc($this->fp);            
        }
        elseif (($c = fgetc($this->fp)) !== false) {
            $this->buf .= $c;
            $this->pos++;
            $this->length++;
        }
        return $c;
    }
    public function read($length) {
        if ($this->pos == -1) {
            return fread($this->fp, $length);
        }
        elseif ($this->pos < $this->length) {
            $len = $this->length - $this->pos;
            if ($len < $length) {
                $s = fread($this->fp, $length - $len);
                $this->buf .= $s;
                $this->length += strlen($s);
            }
            $s = substr($this->buf, $this->pos, $length);
            $this->pos += strlen($s);
        }
        elseif ($this->unmark) {
            $this->buf = "";        
            $this->pos = -1;
            $this->length = 0;
            return fread($this->fp, $length);           
        }
        elseif (($s = fread($this->fp, $length)) !== "") {
            $this->buf .= $s;
            $len = strlen($s);
            $this->pos += $len;
            $this->length += $len;
        }
        return $s;
    }
    public function readuntil($char) {
        $s = '';
        while ((($c = $this->getc()) != $char) && $c !== false) $s .= $c;
        return $s;
    }
    public function seek($offset, $whence = SEEK_SET) {
        if (fseek($this->fp, $offset, $whence) == 0) {
            $this->buf = "";
            $this->unmark = true;
            $this->pos = -1;
            $this->length = 0;
            return 0;
        }
        return -1;
    }
    public function mark() {
        $this->unmark = false;
        if ($this->pos == -1) {
            $this->buf = "";
            $this->pos = 0;
            $this->length = 0;
        }
        elseif ($this->pos > 0) {
            $this->buf = substr($this->buf, $this->pos);
            $this->length -= $this->pos;
            $this->pos = 0;
        }
    }
    public function unmark() {
        $this->unmark = true;
    }
    public function reset() {
        $this->pos = 0;
    }
    public function skip($n) {
        $this->read($n);
    }
    public function eof() {
        if (($this->pos != -1) && ($this->pos < $this->length)) return false;
        return feof($this->fp);
    }
    public function write($string, $length = -1) {
        if ($length == -1) $length = strlen($string);
        return fwrite($this->fp, $string, $length);
    }
}

class HproseProcStream extends HproseAbstractStream {
    protected $process;
    protected $pipes;
    protected $buf;
    protected $unmark;    
    protected $pos;
    protected $length;
    public function __construct($process, $pipes) {
        $this->process = $process;
        $this->pipes = $pipes;
        $this->buf = "";
        $this->unmark = true;
        $this->pos = -1;
        $this->length = 0;        
    }
    public function close() {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        proc_close($this->process);
    }
    public function getc() {
        if ($this->pos == -1) {
            return fgetc($this->pipes[1]);
        }
        elseif ($this->pos < $this->length) {
            return $this->buf{$this->pos++};
        }
        elseif ($this->unmark) {
            $this->buf = "";        
            $this->pos = -1;
            $this->length = 0;
            return fgetc($this->pipes[1]);
        }
        elseif (($c = fgetc($this->pipes[1])) !== false) {
            $this->buf .= $c;
            $this->pos++;
            $this->length++;
        }
        return $c;
    }
    public function read($length) {
        if ($this->pos == -1) {
            return fread($this->pipes[1], $length);
        }
        elseif ($this->pos < $this->length) {
            $len = $this->length - $this->pos;
            if ($len < $length) {
                $s = fread($this->pipes[1], $length - $len);
                $this->buf .= $s;
                $this->length += strlen($s);
            }
            $s = substr($this->buf, $this->pos, $length);
            $this->pos += strlen($s);
        }
        elseif ($this->unmark) {
            $this->buf = "";        
            $this->pos = -1;
            $this->length = 0;
            return fread($this->pipes[1], $length);           
        }
        elseif (($s = fread($this->pipes[1], $length)) !== "") {
            $this->buf .= $s;
            $len = strlen($s);
            $this->pos += $len;
            $this->length += $len;
        }
        return $s;
    }
    public function readuntil($char) {
        $s = '';
        while ((($c = $this->getc()) != $char) && $c !== false) $s .= $c;
        return $s;
    }
    public function seek($offset, $whence = SEEK_SET) {
        if (fseek($this->pipes[1], $offset, $whence) == 0) {
            $this->buf = "";
            $this->unmark = true;
            $this->pos = -1;
            $this->length = 0;
            return 0;
        }
        return -1;
    }
    public function mark() {
        $this->unmark = false;
        if ($this->pos == -1) {
            $this->buf = "";
            $this->pos = 0;
            $this->length = 0;
        }
        elseif ($this->pos > 0) {
            $this->buf = substr($this->buf, $this->pos);
            $this->length -= $this->pos;
            $this->pos = 0;
        }
    }
    public function unmark() {
        $this->unmark = true;
    }
    public function reset() {
        $this->pos = 0;
    }
    public function skip($n) {
        $this->read($n);
    }
    public function eof() {
        if (($this->pos != -1) && ($this->pos < $this->length)) return false;
        return feof($this->pipes[1]);
    }
    public function write($string, $length = -1) {
        if ($length == -1) $length = strlen($string);
        return fwrite($this->pipes[0], $string, $length);
    }
}
?>