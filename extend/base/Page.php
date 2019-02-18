<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace base;

/**
 * 分页驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Page
{
	private $page;
	private $total;
	private $number;
	private $bt_number;
	private $where;
	private $page_total;
	private $url;
	private $html;
	private $page_start_tag;

	/**
	 * [__construct description]
	 * @param [int]    $param['total'] 		[数据总数]
	 * @param [int]    $param['number'] 	[每页数据条数]
	 * @param [int]    $param['bt_number'] 	[分页显示按钮个数]
	 * @param [array]  $param['where'] 		[额外条件(键值对)]
	 * @param [string] $param['url'] 		[url地址]
	 */
	public function __construct($params = array())
	{
		$this->page = max(1, isset($params['page']) ? intval($params['page']) : 1);
		$this->total = max(1, isset($params['total']) ? intval($params['total']) : 1);
		$this->number = max(1, isset($params['number']) ? intval($params['number']) : 1);
		$this->bt_number = isset($params['bt_number']) ? intval($params['bt_number']) : 2;
		$this->where = (isset($params['where']) && is_array($params['where'])) ? $params['where'] : '';
		$this->url = isset($params['url']) ? $params['url'] : '';
		$this->page_total = 1;
		$this->html = '';

		/* 参数设置 */
		$this->SetParem();
	}

	/**
	 * [SetParem 参数设置]
	 */
	private function SetParem()
	{
		/* 防止超出最大页码数 */
		$this->page_total = ceil($this->total/$this->number);
		if($this->page > $this->page_total) $this->page = $this->page_total;

		/* url是否包含问号 */
		$state = stripos($this->url, '?');

		/* 额外条件url设置 */
		if(!empty($this->where) && is_array($this->where))
		{
			$tmp = true;
			foreach($this->where as $k=>$v)
			{
				if(!is_array($v))
				{
					if($k == 'page') continue;
					
					if($tmp)
					{
						$this->url .= ($state === false) ? '?' : '&';
						$this->url .= $k.'='.$v;
						$tmp = false;
					} else {
						$this->url .= '&'.$k.'='.$v;
					}
				}
			}
			$this->page_start_tag = ($tmp == false) ? '&' : (($state === false) ? '?' : '&');
		} else {
			$this->page_start_tag = ($state === false) ? '?' : '&';
		}
	}

	/**
	 * [GetPageHtml 获取生成好的分页代码]
	 */
	public function GetPageHtml()
	{
		$before_disabled = ($this->page > 1) ? '' : ' class="am-disabled"';
		$after_disabled = ($this->page > 0 && $this->page < $this->page_total) ? '' : ' class="am-disabled"';

		$this->html .= '<ul class="am-pagination am-pagination-centered">';
		$this->html .= '<li '.$before_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_start_tag.'page=1" class="am-radius am-icon-angle-double-left"></a>';
		$this->html .= '</li>';

		$this->html .= '<li '.$before_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_start_tag.'page='.($this->page-1).'" class="am-radius am-icon-angle-left"></a>';
		$this->html .= '</li>';

		$this->html .= $this->GetButtonNumberHtml();

		$this->html .= '<li '.$after_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_start_tag.'page='.($this->page+1).'" class="am-radius am-icon-angle-right"></a>';
		$this->html .= '</li>';

		$this->html .= '<li '.$after_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_start_tag.'page='.$this->page_total.'" class="am-radius am-icon-angle-double-right"></a>';
		$this->html .= '</li>';

		$this->html .= '<div>';
		$this->html .= '<span>共 '.$this->total.' 条数据</span>';
		$this->html .= '&nbsp;&nbsp;&nbsp;<span>共 '.$this->page_total.' 页</span>';
		$this->html .= '</div>';
		$this->html .= '</ul>';

		return $this->html;
	}

	/**
	 * [GetButtonNumberHtml 获取button显示个数的html]
	 * @return [string] [按钮个数html代码]
	 */
	private function GetButtonNumberHtml()
	{
		$html_before = '';
		$html_after = '';
		$html_page = '<li class="am-active"><a class="am-radius">'.$this->page.'</a></li>';
		if($this->bt_number > 0)
		{
			/* 前按钮 */
			if($this->page > 1)
			{
				$total = ($this->page-$this->bt_number < 1) ? 1 : $this->page-$this->bt_number;
				for($i=$this->page-1; $i>=$total; $i--)
				{
					$html_before = '<li><a href="'.$this->url.$this->page_start_tag.'page='.$i.'" class="am-radius">'.$i.'</a></li>'.$html_before;
				}
			}

			/* 后按钮 */
			if($this->page_total > $this->page)
			{
				$total = ($this->page+$this->bt_number > $this->page_total) ? $this->page_total : $this->page+$this->bt_number;
				for($i=$this->page+1; $i<=$total; $i++)
				{
					$html_after .= '<li><a href="'.$this->url.$this->page_start_tag.'page='.$i.'" class="am-radius">'.$i.'</a></li>';
				}
			}
		}
		return $html_before.$html_page.$html_after;
	}

	/**
	 * [GetPageStarNumber 获取分页起始值]
	 */
	public function GetPageStarNumber()
	{
		return intval(($this->page-1)*$this->number);
	}
}
?>