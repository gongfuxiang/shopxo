<?php

namespace Api\Controller;

/**
 * 支付宝生活号回调处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class AlipayLifeController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();
    }

    /**
     * 购买确认
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    public function Index()
    {
        file_put_contents('./gggggg.txt', json_encode($_GET));
        file_put_contents('./pppppp.txt', json_encode($_POST));

        $_POST = json_decode('{
          "sign": "dAwU23dBoGse5Ky8+675uWVFwBtdjbMa8Eq6vtGzyEZW/GxPjqn+PVmevgamQbtMWhdaSD3XXDwH1re6hBe+CM6IIh2gbXTTCqC5f0iPKn233YqDsR5lQok6FffyjSFPfq0c6dggzIfT49roeOBIelEqWBtAZI1PLA7pE1BV215Z1Kyog5SDm/fpdrRP5jk3vVyI8Aa6HDLfWv1diqBYkW5Y1rQ1R6ahKczkvtv0BDJKeQVGvmZZ6kMVtbk6UbBJjgqa7tD7iMldnqNr682OZTw58DDZ44acs1ClNVo/zGKDJ1Tqojz/1EiCnz8WdXqEhsH8igv9S2akHshBW/UEvQ==", 
          "charset": "GBK", 
          "biz_content": "<?xml version=\"1.0\" encoding=\"gbk\"?><XML><AppId><![CDATA[2015070400156153]]></AppId><FromUserId></FromUserId><CreateTime><![CDATA[1540193934274]]></CreateTime><MsgType><![CDATA[event]]></MsgType><EventType><![CDATA[verifygw]]></EventType><ActionParam></ActionParam><AgreementId></AgreementId><AccountNo></AccountNo></XML>", 
          "sign_type": "RSA2", 
          "service": "alipay.service.check"
        }', true);

        // 参数
        $params = $_POST;
        if(empty($params['service']))
        {
            die('service error');
        }

        // 类库
        $o = new \Library\AlipayLife($params);

        // 根据方法处理
        switch($params['service'])
        {
            // 校验
            case 'alipay.service.check' :
                $o->Check();
                break;

            // 默认
            default :
                exit('service error');
        }
    }

}
?>