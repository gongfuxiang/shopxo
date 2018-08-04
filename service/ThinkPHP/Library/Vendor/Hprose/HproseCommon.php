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
 * HproseCommon.php                                       *
 *                                                        *
 * hprose common library for php5.                        *
 *                                                        *
 * LastModified: Nov 15, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

class HproseResultMode {
    const Normal = 0;
    const Serialized = 1;
    const Raw = 2;
    const RawWithEndTag = 3;
}

class HproseException extends Exception {}

interface HproseFilter {
    function inputFilter($data);
    function outputFilter($data);    
}

class HproseDate {
    public $year;
    public $month;
    public $day;
    public $utc = false;    
    public function __construct() {
        $args_num = func_num_args();
        $args = func_get_args();
        switch ($args_num) {
            case 0:
                $time = getdate();
                $this->year = $time['year'];
                $this->month = $time['mon'];
                $this->day = $time['mday'];
                break;
            case 1:
                $time = false;
                if (is_int($args[0])) {
                    $time = getdate($args[0]);
                }
                elseif (is_string($args[0])) {
                    $time = getdate(strtotime($args[0]));
                }
                if (is_array($time)) {
                    $this->year = $time['year'];
                    $this->month = $time['mon'];
                    $this->day = $time['mday'];
                }
                elseif ($args[0] instanceof HproseDate) {
                    $this->year = $args[0]->year;
                    $this->month = $args[0]->month;
                    $this->day = $args[0]->day;
                }
                else {
                    throw new HproseException('Unexpected arguments');
                }
                break;
            case 4:
                $this->utc = $args[3];
            case 3:
                if (!self::isValidDate($args[0], $args[1], $args[2])) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->year = $args[0];
                $this->month = $args[1];
                $this->day = $args[2];
                break;
            default:
                throw new HproseException('Unexpected arguments');
        }
    }
    public function addDays($days) {
        if (!is_int($days)) return false;
        $year = $this->year;
        if ($days == 0) return true;
        if ($days >= 146097 || $days <= -146097) {
            $remainder = $days % 146097;
            if ($remainder < 0) {
                $remainder += 146097;
            }
            $years = 400 * (int)(($days - $remainder) / 146097);
            $year += $years;
            if ($year < 1 || $year > 9999) return false;
            $days = $remainder;
        }
        if ($days >= 36524 || $days <= -36524) {
            $remainder = $days % 36524;
            if ($remainder < 0) {
                $remainder += 36524;
            }
            $years = 100 * (int)(($days - $remainder) / 36524);
            $year += $years;
            if ($year < 1 || $year > 9999) return false;
            $days = $remainder;
        }
        if ($days >= 1461 || $days <= -1461) {
            $remainder = $days % 1461;
            if ($remainder < 0) {
                $remainder += 1461;
            }
            $years = 4 * (int)(($days - $remainder) / 1461);
            $year += $years;
            if ($year < 1 || $year > 9999) return false;
            $days = $remainder;
        }
        $month = $this->month;
        while ($days >= 365) {
            if ($year >= 9999) return false;
            if ($month <= 2) {
                if ((($year % 4) == 0) ? (($year % 100) == 0) ? (($year % 400) == 0) : true : false) {
                    $days -= 366;
                }
                else {
                    $days -= 365;
                }
                $year++;
            }
            else {
                $year++;
                if ((($year % 4) == 0) ? (($year % 100) == 0) ? (($year % 400) == 0) : true : false) {
                    $days -= 366;
                }
                else {
                    $days -= 365;
                }
            }
        }
        while ($days < 0) {
            if ($year <= 1) return false;
            if ($month <= 2) {
                $year--;
                if ((($year % 4) == 0) ? (($year % 100) == 0) ? (($year % 400) == 0) : true : false) {
                    $days += 366;
                }
                else {
                    $days += 365;
                }
            }
            else {
                if ((($year % 4) == 0) ? (($year % 100) == 0) ? (($year % 400) == 0) : true : false) {
                    $days += 366;
                }
                else {
                    $days += 365;
                }
                $year--;
            }
        }
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $day = $this->day;
        while ($day + $days > $daysInMonth) {
            $days -= $daysInMonth - $day + 1;
            $month++;
            if ($month > 12) {
                if ($year >= 9999) return false;
                $year++;
                $month = 1;
            }
            $day = 1;
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $day += $days;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        return true;
    }
    public function addMonths($months) {
        if (!is_int($months)) return false;
        if ($months == 0) return true;
        $month = $this->month + $months;
        $months = ($month - 1) % 12 + 1;
        if ($months < 1) {
            $months += 12;
        }
        $years = (int)(($month - $months) / 12);
        if ($this->addYears($years)) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $months, $this->year);
            if ($this->day > $daysInMonth) {
                $months++;
                $this->day -= $daysInMonth;
            }
            $this->month = (int)$months;
            return true;
        }
        else {
            return false;
        }
    }
    public function addYears($years) {
        if (!is_int($years)) return false;
        if ($years == 0) return true;
        $year = $this->year + $years;
        if ($year < 1 || $year > 9999) return false;
        $this->year = $year;
        return true;
    }
    public function timestamp() {
        if ($this->utc) {
            return gmmktime(0, 0, 0, $this->month, $this->day, $this->year);
        }
        else {
            return mktime(0, 0, 0, $this->month, $this->day, $this->year);            
        }
    }
    public function toString($fullformat = true) {
        $format = ($fullformat ? '%04d-%02d-%02d': '%04d%02d%02d');
        $str = sprintf($format, $this->year, $this->month, $this->day);
        if ($this->utc) {
            $str .= 'Z';
        }
        return $str;        
    }
    public function __toString() {
        return $this->toString();
    }

    public static function isLeapYear($year) {
        return (($year % 4) == 0) ? (($year % 100) == 0) ? (($year % 400) == 0) : true : false;
    }
    public static function daysInMonth($year, $month) {
        if (($month < 1) || ($month > 12)) {
            return false;
        }
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    public static function isValidDate($year, $month, $day) {
        if (($year >= 1) && ($year <= 9999)) {
            return checkdate($month, $day, $year);
        }
        return false;
    }

    public function dayOfWeek() {
        $num = func_num_args();
        if ($num == 3) {
            $args = func_get_args();
            $y = $args[0];
            $m = $args[1];
            $d = $args[2];
        }
        else {
            $y = $this->year;
            $m = $this->month;
            $d = $this->day;
        }
        $d += $m < 3 ? $y-- : $y - 2;
        return ((int)(23 * $m / 9) + $d + 4 + (int)($y / 4) - (int)($y / 100) + (int)($y / 400)) % 7;
    }
    public function dayOfYear() {
        static $daysToMonth365 = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365);
        static $daysToMonth366 = array(0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366);
        $num = func_num_args();
        if ($num == 3) {
            $args = func_get_args();
            $y = $args[0];
            $m = $args[1];
            $d = $args[2];
        }
        else {
            $y = $this->year;
            $m = $this->month;
            $d = $this->day;
        }
        $days = self::isLeapYear($y) ? $daysToMonth365 : $daysToMonth366;
        return $days[$m - 1] + $d;
    }
}

class HproseTime {
    public $hour;
    public $minute;
    public $second;
    public $microsecond = 0;
    public $utc = false;
    public function __construct() {
        $args_num = func_num_args();
        $args = func_get_args();
        switch ($args_num) {
            case 0:
                $time = getdate();
                $timeofday = gettimeofday();
                $this->hour = $time['hours'];
                $this->minute = $time['minutes'];
                $this->second = $time['seconds'];
                $this->microsecond = $timeofday['usec'];
                break;
            case 1:
                $time = false;
                if (is_int($args[0])) {
                    $time = getdate($args[0]);
                }
                elseif (is_string($args[0])) {
                    $time = getdate(strtotime($args[0]));
                }
                if (is_array($time)) {
                    $this->hour = $time['hours'];
                    $this->minute = $time['minutes'];
                    $this->second = $time['seconds'];
                }
                elseif ($args[0] instanceof HproseTime) {
                    $this->hour = $args[0]->hour;
                    $this->minute = $args[0]->minute;
                    $this->second = $args[0]->second;
                    $this->microsecond = $args[0]->microsecond;
                }
                else {
                    throw new HproseException('Unexpected arguments');
                }
                break;
            case 5:
                $this->utc = $args[4];
            case 4:
                if (($args[3] < 0) || ($args[3] > 999999)) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->microsecond = $args[3];             
            case 3:
                if (!self::isValidTime($args[0], $args[1], $args[2])) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->hour = $args[0];
                $this->minute = $args[1];
                $this->second = $args[2];
                break;
            default:
                throw new HproseException('Unexpected arguments');
        }
    }
    public function timestamp() {
        if ($this->utc) {
            return gmmktime($this->hour, $this->minute, $this->second) +
                   ($this->microsecond / 1000000);
        }
        else {
            return mktime($this->hour, $this->minute, $this->second) +
                   ($this->microsecond / 1000000);
        }
    }
    public function toString($fullformat = true) {
        if ($this->microsecond == 0) {
            $format = ($fullformat ? '%02d:%02d:%02d': '%02d%02d%02d');
            $str = sprintf($format, $this->hour, $this->minute, $this->second);
        }
        if ($this->microsecond % 1000 == 0) {
            $format = ($fullformat ? '%02d:%02d:%02d.%03d': '%02d%02d%02d.%03d');
            $str = sprintf($format, $this->hour, $this->minute, $this->second, (int)($this->microsecond / 1000));
        }
        else {
            $format = ($fullformat ? '%02d:%02d:%02d.%06d': '%02d%02d%02d.%06d');
            $str = sprintf($format, $this->hour, $this->minute, $this->second, $this->microsecond);            
        }
        if ($this->utc) {
            $str .= 'Z';
        }
        return $str;
    }
    public function __toString() {
        return $this->toString();
    }
    public static function isValidTime($hour, $minute, $second, $microsecond = 0) {
        return !(($hour < 0) || ($hour > 23) ||
            ($minute < 0) || ($minute > 59) ||
            ($second < 0) || ($second > 59) ||
            ($microsecond < 0) || ($microsecond > 999999));
    }
}

class HproseDateTime extends HproseDate {
    public $hour;
    public $minute;
    public $second;
    public $microsecond = 0;    
    public function __construct() {
        $args_num = func_num_args();
        $args = func_get_args();
        switch ($args_num) {
            case 0:
                $time = getdate();
                $timeofday = gettimeofday();
                $this->year = $time['year'];
                $this->month = $time['mon'];
                $this->day = $time['mday'];
                $this->hour = $time['hours'];
                $this->minute = $time['minutes'];
                $this->second = $time['seconds'];
                $this->microsecond = $timeofday['usec'];              
                break;
            case 1:
                $time = false;
                if (is_int($args[0])) {
                    $time = getdate($args[0]);
                }
                elseif (is_string($args[0])) {
                    $time = getdate(strtotime($args[0]));
                }
                if (is_array($time)) {
                    $this->year = $time['year'];
                    $this->month = $time['mon'];
                    $this->day = $time['mday'];
                    $this->hour = $time['hours'];
                    $this->minute = $time['minutes'];
                    $this->second = $time['seconds'];
                }
                elseif ($args[0] instanceof HproseDate) {
                    $this->year = $args[0]->year;
                    $this->month = $args[0]->month;
                    $this->day = $args[0]->day;
                    $this->hour = 0;
                    $this->minute = 0;
                    $this->second = 0;
                }
                elseif ($args[0] instanceof HproseTime) {
                    $this->year = 1970;
                    $this->month = 1;
                    $this->day = 1;
                    $this->hour = $args[0]->hour;
                    $this->minute = $args[0]->minute;
                    $this->second = $args[0]->second;
                    $this->microsecond = $args[0]->microsecond;
                }
                elseif ($args[0] instanceof HproseDateTime) {
                    $this->year = $args[0]->year;
                    $this->month = $args[0]->month;
                    $this->day = $args[0]->day;
                    $this->hour = $args[0]->hour;
                    $this->minute = $args[0]->minute;
                    $this->second = $args[0]->second;
                    $this->microsecond = $args[0]->microsecond;
                }
                else {
                    throw new HproseException('Unexpected arguments');
                }
                break;
            case 2:
                if (($args[0] instanceof HproseDate) && ($args[1] instanceof HproseTime)) {
                    $this->year = $args[0]->year;
                    $this->month = $args[0]->month;
                    $this->day = $args[0]->day;
                    $this->hour = $args[1]->hour;
                    $this->minute = $args[1]->minute;
                    $this->second = $args[1]->second;
                    $this->microsecond = $args[1]->microsecond;
                }
                else {
                    throw new HproseException('Unexpected arguments');
                }
                break;
            case 3:
                if (!self::isValidDate($args[0], $args[1], $args[2])) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->year = $args[0];
                $this->month = $args[1];
                $this->day = $args[2];
                $this->hour = 0;
                $this->minute = 0;
                $this->second = 0;
                break;
            case 8:
                $this->utc = $args[7];
            case 7:
                if (($args[6] < 0) || ($args[6] > 999999)) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->microsecond = $args[6];                
            case 6:
                if (!self::isValidDate($args[0], $args[1], $args[2])) {
                    throw new HproseException('Unexpected arguments');
                }
                if (!self::isValidTime($args[3], $args[4], $args[5])) {
                    throw new HproseException('Unexpected arguments');
                }
                $this->year = $args[0];
                $this->month = $args[1];
                $this->day = $args[2];
                $this->hour = $args[3];
                $this->minute = $args[4];
                $this->second = $args[5];
                break;
            default:
                throw new HproseException('Unexpected arguments');
        }
    }
    
    public function addMicroseconds($microseconds) {
        if (!is_int($microseconds)) return false;
        if ($microseconds == 0) return true;
        $microsecond = $this->microsecond + $microseconds;
        $microseconds = $microsecond % 1000000;
        if ($microseconds < 0) {
            $microseconds += 1000000;
        }
        $seconds = (int)(($microsecond - $microseconds) / 1000000);
        if ($this->addSeconds($seconds)) {
            $this->microsecond = (int)$microseconds;
            return true;
        }
        else {
            return false;
        }
    }
    
    public function addSeconds($seconds) {
        if (!is_int($seconds)) return false;
        if ($seconds == 0) return true;
        $second = $this->second + $seconds;
        $seconds = $second % 60;
        if ($seconds < 0) {
            $seconds += 60;
        }
        $minutes = (int)(($second - $seconds) / 60);
        if ($this->addMinutes($minutes)) {
            $this->second = (int)$seconds;
            return true;
        }
        else {
            return false;
        }
    }
    public function addMinutes($minutes) {
        if (!is_int($minutes)) return false;
        if ($minutes == 0) return true;
        $minute = $this->minute + $minutes;
        $minutes = $minute % 60;
        if ($minutes < 0) {
            $minutes += 60;
        }
        $hours = (int)(($minute - $minutes) / 60);
        if ($this->addHours($hours)) {
            $this->minute = (int)$minutes;
            return true;
        }
        else {
            return false;
        }
    }
    public function addHours($hours) {
        if (!is_int($hours)) return false;
        if ($hours == 0) return true;
        $hour = $this->hour + $hours;
        $hours = $hour % 24;
        if ($hours < 0) {
            $hours += 24;
        }
        $days = (int)(($hour - $hours) / 24);
        if ($this->addDays($days)) {
            $this->hour = (int)$hours;
            return true;
        }
        else {
            return false;
        }
    }
    public function after($when) {
        if (!($when instanceof HproseDateTime)) {
            $when = new HproseDateTime($when);
        }
        if ($this->utc != $when->utc) return ($this->timestamp() > $when->timestamp());
        if ($this->year < $when->year) return false;
        if ($this->year > $when->year) return true;
        if ($this->month < $when->month) return false;
        if ($this->month > $when->month) return true;
        if ($this->day < $when->day) return false;
        if ($this->day > $when->day) return true;
        if ($this->hour < $when->hour) return false;
        if ($this->hour > $when->hour) return true;
        if ($this->minute < $when->minute) return false;
        if ($this->minute > $when->minute) return true;
        if ($this->second < $when->second) return false;
        if ($this->second > $when->second) return true;
        if ($this->microsecond < $when->microsecond) return false;
        if ($this->microsecond > $when->microsecond) return true;
        return false;
    }
    public function before($when) {
        if (!($when instanceof HproseDateTime)) {
            $when = new HproseDateTime($when);
        }
        if ($this->utc != $when->utc) return ($this->timestamp() < $when->timestamp());
        if ($this->year < $when->year) return true;
        if ($this->year > $when->year) return false;
        if ($this->month < $when->month) return true;
        if ($this->month > $when->month) return false;
        if ($this->day < $when->day) return true;
        if ($this->day > $when->day) return false;
        if ($this->hour < $when->hour) return true;
        if ($this->hour > $when->hour) return false;
        if ($this->minute < $when->minute) return true;
        if ($this->minute > $when->minute) return false;
        if ($this->second < $when->second) return true;
        if ($this->second > $when->second) return false;
        if ($this->microsecond < $when->microsecond) return true;
        if ($this->microsecond > $when->microsecond) return false;
        return false;
    }
    public function equals($when) {
        if (!($when instanceof HproseDateTime)) {
            $when = new HproseDateTime($when);
        }
        if ($this->utc != $when->utc) return ($this->timestamp() == $when->timestamp());
        return (($this->year == $when->year) &&
            ($this->month == $when->month) &&
            ($this->day == $when->day) &&
            ($this->hour == $when->hour) &&
            ($this->minute == $when->minute) &&
            ($this->second == $when->second) &&
            ($this->microsecond == $when->microsecond));
    }
    public function timestamp() {
        if ($this->utc) {
            return gmmktime($this->hour,
                            $this->minute,
                            $this->second,
                            $this->month,
                            $this->day,
                            $this->year) +
                   ($this->microsecond / 1000000);
        }
        else {
            return mktime($this->hour,
                          $this->minute,
                          $this->second,
                          $this->month,
                          $this->day,
                          $this->year) +
                   ($this->microsecond / 1000000);
        }
    }
    public function toString($fullformat = true) {
        if ($this->microsecond == 0) {
            $format = ($fullformat ? '%04d-%02d-%02dT%02d:%02d:%02d'
                                   : '%04d%02d%02dT%02d%02d%02d');
            $str = sprintf($format,
                           $this->year, $this->month, $this->day,
                           $this->hour, $this->minute, $this->second);
        }
        if ($this->microsecond % 1000 == 0) {
            $format = ($fullformat ? '%04d-%02d-%02dT%02d:%02d:%02d.%03d'
                                   : '%04d%02d%02dT%02d%02d%02d.%03d');
            $str = sprintf($format,
                           $this->year, $this->month, $this->day,
                           $this->hour, $this->minute, $this->second,
                           (int)($this->microsecond / 1000));
        }        
        else {
            $format = ($fullformat ? '%04d-%02d-%02dT%02d:%02d:%02d.%06d'
                                   : '%04d%02d%02dT%02d%02d%02d.%06d');
            $str = sprintf($format,
                           $this->year, $this->month, $this->day,
                           $this->hour, $this->minute, $this->second,
                           $this->microsecond);            
        }
        if ($this->utc) {
            $str .= 'Z';
        }
        return $str;
    }
    public function __toString() {
        return $this->toString();
    }
    public static function isValidTime($hour, $minute, $second, $microsecond = 0) {
        return HproseTime::isValidTime($hour, $minute, $second, $microsecond);
    }
}

/*
 integer is_utf8(string $s)
 if $s is UTF-8 String, return 1 else 0
 */
if (function_exists('mb_detect_encoding')) {
    function is_utf8($s) {
        return mb_detect_encoding($s, 'UTF-8', true) === 'UTF-8';
    }
}
elseif (function_exists('iconv')) {
    function is_utf8($s) {
        return iconv('UTF-8', 'UTF-8//IGNORE', $s) === $s;
    }
}
else {
    function is_utf8($s) { 
        $len = strlen($s); 
        for($i = 0; $i < $len; ++$i){
            $c = ord($s{$i});
            switch ($c >> 4) {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                    break;
                case 12:
                case 13:
                    if ((ord($s{++$i}) >> 6) != 0x2) return false;
                    break;
                case 14:
                    if ((ord($s{++$i}) >> 6) != 0x2) return false;
                    if ((ord($s{++$i}) >> 6) != 0x2) return false;
                    break;
                case 15:
                    $b = $s{++$i};
                    if ((ord($b) >> 6) != 0x2) return false;
                    if ((ord($s{++$i}) >> 6) != 0x2) return false;
                    if ((ord($s{++$i}) >> 6) != 0x2) return false;
                    if (((($c & 0xf) << 2) | (($b >> 4) & 0x3)) > 0x10) return false;
                    break;
                default:
                    return false;
            }
        }
        return true;
    }
}

/*
 integer ustrlen(string $s)
 $s must be a UTF-8 String, return the Unicode code unit (not code point) length
 */
if (function_exists('iconv')) {
    function ustrlen($s) {
        return strlen(iconv('UTF-8', 'UTF-16LE', $s)) >> 1;
    }
}
elseif (function_exists('mb_convert_encoding')) {
    function ustrlen($s) {
        return strlen(mb_convert_encoding($s, "UTF-16LE", "UTF-8")) >> 1;
    }
}
else {
    function ustrlen($s) {
        $pos = 0;
        $length = strlen($s);
        $len = $length;
        while ($pos < $length) {
            $a = ord($s{$pos++});
            if ($a < 0x80) {
                continue;
            }
            elseif (($a & 0xE0) == 0xC0) {
                ++$pos;
                --$len;
            }
            elseif (($a & 0xF0) == 0xE0) {
                $pos += 2;
                $len -= 2;
            }
            elseif (($a & 0xF8) == 0xF0) {
                $pos += 3;
                $len -= 2;
            }
        }
        return $len;
    }
}

/*
 bool is_list(array $a)
 if $a is list, return true else false
 */
function is_list(array $a) {
    $count = count($a);
    if ($count === 0) return true;
    return !array_diff_key($a, array_fill(0, $count, NULL));
}

/*
 mixed array_ref_search(mixed &$value, array $array)
 if $value ref in $array, return the index else false
*/
function array_ref_search(&$value, &$array) {
    if (!is_array($value)) return array_search($value, $array, true);
    $temp = $value;
    foreach ($array as $i => &$ref) {
        if (($ref === ($value = 1)) && ($ref === ($value = 0))) {
            $value = $temp;
            return $i;
        }
    }
    $value = $temp;
    return false;
}

/*
 string spl_object_hash(object $obj)
 This function returns a unique identifier for the object.
 This id can be used as a hash key for storing objects or for identifying an object.
*/
if (!function_exists('spl_object_hash')) {
    function spl_object_hash($object) {
        ob_start();
        var_dump($object);
        preg_match('[#(\d+)]', ob_get_clean(), $match);
        return $match[1];
    }
}
?>