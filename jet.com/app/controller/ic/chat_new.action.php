<?php
class chat_newController extends icommonController{
  /**
    *
    */
    public function indexAction(){
        if(!in_array(@$_GET['cmd'],array("blackChatUid"))){
            json_exit(4,"非法参数");
        }

        switch($_GET['cmd']){
            case "blackChatUid":
                $this->blackChatUid();
            break;

        }

    }

    //聊天举报
    private function blackChatUid(){
        $chatUid = @$_POST['chatUid']+0; //举报人uid
        $blackChatUid = @$_POST['blackChatUid']+0; //被举报人uid
        $content = addslashes(strip_tags(trim(@$_POST['content']))); //举报内容
        $messageId = @$_POST['messageId']+0;
        $roomId = @$_POST['roomId'];
        
        if(!$chatUid || !$blackChatUid || !$content || !$messageId || !$roomId){
            json_exit(2,"缺少必要参数");
        }
     
        //TODO:入库

        echo json_encode(array("error"=>0));



    }

}