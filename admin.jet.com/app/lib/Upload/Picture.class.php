<?php

_load("Location");

class Upload_Picture{

	/*
	* 	调用示例：
			$shortUrl=Location::toRelativeURL($picInfo["url"]);
			
			list($isSucess,$bigPic, $thumbPic_auto )=Upload_Picture::dealPictureFromTmp2Dest(
				 $shortUrl,
				Location::getPicturePath().$cite_id,
				$newName,
				($r) ? $r['img'] : false ,//如果不为false则删除最后一个参数对应的大小图
				$autoGenerateThumb);
	*/
	public static function dealPictureFromTmp2Dest($tmpUrl ,$fullPathBase ,$name,$oriUrlTobeDel=false,$generateThumb=true){//$pathBase=Location::getEquipmentPath($equipmentModule).$_GET['id']
	
		$retStatus=false;
		$relativePathBase = Location::toRelativePath($fullPathBase);		
		$suffix = strrchr($tmpUrl, '.');
		
		if($oriUrlTobeDel){//删除 $oriUrlTobeDel 下的所有图片, edit的情况下
			$picPath=Location::URL2Path( $oriUrlTobeDel);
			$thumbPath=substr($picPath,0,strrpos($picPath, '.'))."_thumb".$suffix;
		 	if(file_exists($picPath)){unlink($picPath);  }
		 	
		 	if($generateThumb)if(file_exists($thumbPath)){unlink($thumbPath);  } 
		}
		
		$fName =  $name.$suffix;
		$thumbName =  $name."_thumb".$suffix;
		
		$toDir = $fullPathBase.'/picture';
		mkdir($toDir, 0777, true);
		$tmpFile=Location::URL2Path( $tmpUrl );
		$destFullPath=$toDir.'/'.$fName;
		
		if(file_exists($destFullPath)){unlink($destFullPath); } //如果目标存在则先删除
		$issuc = copy($tmpFile , $destFullPath);	
		if(file_exists($tmpFile)){unlink($tmpFile);  }
		 	
		//static $a=0;$a++; if($a==2){var_dump($tmpFile,$destFullPath);exit;}//TODO test del
		
		if($issuc){
			$pictureRelativePath =$relativePathBase.'/picture/'.$fName;
			if($generateThumb){
				$destThumbPath = $toDir.'/'.$thumbName;
				if(file_exists($destThumbPath)){unlink($destThumbPath);  } //如果目标存在则先删除
				$create_thumb = self::createThumb($toDir.'/'.$fName , $destThumbPath,200,200,1);
				if($create_thumb){
					$thumbRelativePath = $relativePathBase.'/picture/'.$thumbName;
				}
			}
			$retStatus=true;
		}				
		
		return [$retStatus,$pictureRelativePath, $thumbRelativePath];
    }
    
    /**
    * @ param array $filetypes 允许上传的图片类型
    * @ param array $imageXY 图片高宽
    * @ param string $bufferFolder 图片存储路径
    * @ param string $fileName 图片名（不带后缀）
    */
    public function uploadForUploadify($filetypes,$imageXY = false,$bufferFolder ='',$fileName=''){
    	header('Content-type: application/json; charset=utf-8');//return ['code'=>0,'message'=>'test'];
        @$error = $this->checkError($_FILES['Filedata']['error']);
        if(!$error){  //没有错误
            //处理文件
            $oriName= $_FILES['Filedata']['name'];
            //echo _USER_CACHE_DIR_;
            
            $verifyToken = md5('unique_salt' . $_POST['timestamp']);
            if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
                $fileParts = pathinfo($_FILES['Filedata']['name']);

                $tempFile = $_FILES['Filedata']['tmp_name'];
				if($imageXY){  //需要限制图片的高宽
					$imageSizeInfo = getimagesize($tempFile);
					if( !(in_array($imageSizeInfo[0],$imageXY) && in_array($imageSizeInfo[1],$imageXY)) ){
                        return array('code'=>0,'message'=>'图片的像素横宽比错误');
					}
				}
                $extension =  strtolower($fileParts['extension']); //后缀
                //TODO: 路径问题
                if(!$bufferFolder){
                    $bufferFolder = Location::getUploadCacheRelativePath();
                    $targetPath =Location::getUploadCachePath();
                }else{
                    $targetPath = $bufferFolder;
                }
                if(!file_exists($targetPath)){
                    mkdir($targetPath,0777,true);
                }
                $fileName = $fileName?$fileName:uniqid();
                $targetName = $fileName.'.'.$extension;
                $targetFile = rtrim($targetPath,'/') . '/' . $targetName;
                if(file_exists($targetFile)){
                    unlink($targetFile);
                }
                if (in_array($extension,$filetypes)) {
                    $isscuccess = move_uploaded_file($tempFile,iconv("UTF-8","gb2312", $targetFile));
                    if($isscuccess){
                        return array('code'=>1,'message'=>'上传成功','url'=>Location::toFullURL($bufferFolder.$targetName));
                    }else{
                        return array('code'=>0,'message'=>'移动文件失败');
                    }
                } else {
                   return array('code'=>0,'message'=>'错误的文件类型');
                }
            }
        }else{ //有错误
            return array('code'=>0,'message'=>$error);
        }
    	
  
    }



    //生成缩略图
  
    public static function createThumb($src_img, $dst_img, $width = 250, $height =false, $cut = 0, $proportion = 0){
        $pictureClass = new Upload_Picture();
        return $pictureClass->img2thumb($src_img, $dst_img, $width, $height, $cut, $proportion);
    }
    
    //返回实际的文件名
    /*
    *	$arrTmpFile 上传的缓存文件信息，如 $_FILES["my_file"]
    *	$allowedFileTypes 合法的后缀，如 ["png","jpg"]
    *	$dstFolder 目标文件夹
    *	$dstName 目标文件名（无后缀），当为false时会自动生成
    *	
    */
    public function uploadCommon($arrTmpFile, $allowedFileTypes,  $dstFolder, $dstName = false){ 
   		 	$fName = $arrTmpFile["name"];
   		 	$fType= $arrTmpFile["type"];
   		 	$fSize= $arrTmpFile["size"];
   		 	$fTmpName= $arrTmpFile["tmp_name"]; 
   		 	
   		 	if(!file_exists($dstFolder)){
				mkdir($dstFolder,0777,true);
			}
			
			$fileParts = pathinfo($fName);
			$fileName = $dstName?$dstName:uniqid();
			$extension =  strtolower($fileParts['extension']); 
			$targetName = $fileName.'.'.$extension;
			$targetFile = rtrim($dstFolder,'/') . '/' . $targetName;
			
			if(file_exists($targetFile)){
				unlink($targetFile);
			}
			if (in_array($extension,$allowedFileTypes)) {
			 
				$isSucc = move_uploaded_file($fTmpName,iconv("UTF-8","gb2312", $targetFile));
				if ($isSucc ){
					return $targetName;
				}  
			} 
			
			return false;
    	
    }
    
    
    public function checkError($error){
        switch($error){
            case 0:
                return '';
            case 1:
                return '上传文件大小超过限制';
            case 2:
                return '上传文件大小超过限制';
            case 3:
                return '文件只有部分被上传';
            case 4:
                return '没有文件被上传';
            case 6:
                return '找不到临时文件';
            case 7:
                return '文件写入失败';
        }
    }

 

    /**
     * 生成缩略图
     * @author yangzhiguo0903@163.com
     * @param string     源图绝对完整地址{带文件名及后缀名}
     * @param string     目标图绝对完整地址{带文件名及后缀名}
     * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param int        是否裁切{宽,高必须非0}
     * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    public function img2thumb($src_img, $dst_img, $width = 250, $height =false, $cut = 0, $proportion = 0)
    {
    	//function fileext($file){return pathinfo($file, PATHINFO_EXTENSION);}
    	if(!is_file($src_img))
    	{
    		return false;
    	}
    	$ot = strtolower(pathinfo($dst_img,PATHINFO_EXTENSION));
        if(!in_array($ot,array('jpg','jpeg','bmp','png','gif','xbm','wbmp','gd2','gd'))){
            $ot = 'jpg';
        }
    	$otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
    	$srcinfo = @getimagesize($src_img);
    	$src_w = $srcinfo[0];
    	$src_h = $srcinfo[1];
        if($width>$src_w && $height>$src_h && $cut == 1){
            copy($src_img,$dst_img);
            return true;
        }
        if($width>$src_w){$width=$src_w;}
        if(@$height>$src_h){$height=$src_h;}
    	$type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
    	$createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
    	$dst_h = $height;
    	$dst_w = $width;
    	$x = $y = 0;
    	/**
    	 * 缩略图不超过源图尺寸（前提是宽或高只有一个）
    	 */
    	if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
    	{
    		$proportion = 1;
    	}
    	if($width> $src_w)
    	{
    		$dst_w = $width = $src_w;
    	}
    	if($height> $src_h)
    	{
    		$dst_h = $height = $src_h;
    	}
    
    	if(!$width && !$height && !$proportion)
    	{
    		return false;
    	}
    	if(!$proportion)
    	{
    		if($cut == 0)
    		{
    			if($dst_w && $dst_h)
    			{
    				if($dst_w/$src_w> $dst_h/$src_h)
    				{
    					$dst_w = $src_w * ($dst_h / $src_h);
    					$x = 0 - ($dst_w - $width) / 2;
    				}
    				else
    				{
    					$dst_h = $src_h * ($dst_w / $src_w);
    					$y = 0 - ($dst_h - $height) / 2;
    				}
    			}
    			else if($dst_w xor $dst_h)
    			{
    				if($dst_w && !$dst_h)  //有宽无高
    				{
    					$propor = $dst_w / $src_w;
    					$height = $dst_h  = $src_h * $propor;
    				}
    				else if(!$dst_w && $dst_h)  //有高无宽
    				{
    					$propor = $dst_h / $src_h;
    					$width  = $dst_w = $src_w * $propor;
    				}
    			}
    		}
    		else
    		{
    			if(!$dst_h)  //裁剪时无高
    			{
    				$height = $dst_h = $dst_w;
    			}
    			if(!$dst_w)  //裁剪时无宽
    			{
    				$width = $dst_w = $dst_h;
    			}
    			$propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
    			$dst_w = (int)round($src_w * $propor);
    			$dst_h = (int)round($src_h * $propor);
    			$x = ($width - $dst_w) / 2;
    			$y = ($height - $dst_h) / 2;
    		}
    	}
    	else
    	{
    		$proportion = min($proportion, 1);
    		$height = $dst_h = $src_h * $proportion;
    		$width  = $dst_w = $src_w * $proportion;
    	}
        if(function_exists($createfun)){
    	    $src = @$createfun($src_img);
        }else{
            return false;
        }

    	if(!is_resource($src)){
            return false;
        }

        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
    	$white = imagecolorallocate($dst, 255, 255, 255);
    	imagefill($dst, 0, 0, $white);
        
    	if(function_exists('imagecopyresampled'))
    	{
    		imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    	}
    	else
    	{
    		imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
    	}
        if($otfunc == 'imagejpeg'){
    	    $otfunc($dst, $dst_img,100);
        }else{
            $otfunc($dst, $dst_img);
        }
    	imagedestroy($dst);
    	imagedestroy($src);
    	return true;
    }
}
?>