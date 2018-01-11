<?php
class paygateController extends payCommonController{
    
    //检查订单支付状态
    public function isTransPaiedAction(){
        $trans_id = trim($_GET['trans_id']);
        if(!is_numeric($trans_id)){
            echo 'NO';exit;
        }
        $payService = new PayService();
        $isPaied = $payService->checkOrderIsPaied($trans_id);
        if($isPaied){
            echo 'YES';
        }
    }
}