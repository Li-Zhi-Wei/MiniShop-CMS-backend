<?php


namespace app\api\controller\v1;


use app\api\model\DeliverRecord;

class test
{

    public function test(){
        $a = DeliverRecord::field('number')->where('order_no','=','C902911669242605')->find()->number;
        echo $a;
        echo '......';
        var_dump($a);
    }
}
