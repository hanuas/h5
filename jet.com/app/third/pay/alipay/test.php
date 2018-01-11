<?php
include("AopSdk.php");
$aop = new AopClient ();
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
$aop->appId = '2016073000121885';
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAsqweHdDguIZl2lEL6Wb2pwwGEzdeqigkBV/QS5tizNbDFwazleVU2FkfERWqeLuVDweTaVDPwa++HI2o+IqVGIRYCGlJP/uPFSH543vMgHAbiHXx8PzkoJVeG757gbuS/1DvS24YDjyWiYyVUGzjuV3mj+GCmZOl63VsL+QRT2WX6qPzOlHN7wLiZXMm9dEQygI09zb6R9sA931GG6v8stJkoa0QbdeLqwJQkoZtX8ELZaKmgwetUSD95+lRvppT1w75ZnBMvnfN4JN/plNjejD7wzPnL7Irgtb9CHffARaoAtqcqqLOxvOq5YQ1jrpDBarPBy2k0GMqb3V3wQE1UQIDAQABAoIBAQCsBHmaSxePkBVPcuf89lxRx4mxxoUlXTAQ3IY1xIwCmmGJMbKEhh6BjIwUbr+JLU/9AFYz9tGOsBxgcSJU51dUG1aGApe7Of4vYl6rXO0uWsCnSKZ+VXvn/uZz35gY9vY3STyZao1OZ5anJuo6XBuCw5zOuVZ7dWMwpgRat0DTJmge6lrnnKNWQVE+d/YNDoKQHzHH+WnhAh6RMghN+SdbCZoaJH9HoiwwfssZnoZuwiedo6Tt30euK32wek/OuA3QOz8a+F64qxwvB7KCWHvxDrgtHPZ4bKEkfMk6avjT0WoyBYgtdOIZoWS3PpHtbHPNH186B2DEb50P1VLRBFCBAoGBAODj91PUYlnd8MpMz7EgusHnQPg8BNdYZO/GN4H8Z9dt2DYjM6SZlkx4Llm9RFyClWSMcshfmaXcvDYGaGfaJtdlB5StL6JSVWvt1cvymxnh4Vh2YykU7OmbPcRrng2ke8rRMvGMHPxj6AeQAEmseqVx+CvoVbRr4Rq42SVLmHA1AoGBAMtjbjrmFVIJ2JRpv1Li/kpY3TVuc3fFo3Ih6s+1tI9kQ2tnRa03WD6SLctvM7gRjl1UraJHYHBe7P5MMc+paOLiGovw0toRNe91XuJvFyupeIfTG5PKEPG6S34La3pxXogjiWAxZJ5cq1919eDdOViYpqzBRXmcj/KRXv9OhwwtAoGAT/Q8hm1H2jNx3DqazJqaSz3wd1C/V+BxYl3SdkBKmujOqdCyF58TNHS15enIWCuE+Y6FcH+snK9xqrN0gxeoH4QnwdAg01D6Vbjk/fFu+zC3cUAF2SE7aYJr311zf94utNjTh9UMqR7L64Ri1c6gfBmc5d9gORjxmxKFASFzPNECgYAzkC63AfPvy407+x9FPUUoxiS20hy2BcYtPkoQhMmU/N9W0GTKyFg37MltZPDgvpqwMLKgjKX3dylwQxLxycpMkvQV/GUIYgqhfckjcPSX3iwviybXrgfjsM5I005ZSXqk2xWN7JfMykOsPPSsIInUW0cOTyV08mtQHOjPozH7tQKBgQC98R5EsiU6igx0Pi4m01wQYiDaCf4AVgPLD4Qg0uZRH48KvE65uxEpW4UIe92WE77Jqju9FsoYRnQVwjBFEMfNJUnghbSJ2cATxjY1o+U7br9OoG8PpBpzu+qiuXX7VxM/22wGnnz6GXxrJ8w0M8bNCNLHgRnq5SphQbe2i1EwhA==';
$aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAx4GEqY/78qvBoEgM4B3I2ibWrdLhrqKHjuNmnPDKhr0KHI9EEcmdBqBvAOZtpk3XK3HZ5tSdBSCAwQEK7B5oPrftKpCFISOCVEVuxJBnZIBX1BuxTQ79FvsQY5jEv/UPiYaaBoCQb874WYt7B8ixD49lTSnaWzntdu/X6oPA191l7rlDUvdiwIQ8iUViC54cYhjRDyjmSYfTxCBGwm6cPmJEbEQRHZbepUNRbf+d2yDh48Nh4BcGZaFM0g4cAFBiG/eAdzEl4BQjPk2OkcfDohI8fAXoMeC2rHS2QFZqZMzrug+xaVyAHyhqI9rmzGXnUr3ljl52YOSZlqzw1+uCswIDAQAB';
$aop->apiVersion = '1.0';
$aop->postCharset='utf-8';
$aop->format='json';
$aop->signType='RSA2';
$request = new AlipayTradeWapPayRequest ();
$request->setReturnUrl('http://localhost');
$product_args = array(
    "body"=>"月卡包",
    "subject"=>"月卡包",
    "out_trade_no"=>time(),
    "timeout_express"=>"90m",
    "total_amount"=>"0.01",
    "product_code"=>"QUICK_WAP_WAY",
);
$con = json_encode($product_args);


$request->setBizContent($con);
$result = $aop->pageExecute ( $request,'GET');
#$aop->sdkExecute();
echo $result;