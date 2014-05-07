function callInfo(msg){
	var n = noty({
		text: msg,
		type: '',
		layout: 'top',
		dismissQueue: true
	});
}

$(document).ready(function(){
	$("#USER_LOGIN")
		.on("focus", function(){
			if($(this).val() == "Логин"){
				$(this)
					.val("");
			}
		})
		.on("blur", function(){
			if($.isEmptyObject($(this).val())){
				$(this)
					.val("Логин");
			}
		});
	$("#USER_PASS")
		.on("focus", function(){
			if($(this).val() == "Пароль"){
				$(this)
					.val("");
			}
		})
		.on("blur", function(){
			if($.isEmptyObject($(this).val())){
				$(this)
					.val("Пароль");
			}
		});
});