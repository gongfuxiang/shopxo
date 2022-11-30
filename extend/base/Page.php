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
	private $page_size;
	private $total;
	private $bt_number;
	private $where;
	private $not_fields;
	private $page_total;
	private $url;
	private $html;
	private $page_join;
	private $tips_msg;

	/**
	 * 构造方法
	 * @param [int]    $params['page'] 				[页码]
	 * @param [int]    $params['page_size / number'][每页数据条数]
	 * @param [int]    $params['total'] 			[数据总数]
	 * @param [int]    $params['bt_number'] 		[分页显示按钮个数]
	 * @param [array]  $params['where'] 			[额外条件(键值对)]
	 * @param [array]  $params['not_fields'] 		[不参与条件拼接的字段]
	 * @param [string] $params['url'] 				[url地址]
	 */
	public function __construct($params = [])
	{
		$this->page = max(1, isset($params['page']) ? intval($params['page']) : 1);
		$this->page_size = empty($params['page_size']) ? (empty($params['number']) ? 10 : intval($params['number'])) : intval($params['page_size']);
		$this->total = max(1, isset($params['total']) ? intval($params['total']) : 1);
		$this->bt_number = isset($params['bt_number']) ? intval($params['bt_number']) : 2;
		$this->where = (isset($params['where']) && is_array($params['where'])) ? $params['where'] : '';
		$this->not_fields = (!empty($params['not_fields']) && is_array($params['not_fields'])) ? $params['not_fields'] : [];
		$this->url = isset($params['url']) ? $params['url'] : '';
		$this->tips_msg = empty($params['tips_msg']) ? '' : trim($params['tips_msg']);
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
		$this->page_total = ceil($this->total/$this->page_size);
		if($this->page > $this->page_total) $this->page = $this->page_total;

		/* url是否包含问号 */
		$state = stripos($this->url, '?');

		/* 额外条件url设置 */
		if(!empty($this->where) && is_array($this->where))
		{
			$tmp = true;
			foreach($this->where as $k=>$v)
			{
				if(!in_array($k, $this->not_fields) && !is_array($v))
				{
					if($k == 'page')
					{
						continue;
					}
					$k = empty($k) ? $k : htmlspecialchars($k);
					$v = empty($v) ? $v : htmlspecialchars($v);

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
			$this->page_join = ($tmp == false) ? '&' : (($state === false) ? '?' : '&');
		} else {
			$this->page_join = ($state === false) ? '?' : '&';
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
		$this->html .= '<a href="'.$this->url.$this->page_join.'page=1" class="am-radius am-icon-angle-double-left"></a>';
		$this->html .= '</li>';

		$this->html .= '<li '.$before_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_join.'page='.($this->page-1).'" class="am-radius am-icon-angle-left"></a>';
		$this->html .= '</li>';

		$this->html .= $this->GetButtonNumberHtml();

		$this->html .= '<li '.$after_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_join.'page='.($this->page+1).'" class="am-radius am-icon-angle-right"></a>';
		$this->html .= '</li>';

		$this->html .= '<li '.$after_disabled.'>';
		$this->html .= '<a href="'.$this->url.$this->page_join.'page='.$this->page_total.'" class="am-radius am-icon-angle-double-right"></a>';
		$this->html .= '</li>';

		$this->html .= '<span class="am-margin-left-sm">每页</span>';
		$this->html .= '<input type="text" min="1" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="'.$this->page_size.'" onchange="window.location.href=\''.str_replace(['&page_size='.$this->page_size, 'page_size='.$this->page_size.'&'], '', $this->url).$this->page_join.'page_size=\'+(isNaN(parseInt(this.value)) ? 10 : parseInt(this.value) || 10);" onclick="this.select()" />';
		$this->html .= '<span>条</span>';

		$this->html .= '<span class="am-margin-left-sm">跳转到</span>';
		$this->html .= '<input type="text" min="1" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="'.$this->page.'" onchange="window.location.href=\''.$this->url.$this->page_join.'page=\'+(isNaN(parseInt(this.value)) ? 1 : parseInt(this.value) || 1);" onclick="this.select()" />';
		$this->html .= '<span>页</span>';

		$this->html .= '<div>';
		$this->html .= '<span>共 '.$this->total.' 条数据</span>';
		$this->html .= '<span class="am-margin-left-sm">共 '.$this->page_total.' 页</span>';
		if(!empty($this->tips_msg))
		{
			$this->html .= '<span class="am-margin-left-sm">'.$this->tips_msg.'</span>';
		}
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
					$html_before = '<li><a href="'.$this->url.$this->page_join.'page='.$i.'" class="am-radius">'.$i.'</a></li>'.$html_before;
				}
			}

			/* 后按钮 */
			if($this->page_total > $this->page)
			{
				$total = ($this->page+$this->bt_number > $this->page_total) ? $this->page_total : $this->page+$this->bt_number;
				for($i=$this->page+1; $i<=$total; $i++)
				{
					$html_after .= '<li><a href="'.$this->url.$this->page_join.'page='.$i.'" class="am-radius">'.$i.'</a></li>';
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
		return intval(($this->page-1)*$this->page_size);
	}
}
?>