<?php
namespace app\plugins\expressforkdn\index;

use think\Controller;
use app\service\PluginsService;

/**
 * 快递鸟API接口 - 前端
 * @author   GuoGuo
 * @blog     http://gadmin.cojz8.com/
 * @version  1.0.0
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    /**
     * 获取物流信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-12
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [type]                   [description]
     */
    public function getexpinfo($params = [])
    {
        // html
        $html = '';

        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'express_id',
                'error_msg'         => '快递id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'express_number',
                'error_msg'         => '快递单号有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1, '<p>'.$ret.'</p>');
        }

        // 获取配置数据
        $ret = PluginsService::PluginsData('expressforkdn');
        if($ret['code'] == 0)
        {
            // 是否配置物流代码
            if(empty($ret['data']['express_ids'][$params['express_id']]))
            {
                return DataReturn('请先再后台配置物流代码', -1, '<p>请先再后台配置物流代码</p>');
            }

            // 获取快递信息
            $data = $this->expresstraces(['shipper_code'=>$ret['data']['express_ids'][$params['express_id']],'logistic_code'=>$params['express_number'], 'config'=>$ret['data']]);

            // 状态列表
            $status_arr = [
                0 => '暂无物流信息',
                1 => '快递公司已揽收',
                2 => '快递正在配送途中...',
                3 => '该物流已被签收',
                4 => '该物流问题件，请咨询物流商处理！',
            ];
            if(!isset($data['State']) || !isset($status_arr[$data['State']]))
            {
                return DataReturn('查询失败', -1, '<p>查询失败</p>');
            }

            // 开始处理
            $html .='<p>'.$status_arr[$data['State']].'</p>';
            $html .= '<ul class="am-list am-list-static">';

            // 快递信息
            if(in_array($data['State'], [2,3]) && !empty($data['Traces']) && is_array($data['Traces']))
            {
                foreach($data['Traces'] as $k=>$v)
                {
                    $html .='<li class="items">'.$v['AcceptTime'].''.$v['AcceptStation'].'</li>';
                }
            }
            return DataReturn('处理成功', 0, $html);
        } else {
            return DataReturn($ret['msg'], -100);
        }
    }

    /**
     * 获取物流信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-12
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [type]                   [description]
     */
    public function expresstraces($params = [])
    {
        // 参数
        if(empty($params['shipper_code']) || empty($params['logistic_code']) || empty($params['config']))
        {
            return ['State'=>0, 'Reason'=>'请求参数有误'];
        }

        // 请求数据
        $url = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
        $request_data = json_encode([
            'OrderCode'     => '',
            'ShipperCode'   => $params['shipper_code'],
            'LogisticCode'  => $params['logistic_code'],
        ]);
        $data = array(
            'EBusinessID'   => $params['config']['ebid'],
            'RequestType'   => '1002',
            'RequestData'   => urlencode($request_data) ,
            'DataType'      => '2',
        );
        $data['DataSign'] = $this->encrypt($request_data, $params['config']['appkey']);
        return $this->request_post($url, $data);    
    }
 
    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $data 提交的数据 
     * @return url响应返回的html
     */
    public function request_post($url, $data)
    {
        $temps = array();   
        foreach ($data as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);      
        }   
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;   
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);  
        
        return json_decode($gets, true);
    }
    /**
     * 电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名
     */
    public function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}
?>