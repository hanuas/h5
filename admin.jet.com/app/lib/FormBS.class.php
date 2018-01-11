<?php
/**
 * @name FormBS
 * @author qiaochenglei
 *  基于Bootsrap的表单生成器
 */

class FormBS {
	public static function get_arr_val($arr,$name,$default){
		if(!is_array($arr))return $default;
		return (isset($arr[$name])?$arr[$name]:$default);
	}
	public static function out_pannel_begin($id='',$classStr=""){
		$attId=$id ? ' id="'.$id.'"':'';
		echo '<p><div class="form-horizontal text-center '. $classStr .'"><div class="text-left" '.$attId.'>';
	}
	public static function out_pannel_end(){echo '</div></div></p>';}
	public static function out_pannel_body_begin(){echo '<div class="panel-body">';}
	public static function out_pannel_body_end(){echo '</div>';}
	public static function out_form_begin($domId,$action="",$classStr="",$checkFormJs="check_form()"){
		echo '<form  role="form" id="'.$domId.'" class="form-horizontal text-left'. $classStr .'" enctype="multipart/form-data" action="'.$action.'" method ="post" onsubmit="return '.$checkFormJs.'">';
	}
	public static function out_form_end(){echo '</form>';}
	public static function out_pannel_val($name,$val){
		self::out_pannel_val_begin($name);
		echo '<p class="form-control-static">'.$val.'</p>';
		self::out_pannel_val_end();
	}
	public static function out_form_val($label,$val,$name,$ops=FALSE){
		$type=self::get_arr_val($ops,"type","input");
		$placeholder=self::get_arr_val($ops,"placeholder","");
		$help = self::get_arr_val($ops,"help","");
		$required = self::get_arr_val($ops,'required',"");
		$disabled = self::get_arr_val($ops,'disabled',"");
        $readonly = self::get_arr_val($ops,'readonly',"");
		$id=self::get_arr_val($ops,"id",false);
		$domId=$id?' id ="'.$id.'" ':"";
		
		$strOut="";
		switch ($type) {
			case "input":{
				
				$strOut='<input type="text"  '.$required.'  '.$disabled.' '.$readonly.' class="form-control"  name="'.$name.'" placeholder="'.$placeholder.' "  value="'.$val.'" '.$domId.'>';
				
				//var_dump($strOut);exit;
				break;
			}
			case "hidden":{
				$strOut='<input type="hidden" name="'.$name.'" placeholder="'.$placeholder.'" value="'.$val.'" '.$domId.'>';
				break;
			}
			case "textarea":{
				$strOut='<textarea  class="form-control"  '.$required.'  '.$disabled.' '.$readonly.'   name="'.$name.'" placeholder="'.$placeholder.'" rows="4" '.$domId.'>'.$val.'</textarea>';
				break;
			}
            case "file":{
                $strOut='<input type="file"  '.$required.' class=""  name="'.$name.'" placeholder="'.$placeholder.' "  value="'.$val.'" '.$domId.'>';
                break;
            }
			case "radio_inline":{
				$options=self::get_arr_val($ops,"options",array());
				$strOut="";
				foreach ($options as $option) {
					$strOut.='<div class="radio-inline"><label >';
					$strOut.='<input type="radio" name="'.$name.'"  '.$disabled.' '.$readonly.'   value="'.$option['value'].'" '.($val==$option['value']?" checked ":"").'>';
					$strOut.=$option['label'];
					$strOut.='</label></div>';
				}
				break;
			}
			case "radio":{
				$options=self::get_arr_val($ops,"options",array());
				$strOut="";
				foreach ($options as $option) {
					$strOut.='<div class="radio"><label >';
					$strOut.='<input type="radio" name="'.$name.'"  '.$disabled.' '.$readonly.'   value="'.$option['value'].'" '.($val==$option['value']?" checked ":"").'>';
					$strOut.=$option['label'];
					$strOut.='</label></div>';
				}
				break;
			}
			case "select":{
				$options=self::get_arr_val($ops,"options",array());
				$strOut='<select class="form-control" name="'.$name.'"  '.$disabled.' '.$readonly.' >';
				foreach ($options as $option) {
					$strOut.='<option value="'.$option['value'].'" '.($val==$option['value']?" selected ":"").'>';
					$strOut.=$option['name'];
					$strOut.='</option>';
				}
				$strOut.='</select>';
				break;
			}
			default:;break;
		}
		if($help != ''){
			$strOut.='<span class="help-block">'.$help.'</span>';
		}
		if($type=="hidden"){
			echo $strOut;
		}else{
			self::out_pannel_val_begin($label,$required);
			echo $strOut;
			self::out_pannel_val_end();
		}
	}
	public static function out_pannel_val_begin($name,$required=''){
        if($required == 'required'){
            $required = '<span style="color:red">*</span>';
        }
		echo '<div class="form-group">
		<label for="sl_must_known" class="col-sm-1 control-label">'.$name.' '.$required.'</label>
		<div class="col-sm-10"><div class="col-sm-9">';
	}
	public static function out_pannel_val_end(){echo '</div></div></div>';}
	
	
	public static function out_form_btn_begin($cols=4,$offCols=4){echo '<div class="form-group"><div class="col-sm-'.$cols.' col-sm-offset-'.$offCols.'">';}
	public static function out_form_btn_add($name="&nbsp;&nbsp;&nbsp;确定&nbsp;&nbsp;&nbsp;",$type="submit",$id=false){
		$attId=$id ? ' id="'.$id.'"':'';
		echo '<button type="'.$type.'" class="btn btn-primary" '.$attId.'>'.$name.'</button>';
	}
	public static function out_form_btn_end(){echo '</div></div>';}
	
	
	public static function out_gallary_begin(){echo '<div class="row">';}
	public static function out_gallary_pic($url,$title,$content){
		echo '<div class="col-sm-6 col-md-4">
		<div class="thumbnail">
		  <img src="'.$url.'" alt="200x200">
		  <div class="caption">
			<h3>'.$title.'</h3>
			<p>'.$content.'</p>
		  </div>
		</div>
	  </div>';
	}
	public static function out_gallary_end(){echo '</div>';}
	
	public static function out_datalist_template($title,$opts=null,$id="data_list"){
		self::out_pannel_body_begin();
		echo '<h1>'.$title.'</h1>';
		if($opts){if(in_array("add",$opts))echo '<a href="javascript:void(0)" class="btn btn-default editor_create info">添加</a>';}
		echo '<table class="table table-striped table-bordered table-hover datatable " id="data_list">
			<thead></thead><tfoot></tfoot>
		</table>';
		self::out_pannel_body_end();
	}
	
	public static function radio_yes_no_opt(){
		return array("type"=>"radio_inline" ,
				"options"=>array(
						array("label"=>"否","value"=>0),
						array("label"=>"是","value"=>1)
				)
		);
	}
	
	public static function toRelativeURL_WithinSameDomain($url){//后台中用。前后台在同一域名下，且后台在前台根的子目录下
		$preFix=(_HOME_DIR_==_ROOT_DIR_? "":"../");
		$relative=$url;
		if( substr($url,0, strlen(_WEBURL_))  ==_WEBURL_ )$relative=substr($url, strlen(_WEBURL_)+1, strlen($url));
		return $preFix.$relative;
	}
	
}