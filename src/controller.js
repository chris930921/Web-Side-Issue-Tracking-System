window.onload = function(){
	var login = document.getElementById("login_button");
	login.addEventListener("click",function(){
		document.getElementById("login").style.WebkitAnimation = "close_login 1s 1";
		document.getElementById("login").style.animation = "close_login 1s 1";
		document.getElementById("login").addEventListener("webkitAnimationEnd", function(e){
			document.getElementById("login").style.visibility = "hidden";
			document.getElementById("login").style.animation = "";
		}, false);
	});
}