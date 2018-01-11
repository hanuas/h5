<?php
class AlipayService{
    private $rsaPrivateKey = 'MIIEpAIBAAKCAQEAsqweHdDguIZl2lEL6Wb2pwwGEzdeqigkBV/QS5tizNbDFwazleVU2FkfERWqeLuVDweTaVDPwa++HI2o+IqVGIRYCGlJP/uPFSH543vMgHAbiHXx8PzkoJVeG757gbuS/1DvS24YDjyWiYyVUGzjuV3mj+GCmZOl63VsL+QRT2WX6qPzOlHN7wLiZXMm9dEQygI09zb6R9sA931GG6v8stJkoa0QbdeLqwJQkoZtX8ELZaKmgwetUSD95+lRvppT1w75ZnBMvnfN4JN/plNjejD7wzPnL7Irgtb9CHffARaoAtqcqqLOxvOq5YQ1jrpDBarPBy2k0GMqb3V3wQE1UQIDAQABAoIBAQCsBHmaSxePkBVPcuf89lxRx4mxxoUlXTAQ3IY1xIwCmmGJMbKEhh6BjIwUbr+JLU/9AFYz9tGOsBxgcSJU51dUG1aGApe7Of4vYl6rXO0uWsCnSKZ+VXvn/uZz35gY9vY3STyZao1OZ5anJuo6XBuCw5zOuVZ7dWMwpgRat0DTJmge6lrnnKNWQVE+d/YNDoKQHzHH+WnhAh6RMghN+SdbCZoaJH9HoiwwfssZnoZuwiedo6Tt30euK32wek/OuA3QOz8a+F64qxwvB7KCWHvxDrgtHPZ4bKEkfMk6avjT0WoyBYgtdOIZoWS3PpHtbHPNH186B2DEb50P1VLRBFCBAoGBAODj91PUYlnd8MpMz7EgusHnQPg8BNdYZO/GN4H8Z9dt2DYjM6SZlkx4Llm9RFyClWSMcshfmaXcvDYGaGfaJtdlB5StL6JSVWvt1cvymxnh4Vh2YykU7OmbPcRrng2ke8rRMvGMHPxj6AeQAEmseqVx+CvoVbRr4Rq42SVLmHA1AoGBAMtjbjrmFVIJ2JRpv1Li/kpY3TVuc3fFo3Ih6s+1tI9kQ2tnRa03WD6SLctvM7gRjl1UraJHYHBe7P5MMc+paOLiGovw0toRNe91XuJvFyupeIfTG5PKEPG6S34La3pxXogjiWAxZJ5cq1919eDdOViYpqzBRXmcj/KRXv9OhwwtAoGAT/Q8hm1H2jNx3DqazJqaSz3wd1C/V+BxYl3SdkBKmujOqdCyF58TNHS15enIWCuE+Y6FcH+snK9xqrN0gxeoH4QnwdAg01D6Vbjk/fFu+zC3cUAF2SE7aYJr311zf94utNjTh9UMqR7L64Ri1c6gfBmc5d9gORjxmxKFASFzPNECgYAzkC63AfPvy407+x9FPUUoxiS20hy2BcYtPkoQhMmU/N9W0GTKyFg37MltZPDgvpqwMLKgjKX3dylwQxLxycpMkvQV/GUIYgqhfckjcPSX3iwviybXrgfjsM5I005ZSXqk2xWN7JfMykOsPPSsIInUW0cOTyV08mtQHOjPozH7tQKBgQC98R5EsiU6igx0Pi4m01wQYiDaCf4AVgPLD4Qg0uZRH48KvE65uxEpW4UIe92WE77Jqju9FsoYRnQVwjBFEMfNJUnghbSJ2cATxjY1o+U7br9OoG8PpBpzu+qiuXX7VxM/22wGnnz6GXxrJ8w0M8bNCNLHgRnq5SphQbe2i1EwhA==';
    private $alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx4GEqY/78qvBoEgM4B3I2ibWrdLhrqKHjuNmnPDKhr0KHI9EEcmdBqBvAOZtpk3XK3HZ5tSdBSCAwQEK7B5oPrftKpCFISOCVEVuxJBnZIBX1BuxTQ79FvsQY5jEv/UPiYaaBoCQb874WYt7B8ixD49lTSnaWzntdu/X6oPA191l7rlDUvdiwIQ8iUViC54cYhjRDyjmSYfTxCBGwm6cPmJEbEQRHZbepUNRbf+d2yDh48Nh4BcGZaFM0g4cAFBiG/eAdzEl4BQjPk2OkcfDohI8fAXoMeC2rHS2QFZqZMzrug+xaVyAHyhqI9rmzGXnUr3ljl52YOSZlqzw1+uCswIDAQAB';

    //private $gatewayUrl = "https://openapi.alipay.com/gateway.do";
    private $gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
    private $appId = '2016073000121885';
    private $apiVersion = '1.0';
    private $postCharset='utf-8';
    private $format='json';
    private $signType='RSA2';



    private $notify_url = "";
    private $return_url = "";

    public function __construct(){
        $http_type = getHttpType();
        require_once _THIRD_DIR_.'pay/alipay/AopSdk.php';
        $this->notify_url = $http_type.$_SERVER['HTTP_HOST'].'/pay/alipay2/payNotify';
        $this->return_url = $http_type.$_SERVER['HTTP_HOST'].'/pay/alipay2/payReturn';
       # $this->notify_url = 'http://www.noaindustry.com/en/product/lala';
    }

    //生成手机支付跳转地址
    public function getMobilePayRedirectUrl($order_id,$amount,$productSubject){
        $aop = new AopClient();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->alipayrsaPublicKey;
        $aop->apiVersion = $this->apiVersion;
        $aop->postCharset = $this->postCharset;
        $aop->format = $this->format;
        $aop->signType = $this->signType;
        $request = new AlipayTradeWapPayRequest ();
        $request->setReturnUrl($this->return_url);
        $request->setNotifyUrl($this->notify_url);
        $product_args = array(
            "body"=>$productSubject,
            "subject"=>$productSubject,
            "out_trade_no"=>$order_id,
            "timeout_express"=>"90m",
            "total_amount"=>$amount,
            "product_code"=>"QUICK_WAP_WAY",
        );
        $con = json_encode($product_args);

        $request->setBizContent($con);
        try {
            $url = $aop->pageExecute($request, 'GET');
        }catch(Exception $e){
            return false;
        }
        return $url;

    }


    //充值回调验证参数
    public function checkNotify($post){
        $client = new AopClient();
        $client->alipayrsaPublicKey = $this->alipayrsaPublicKey;
        return $client->check($post,$this->alipayrsaPublicKey);

    }

    

}