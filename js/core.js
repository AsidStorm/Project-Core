var PARAMS = new Object();
var MODULE_NAME, MODULE_ACTION;

/* ajax actions */

function modal(title, fields, buttons){
	$('#modal')
		.modal()
		.open({
			title: title,
			fields: fields,
			buttons: buttons,
			onOpen: function(options){
				$.noty.closeAll();
			}
		});
}

function modalAjaxLoader(data, callback){
	var tmp;
	$.ajax({
		beforeSend: function(){
			$(".loader")
				.show();
			$(".loader img")
				.center();
		},
		url: '_ajax.php',
		type: 'POST',
		data: data,
		success: function(json){
			var isError = false;
			try{
				var data = $.parseJSON(json);
			}
			catch(e){
				isError = true;
			}
			finally{
				$(".loader")
					.hide();

				if(!isError){
					if(data['status'] == 'success'){
						if(callback){
							callback(data);
						}
					}
					else{
						n(data['desc'], "top", "error");
					}
				}
				else{
					n(json.replace(/<[\/]*script(.*?)>/g, "").replace("<br />", ""), "top", "error");
				}
			}
		}
	});
}

/* pagination */

function pagination(action, currPage, setPage, callback){
	modalAjaxLoader({
		act:      action,
		currPage: currPage,
		setPage:  setPage
	}, function(data){
		$("#pagination")
			.html(data['pagination']);
		
		var fn = window[callback];
		
		if(typeof fn === "function"){
			fn(data);
		}
	});
}

function simplyPagination(data){
	$(".preData ~ .dataRow")
		.remove();
	
	$(".onPage")
		.text(data['onPage']);
	
	var afterData = $(".afterData");
	$.each(data['data'], function(key, html){
		$(afterData)
			.before(html);
	});
}

/* notifications */

function n(msg, position, type, callback){
	noty({
		force: true,
		text: msg,
		type: type,
		layout: position,
		dismissQueue: true,
		timeout: 1500, // 1.5 sec for dismiss
		buttons: (type != 'confirm') ? false : [
		{
			addClass: 'btn btn-primary',
			text: 'Да',
			onClick: function($noty) {
		    	$noty.close();
			}
		},
		{
			addClass: 'btn btn-danger',
			text: 'Отмена',
			onClick: function($noty) {
		    	$noty.close();
			}
		}]
	});
}