<?php 
/**
 * 后台Menu管理类
 * @author qiaochenglei
 * 
 *	
 */
 
class Admin_Menu{

	/*
	*	生成HTML格式的菜单
	*		$menuConf ,		菜单配置，返回格式化后的配置
	*		$allPrivilege, 	DB中读取的权限 
	*    返回值：HTML 格式菜单
	*/
	
	static function generateMenu(&$menuConf , &$allPrivilege , $addActiveMark = true){
		$parentAuth =[] ;
		list($shouldShow ,$childHasActive) = self::formatConf( $menuConf , $parentAuth , $allPrivilege);
		
		$html='<ul class="nav nav-list" style="top: 0px;">';
		$firstPrivilegedMenu=[];
		$fTraverse = function( &$menuConf ,&$html) use(&$fTraverse,$addActiveMark, &$firstPrivilegedMenu) {
			foreach($menuConf as $id=>&$conf){
				if($conf['show'] ){
					
					$url = "?m={$conf['auth']['m']}".
						(empty($conf['auth']["c"])? "" : "&c={$conf['auth']['c']}").
						(empty($conf['auth']["a"])? "" : "&a={$conf['auth']['a']}");
					
					$hasChild = $conf['childHasPrivilege'];
					$dropDowToggle = $hasChild?' class="dropdown-toggle"' : '';
					
					$liClass = "mmenu mmenu_{$conf['auth']['m']}";
					switch($conf['auth']['type']){
					case "mc":	$liClass .= "_".$conf['auth']['c']; break;
					case "mca":	$liClass .= "_".$conf['auth']['c']."_".$conf['auth']['a']; break;
					}
					
					if($addActiveMark){
						if(	$conf['active']	) 	$liClass .= " active ";
						if(	$conf['open']	) 	$liClass .= " open ";
					}
					if( $conf['active'] && !$conf['open'] ) $liClass .=" mmenu_leaf_active ";
					
					//$liClass .= " mmenu_authtype_{$conf['auth']['type']}";
					//添加URL
					$html.='<li class="'. $liClass .'" id="'.$id.'">
							<a href="'.$url.'" '.$dropDowToggle.'>';
					//添加ICON
					if(isset($conf['icon']))
						$html.=$conf['icon'];
					else
						$html.='<i class="menu-icon fa fa-caret-right"></i>';
					
					//添加菜单名
					$html.='<span class="menu-text">
									'.$conf['name'].'
							</span>';
					//添加折叠箭头
					if($hasChild)
						$html.='<b class="arrow fa fa-angle-down"></b>';
					//添加：不知为何物，反正都有
					$html.='</a><b class="arrow"></b>';
						
						
					//添加子菜单
					if($hasChild){
						$html.='<ul class="submenu">';
						$fTraverse($conf['submenu'],$html);
						$html.='</ul>';
					}else{//TODO: 记录第一个有权限的叶子菜单
						if(empty($firstPrivilegedMenu)){
							$firstPrivilegedMenu["conf"]=$conf;
						}
					}
					
					$html.'</li >';
						
				}//end if show
			}
		};
			
		$fTraverse( $menuConf , $html);
		
		$html.="
		</ul>";
		
		return [$html, $firstPrivilegedMenu];
	}
	
	
	
	/*
	*	格式化菜单配置，并根据权限在每个item里添加show变量
	*		$menuConf ,		菜单配置
	*		$parentAuth,	父节点权限配置，Array
	*		$allPrivilege, 	DB中读取的权限 
	*/
	
	public static function formatConf( &$menuConf ,	 &$parentAuth, &$allPrivilege ){
		
		$parentShouldShow = false;
		$parentShouldActive = false;
		
		
		foreach($menuConf as &$conf){
			
			//标准化格式：缺省权限配置继承父菜单
			switch( $conf["auth"]["type"]){
			case "mca":
				if(  !isset( $conf["auth"]["a"] ) && !empty($parentAuth["a"])  )
					$conf["auth"]["a"] = $parentAuth["a"];
				
			case "mc":
				if(  !isset( $conf["auth"]["c"] ) && !empty($parentAuth["c"])  )
					$conf["auth"]["c"] = $parentAuth["c"];
					
			case "m":
				if(  !isset( $conf["auth"]["m"] ) && !empty($parentAuth["m"])  )
					$conf["auth"]["m"] = $parentAuth["m"];
			
			}
			
			//递归子菜单
			$childHasPrivilege = false;
			$childHasActive = false;
			if(!empty($conf["submenu"])){
			
				list($childHasPrivilege ,$childHasActive)= 
						self::formatConf($conf["submenu"],$conf["auth"],$allPrivilege );
			}
			
			//显示设置
			
			$hasPrivilege 	= self::hasPrivilege($conf["auth"],$allPrivilege);
			$isActive 		= self::isActive($conf["auth"]);
			
			$conf["show"] = $hasPrivilege || $childHasPrivilege;//用于判断当前项是否显示
			
			$conf["childHasPrivilege"] = $childHasPrivilege;	//用于判断是否有一个下三角的箭头
			
			$conf["active"] = $isActive || $childHasActive  ;	//当前条目及其父条目，会被置为 active		
			$conf["open"] = $childHasActive  ;	//用于判断是否打开
			
			
			if($conf["show"] ){
				$parentShouldShow = true;
			}
			if($conf["active"]){
				$parentShouldActive = true;
			}
		
		}
		return [$parentShouldShow ,$parentShouldActive ];
		
	}
	
	
	
	/*
	*	判断当前项是否有权限
	*		$auth 当前菜单项
	*		$allPrivilege 从DB读取的用户权限数组
	*/
	public static function hasPrivilege(&$auth,&$allPrivilege){
		
			//var_dump($allPrivilege);exit;
		$m = isset($auth["m"]) ? strtolower($auth["m"])  : "index";
		$c = isset($auth["c"]) ? strtolower($auth["c"])  : "index";
		$a = isset($auth["a"]) ? strtolower($auth["a"])  : "index";
		
		
		foreach($allPrivilege as &$p){
			//var_dump($p);exit;
			
			$pm = isset($p["m"]) ? strtolower($p["m"] ) : "index";
			$pc = isset($p["c"]) ? strtolower($p["c"] ) : "index";
			$pa = isset($p["a"]) ? strtolower($p["a"] ) : "index";
// 			
// 			if($m= "cps_agent" && $c = "ca_orders"
// 			&&$pm= "cps_agent" 
// 			){
// 				//Doris\debugWeb([$auth,$allPrivilege]);
// 				Doris\debugWeb([[$m,$c,$a],[$pm,$pc,$pa]]);
// 			}
			switch( $p["authtype"]){
			case "m":
				if( $m == $pm   )
					return true;
				break;	
			case "mc":
				if( $m == $pm   && $c == $pc)
					return true;
				break;	
					
			case "mca": 
			case "mcab":
				if($m == $pm  && $c == $pc && $a == $pa)
					return true;
				break; 
			}
		}
		return false;
	}
	
	/*
	*	判断当前项是否有权限
	*		$auth 当前菜单项
	*		$allPrivilege 从DB读取的用户权限数组
	*/
	public static function isActive(&$auth){
		$m = @strtolower($auth["m"]);
		$c = @strtolower($auth["c"]);
		$a = @strtolower($auth["a"]);
		
		$curM = strtolower(_MODULE_);
		$curC = strtolower(_CONTROLLER_);
		$curA = strtolower(_ACTION_);
		switch( $auth["type"]){
		case "m":
			return $m == $curM;
		case "mc":
			return $m == $curM && $c == $curC ;	
		case "mca":
		case "mcab":
			return $m == $curM && $c == $curC && $a == $curA ;
		}
		
		return false;
	}
	
}