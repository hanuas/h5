<?php
require_once "lib/WxPay.Api.php";

/**
 * 
 * H5支付实现类
 * @author widyhu
 *
 */
class H5Pay
{
	
	/**
	 * 
	 * 生成直接支付url，支付url有效期为2小时,模式二
	 * @param UnifiedOrderInput $input
	 */
	public function GetPayUrl($input)
	{
            $result = WxPayApi::unifiedOrder($input);
			return $result;
	}
}