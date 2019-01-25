$.ajax({
  url: "http://api.openweathermap.org/data/2.5/weather?q=takasaki,jp&appid=59ca09e691c813495a3bc5cdd62ad209",
  cache: false,
  success:function (weatherdata){
    // img insert

    var img = document.createElement('img');
    img.src = "http://openweathermap.org/img/w/"+weatherdata.weather[0].icon+".png";
    img.alt = weatherdata.weather[0].main;
    document.getElementById('icon').appendChild(img);

    // 温度取得
    document.getElementById('temp').innerHTML = Math.floor(weatherdata.main.temp - 273.15);

    // 位置取得
    document.getElementById('here').innerHTML = weatherdata.name;

  }
});