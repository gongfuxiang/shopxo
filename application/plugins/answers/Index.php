<?php
namespace app\plugins\answers;

use think\Controller;
use app\service\PluginsService;
use app\service\AnswerService;
use app\service\UserService;
use app\plugins\answers\Service;

/**
 * 问答 - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    /**
     * 前端页面入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-07
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        // 基础数据
        $base = PluginsService::PluginsData('answers', ['images']);
        $this->assign('plugins_answers_data', isset($base['data']) ? $base['data'] : []);

        // 幻灯片
        $data_params = [
            'where'     => ['is_enable'=>1],
        ];
        $slider = Service::SlideList($data_params);
        $this->assign('plugins_answers_slider', isset($slider['data']) ? $slider['data'] : []);

        // 商品数据
        $goods = Service::GoodsList();
        $this->assign('plugins_answers_goods_list', $goods['data']['goods']);

        // 最新问答内容
        $middle_new_page_number = isset($base['data']['middle_new_page_number']) ? intval($base['data']['middle_new_page_number']) : 15;
        $answer = Service::AnswerList(['n'=>$middle_new_page_number]);
        $this->assign('plugins_answers_middle_answer_list', $answer['data']);

        // 推荐问答
        if(!empty($base['data']['category_ids']))
        {
            $answers = Service::AnswerList(['n'=>100, 'category_ids'=> $base['data']['category_ids']]);
            $this->assign('plugins_answers_rc_list', $answers['data']);
        } else {
            $this->assign('plugins_answers_rc_list', []);
        }

        return $this->fetch('../../../plugins/view/answers/index/index');
    }

    /**
     * 提问
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function answer($params = [])
    {
        $params = input('post.');
        $params['user'] = UserService::LoginUserInfo();
        return AnswerService::Add($params);
    }
}
?>