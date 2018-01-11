<?php
use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;

/**
 * @name IndexController
 * @author qiaochenglei
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class indexController extends commonController {
	public function indexAction(){
		_admin_assign($this,"menu", "user/menu");
        _admin_assign($this,"second_menu", "user/second_menu");
		_admin_assign($this,"js", "user/front_user_list.js");
		//_admin_assign($this,"js_para", json_encode(array("privilege_code"=>"n")));
		
		_admin_assign($this,"title", _lan('MemberManagement','前台用户管理'));
		_admin()->display("common_list");
		return FALSE;
	}
	
	public function index_ajaxAction(){
    	
		DataTables_DataTables::load();
    	$editor = Editor::inst( $db, 'lma_user' )
    	->field(
    			Field::inst( 'id' ),
    			Field::inst( 'email'),
    			Field::inst( 'phone'),
    			Field::inst('gender'),
    			Field::inst('score'),
    			Field::inst('name'),
    			Field::inst('bindinfo'),
    			Field::inst('create_time'),
    			Field::inst('signature'),
    			Field::inst('qqweibo'),
    			Field::inst('weibo'),
    			Field::inst('qq'),
    			Field::inst('weixin'),
    			Field::inst('birthday'),
    			Field::inst('career'),
    			Field::inst('career_cat'),
    			Field::inst( 'user_pwd')->validator( 'Validate::required' )
    			->setFormatter( function ($val, $data, $field) {
                    if(@$data['id']){
    				    $userinfo = (new Admin_UserModel())->readUserById($data['id']);
    				    $old_md5_pass=$userinfo['user_pwd'];
                        $new_md5_pass= md5($val);
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
        $userModel = (new Admin_UserModel());
        $uid = @$_POST['data']['id']?@$_POST['data']['id']:0;
        if(@$_POST['action'] == 'remove'){
            $uid = substr($_POST['id'][0],4);
        }
        $userInfo = $userModel->readUserById($uid);
        $bindCode = $userModel->getBindCode();    	
        if(@$_POST['action'] == 'create'){ unset($_POST['data']['id']); $_POST['data']['create_time'] =time(); }
        if(@$_POST['action'] == 'create' || @$_POST['action'] == 'edit'){
            if($_POST['data']['email']){
                //检查邮箱和手机唯一性
                $emailUnique = $userModel->checkEmailUnique($_POST['data']['email'],@$_POST['data']['id']);
                if(!$emailUnique){
                    echo '{"error":"对不起，邮箱已经注册"}';exit;
                }
            }
            if($_POST['data']['phone']){
                $phoneUnique = $userModel->checkPhoneUnique($_POST['data']['phone'],@$_POST['data']['id']);
                if(!$phoneUnique){
                    echo '{"error":"对不起，电话号已经注册"}';exit; 
                }
            }
            $bindinfo = 0;
            if(@$_POST['data']['bindinfo']){
                foreach($_POST['data']['bindinfo'] as $v){
                    $bindinfo = $bindinfo | $v;
                }
            }
            $_POST['data']['bindinfo'] = $bindinfo;
        }
        $out = $editor->process($_POST)->data();
        if(@$_POST['action'] == 'create' || @$_POST['action'] == 'edit'){
            $out['row']['bindinfo'] = (new Admin_UserModel())->bindCodeToArr($out['row']['bindinfo']);
        }
        if ( !isset($_POST['action']) ) {
            foreach($bindCode as $k=>$v){ $out['bindCode'][] = array('value'=>$v,'label'=>$k);}
            foreach(@$out['data'] as $key=>$value){
                $out['data'][$key]['bindinfo'] = (new Admin_UserModel())->bindCodeToArr($value['bindinfo']);
            }
            $out['careers'] = (new Admin_UserModel())->getCareerList();
        }
        if(@$out['row']['id'] || @$out['data']['id']){
            $uid = @$out['row']['id']?$out['row']['id']:$out['data']['id'];
            $userModel->addEmail_User($_POST['data']['email'],$uid,$userInfo['email']);
            $userModel->addPhone_User($_POST['data']['phone'],$uid,$userInfo['phone']); 
        }
        if(@$_POST['action'] =='remove' && !$out){
            if($userInfo['phone']){ $userModel->delPhone_user($userInfo['phone']);}
            if($userInfo['email']){ $userModel->delEmail_user($userInfo['email']);}
        }
    	echo json_encode($out);
	}
    /**
    *@Description 标签管理
    *
    */
    public function tagAction(){
		_admin_assign($this,"menu", "user/menu");
        _admin_assign($this,"second_menu", "user/second_menu");
		_admin_assign($this,"js", "tag_list.js");
		//_admin_assign($this,"js_para", json_encode(array("privilege_code"=>"n")));
		
		_admin_assign($this,"title", '标签管理');
		_admin()->display("common_list");
    }

    public function tag_ajaxAction(){
        DataTables_DataTables::load();
    	$editor = Editor::inst( $db, 'lma_tag' )
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
        $form_action="?m=sysuser&c=index&a=relatedtags&id=".$user_id;
        $userInfo = _pdo_slave()->query("select * from lma_user where id = {$user_id}")->fetch(PDO::FETCH_ASSOC);
        if(!$userInfo){
            _admin()->error('未找到相关页面','index.php');
        }
        if($_POST){
            if(!$user_id){
                _admin()->error(_lan("ParamIncomplete","参数不全"));
                return false;
            }
            _pdo_m0()->query("update lma_user set tags = '{$_POST['the_sels']}' where id = {$user_id}");
            _admin()->success('操作成功','?m=sysuser&c=index&a=relatedtags&id='.$_GET['id']);
            exit;
        }
        $this->tagListAction($userInfo['tags'] , "选择标签 &nbsp;<small>用户昵称:{$userInfo['name']}</small>" , $form_action);        
    }
    

    private function tagListAction($default ,$title, $form_action){
        //_load_class("FormBS");
    	_admin_assign($this,"js", "tag_list_sel.js");
    	_admin_assign($this,"title", $title);
    	_admin_assign($this,"form_action", $form_action);//
    	_admin_assign($this,"js_para", '{default:"'.$default.'"}');
        _admin_assign($this,"menu","user/menu");
        _admin_assign($this,"second_menu","user/second_menu");
    	_admin()->display("common_list_submit");
    
    }

    public function tagList_ajaxAction() {
    	DataTables_DataTables::load();
    	$content=Editor::inst($db, 'lma_tag')
    	->fields(
    		Field::inst( 'id' ),
    		Field::inst( 'tag_name' )
        )
    	->process($_POST)
    	->data();
    	echo json_encode($content);
    }



	
}