var aaaa = null;
var bbbb = null;
var cccc = null;

function GetData(url){
    return new Promise((resolve,reject) => {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if(xhr.readyState ===4 && xhr.status === 200){    //通信が正常時
        resolve(JSON.parse(xhr.responseText));
      }else if(xhr.status === 404){                     //通信が異常時
        console.log("Err : Not Found Code:404");
      }
    }    
    xhr.open("GET",url,true);
    xhr.send(null);
  });
}

function ChangeData(Data){
    return new Promise((resolve,reject) => {
      var MyDate = new Date(Data['UpdateTime']);
      var Hours = MyDate.getHours();
      var Minutes = MyDate.getMinutes();
      resolve( {"Hours" : Hours,"Minutes" : Minutes,"Status" : Data['Status']} );
    });
}

function SetParameters(Data){
    return new Promise((resolve,reject) => {
        console.log(Data);
        aaaa = Data['Status'];
        bbbb = Data['Hours'];
        cccc = Data['Minutes'];
        resolve(Data);
    });
}

function ShowSetData(data){
    return new Promise((resolve,reject) => {
        console.log(aaaa);
        console.log(bbbb);
        console.log(cccc);
    });
}

GetData("./php/GetStatusTime.php").then( data => {
    ChangeData(data).then( data => {
        SetParameters(data).then( data => {
            ShowSetData(data).then( function (){
            });
        });
    });
});
