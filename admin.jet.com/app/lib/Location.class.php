<?php
/**
 * @name Location
 * @author qiaochenglei
 *
 */

class Location{ 
    // 前台根目录 
    public static function getPublicRoot(){return  Doris\DConfig::get("dir/public") ;  }
    public static function getPublicSite(){return  Doris\DConfig::get("site/public") ;  }
    // 后台根目录 
    public static function getAdminRoot(){return Doris\DConfig::get("dir/admin") ; }
    public static function getAdminSite(){return Doris\DConfig::get("site/admin") ; }
    
	
    // 文章目录,前台目录下 
    public static function getArticlePath($module="news"){return self::getPublicRoot()."/rsc/a/$module/"; }
    public static function getArticleRelativePath($module="news"){return "rsc/a/$module/"; }
     
    // 图片目录,前台目录下 
    public static function getPicturePath(){return self::getPublicRoot()."/rsc/pics/"; }
    public static function getPictureRelativePath(){"rsc/pics/"; }
    public static function getThumbByUrl($url){return substr($url,0,strrpos($url,'.'))."_thumb".strrchr($url,'.');}    
	public static function extractFileName($url){$pre= substr($url,0,strrpos($url,'.')); return ltrim(strrchr($pre,'/'),'/');}
	public static function extractFileExt($url){return ltrim(strrchr($url,'.'),".");}
	
    // 用户上传的缓存目录,前台目录下 
    public static function getUploadCachePath(){return self::getPublicRoot()."/rsc/caches/"; }
    public static function getUploadCacheRelativePath(){return "rsc/caches/"; }
//     public static function getUploadCacheUrl(){return self::getPublicRoot()."/rsc/caches/"; }
//     public static function getUploadCacheRelativeUrl(){return "rsc/caches/"; }
    
    
    
    public static function getUploadAvatarPath(){return self::getPublicRoot()."/rsc/avatar/"; }
    public static function getUploadAvatarRelativePath(){return "rsc/avatar/"; }
   
   //转换成前台绝对URL——前后台独立虚拟机情况下用。
	public static function toFullURL($relativeURL){return self::getPublicSite()."/".$relativeURL;}
	//转换成后台绝对URL
	public static function toFullAdminURL($relativeURL){return self::getAdminSite()."/".$relativeURL;}

	public static function toRelativeURL($url){
		$relativeUrl=str_replace(self::getPublicSite(),"",$url);
		$relativeUrl=str_replace(self::getAdminSite() ,"",$relativeUrl);
		$relativeUrl=ltrim($relativeUrl,"/");
		return $relativeUrl;
	}
	public static function toRelativePath($fullPath){
		$relative=str_replace(self::getPublicRoot(),"",$fullPath);
		$relative=str_replace(self::getAdminRoot() ,"",$relative);
		$relative=ltrim($relative,"/");
		return $relative;
	}
	
	public static function URL2Path($url){
		return self::getPublicRoot().'/'.self::toRelativeURL($url);
	}
    //界面模板
    static function adminTemplateDir($module=null){return self::adminTemplateHomeDir()._admin()->c()."/";}
	static function adminTemplateHomeDir($module=null){ if(!$module) $module = _admin()->m();  return _conf()->dir->modules."/".ucfirst($module)."/views/"; }


	//
	static function mediaTypes(){return ['png','jpg','jpeg','gif','bmp','mp4','flv','avi','mp3'];}
	static function isAudio($ext){return in_array( strtolower($ext),['mp3']);}
	static function isVideo($ext){return in_array( strtolower($ext),['mp4','flv','avi']);}
	static function isPicture($ext){return in_array( strtolower($ext),['png','jpg','jpeg','gif','bmp']);}
	
	static function contentToFullURL($content){
		$pattern = '/<img.*?src="(.*?)".*?\/>/i';
        preg_match_all($pattern, $content, $matches);
        foreach (@$matches[1] as $key => $value) {
            $content=str_replace("\"".$value, "\"".self::toFullURL($value), $content);
        }
        return $content;
	}

}
