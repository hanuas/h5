<?php
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;

use Doris\DConfig,
Doris\DCache;

 

/**
 * @Description 前台用户管理
 * 
 */

class indexController extends commonController{
	public function indexAction(){
		$this->assign("menu", "user/menu.tpl");
        $this->assign("second_menu", "user/second_menu.tpl");
		$this->assign("js", "user/front_user_list.js");
		//$this->assign("js_para", json_encode(array("privilege_code"=>"n")));
		
		$this->assign("title", '用户管理');
		$this->render(false, "common_list.tpl");
	}
	
	public function index_ajaxAction(){
    	$db = Doris\DApp::loadDT();
    	$editor = Editor::inst( $db, 'user' )
    	->field(
    			Field::inst( 'id' ),
    			Field::inst('user_name'),
    			Field::inst('nick_name'),
    			Field::inst( 'phone'),
    			Field::inst( 'email'),
    			Field::inst('user_status'),
    			Field::inst('qq'),
    			Field::inst('weibo'),
    			Field::inst('weixin'),
    			Field::inst('qqweibo'),
    			Field::inst('score'),
    			Field::inst('birthday')
					->validator( 'Validate::dateFormat', array(
						'empty' => false,
						"format"  => Format::DATE_ISO_8601,
						"message" => "Please enter a date in the format yyyy-mm-dd"
					) )
					->getFormatter( function ($val, $data, $field){
						return Doris\timestamp2str_date($val);
					})
					->setFormatter( function ($val, $data, $field) {
						return Doris\str_date2timestamp($val);
					}),
    			Field::inst('gender'),
    			Field::inst('portrait'),
    			Field::inst('career'),
    			Field::inst('bindinfo'),
    			Field::inst('career_cat'),
    			Field::inst('signature'),
    			Field::inst('moreinfo'),
    			Field::inst('create_time')
    				// ->validator( 'Validate::dateFormat', array(
// 						'empty' => false,
// 						'format' => 'Y-m-d H:i'
// 					) )
					->getFormatter( function ($val, $data, $field){
						return Doris\timestamp2str_datetime($val,false);
					})
// 					->setFormatter( function ($val, $data, $field) {
// 						return Doris\str_datetime2timestamp($val);
// 					})
					,
    			Field::inst( 'user_pwd')->validator( 'Validate::required' )
    			->setFormatter( function ($val, $data, $field) {
                    if(@$data['id']){
    				    $userinfo =  Doris\DApp::newClass('Admin_UserModel')->readUserById($data['id']);
    				    $old_md5_pass=$userinfo['user_pwd'];
                        $new_md5_pass= md5(md5($val));
                        if($old_md5_pass==$val){
                            return $val;//密码未做更改
                        }else{
                            return $new_md5_pass;
                        }
                    }else{
                        return md5($val);
                    } 
    			} 
    			),

    			Field::inst( 'gender')
    	);
        $userModel = Doris\DApp::newClass('Admin_UserModel');
        //echo json_encode($_POST);;
        
        
		$out = false;
        if( !empty( $_POST['data'] ) ){//增、删、改，不含有查！！
        	$uinfos = [];
        	//第一个循环，处理将要返回的数据
			foreach( $_POST['data'] as $key => &$postUserData){
					   	
					if(@$_POST['action'] == 'create'){
						unset($postUserData['id']); 
						$postUserData['create_time'] =time(); 
					}
			
					if(@$_POST['action'] == 'create' || @$_POST['action'] == 'edit'){
						if($postUserData['email']){
							//检查邮箱和手机唯一性
							$emailUnique = $userModel->checkEmailUnique($postUserData['email'],@$postUserData['id']);
							if(!$emailUnique){
								echo '{"error":"对不起，邮箱已经注册"}';exit;
							}
						}
						if($postUserData['phone']){
							$phoneUnique = $userModel->checkPhoneUnique($postUserData['phone'],@$postUserData['id']);
							if(!$phoneUnique){
								echo '{"error":"对不起，电话号已经注册"}';exit; 
							}
						}
						$bindinfo = 0;
						if(@$postUserData['bindinfo']){
							foreach($postUserData['bindinfo'] as $v){
								$bindinfo = $bindinfo | $v;
							}
						}
						$postUserData['bindinfo'] = $bindinfo;
					}
					
					//必须放在第一个循环里，因为只要DT 里process一下，用户就没了，再在第二个循环里删除用户相关表信息时就会查不到用户信息
					$uid = @$postUserData['id']?@$postUserData['id']:0;
					
					$uinfos[$key] = $userModel->readUserById($uid);
			}// end  foreach( $_POST['data'] first
			
			$out = $editor->process($_POST)->data();
			
			//第二个循环，根据返回数据处理相关表
			$oIndex = 0;
			foreach( $_POST['data'] as $key => &$postUserData){
					//$curOutItemForCreate = &$out['data'][ 0 ]; //新建时输出只有一条！！
					$curOutItem = &$out['data'][ $oIndex ];
					$userInfo = $uinfos[$key];
					if( @$_POST['action'] == 'create' && @$curOutItem['id']){
				   		$uid = $curOutItem['id'];
				   		$userInfo = $userModel->readUserById($uid);
				   	}
				   	
				   	
					$bindCode = $userModel->getBindCode(); 
					if(@$_POST['action'] == 'create' || @$_POST['action'] == 'edit'){
						$curOutItem['bindinfo'] = $userModel->bindCodeToArr($postUserData['bindinfo']);	
					}
				
				
				   // 当修改用户时添加到相关索引表
				   if( @$_POST['action'] == 'edit' && $postUserData['id']){
						$oldEmail = empty($userInfo['email']) ? "": $userInfo['email'];
						$oldPhone = empty($userInfo['phone']) ? "": $userInfo['phone'];
						$userModel->addEmail_User($postUserData['email'],$uid, $oldEmail);
						$userModel->addPhone_User($postUserData['phone'],$uid, $oldPhone); 
					}
					
				   // 当新建用户时添加到相关索引表
				   if( @$_POST['action'] == 'create' && @$curOutItem['id']){
				   
				   		$userModel->addEmail_User($postUserData['email'],$uid, "");
						$userModel->addPhone_User($postUserData['phone'],$uid, ""); 
				   }
				   
					// 当删除用户时删除相关索引表中的索引
					if(@$_POST['action'] =='remove' ){
						
						if( !empty( $userInfo['phone'])){ 
							$userModel->delPhone_user($userInfo['phone']);
						}
						if(!empty($userInfo['email'])){
							 $userModel->delEmail_user($userInfo['email']);
						}
					}
					$oIndex++;
				}//end  foreach( $_POST['data'] second
		}else{
        	$out = $editor->process($_POST)->data();
				$bindCode = $userModel->getBindCode(); 
        			
				foreach($bindCode as $k=>$v){ //绑定字典
					$out['bindCode'][] = array('value'=>$v,'label'=>$k);
				}
				foreach( $out['data'] as $key => &$curOutItem){ //处理用户的绑定信息到DT能认的格式
					if ( isset($curOutItem['bindinfo']) )
						$curOutItem['bindinfo'] = $userModel->bindCodeToArr($curOutItem['bindinfo']);
				}
				//职业字典
				$out['careers'] = $userModel->getCareerList();
			
        	
        }
        
    	echo json_encode($out);
	}
	
	
	//查看 用户信息
	public function viewUserinfoAction(){
		$uid = $_GET["uid"];
		$userModel = Doris\DApp::newClass('Admin_UserModel');
		$userInfo = $userModel->readUserById($uid);
		$phone = $userInfo["phone"];
		$bindLabel = $userModel->getBindLabel(); 
		$userInfo['bindinfo'] = $userModel->bindCodeToArr($userInfo['bindinfo']);
			
		$fieldsLabel = $userModel->getFieldsLabel();
		
		foreach( $userInfo['bindinfo'] as $k => &$v ){
			$v = $fieldsLabel[ $bindLabel[$v]  ]."（内部值： $v ）";
		}
		$userInfo['create_time'] = Doris\timestamp2str_datetime($userInfo['create_time']);
		$userInfo['birthday'] = Doris\timestamp2str_date($userInfo['birthday']);
		$userInfo['user_status'] = $userInfo['user_status'] ==1 ? "正常（1）": "封禁（{$userInfo['user_status']}）";
		$userInfo['last_use_date'] = Doris\timestamp2str_datetime($userInfo['last_use_date']);
    	$userInfo['last_sync_time'] = Doris\timestamp2str_datetime($userInfo['last_sync_time'] );
    	$userInfo['last_install_time'] = Doris\timestamp2str_datetime($userInfo['last_install_time'] );
    	$userInfo['train_begin_date'] = Doris\timestamp2str_datetime($userInfo['train_begin_date'] );
		switch( $userInfo['synchornize_status'] ){
		case 0: $userInfo['synchornize_status'] = "未修改（ 0 ）";break;
		case 1: $userInfo['synchornize_status'] = "修改未上传（ 1 ）";break;
		case 2: $userInfo['synchornize_status'] = "新建未上传（ 2 ）";break;
		}
		$var = [];
		foreach( $fieldsLabel as $k => &$v ){
			if(isset($userInfo[$k]))
				$var[$v] = $userInfo[$k];
		}
		
		
		_load("Service_User");

		$uRedis = DCache::redis("rUser");
		
		list($redis,$key) = getRedisAndKey("user/token",true);  
		
		$var["Token"] =   $redis->hGet( $key , $uid);
		$var["最后一次同步时间"] =  Service_User::getLastSyncTime( $uid )[2];
		$var["最后一次使用客户端时间"] =  Service_User::getLastUseDate( $uid )[2];
		$var["最后一次安装登录时间"] =  Service_User::getTraceBackMinDate( $uid )[2];
		$var["注册码"] =  Service_User::getPhoneValidationCode( $phone,"Reg" )[2];
		$var["找回密码安全码"] =  Service_User::getPhoneValidationCode( $phone,"FindSecret" )[2];
		//用户基本信息
		$this->assign("var",$var);
		
		
		
		$this->render("userinfo.tpl", "main.tpl");
	}
    /**
    *@Description 标签管理
    *
    */
    public function tag(){
		$this->assign("menu", "user/menu.tpl");
        $this->assign("second_menu", "user/second_menu.tpl");
		$this->assign("js", "tag_list.js");
		//$this->assign("js_para", json_encode(array("privilege_code"=>"n")));
		
		$this->assign("title", '标签管理');
		$this->display("common_list.tpl");
    }

    public function tag_ajax(){
        $db = Doris\DApp::loadDT();
    	$editor = Editor::inst( $db, 'tag' )
    	->field(
    			Field::inst( 'id' ),
    			Field::inst( 'tag_name')
      	);
        $out = $editor->process($_POST)->data();
        echo json_encode($out);
    
    }

    /**
    *@Description 关联标签
    *
    */
    public function relatedtagsAction(){
        $user_id = @$_GET['id'];
        $form_action="?m=user&c=index&a=relatedtags&id=".$user_id;
        $userInfo = Doris\DDB::pdoSlave()->query("select * from user where id = {$user_id}")->fetch(PDO::FETCH_ASSOC);
        if(!$userInfo){
            $this->error('未找到相关页面','index.php');
        }
        if($_POST){
            if(!$user_id){
                $this->error("参数不全");
                return false;
            }
            Doris\DDB::pdo()->query("update user set tags = '{$_POST['the_sels']}' where id = {$user_id}");
            $this->success('操作成功','?m=user&c=index&a=relatedtags&id='.$_GET['id']);
            exit;
        }
        $this->tagListAction($userInfo['tags'] , "选择标签 &nbsp;<small>用户昵称:{$userInfo['name']}</small>" , $form_action);        
    }
    

    private function tagListAction($default ,$title, $form_action){
        Doris\DApp::loadClass("FormBS");
    	$this->assign("js", "tag_list_sel.js");
    	$this->assign("title", $title);
    	$this->assign("form_action", $form_action);//
    	$this->assign("js_para", '{default:"'.$default.'"}');
        $this->assign("menu","user/menu.tpl");
        $this->assign("second_menu","user/second_menu.tpl");
    	$this->render(false,"common_list.tpl");
    
    }

    public function tagList_ajaxAction() {
    	$db = Doris\DApp::loadDT();
    	$content=Editor::inst($db, 'tag')
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'tag_name' )
        )
    	->process($_POST)
    	->data();
    	echo json_encode($content);
    }




	
}