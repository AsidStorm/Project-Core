;(function($, window, document, undefined) {
	"use strict";
	var files = new Object;
	files.test = {
		1: 0
	};
	
	function lockContainer() {
		$('html, body')
			.addClass('lock');
	}

	function unlockContainer() {
		$('html, body')
			.removeClass('lock');
	}

	function init(els, options) {
		var window = $("#modal-wrap");
		var layout = $("#fader");
		
		return {
			open: function(options) {
				var fields  = options.fields;
				var buttons = options.buttons;
				
				lockContainer();
				
				window.show();
				layout.show();
				
				$(".modal-close")
					.off("click")
					.on("click", function(){
						$.modal().close();
					});
					
				$(".modal-loader")
					.hide();
					
				window
					.find(".title")
					.text(options.title);
				
				options
					.onOpen();
				
				if(!$.isEmptyObject(fields)){
					var tpl = "<div style='padding: 5px;'></div>";
					tpl += "<table class='modal-table'>";
					$.each(fields, function(id, object){
						var uid = Math.floor(Math.random() * 10000);
						/* prepare */
						
						switch(object.type){
							case 'textarea':
								tpl += "<tr id='" + uid + "'>"
									+ "<td colspan='2' style='text-align: center; padding-top: 2px; padding-bottom: 2px;'>" + object.title + "</td>"
									+ "</tr>"
									+ "<tr ud='" + uid + "_1'>"
									+ "<td colspan='2'>"
									+ "<textarea id='" + id + "' style='width: 98%; height: 150px;'" + ((object.readonly !== undefined)?(' readonly'):('')) + ">" + ((object.value)?object.value:'') + "</textarea>"
									+ "</td>"
									+ "</tr>";
								break;
							case 'hidden':
								tpl += "<input type='hidden' value='" + object.value + "' id='" + id + "' />";
								break;
							case 'file':
								tpl += "<tr id='" + uid + "'>"
									+ "<td>"
									+ object.title
									+ "</td>"
									+ "<td>"
									+ "<input type='" + object.type + "' id='" + id + "' style='width: 98%;' />"
									+ "</td>"
									+ "</tr>";
								break;
							case 'password':
								tpl += "<tr id='" + uid + "'>"
									+ "<td>"
									+ object.title
									+ "</td>"
									+ "<td>"
									+ "<input type='" + object.type + "' id='" + id + "' style='width: 98%;' value='" + ((object.value)?object.value:'') + "' />"
									+ "</td>"
									+ "</tr>";
								break;
							case 'information':
								tpl += "<tr id='" + uid + "' style='text-align: center;'>"
									+ "<td colspan='2'><span class='red-text' style='font-size: 22.5px;'>" + object.value + "</span><div style='padding: 2px;'></div></td>"
									+ "</tr>";
								break;
							case 'double': /* from [ ] to [ ] */
								break;
							case 'chooser': /* Select one field and place it into form */
								$.each(object.data, function(k, v){
									tpl += "<tr>"
										+ "<td colspan='2' style='padding: 2.5px; text-align: center;'>"
										+ "<a href='javascript:" + object.callback + "(\"" + v.value + "\"," + v.data + ");' class='sky'>"
										+ v.value
										+ "</a>"
										+ "</td>"
										+ "</tr>";
								});
								break;
							case 'multi.chooser': /* Select many fields and place it into form */
								$.each(object.data, function(k, v){
									var selected = (($.inArray(v.data, object.selected) == -1)?"false":"true");
									var text     = (($.inArray(v.data, object.selected) == -1)?"Не выбрано":"Выбрано");
									var color    = (($.inArray(v.data, object.selected) == -1)?"red":"sky");

									tpl += "<tr style='cursor: pointer;' onClick='" + object.callback + "(\"" + v.value + "\", " + v.data + ");' onMouseOver='this.style.backgroundColor=\"#ccc\";' onMouseOut='this.style.backgroundColor=\"#fff\";'>"
										+ "<td style='text-align: right !important; padding: 2.5px; width: 50%;'>"
										+ "[ <span class='" + color + "' id='m-c-" + v.data + "' data-selected='" + selected + "'>" + text + "</span> ]"
										+ "</td>"
										+ "<td style='padding: 2.5px; width: 50%;'>"
										+ v.value
										+ "</td>"
										+ "</tr>";
								});
								break;
							case 'input':
								tpl += "<tr id='" + uid + "'>"
									+ "<td>"
									+ object.title
									+ "</td>"
									+ "<td>"
									+ "<input type='" + object.type + "' id='" + id + "' style='width: 98%;' value='" + ((object.value)?object.value:'') + "' " + ((object.readonly !== undefined)?('readonly '):('')) + "/>"
									+ "</td>"
									+ "</tr>";
									
								if(object.special == 'dateSelector'){
									tpl += '<script>$("#' + id + '").pickmeup({ hide_on_select: true });</script>';
								}
								else if(object.special == 'autocomplete'){
									$.modal().autocomplete(id, object.specialData);
									
									tpl += "<input type='hidden' id='" + id + "ID' value='-1' />";
								}
								break;
							case 'multidata':
								$.each(object.data, function(k, v){
									tpl += "<tr id='" + v.id + "' style='padding: 2.5px;'>"
										+ "<td colspan='2' style='padding: 2.5px;' class='titles'>"
										+ v.title
										+ "</td>"
										+ "</tr>";
										
									$.each(v.actions, function(i, o){
										tpl += "<tr id='" + o.id + "' style='padding: 2.5px;' onMouseOver='this.style.backgroundColor=\"#ccc\";' onMouseOut='this.style.backgroundColor=\"#fff\";'>"
											+ "<td style='padding: 2.5px; padding-left: 5px;'>"
											+ o.title
											+ "<input type='hidden' id='acl_" + o.aclID + "' value='" + (o.access) + "' />"
											+ "</td>"
											+ "<td style='padding: 2.5px; text-align: center; cursor: pointer;' onClick='adminSwitchAccess(\"" + o.aclID + "\");' id='td_" + o.aclID + "'>"
											+ ((o.access)?'<span class="sky">Разрешено</span>':'<span class="red">Запрещено</span>')
											+ "</td>"
											+ "</tr>";
									});
								});
								break;
							case 'select':
								tpl += "<tr id='" + uid + "'>"
									+ "<td>"
									+ object.title
									+ "</td>"
									+ "<td>"
									+ "<select id='" + id + "' style='width: 100%;'>";
								if(!$.isEmptyObject(object.values)){
									$.each(object.values, function(k, o){
										tpl += "<option value='" + k + "'" + ((object.selected == k)?' selected':'') + ">" + o.t + "</option>";
									});
								}
								else{
									$.ajax({
										url: '_ajax.php',
										type: 'POST',
										data: object.data,
										success: function(json){
											var isError = false;
											try{
												var data = $.parseJSON(json);
											}
											catch(e){
												isError = true;
											}
											finally{
												if(!isError){
													if(data['status'] == 'success'){
														$.modal().fillSelect(id, data['data'], object.selected);
													}
													else{
														n(data['desc'], "top", "error");
													}
												}
												else{
													$.modal().close();
													n(json.replace(/<[\/]*script(.*?)>/g, "").replace("<br />", ""), "top", "error");
												}
											}
										}
									});
								}
								tpl += "</select>"
									+ "</td>"
									+ "</tr>";
								break;
							default:
								break;
						}
						
					});
					
					tpl += "<tr style='border: 0px !important;'>"
						+ "<td style='border: 0px !important;' align='center' colspan='2'>"
						+ "<button class='btn btn-primary' style='margin-left: 0px;' id='bOk'>" + buttons['ok'].title + "</button>"
						+ ((buttons['cancel'] !== undefined)?("<button class='btn btn-danger' style='margin-left: 0px;' id='bCancel'>" + buttons['cancel'].title + "</button>"):(""))
						+ "</td>"
						+ "</tr>"
						+ "</table>";
					
					window
						.find(".content")
						.html(tpl);
					
					
					$.modal().initFiles();
					$.modal().setupButtons(buttons, fields);
				}
			},
			close: function(){
				unlockContainer();
				
				window.hide();
				layout.hide();
				
				$(".modal-close")
					.off();
			},
			fillSelect: function(id, data, selected){
				if(!$.isEmptyObject(data)){
					$.each(data, function(k, o){
						$("#" + id)
							.append("<option value='" + k + "'" + ((selected == k)?' selected':'') + ">" + o.t + "</option>");
					});
				}
			},
			initFiles: function(){
				$("input[type='file']")
					.change(function(event){
						var id = $(this).attr("id");
						files[id] = event.target.files;
					});
			},
			autocomplete: function(id, action){
				$.ajax({
					url: "_ajax.php",
					type: "POST",
					data: {
						act: action
					},
					success: function(json){
						var isError = false;
							
						try{
							var data = $.parseJSON(json);
						}
						catch(e){
							isError = true;
						}
						finally{
							if(!isError){
								if(data['status'] == 'success'){
									$("#" + id)
										.autocomplete({
											lookup: data['data'],
											onSelect: function(suggestion) {
												$("#" + id + "ID")
													.val(suggestion.data);
											}
										});
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
			},
			getFiles: function(){
				return files;
			},
			setupButtons: function(buttons, fields){
				if(buttons['cancel'] !== undefined){
					$("#bCancel")
						.text(buttons['cancel'].title)
						.off()
						.on("click", function(){
							$.noty.closeAll();
							
							if(!buttons['cancel'].onClick){
								$.modal().close();
							}
							else{
								buttons['cancel'].onClick();
							}
						});
				}
				
				if(buttons['ok'].hide == true){
					$("#bOk")
						.hide();
					return;
				}
				
				if(buttons['ok'].noAct == true){
					$("#bOk")
						.off()
						.on("click", function(){
							$.noty.closeAll();
							$.modal().close();
						});
					return;
				}
				
				$("#bOk")
					.off()
					.on("click", function(){
						var data = new FormData();
						data.append('act', buttons['ok'].action);
					
						$.each(fields, function(key, object){
							if(object.type == 'multidata'){
								$.each(object.data, function(k, v){
									$.each(v.actions, function(i, o){
										data.append('acl_' + o.aclID, $("#acl_" + o.aclID).val());
									});
								});
							}
							else if(object.type == 'file'){
								data.append(key, $("#" + key)[0].files[0]);
							}
							else{
								data.append(key, $("#" + key).val());
							}
							
							if(object.special == 'autocomplete'){
								data.append(key + "ID", $("#" + key + "ID").val());
							}
						});
						
						$.ajax({
							beforeSend: function(){
								$(".modal-loader")
									.find("img")
									.center();
								
								$(".modal-loader")
									.fadeTo(500, .7);
									
								
				
								$("#bOk, #bCancel")
									.attr("disabled", true);
							},
							url: "_ajax.php",
							type: "POST",
							processData: false,
							contentType: false,
							data: data,
							success: function(json){
								$("#bOk, #bCancel")
									.attr("disabled", false);

								$(".modal-loader")
									.fadeTo(500, 0, function(){
										$(this).hide();
									});

								var isError = false;
							
								try{
									var data = $.parseJSON(json);
								}
								catch(e){
									isError = true;
								}
								finally{
									if(!isError){
										if(data['status'] == 'success'){
											buttons['ok'].onClick(data); /* CallBack */
										}
										else{
									
											if(Object.keys(($.noty.store)).length >= 3){ /* Close $.noty while to much */
												$.noty.closeAll();
											}
								
											n(data['desc'], "top", "error");
										}
									}
									else{
										n(json.replace(/<[\/]*script(.*?)>/g, "").replace("<br />", ""), "top", "error");
									}
								}
							}
						});
					});
			},
			showLoader: function(){
				$(".modal-loader")
					.css("opacity", 0)
					.find("img")
					.center()
					.parent()
					.fadeTo(500, .5);
			},
			closeLoader: function(){
				$(".modal-loader")
					.fadeTo(500, 0, function(){
						$(this)
							.hide();
					});
			}
		};
	}

	$.modal = function(options){
		return init($(), options);
	};

	$.fn.modal = function(options) {
		return init(this, options);
	};

})(jQuery, window, document);