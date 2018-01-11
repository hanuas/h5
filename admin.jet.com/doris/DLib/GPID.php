<?php
/*================================GPID.php===================================*/
/**
 * @author 乔成磊
 * 工具类
 * 针对 MySql中BIGINT数据结构设计的一种分组ID（gid），ID中包含父子关系及分组关系。
 * 最高位固定是1，代表根类，处于level0，其它的从个位算起，每三位一个层级，共六层。
 * 如：1,000……是根类，level为0,    1,001……~1,999……处于level1，它们的父类是1000……， 1,001……~1999……属于同一层级
 * 同理，1001,000……是1001,001……~1,001,999……的父类
 */
namespace Doris;

class GPID{
    private static $maxLevel=6;
 
    public static  function getRoot(){return "1000000000000000000";}
     
    public static function getSeg($gid,$level=false){
        if($level===false)$level=self::getLevel($gid);
        if($level==0)/*最高位是1*/return 1;
        if($level>self::$maxLevel)return false;
        $seg=substr($gid,3*$level-2, 3);
        return (int)$seg;
    }

    public static function getParent($gid){
        $level=self::getLevel($gid);
        if($level==0)/*最高位是1*/return 1;
        if($level>self::$maxLevel)return false;
        //1001033000000000000 str_pad($input, 10, "-=", STR_PAD_LEFT);
        $parent = str_pad( substr($gid,0,($level-1)*3+1),19,'0',STR_PAD_RIGHT );
        return $parent;
    
    }
     
    public static function getLevel($gid){
        $gid=$gid."";
        $level=self::$maxLevel;
         while(0==(int)substr($gid,3*$level-2, 3)&&$level>0)$level--;
        return $level;      
    }
     
    public static function increaseSeg($gid,$level=false){
         if($level===false)$level=self::getLevel($gid);
         $newcid= self::replaceWithSeg($gid,  self::getSeg($gid,$level)+1,$level);
         $newcid=$newcid ? $newcid:$gid;
         return $newcid;
    }
    public static function replaceWithSeg($gid,$newSeg,$level=false){
        if($level===false)$level=self::getLevel($gid);
        if( $level>self::$maxLevel ||$newSeg>999||$newSeg<0)return false;
        $sSeg=$newSeg.'';
        $sLen=strlen($sSeg);
        switch($sLen){
            case 1:$sSeg="00".$sSeg;break;
            case 2:$sSeg="0".$sSeg; break;
        }
        if($level==0)   return  substr_replace($gid, $newSeg,0,1);
        $gid=substr_replace($gid, $sSeg,3*($level)-2, 3);
        return $gid;
    }
    
    public static function isA($gid,$parent_gid){
     	$nxtPid=self::increaseSeg($parent_gid);
     	$level=self::getLevel($gid);
     	$pLevel=self::getLevel($parent_gid);
        
     	return $gid > $parent_gid && $gid < $nxtPid && $level > $pLevel;
    }
    public static function getPrefix($gid,$level=false){
     	if($level===false)$level=self::getLevel($gid);
     	$prefix=substr($gid,0, 3*$level+1);
        return $prefix;
    
    }
}
 
 