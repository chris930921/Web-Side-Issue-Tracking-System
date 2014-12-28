window.onload = function(){
	console.log("Dom loaded.");
	hidden();
	$(document).ready(init);
}

function init(){
	console.log("Jquery loaded.");
	var login = document.getElementById("login_button");
	login.addEventListener("click",function(){
		$.ajax({
		  type: "POST",
		  url: "./login.php",
		  data: {
		  	email : document.getElementById("login_email").value,
		  	password : document.getElementById("login_password").value
		  },
		  success: function(data){
		  	console.log(data);
		  	if('token' in data){
	  			try{
					var cookie = JSON.parse(document.cookie);
				}catch(e){
					document.cookie='{}';
					var cookie = JSON.parse(document.cookie);
				}
				cookie.token = data.token;
				document.cookie = JSON.stringify(cookie);
				logined();
		  	}else if('error' in data){
		  		console.log(data.error);
		  		document.getElementById("login_text").innerHTML = data.error;
		  	}
		  },
		  error: function(jqXHR, textStatus, errorThrown){
		  	console.log(errorThrown);
		  },
		  dataType: "json"
		});

	});
	
	var register = document.getElementById("register_button");
	register.addEventListener("click",function(){
		$.ajax({
		  type: "POST",
		  url: "./register.php",
		  data: {
		  	email : document.getElementById("register_email").value,
		  	password : document.getElementById("register_password").value
		  },
		  success: function(data){
		  	console.log(data);
	  			document.getElementById("register_text").innerHTML = data.message;
		  },
		  error: function(jqXHR, textStatus, errorThrown){
		  	console.log(errorThrown);
		  },
		  dataType: "json"
		});
	});
	var logout = document.getElementById("logout_button");
	logout.addEventListener("click",function(){
		document.cookie='{}';
		document.getElementById("login").style.visibility = "visible";
		document.getElementById("login").removeEventListener("webkitAnimationEnd", logined_animation_end);
		document.getElementById("login").style.WebkitAnimation = "open_login 1s 1";
		document.getElementById("login").style.animation = "open_login 1s 1";

		hidden();
	});
}
function logined(){
	document.getElementById("login").style.WebkitAnimation = "close_login 1s 1";
	document.getElementById("login").style.animation = "close_login 1s 1";
	document.getElementById("login").addEventListener("webkitAnimationEnd", logined_animation_end, false);
	var elements = document.getElementsByClassName('right_option');
	for(var i = 0; i<elements.length; i++){
		elements[i].style.visibility='visible';
	}
}
function hidden(){
	document.getElementById("load_spinner").style.visibility='hidden';
	var elements = document.getElementsByClassName('right_option');
	for(var i = 0; i<elements.length; i++){
		elements[i].style.visibility='hidden';
	}
}
function logined_animation_end(){
	document.getElementById("login").style.visibility = "hidden";
	document.getElementById("login").style.WebkitAnimation = "";
	document.getElementById("login").style.animation = "";
}