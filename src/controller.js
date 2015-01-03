window.onload = function(){
	console.log("Dom loaded.");
	hidden();
	$(document).ready(init);
}

function init(){
	console.log("Jquery loaded.");
	login_button_event();
	register_button_event();
	logout_button_event();
	new_issue_event();
	manage_button_event();
	issue_list_event();
}
function login_button_event(){
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
}
function register_button_event(){
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
}
function logout_button_event(){
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
function new_issue_event(){
	var new_issue = document.getElementById("new_issue_button");
	new_issue.addEventListener("click",function(){
		open_page("new_issue_page");
	});
}
function manage_button_event(){
	var manage_issue = document.getElementById("manage_issue_button");
	manage_issue.addEventListener("click",function(){
		open_page("manage_issue_page");
	});
}
function issue_list_event(){
	var issue_list = document.getElementById("issue_list_button");
	issue_list.addEventListener("click",function(){
		open_page("issue_list_page");
	});
}
function logined(){
	open_page("issue_list_page");
	document.getElementById("login").style.WebkitAnimation = "close_login 1s 1";
	document.getElementById("login").style.animation = "close_login 1s 1";
	document.getElementById("login").addEventListener("webkitAnimationEnd", logined_animation_end, false);
	var elements = document.getElementsByClassName('right_option');
	for(var i = 0; i<elements.length; i++){
		elements[i].style.visibility='visible';
	}
	init_issue_list_page();
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
function open_page(page_name){
	var pages = document.getElementsByClassName("page");
	for(var i = 0; i < pages.length ; i++){
		pages[i].style.visibility='hidden';
	}
	document.getElementById(page_name).style.visibility='visible';
}
function init_issue_list_page(){
	$.ajax({
		  type: "POST",
		  url: "./issue_list.php",
		  data: {
		  	token : JSON.parse(document.cookie).token
		  },
		  success: function(data){
		  	var content_tr = '';
		  	for(var i = 0;;i++){
		  		try{
		  			content = '<td value=' + data[i].id + '>'+data[i].title+'</td>'
		  				+'<td>'+data[i].state+'</td>'
		  				+'<td>'+data[i].priority+'</td>'
		  				+'<td>'+data[i].occurency_date+'</td>'
		  				+'<td>'+data[i].expectation_date+'</td>'
		  				+'<td>'+data[i].finished_date+'</td>';
		  			content_tr += '<tr>'+content+'</tr>';
		  		}catch(err){
		  			console.log('跳出issue讀取迴圈');
		  			break;
		  		}
		  	}
		  	var table_content = document.getElementById("table_content");
		  	var table_title = table_content.children[0].innerHTML;
		  	table_content.innerHTML = table_title + content_tr;
		  	for(var i = 1; i < table_content.children.length; i++ ){
		  		var row = table_content.children[i];
		  		row.children[0].addEventListener("click",function(){
		  			open_page("show_issue_page");
		  		});
		  	}
		  },
		  error: function(jqXHR, textStatus, errorThrown){
		  	console.log('載入issue列表失敗: '+errorThrown);
		  },
		  dataType: "json"
	});
}