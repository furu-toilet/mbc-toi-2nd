<?php
/* このファイルについて */
// 作成者：古澤
// 最終更新日付：2019/08/23
// 戻り値：DBの値を配列で返す。ToiletTerminalテーブル
//        array(status,UpdateTime)
/* 以下コード */

require_once "./Common.php";
$db = new Common();

$sql = "select * from \"ToiletTerminal\" where \"TanmatsuInfo\" = 'ラズパイ1F男子トイレ';";
$SqlData = $db->db_sql($sql);     // 現在１レコードを想定

$StatusData = $SqlData[0]['Status'];
$UpdateTimeData = $SqlData[0]['UpdateTime'];

$result = json_encode(array("Status"=>$StatusData,"UpdateTime"=>$UpdateTimeData));

echo $result;
?>
