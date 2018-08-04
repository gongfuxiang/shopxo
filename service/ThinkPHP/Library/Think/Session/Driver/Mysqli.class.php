<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: hainuo<admin@hainuo.info> liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// | change  mysql to mysqli  解决php7没有mysql扩展时数据库存放session无法操作的问题
// +----------------------------------------------------------------------
namespace Think\Session\Driver;
/**
 * 数据库方式Session驱动
 *    CREATE TABLE think_session (
 *      session_id varchar(255) NOT NULL,
 *      session_expire int(11) NOT NULL,
 *      session_data blob,
 *      UNIQUE KEY `session_id` (`session_id`)
 *    );
 */
class Mysqli
{

    /**
     * Session有效时间
     */
    protected $lifeTime = '';

    /**
     * session保存的数据库名
     */
    protected $sessionTable = '';

    /**
     * 数据库句柄
     */
    protected $hander = array();

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName)
    {
        $this->lifeTime = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime');
        $this->sessionTable = C('SESSION_TABLE') ? C('SESSION_TABLE') : C("DB_PREFIX") . "session";
        //分布式数据库
        $host = explode(',', C('DB_HOST'));
        $port = explode(',', C('DB_PORT'));
        $name = explode(',', C('DB_NAME'));
        $user = explode(',', C('DB_USER'));
        $pwd = explode(',', C('DB_PWD'));
        if (1 == C('DB_DEPLOY_TYPE')) {
            //读写分离
            if (C('DB_RW_SEPARATE')) {
                $w = floor(mt_rand(0, C('DB_MASTER_NUM') - 1));
                if (is_numeric(C('DB_SLAVE_NO'))) {//指定服务器读
                    $r = C('DB_SLAVE_NO');
                } else {
                    $r = floor(mt_rand(C('DB_MASTER_NUM'), count($host) - 1));
                }
                //主数据库链接
                $hander = mysqli_connect(
                    $host[$w] . (isset($port[$w]) ? ':' . $port[$w] : ':' . $port[0]),
                    isset($user[$w]) ? $user[$w] : $user[0],
                    isset($pwd[$w]) ? $pwd[$w] : $pwd[0]
                );
                $dbSel = mysqli_select_db(
                    $hander,
                    isset($name[$w]) ? $name[$w] : $name[0]
                );
                if (!$hander || !$dbSel)
                    return false;
                $this->hander[0] = $hander;
                //从数据库链接
                $hander = mysqli_connect(
                    $host[$r] . (isset($port[$r]) ? ':' . $port[$r] : ':' . $port[0]),
                    isset($user[$r]) ? $user[$r] : $user[0],
                    isset($pwd[$r]) ? $pwd[$r] : $pwd[0]
                );
                $dbSel = mysqli_select_db(
                    $hander,
                    isset($name[$r]) ? $name[$r] : $name[0]
                );
                if (!$hander || !$dbSel)
                    return false;
                $this->hander[1] = $hander;
                return true;
            }
        }
        //从数据库链接
        $r = floor(mt_rand(0, count($host) - 1));
        $hander = mysqli_connect(
            $host[$r] . (isset($port[$r]) ? ':' . $port[$r] : ':' . $port[0]),
            isset($user[$r]) ? $user[$r] : $user[0],
            isset($pwd[$r]) ? $pwd[$r] : $pwd[0]
        );
        $dbSel = mysqli_select_db(
            $hander,
            isset($name[$r]) ? $name[$r] : $name[0]
        );
        if (!$hander || !$dbSel)
            return false;
        $this->hander = $hander;
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        if (is_array($this->hander)) {
            $this->gc($this->lifeTime);
            return (mysqli_close($this->hander[0]) && mysqli_close($this->hander[1]));
        }
        $this->gc($this->lifeTime);
        return mysqli_close($this->hander);
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
    public function read($sessID)
    {
        $hander = is_array($this->hander) ? $this->hander[1] : $this->hander;
        $res = mysqli_query($hander, "SELECT session_data AS data FROM " . $this->sessionTable . " WHERE session_id = '$sessID'   AND session_expire >" . time());
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            return $row['data'];
        }
        return "";
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     */
    public function write($sessID, $sessData)
    {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        $expire = time() + $this->lifeTime;
        mysqli_query($hander, "REPLACE INTO  " . $this->sessionTable . " (  session_id, session_expire, session_data)  VALUES( '$sessID', '$expire',  '$sessData')");
        if (mysqli_affected_rows($hander))
            return true;
        return false;
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
    public function destroy($sessID)
    {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        mysqli_query($hander, "DELETE FROM " . $this->sessionTable . " WHERE session_id = '$sessID'");
        if (mysqli_affected_rows($hander))
            return true;
        return false;
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
    public function gc($sessMaxLifeTime)
    {
        $hander = is_array($this->hander) ? $this->hander[0] : $this->hander;
        mysqli_query($hander, "DELETE FROM " . $this->sessionTable . " WHERE session_expire < " . time());
        return mysqli_affected_rows($hander);
    }

}
