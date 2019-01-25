showstatus();
function showstatus(){  
  Promise.all([   //下記で呼び出された関数が全て終了してからthen移行へ移行
    RequestStartStatus('./php/GetStatus.php')     //GetStatus.phpを実行
  ]).then(        //すべて上記が正常終了した場合に処理開始
    success => {        //resolveが代入される
      var status = JSON.parse(success);
      var vacancy = document.getElementById('vacancy');
      switch(status){     //statusの値でcaseで分岐
        case  0:
          vacancy.src = "./img/free.png";
          //console.log(0);
          break;
        case  1:
          vacancy.src = "./img/use.png";
          //console.log(1);
          break;
        case -1:
          vacancy.src = "./img/not.jpg";
          //console.log(-1);
          break;
        default :
          //console.log("default");
          break;
                   }
    },
  )

  function RequestStartStatus(url){
    return new Promise((resolve,reject) => {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function(){
        if(xhr.readyState ===4 && xhr.status === 200){    //通信が正常時
          var responsedata = xhr.responseText;
          resolve(responsedata);
        }else if(xhr.status === 404){                     //通信が異常時
          console.log("Err : Not Found Code:404");
          //reject("Err : Not Found");
        }
      }    
      xhr.open("GET",url,true);
      xhr.send(null);
    });
  }
    
    setTimeout('showstatus()',1*1000);
}
