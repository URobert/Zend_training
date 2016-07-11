<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Nixie+One' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Ledger' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
		
		<title> Local Weather App </title>
		<h2> Local Weather App </h2>
		
		<style>
		body { 
		  background: url(http://hdwallpapersimage.com/wp-content/uploads/2014/06/hdwallpapersimage.com-city-lights-beyond-the-rainy-window-wide-hd-wallpaper-2560x1600.jpg) no-repeat center center fixed; 
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		}
		
		#mycontainer{
		 background-color:rgba(0,0,0,0.4);
		}
		
		.wrapper{
		  text-align:center;
		}
		
		.top-buffer { margin-top:20px; }
		.intro-buffer { margin-top: 20px;}
		
		h2{
		  font: 400 40px/0.8 'Great Vibes', Helvetica, sans-serif;
		  color: #fff;
		  text-shadow: 4px 4px 3px black; 
		  text-align: center;
		  margin-top:50px;
		}
		
		.icon {
		border-radius: 0.2em; 
		font-size: 3em; 
		display: inline-block;  
		padding: 0em;
		color: white;
		background:transparent;
		  
		}
		
		.temperature {
		border-radius: 1em; 
		font: 400 40px/1.6 'Ledger', Garamond, Georgia, serif; 
		display: inline-block;  
		padding: 0.5em;
		color: white;
		background:transparent;
		text-align: center;
		}
		
		.weatherCondition {
		text-align: center;
		border-radius: 1em; 
		font-size: 1.5em; 
		display: inline-block;   
		padding: 0.5em;
		color: white;
		background:transparent;
		}
		
		.location {
		text-align: center;  
		/*width: 20%;*/
		border-radius: 0.2em; 
		font: 400 25px/1.6 'Ledger', Garamond, Georgia, serif;
		display: inline-block;    
		padding: 0.5em;
		color: white;
		background:transparent;
		}
		
		.container-fluid{
		  border: 2px solid gray;
		  background-color:rgba(0,0,0,0.8);
		}
		
		#element1 , #element2 {
		  font-style: italic;
		}
		
		p {
		  font: 400 15px/1.6 'Ledger', Garamond, Georgia, serif;
		}
		
		button {
		  color: black;
		  /*border-radius: 1.5em;*/
		}
		
		.btn-default{
		  background: #D3D3D3;
		}
		
		#switchB{
		  background:white;
		  font: 400 30px/0.8 'Great Vibes', Helvetica, sans-serif;
		  padding:10px;
		}


		</style>
	</head>

<body>
<div class = "wrapper">
<div class = "intro-buffer"> </div>
<div class ="container-fluid" id="myContainer">
   <div class = "location col-md-12"><p id ="data">Not Available</p></div>
    <div class="top-buffer"></div> 
    <div class = icontemp>
    <div class = col-md-12>
    <div class = "icon"><img src="http://openweathermap.org/img/w/10d.png" id="myIcon"></img></div>
      <div class = "temperature"><p>Not Available</p></div> 
  </div> <!-- end of col-md-12 for icon an temperature -->
</div> <!-- end of incontemp -->

  <div class="top-buffer"></div> 
  <div class = "weatherCondition col-md-12">
    <p id = "element1">Weather description: Not Available</p>
    <p id = "element2">Wind speed: Not Available</p>
    <button class=btn-default id="switchB" onclick="switchFunction();">Switch to Fahrenheit</button>
  </div>

</div> <!-- End of container-fluid -->
</div> <!-- End of wrapper -->
</body>
</html>



<script language = "javascript">

	var city,country,temp,wind,windKm,wIcon,wIconDisplay,wDescription;
	var appId = "01ffc2b8227e5302ffa7f8555ba7738e";
	var units = "metric" , unitDisplay = "C" , counter = 1; var temp;
	var wUnit = " Km/h";
	var weatherApiUrl;
	var currentLat, currentLong;

	//GET GeoLocation
	var url = 'http://ip-api.com/json/?callback=?';
	  $.getJSON(url, function(json) {
		currentLat=json.lat;
		currentLong=json.lon;
		getCurrentWeatherParameters();
	  });

	function getCurrentWeatherParameters() {
	weatherApiUrl = 'http://api.openweathermap.org/data/2.5/weather?lat=' + currentLat + '&lon=' +    currentLong  + "&units=" + units + "&APPID=" + appId;
    
    $.getJSON(weatherApiUrl, function(json) {
    city = json.name;
	if (city === "Subcetate"){
		city = "Oradea";
	}
	
    country = json.sys.country; 
    temp = json.main.temp;  
    wind = json.wind.speed; 
    wIcon = json.weather[0].icon;
    wDescription = json.weather[0].description; 
    wDescription = wDescription.charAt(0).toUpperCase() + wDescription.slice(1);  
      
        
	// Update HTML with content  
	 
	wIconDisplay = "http://openweathermap.org/img/w/" + wIcon + ".png";
	document.getElementById("myIcon").src=wIconDisplay; 
	temp = Math.round( temp * 10) / 10;
	if (wUnit === " Km/h"){
	windKm = wind * 3.6;     // converting Knots to Km/h
	}else{
	windKm = wind * 1.15;   // converting Knots to Mph
	}
	windKm = windKm.toFixed(2); //displaying only two decimals
	$(".temperature").html(temp + " "+ unitDisplay);
	$(".location").html(country+","+city);
	$("#element1").html("Weather description: " + wDescription);
	$("#element2").html("Wind speed: " + windKm  + wUnit);
	modBackground(temp,units);
      //}
    //}  
  });
}    


	// Toggle Fahrenheit - Celsius
	function switchFunction () {
	counter++; 
	console.log(counter);
	if (counter % 2 === 0){ 
		units = "imperial";
		unitDisplay = "°F";
		$("#switchB").html("Switch to Celsius");
		wUnit = " Mph";
		getCurrentWeatherParameters();
	  
		}else{
		units = "metric";
		unitDisplay = "C";
		$("#switchB").html("Switch to Fahrenheit");
		wUnit = " Km/h";
		getCurrentWeatherParameters(); 
		}
	}
	
	
	// Swtich background depending on temperature
	function modBackground(t){
	if (counter === 1){
	  if (t  < 0 ){
		$('body').css("background", "url(http://www.eveboo.com/wp-content/uploads/2013/04/winter-hd-1080p-wallpapers-download.jpg");
	  }
	
	  if (t  >= 10  && t <= 20 ){
		$('body').css("background", "url(http://www.hdhalloweenimages.com/wp-content/uploads/2015/11/November-Wallpaper-1.jpg");
	  }
	
	  if (t  > 20  && t <= 30 ){
	   // $("#myContainer").css("background","#2A8077");
		$('body').css("background", "url(https://newevolutiondesigns.com/images/freebies/summer-wallpaper-25.jpg");
	  }
	
	  if (t  > 30 ){
		//$("#myContainer").css("background","#2A8077");
		$('body').css("background", "url(http://quotesideas.com/wp-content/uploads/2015/05/funny-summer-wallpaper1.jpeg");
		 }
	  }
	}


</script>