<?php
class ErrorCodeService{
    const NET_ERROR = 4; //网络错误
    const MOBILE_UNBIND = 10056; //当前还未绑定任何信息
    const MOBILE_INUSE = 10008; //该手机号已被使用
    const PARAMETER_ERROR = 10021; //参数错误
    const MOBILE_CODE_ERROR = 10031; //验证码错误
    const MOBILE_CODE_INVALID = 10039; //验证码失效(已使用)
    const MOBILE_CODE_TIMEOUT = 10033; //验证码过期
    const MOBILE_CODE_OVER_LIMIT = 10052; //发送验证码超过次数限制
    const RESET_PASSWORD_FAILED = 10037; //重置密码错误
    const USER_NOT_EXIST = 10016; //用户不存在
    const USER_PASSWORD_ERROR = 10010;//用户名或密码错误
    const USER_TOKEN_ERROR = 10054; //无效的token信息
    const USER_NOT_VERIFY_REAL_NAME = 10089; //还未进行实名认证
    const USER_ID_CARD_EXIST = 10097; //身份证已被使用
    const UNVAILD_GOOD = 10082; //无效的商品

    const GAME_NOT_EXIST = 10000001;//游戏不存在
    const GAME_ITEM_NOT_EXIST = 10000002;//游戏商品不存在
    const INSERT_DB_ERROR = 10000003;//添加数据库错误

}


/**
 * SDK 标识码详细说明 
 
return [
	// 错误代码
	"10001"=>"参数不能为空",
	"10002"=>"非法参数",
	"10003"=>"验证异常，请重试", //非法的数字签名
	"10004"=>"手机号格式不正确",
	"10005"=>"该手机号已被注册，请直接登录",
	"10006"=>"注册失败",
	"10007"=>"账号绑定失败",
	"10008"=>"该手机号已被使用",
	"10009"=>"该邮箱已被使用",
	"10010"=>"用户名或密码错误",
	"10011"=>"token已失效，请重新登录",
	"10012"=>"token验证失败",
	"10013"=>"密码不符合要求",
	"10014"=>"账号或密码不能为空",
	"10015"=>"请输入正确的用户名",
	"10016"=>"该用户帐号不存在",
	"10017"=>"该账号已被使用",
	"10018"=>"游戏id不能为空",
	"10019"=>"厂商id不能为空",
	"10020"=>"无效的游戏信息",
	"10021"=>"参数错误",
	"10022"=>"该账号还未绑定手机号或邮箱",
	"10023"=>"不能频繁发送邮件,请稍后再试",
	"10024"=>"重新选择找回密码方式",
	"10025"=>"邮件发送失败",
	"10026"=>"一天只有三次找回密码的机会",
	"10027"=>"一天只有五次找回密码的机会",
	"10028"=>"该手机号与绑定的号码不匹配",
	"10029"=>"验证码发送失败",
	"10030"=>"验证码不能为空",
	"10031"=>"验证码错误",
	"10032"=>"账号信息不正确",
	"10033"=>"验证码已过期",
	"10034"=>"UID不能为空",
	"10035"=>"请输入新密码",
	"10036"=>"参数错误，重置密码失败",
	"10037"=>"重置密码失败",
	"10038"=>"获取信息失败",
	"10039"=>"该验证码已失效",
	"10040"=>"请输入正确的邮箱",
	"10041"=>"该邮箱与绑定的邮箱不匹配",
	"10042"=>"绑定账号不能为空",
	"10043"=>"该号码已被使用",
	"10044"=>"邮箱不能为空",
	"10045"=>"手机号不能为空",
	"10046"=>"同一邮箱不允许重复绑定",
	"10047"=>"同一手机不允许重复绑定",
	"10048"=>"每天只能认证三次",
	"10049"=>"账号类型不能为空",
	"10050"=>"token 不能为空",
	"10051"=>"无效的登录信息",
	"10052"=>"一天只能注册三次",
	"10053"=>"vstr不能参数为空",
	"10054"=>"无效的token信息",
	"10055"=>"用户名不能为空",
	"10056"=>"当前还未绑定任何信息",
	"10057"=>"原来密码不能为空",
	"10058"=>"原来密码不正确",
	"10059"=>"请输入密码",
	"10060"=>"密码错误",
	"10061"=>"您已绑定该账号",
	"10062"=>"无效的请求信息",
	"10063"=>"无效的 appkey",
	"10064"=>"无效的 appid",
	"10065"=>"不能跟原密码相同",
	"10066"=>"唯一标识不能为空",
	"10067"=>"游戏大区不能为空",
	"10068"=>"游戏服不能为空",
	"10069"=>"角色id不能为空",
	"10070"=>"角色名称不能为空",
	"10071"=>"商品id不能为空",
	"10072"=>"商品名称不能为空",
	"10073"=>"数量不能为空",
	"10074"=>"单价不能为空",
	"10075"=>"游戏账号不能为空",
	"10076"=>"支付来源错误",
	"10077"=>"无效的订单号",
	"10078"=>"无效的订单信息",
	"10079"=>"支付失败",
	"10080"=>"无效的支付信息",
	"10081"=>"无效的App版本",
	"10082"=>"无效的商品信息",
	"10083"=>"第三方uid为空",
	"10084"=>"第三方登录类型不能为空",
	"10085"=>"两次输入的信息不一致",
	"10086"=>"该功能暂未开启",
		
	"10087" => "请输入真实姓名",
	"10088" => "请输入正确的身份证号码",
	"10089" => "还未实名认证",
	"10090" => "年龄未满18周岁",		
	"10091" => "角色等级不能为空",
	"10092" => "无效的支付信息",

    "10093" => "新闻ID不能为空",
    "10094" => "新闻分类ID不能为空",
    "10095" => "无效的productID",


	// 银联部分
	"10101"=>"无效的参数信息",
	"10102"=>"应答报文验签失败",
	"10103"=>"获取信息失败",
	"10104"=>"返回标识有误",
	"10105"=>"返回信息有误",


	// 支付宝部分
	"10201"=>"正在处理中，支付结果未知",	// 支付宝 8000
	"10202"=>"订单支付失败",			// 支付宝 4000
	"10203"=>"用户中途取消",			// 支付宝 6001
	"10204"=>"网络连接出错",			// 支付宝 6002
	"10205"=>"支付结果未知",			// 支付宝 6004
	"10206"=>"其它支付错误",			// 支付宝 其它
	
	
	"1201"=>"请求不允许",
	"1203"=>"请求太频繁",
    "1204"=>"支付签名验证失败",
    "1205"=>"其他错误",

	// 成功代码
	"20000"=>"成功",
	"20001"=>"验证码已发到你的手机号码",
	"20002"=>"注册成功",
	"20003"=>"登录成功",
	"20004"=>"获取成功",
	"20005"=>"成功",
	"20006"=>"密码重置成功",
	"20007"=>"邮件已发到你的邮箱",
	"20008"=>"认证邮件已经发到您邮箱",
	"20009"=>"账号绑定成功",
	"20010"=>"账号退出成功",
	"20011"=>"密码修改成功",
	"20012"=>"购买成功",
	"20013"=>"支付成功"
];
*/