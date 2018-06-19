<?php
namespace common\wechat;
/**
 * 
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
