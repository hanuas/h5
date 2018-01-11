<?php
include_once _ROOT_DIR_."common/action.php";
class indexController extends Action{
    
    /**
    *   玩游戏入口
    */
    public function indexAction(){
        $gameid = @$_GET['gameid']+0;
        if(!$gameid){
            echo 'game not exist';exit;
        }
        $gameService = new GameService();
        $gameInfo = $gameService->getGameInfoByAppId($gameid);
        if(!$gameInfo){
            echo 'game not exist';exit;
        }
        $game_info = array(
            'url'=>$gameInfo['url'],
            'name'=>$gameInfo['game_name'],
            'game_url'=>$gameInfo['game_url'],  //游戏地址
            'type'=>$gameInfo['type'],
            'dc_appid'=>$gameInfo['dc_appid'],
            'td_appid'=>$gameInfo['td_appid'],  //talkingdata appid
            'ext'=>array(
                'desktopIcon'=>$gameInfo['desktop_icon']  //添加到桌面icon图片
            ),
            //'token_type'=>$gameInfo['token_type'], //传递给游戏所需的token类型，1传递user_token(开天token) 0传递access_token
            'token_type'=>1,
            'orientation'=>$gameInfo['orientation'],//是否横屏游戏，1是，0否
            'wx_option'=>$gameInfo['wx_option'], //是否显示微信右上角菜单  0:隐藏，1不隐藏
            'entry_url'=>$gameInfo['entry_url'], //分享地址
            'content_url'=>$gameInfo['content_url'], //进入游戏地址
            'use_vucoupon'=>$gameInfo['use_vuconpon'] //不知道干嘛的
        );
        $this->assign('title',$gameInfo['game_name']);
        $this->assign('game_info',$game_info);
        $this->render("index.htm",'game_index.tpl');

    }


}

