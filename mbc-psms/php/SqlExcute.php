<?php

//Ajaxにて実行でSQL事項結果を2次元連想配列にて返す。
//$data内に配置

require_once "./Common.php";
$db = new Common();


//header('Content-type: text/plain; charset= UTF-8');

if(isset($_POST['sql'])){
    $sql = $_POST['sql'];
    $data = $db->db_sql($sql);
    
    if($data == null){
        if($db->db_msg() == null){
            //$data = "OK";
            //$result = array([0] => "400",[1]=>$db->db_msg());
            //echo json_encode( $result );
            echo json_encode($data),json_encode("OK");
        }else{
            $result = array([0]=> "100",[1]=>$db->db_msg());
            //$data = "ERR";
            //echo json_encode( $result );
            //echo $result;
            echo json_encode($data),json_encode("ERR");
        }
    }else{
        //正常終了時
        echo json_encode( $data );
    }
    //echo json_encode( $data );
}else{
    echo 'FAIL TO AJAX REQUEST';
}

?>
