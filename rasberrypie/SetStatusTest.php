<?php
/*
トイレ端末からの自動ログイン機能を持った
トイレ状態遷移ファイル
URLからパラメータ-を与えて、ログインを行う。
パラメーターの取得はURLからGETで行う。
パラメーターはIDとOpenSSLで暗号化されたパスワード、
トイレ状態も付加して使用する。
**下記URLのサンプル**
https://test-mbc.herokuapp.com/rasberrypie/SetStatusTest.php/?Terminal=01&encpw=/I034gPQPtBQaxGVr7PqPQ==&status=1
?以降がパラメーターであり、項目は＆で区切る。
Terminal...端末ID
encpw......OpenSSLで暗号化されたパスワード
status.....変更したい状態を入力（-1,0,1  →→　使用不可、空室、在室）
*/

require_once "../php/Common.php";      //～～おまじない～～
$db = new Common();             //
$key = "mbctoilet";             //OpenSSL用暗号化パスワード（復号化にも使用）
$flg = false;   //LoginFlg

/*  アクセス端末のパラメータ情報取得  */
if(isset($_GET['Terminal'])){
    $id = $_GET['Terminal'];        //パラメータから端末ID取得
}
if(isset($_GET['encpw'])){
    $encpw = $_GET['encpw'];        //パラメータから端末ID取得
}
if(isset($_GET['status'])){
    $status = $_GET['status'];
}

/*  DB側のHash情報をIDをもとに取得  */
$sql = 'select hash from "TerminalInfo"where id = \'' . $id . '\'';
$dbhash = $db->db_sql($sql);    //DB側のhash値

/*  DBとパラメーターHash値の照合  */
/*  password_verify($dbhash) 、OpenSSLを使用  */
/*  共通暗号化キー"mbctoilet"で２重対策し、Verifyを行う。  */
/*  なお、"mbctoilet"キーを使用するのは、通信取得したencpwパラメーターに限定する。  */
//openssl_encrypt();  //暗号化

//OpenSSLにて
if($dbhash != null){
    $dec_pass = openssl_decrypt($encpw,'AES-128-ECB',$key);  //暗号化されたPWを復号化(URLパラメータから取得したものを使用)
    echo $dec_pass;
    var_dump( $dbhash[0]['hash']);
    if(password_verify($dec_pass,$dbhash[0]['hash'])){     //DBのhashをパラメーターから受け取ったパスワードでベリファイする。
        //照合してOKならログインFLGをTrueに変更
        echo "flgTrue!!!";
        $flg = true;
    }
}



if($flg == true){
    $sql = 'SELECT "Status" 
            FROM "ToiletTerminal";';      //DBManagerからSQL文が決まったらここに入力！   現在のトイレの状態を取得するクエリ（実行結果：-1,0,1　のどれか）
    $NowStatus = $db->db_sql($sql);    //現在のトイレの情報を取得
    if($NowStatus !== $status){   //在室ならDBを操作しない（誤作動の可能性を考慮）
        $sql = 'UPDATE "ToiletTerminal" SET 
                       "Status" =  '. $status .',"UpdateTime"    = CURRENT_TimeStamp + \'9 hours\';';
        $db->db_sql($sql);    //状態のセット実行

        $sql = 'INSERT INTO "RuiInfo" ("TanmatsuInfo","Date","StartTime","EndTime","UsedTime")
                SELECT "TanmatsuInfo",CAST("UpdateTime"as Date),"UpdateTime",(NULL),(NULL)
                FROM   "ToiletTerminal";'; //入室時間のデータをトイレ情報累積テーブルに格納する。
        $db->db_sql($sql);    //使用状況のセット実行
    }
    $db->db_close();
}

?>
