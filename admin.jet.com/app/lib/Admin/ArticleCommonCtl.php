<?php 
/**
 * @name 文章管理器
 * @desc 由于后台有很要用到文章管理的地方，所以把逻辑统一提取出来
 * @author qiaochenglei
 */

use DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Join,
DataTables\Editor\Validate;
//test:
//http://admin.freehome.com/index.php?m=article&c=article&a=index&root_catalog=1003000000000000000
class Admin_ArticleCommonCtl  extends Yaf_Controller_Abstract{
	public function indexAction(){
		_admin_assign($this,"second_menu", "article/second_menu");
		_admin_assign($this,"js", "article/article_list.js");
		_admin_assign($this,"title", _lan('ArticleManagement','文章管理'));
        _admin_assign($this,'menu','article/menu');

        
      	self::parseCatalogId($root_catalog,$cata_id);
      	self::parseCiteModule($cite_module,$cite_id);
      	
//      _admin_assign($this,'cur_catalog' ,$cur_catalog);
//      _admin_assign($this,'root_catalog',$root_catalog);
        _admin_assign($this,"js_para", json_encode([
        	'cur_catalog'=>$cata_id , 
        	'root_catalog'=>$root_catalog,
        	'cite_module'=>($cite_module?$cite_module:0),
        	'cite_id'=>($cite_id?$cite_id:0)
        ]));
	
	}
	public static function parseCatalogId(&$root_catalog,&$cata_id){
		$GPID=(new Util_GPID());
     	$cata_id=@$_GET['cur_catalog'];
     	
    	$root_catalog=@$_GET['root_catalog'];
      	if(!$root_catalog)$root_catalog=$GPID::getRoot();
     	if(!$cata_id || !$GPID::isA($cata_id,$root_catalog)){
     		$cata_id=$root_catalog;
     	}
	}
	public static function parseCiteModule(&$cite_module,&$cite_id){
		$cite_module=@$_GET['cite_module'];
    	$cite_id=@$_GET['cite_id'];
    }
	public function index_ajaxAction(){
        DataTables_DataTables::load();
		 $editor = Editor::inst( $db, 'cm_article' )
			->fields(
				Field::inst( 'cm_article.id' ),
				Field::inst( 'cm_article.title' ),
				Field::inst( 'cm_article.create_time' ),
				Field::inst( 'cm_article.is_choiceness' ),
                Field::inst( 'cm_article.is_hot' ),
				Field::inst( 'cm_article.plat' ),
				Field::inst( 'cm_article.ord' )
			);
 
		$GPID=(new Util_GPID());
     	$cata_id=@$_GET['cur_catalog'];
     	$cite_module=@$_GET['cite_module'];
		$cite_id=@$_GET['cite_id']?$_GET['cite_id']:0;
		
		$updateLinkExt="";
        if( $cata_id) {
     		$nxtCid=$GPID->increaseSeg($cata_id);
     		//  $editor 
// 				->leftJoin( 'cm_article_cite', 'cm_article_cite.article_id', '=', 'cm_article.id' )
// 				->where("cm_article_cite.module","cm_catalog","=" )
// 				->where("cm_article_cite.cite_id",$cata_id,">=" )
// 				->where("cm_article_cite.cite_id",$nxtCid,"<" )
// 				;
			if($cite_module)$updateLinkExt.="&cite_module={$cite_module}&cite_id={$cite_id}";
       	 	if($cite_module && $cite_id){ 
       	 		//echo $cite_module.$cite_id;exit;
       	 		$editor 
				->leftJoin( 'cm_article_cite', 'cm_article_cite.article_id', '=', 'cm_article.id' )
// 				->where("cm_article_cite.module","cm_catalog","=" )
// 				->where("cm_article_cite.cite_id",$cata_id,">=" )
// 				->where("cm_article_cite.cite_id",$nxtCid,"<" )
				
       	 		->where("cm_article_cite.module",$cite_module,"=" )
       	 		->where("cm_article_cite.cite_id",$cite_id,"=" );
       	 		
//        	 	->where( function ( $q ) {
// 				  $q->where("cm_article_cite.module","cm_catalog","=" )
// 					->where("cm_article_cite.cite_id",$cata_id,">=" )
// 					->where("cm_article_cite.cite_id",$nxtCid,"<" )
// 					->or_where("cm_article_cite.module",$cite_module,"=" )
// 					->and_where("cm_article_cite.cite_id",$cite_id,"=" );
// 				} );
       	 		//echo 123;exit;
       	 		
       	 	}else{
       	 		$editor 
				->leftJoin( 'cm_article_cite', 'cm_article_cite.article_id', '=', 'cm_article.id' )
				->where("cm_article_cite.module","cm_catalog","=" )
				->where("cm_article_cite.cite_id",$cata_id,">=" )
				->where("cm_article_cite.cite_id",$nxtCid,"<" )
				;
       	 	}
        }
        
        
        $out = $editor->process($_POST)->data(); 
        $out['data'] = @$out['data']?$out['data']:array();
        foreach($out['data'] as $k=>$v){
            $option = '';
            $option.= '<a href="?m=article&c=article&a=updateArticle&id='.$v['cm_article']['id'].$updateLinkExt.'">修改</a>&nbsp;&nbsp;'; 
            $option.= '<a href="?m=article&c=article&a=delArticle&id='.$v['cm_article']['id'].'" onclick= "return confirm(\'确定要删除此资讯吗?\');" >删除</a>&nbsp;&nbsp;'; 
            
            $out['data'][$k]['option'] =$option; 
        }
		echo json_encode($out);
	}
	
    private function reAddCatalogs($article_id) {
		if(isset($_POST["catalogs"]) && $_POST["catalogs"]){
			$catalogs=$_POST["catalogs"];
		
			$articleCite=(new Cm_ArticleCiteModel());
			$articleCite->delByArticleId($article_id , 'cm_catalog');
			foreach ($catalogs as $catlog_id) {
				$articleCite->addArticleCite(array('cite_id'=>$catlog_id,'article_id'=>$article_id,'module'=>'cm_catalog'));
			}//exit;
    	}
    	
    	if(isset($_POST["cites"]) && $_POST["cites"]){
    		$cites=$_POST["cites"];
    		$module=$_POST["module"];
    		
    		$articleCite=(new Cm_ArticleCiteModel());
			$articleCite->delByArticleId($article_id , $module);
			foreach ($cites as $cite_id) {
				$articleCite->addArticleCite(array('cite_id'=>$cite_id,'article_id'=>$article_id,'module'=>$module));
			}//exit;
    	}
    	return true;
    }
    
    /**
    *@Description 添加资讯
    */
    public function addArticleAction(){
    
    	_admin_assign($this,"js_para",["session_id"=>session_id()]); 
        //_admin_assign($this,"menu", "article/menu");
        if(isset($_POST['title'])){
    		// if(empty($_POST['title']) || empty($_POST['content'])  || empty($_POST['picture'])){
            if(empty($_POST['title']) || empty($_POST['content']) ){
    			_admin()->error('信息填写不完整',-1);
    		}
    		$articleModule=@$_GET['module']? $_GET['module']:"common";
    		
            $_POST['create_time'] = date('Y-m-d H:i:s');
            $articleModel = (new Cm_ArticleModel());
            $content = $_POST['content'];
            $intro = $_POST['intro'];
            unset($_POST['content'],$_POST['intro']);
            $res = $articleModel->addArticle('cm_article',$_POST);//成功的话返回文章ID

    		if(!$res){
    			_admin()->error("添加失败",-1);
    		}
    		
    		$this->reAddCatalogs( $res );
    		
    		$suffix = strrchr($_POST['picture'], '.');
    	
    		$toDir = Location::getArticlePath($articleModule).$res.'/picture';
    		mkdir($toDir,0777,true);//创建多级目录
    		
    		$tmpFile=Location::URL2Path($_POST['picture']);
    		$issuc =  copy($tmpFile,$toDir.'/show'.$suffix);	
    		unlink($tmpFile);
            $CKEditor = new CKEditor();
            $content = $CKEditor->handleContent($content,Location::getArticleRelativePath($articleModule).$res.'/picture/ckeditor_content');
            $intro = $CKEditor->handleContent($intro,Location::getArticleRelativePath($articleModule).$res.'/picture/ckeditor_intro');
         //   echo "Intro:$intro<br>Content:$content";exit;
            $updateData['content'] = $content;
            $updateData['intro'] = $intro;
			if($issuc){
                $updateData['picture'] = Location::getArticleRelativePath($articleModule).$res.'/picture/show'.$suffix;

                $create_thumb = $this->createThumb($toDir.'/show'.$suffix,$toDir.'/show_thumb'.$suffix,200,200,1);
                if($create_thumb){
                    $updateData['thumb'] = Location::getArticleRelativePath($articleModule).$res.'/picture/show_thumb'.$suffix;
                }
            }
            $articleModel->updateArticle('cm_article',$updateData,$res);
				
            if($_POST['is_choiceness'] == 1){
                (new Cm_ChoicenessModel())->insertChoiceness(array('module'=>'article','cite_id'=>$res,'title'=>$_POST['title'],'plat'=>$_POST['plat']));
                (new Cm_CacheManagerModel())->clearChoiceness();
            }
            (new Cm_CacheManagerModel())->clearArticle();
			unset($_POST);
            _admin()->success("添加成功",-1);exit;  
        }
  	    _admin_assign($this,'title','新建文章');
        //_admin()->display("article/updateArticle");

    }
    /**
    *@Description 修改资讯
    */
    public function updateArticleAction(){
        _admin_assign($this,"js_para",["session_id"=>session_id()]); 
       // _admin_assign($this,"menu", "article/menu");
        $article = (new Cm_ArticleModel())->getArticleById($_GET['id']);       
		
        if(isset($_POST['title'])){//echo 123452342;
    		// if(empty($_POST['title']) || empty($_POST['content'])  || empty($_POST['picture'])){
            if(empty($_POST['title']) || empty($_POST['content']) ){
    			_admin()->error('信息填写不完整',-1);
    		}
    		$articleModule=@$_GET['module']? $_GET['module']:"common";//文章的存放模块 
    		
            $CKEditor = new CKEditor();
            $_POST['content'] = $CKEditor->handleContent($_POST['content'],Location::getArticleRelativePath($articleModule).$_GET['id'].'/picture/ckeditor_content');
            $_POST['intro'] = $CKEditor->handleContent($_POST['intro'],Location::getArticleRelativePath($articleModule).$_GET['id'].'/picture/ckeditor_intro');
    		$picture = $_POST['picture'];
	   		if($article['picture'] != $_POST['picture']){
	   			$suffix = strrchr($_POST['picture'], '.');
	   			//$toDir = _ROOT_DIR_.'/admin/Resources/article/article/'.$_GET['id'].'/picture';
	   			$toDir = Location::getArticlePath('common').$_GET['id'].'/picture';
	   			mkdirs($toDir);
	   			
    			$tmpFile=Location::URL2Path($_POST['picture']);
	   			$issuc = copy($tmpFile , $toDir.'/show'.$suffix);	
    			unlink($tmpFile);   		
	   			if($issuc){
	   				$_POST['picture'] = Location::getArticleRelativePath($articleModule).$_GET['id'].'/picture/show'.$suffix;
                    $create_thumb = $this->createThumb($toDir.'/show'.$suffix,$toDir.'/show_thumb'.$suffix,200,200,1);
                    if($create_thumb){
                        $_POST['thumb'] = Location::getArticleRelativePath($articleModule).$_GET['id'].'/picture/show_thumb'.$suffix;
                    }
	   			}else{
	   				_admin()->error('图片拷贝失败',-1);
	   			}	   				
	   		}
            $_POST['need_static'] = 1;
            $_POST['is_update'] = 1;
            $res = (new Cm_ArticleModel())->updateArticle('cm_article',$_POST,$_GET['id']);
            
    		//TODO: 重新添加栏目引用
    		$addCiteSucc=$this->reAddCatalogs( $_GET['id'] );
    		
            if($res || @$issuc || $addCiteSucc){
                $choiceness = (new Cm_ChoicenessModel())->readByCiteId('article',$_GET['id']);
                $choiceness_id = @$choiceness['id'];
                $choicenessData = array('module'=>'article','cite_id'=>$_GET['id'],'title'=>$_POST['title'],'plat'=>$_POST['plat']);
                if($_POST['is_choiceness'] == 1){
                    if(!$choiceness_id){
                        (new Cm_ChoicenessModel())->insertChoiceness($choicenessData);
                    }else{
                        (new Cm_ChoicenessModel())->updateChoiceness($choicenessData,$choiceness_id);
                    }
                }else{
                    if($choiceness_id){
                        (new Cm_ChoicenessModel())->delChoiceness($choiceness_id);
                    }
                }
                (new Cm_CacheManagerModel())->clearArticle();
                (new Cm_CacheManagerModel())->clearChoiceness();
                _admin()->success("修改成功",-1);exit;  
            }else{
                _admin()->error('修改失败',-1);
                //_admin()->success("修改成功",-1);
            }
        }//end POST edit
		$catalogs=_db_slave()->cm_article_cite(" article_id= {$_GET['id']} AND module='cm_catalog'")->select("article_id,cite_id");
		$catalogs=dbrows2array($catalogs);
    	if($catalogs)	_admin_assign($this,"catalogs", $catalogs);
    	//var_dump($catalogs);exit;
    	$cite_module=@$_GET['cite_module'];
    	if($cite_module){
			$cites=_db_slave()->cm_article_cite(" article_id= {$_GET['id']}  AND module='$cite_module'")->select("article_id,cite_id");
			$cites=dbrows2array($cites);
			if($cites)	_admin_assign($this,"cites", $cites);
			_admin_assign($this,"cite_module", $cite_module);
    		//var_dump($cites);exit;
    	}
        _admin_assign($this,'article',$article);   
        _admin_assign($this,'version','?v='.time()); 
    	_admin_assign($this,'title','修改文章'); 
        //_admin()->display("article/updateArticle");
    }

    public function delArticleAction(){
        $res = (new Cm_ArticleModel())->delArticle('cm_article',$_GET['id']);
        
        if($res){
        	
    		$articleModule=@$_GET['module']? $_GET['module']:"news";
        	//TODO: 路径问题
            // rmdirs(_ROOT_DIR_.'/admin/Resources/article/article/'.$_GET['id'].'/');
            rmdirs(Location::getArticlePath($articleModule).$_GET['id'].'/');
            (new Cm_ArticleCiteModel())->delByArticleId($_GET['id']);
            (new Cm_ChoicenessModel())->del('article',$_GET['id']);
           // (new Cm_CarouselModel())->del('article',$_GET['id']);
            (new Cm_CacheManagerModel())->clearArticle();
            (new Cm_CacheManagerModel())->clearChoiceness();
            _admin()->success('操作成功',-1);
        }else{
            _admin()->error('删除失败',-1);
        }
    }
    
    //生成缩略图
    public function createThumb($src_img, $dst_img, $width = 250, $height =false, $cut = 0, $proportion = 0){
        $pictureClass = new Util_Upload_Picture();
        return $pictureClass->img2thumb($src_img, $dst_img, $width, $height, $cut, $proportion);
    }

///uploadfy 的上传
	public function uploadImg_ajaxAction(){
		//echo json_encode( ['code'=>0,'message'=>'test']);
		//exit;
		
        if(isset($_GET['heightEqWidth'])){
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $imageSizeInfo = getimagesize($tempFile);
            if($imageSizeInfo[0]!=$imageSizeInfo[1]){
                echo json_encode(array('code'=>0,'message'=>'图片的像素横宽必须相等'));exit;
            }
        }
		$pictureClass = new Util_Upload_Picture();
		$result = $pictureClass->upload(Location::mediaTypes());
		echo json_encode($result);
	}
	

    public function uploadImg_CkEditorAction(){
        $callback=$_GET['CKEditorFuncNum'];
        $toDir=Location::getUploadCachePath();

        $filename=uniqid();
        $suffix = strrchr($_FILES['upload']['name'], '.');
        $tmpFile=$_FILES['upload']['tmp_name'];
        $issuc = move_uploaded_file($tmpFile,$toDir.$filename.$suffix);
        $path=Location::getPublicSite().'/'.Location::getUploadCacheRelativePath();
        echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(".$callback.",'".$path.$filename.$suffix."','');</script>";
        return null;
    }
}