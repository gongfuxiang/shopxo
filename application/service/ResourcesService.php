<?php
namespace app\service;

/**
 * 资源服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesService
{
    /**
     * 获取地区名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $express_id [快递id]
     */
    public static function ExpressName($express_id = 0)
    {
        return empty($express_id) ? null : db('Express')->where(['id'=>intval($express_id)])->value('name');
    }

    /**
     * 获取订单支付名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [int]          $order_id [订单id]
     */
    public static function OrderPaymentName($order_id = 0)
    {
        return empty($order_id) ? null : db('PayLog')->where(['order_id'=>intval($order_id)])->value('payment_name');
    }

    /**
     * 快递列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ExpressList($params = [])
    {
        $where = [];
        if(isset($params['is_enable']))
        {
            $where['is_enable'] = intval($params['is_enable']);
        }
        $data = db('Express')->where($where)->field('id,icon,name,sort,is_enable')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['icon_old'] = $v['icon'];
                $v['icon'] = empty($v['icon']) ? null : $images_host.$v['icon'];
            }
        }
        return $data;
    }

    /**
     * 支付方式列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PaymentList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        if(isset($params['is_enable']))
        {
            $where['is_enable'] = intval($params['is_enable']);
        }
        if(isset($params['is_open_user']))
        {
            $where['is_open_user'] = intval($params['is_open_user']);
        }

        $data = db('Payment')->where($where)->field('id,logo,name,sort,payment,config,apply_terminal,apply_terminal,element,is_enable,is_open_user')->order('sort asc')->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['logo_old'] = $v['logo'];
                $v['logo'] = empty($v['logo']) ? null : $images_host.$v['logo'];
                $v['element'] = empty($v['element']) ? '' : json_decode($v['element'], true);
                $v['config'] = empty($v['config']) ? '' : json_decode($v['config'], true);
                $v['apply_terminal'] = empty($v['apply_terminal']) ? '' : json_decode($v['apply_terminal'], true);
            }
        }
        return $data;
    }

    /**
     * 获取支付方式列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    下订单根据终端自动筛选支付方式
     * @param   [array]          $params [输入参数]
     */
    public static function BuyPaymentList($params = [])
    {
        $data = self::PaymentList($params);

        $result = [];
        if(!empty($data))
        {
            foreach($data as $v)
            {
                // 根据终端类型筛选
                if(in_array(APPLICATION_CLIENT_TYPE, $v['apply_terminal']))
                {
                    $result[] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * 获取地区节点数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RegionNode($params = [])
    {
        $field = empty($params['field']) ? 'id,name,level,letters' : $params['field'];
        $where = empty($params['where']) ? [] : $params['where'];
        $where['is_enable'] = 1;

        return db('Region')->where($where)->field($field)->order('id asc, sort asc')->select();
    }

    /**
     * [ContentStaticReplace 编辑器中内容的静态资源替换]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-22T16:07:58+0800
     * @param    [string]    $content [在这个字符串中查找进行替换]
     * @param    [string]    $type    [操作类型[get读取额你让, add写入内容](编辑/展示传入get,数据写入数据库传入add)]
     * @return   [string]             [正确返回替换后的内容, 则返回原内容]
     */
    public static function ContentStaticReplace($content, $type = 'get')
    {
        switch($type)
        {
            // 读取内容
            case 'get':
                return str_replace('/static/', __MY_URL__.'static/', $content);
                break;

            // 内容写入
            case 'add':
                return str_replace(array(__MY_URL__.'static/', __MY_ROOT__.'static/'), '/static/', $content);
        }
        return $content;
    }

    /**
     * 附件路径处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-12
     * @desc    description
     * @param   [string]          $value [附件路径地址]
     */
    public static function AttachmentPathHandle($value)
    {
        return str_replace([__MY_URL__, __MY_ROOT__], DS, $value);
    }

    /**
     * APP获取首页导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-19
     * @desc    description
     * @param   array           $params [description]
     */
    public static function AppHomeNav($params = [])
    {
        $data = db('AppHomeNav')->field('id,name,images_url,event_value,event_type,bg_color')->where(['platform'=>APPLICATION_CLIENT_TYPE, 'is_enable'=>1])->order('sort asc')->select();
        if(!empty($data))
        {
            $images_host = config('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['images_url_old'] = $v['images_url'];
                $v['images_url'] = $images_host.$v['images_url'];
                $v['event_value'] = empty($v['event_value']) ? null : $v['event_value'];
            }
        }
        return $data;
    }

}
?>