<?php 
/**
*	后台主菜单配置
*	@乔成磊 20150816
*/

return array( //BEGIN MENU!!!

/*
*	游戏管理
*/
"mmenu_game_manage"=>[
	"auth"=>["m"=>"game_manage","type"=>"m"],
	"name"=>"游戏管理",
	"icon"=>'<i class="menu-icon fa fa-gamepad"></i>',
	"submenu"=>array(
	
		/*
		* 
		*/
		"mmenu_game_manage_game"=>[//key是ID
			"auth"=>["c"=>"game","type"=>"mc"],
			"name"=>"游戏管理",
			"submenu"=>array(),
		],
		
	)
	
],//END 游戏管理


/*
*	充值管理
*/
"mmenu_pay_manage"=>[
	"auth"=>["m"=>"pay_manage","type"=>"m"],
	"name"=>"充值管理",
	"icon"=>'<i class="menu-icon fa fa-cny"></i>',
	"submenu"=>array(
	
		/*
		* 
		*/
		"mmenu_pay_manage_orders"=>[//key是ID
			"auth"=>["c"=>"orders","type"=>"mc"],
			"name"=>"订单查询",
			"submenu"=>array(),
		],
		"mmenu_pay_manage_payChannels"=>[//key是ID
			"auth"=>["c"=>"payChannels","type"=>"mc"],
			"name"=>"充值渠道管理",
			"submenu"=>array(),
		],
		
	)
	
],//END 充值管理



	

/*
*	系统设置
*/
"mmenu_system"=>[
	"auth"=>["m"=>"admin","type"=>"m"],
	"name"=>"系统设置",
	"icon"=>'<i class="menu-icon fa fa-cog"></i>',
	"submenu"=>array(
	
		/*
		*	首页
		*/
		"mmenu_index"=>[//key是ID
			"auth"=>["m"=>"index","type"=>"m"],
			//"icon"=>'<i class="menu-icon fa fa-user"></i>',
			"name"=>"我的",
			"submenu"=>array(
				"mmenu_my_info"=>[
						"auth"=>["c"=>"home","a"=>"modify","type"=>"mca"],
						"name"=>"修改个人信息",
						"submenu"=>array(),
				],
		
				"mmenu_my_pass"=>[
						"auth"=>["c"=>"login","a"=>"change_pass","type"=>"mca"],
						"name"=>"修改密码",
						"submenu"=>array(),
				],
			),
		],

		//操作日志
		"mmenu_oplog"=>[
				"auth"=>["m"=>"admin","c"=>"action_log","type"=>"mc"],
				"name"=>"操作日志",
				"submenu"=>array(),
		],
		
		//系统变量
		"mmenu_sys_vars"=>[
				"auth"=>["m"=>"admin","c"=>"system","type"=>"mc"],
				"name"=>"系统变量",
				"submenu"=>array(),
		],
		
		//后台用户管理
		"mmenu_sysuser_manage"=>[
		
				"auth"=>["m"=>"sysuser","type"=>"m"],
				"name"=>"后台用户管理",
				//"icon"=>'<i class="menu-icon fa fa-user"></i>',
				"submenu"=>array(
					//用户管理
					"mmenu_sysuser_info"=>[
							"auth"=>["c"=>"adminIndex","type"=>"mc"],
							"name"=>"用户管理",
							"submenu"=>array(),
					],
					
					//角色管理
					"mmenu_sysuser_role"=>[
							"auth"=>["c"=>"role","type"=>"mc"],
							"name"=>"角色管理",
							"submenu"=>array(),
					],
					
					//权限管理
					"mmenu_sysuser_privilege"=>[
							"auth"=>["c"=>"privilege","type"=>"mc"],
							"name"=>"权限管理",
							"submenu"=>array(),
					],
				),
		],//END 后台用户管理
		
		
	)
	
],//END 系统设置


	
	
);//END MENU!!!