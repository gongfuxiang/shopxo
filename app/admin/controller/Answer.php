<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\AnswerService;

/**
 * 问答管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Answer extends Base
{
	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
        return MyView();
	}

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-30
     * @desc    description
     */
    public function Detail()
    {
        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 模板数据
        $assign = [
            // 静态数据
            'common_is_show_list' => MyConst('common_is_show_list'),
            'common_is_text_list' => MyConst('common_is_text_list'),
        ];
        // 数据
        $data = $this->data_detail;
        if(!empty($data))
        {
            // 内容
            if(!empty($data['content']))
            {
                $data['content'] = str_replace('<br />', "\n", $data['content']);
            }

            // 回复内容
            if(!empty($data['reply']))
            {
                $data['reply'] = str_replace('<br />', "\n", $data['reply']);
            }
        }
        $assign['data'] = $data;

        // 参数
        $params = $this->data_request;
        unset($params['id']);
        $assign['params'] = $params;

        // 数据赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Save()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(AnswerService::AnswerSave($params));
    }

	/**
	 * 问答删除
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
        $params = $this->data_request;
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(AnswerService::AnswerDelete($params));
	}

	/**
	 * 问答回复处理
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-03-28T15:07:17+0800
	 */
	public function Reply()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(AnswerService::AnswerReply($params));
	}

	/**
	 * 状态更新
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusUpdate()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(AnswerService::AnswerStatusUpdate($params));
	}
}
?>