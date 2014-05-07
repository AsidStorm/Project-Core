/* modules */

function moduleDelete(id, title){
	modal("Вы действительно хотите удалить модуль [" + title + "]?", {
		'moduleID': {
			type:  'hidden',
			value: id
		},
		'info': {
			type:  'information',
			value: 'Убедитесь, что все вложенные действия удалены!'
		}
	}, {
		'ok': {
			title:  'Да',
			action: 'admin.module.delete',
			onClick: function(data){
				window.location.href = "./admin.php?act=modules";
			}
		},
		'cancel': {
			title: 'Нет'
		}
	});
}

function moduleAdd(){
	modal("Создание модуля", {
		'title': {
			title: 'Название модуля',
			type:  'input'
		},
		'display': {
			title:  'Отображение',
			type:   'select',
			values: {
				1: {
					t: 'Отображать'
				},
				0: {
					t: 'Не отображать'
				}
			}
		},
		'define': {
			title: 'Определение',
			type:  'input'
		}
	}, {
		'ok': {
			title:   'Добавить',
			action:  'admin.module.add',
			onClick: function(data){
				n("Модуль успешно добавлен", "top", "success");
				$("#M_ITEMS")
					.find("li")
					.last()
					.before("<li class='item'><a href='admin.php?act=modules&id=" + data['object']['id'] + "' class='item-a'>" + data['object']['title'] + "</a></li>");
				$.modal().close();
			}
		},
		'cancel': {
			title:   'Отмена'
		}
	});
}

function moduleEdit(id, title){
	modalAjaxLoader({
		act: 'admin.module.info',
		moduleID: id
	}, function(data){
		modal("Редактирование модуля [" + title + "]", {
			'title': {
				title: 'Название модуля',
				value: data['module']['title'],
				type:  'input'
			},
			'display': {
				title:  'Отображение',
				type:   'select',
				values: {
					1: {
						t: 'Отображать'
					},
					0: {
						t: 'Не отображать'
					}
				},
				selected: data['module']['display']
			},
			'define': {
				title: 'Определение',
				type:  'input',
				value: data['module']['define']
			},
			'moduleID': {
				title: 'ID модуля',
				type:  'hidden',
				value: id
			}
		}, {
			'ok': {
				title:   'Сохранить',
				action:  'admin.module.edit',
				onClick: function(data){
					n("Модуль успешно отредактирован", "top", "success");
					
					$("#module_" + data['id'])
						.text(data['title']);
						
					$(".module-define")
						.text(data['module']);
					
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

/* actions */

function actionAdd(id, title){
	modal("Добавление действия в модуль [" + title + "]", {
		'title': {
			title: 'Название действия',
			type:  'input'
		},
		'display': {
			title: 'Отображение',
			type:  'select',
			values: {
				1: {
					t: 'Отображать'
				},
				0: {
					t: 'Не отображать'
				}
			}
		},
		'define': {
			title: 'Определение',
			type:  'input'
		},
		'desc': {
			title: 'Краткое описание',
			type:  'input'
		},
		'moduleID': {
			type:  'hidden',
			value: id
		},
		'pos': {
			title: 'Позиция',
			type:  'input',
			value: 0
		}
	}, {
		'ok': {
			title:   'Добавить',
			action:  'admin.action.add',
			onClick: function(data){
				n("Действие успешно добавлено", "top", "success");
			
				$(".afterData")
					.before(data['row']);
				
				$("#actions-count")
					.text( parseInt($("#actions-count").text()) + 1 );
					
				$.modal().close();
			}
		},
		'cancel': {
			title: 'Отмена'
		}
	});
}

function actionDelete(actionID, moduleID){
	modalAjaxLoader({
		act:      'admin.action.info',
		actionID: actionID,
		moduleID: moduleID
	}, function(data){
		modal("Вы действительно хотите удалить действие [" + data['title'] + "]?", {
			'moduleID': {
				title: 'ID модуля',
				type:  'hidden',
				value: data['moduleID']
			},
			'actionID': {
				title: 'ID действия',
				type:  'hidden',
				value: data['actionID']
			},
			'aclID': {
				title: 'ID права доступа',
				type:  'hidden',
				value: data['aclID']
			},
			'info': {
				title: 'Информация',
				type: 'information',
				value: 'Это действие [' + data['action'] + '] из модуля [' + data['module'] + ']'
			}
		}, {
			'ok': {
				title:  'Да',
				action: 'admin.action.delete',
				onClick: function(data){
					n("Действие успешно удалено", "top", "success");
					
					$("#actions-count")
						.text( parseInt($("#actions-count").text()) - 1 );
						
					$("#" + data['id'])
						.animate({
							backgroundColor: "#B01564",
							opacity: .1
						}, 1000, function(){
							$(this)
								.remove();
						});
						
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Нет'
			}
		});
	});
}

function actionEdit(actionID, moduleID){
	modalAjaxLoader({
		act:      'admin.action.info',
		actionID: actionID,
		moduleID: moduleID
	}, function(data){
		modal('Редактирование действия [' + data['action'] + '] из модуля [' + data['module'] + ']', {
			'aclID': {
				type:  'hidden',
				value: data['aclID']
			},
			'title': {
				title: 'Название действия',
				type:  'input',
				value: data['title']
			},
			'display': {
				title: 'Отображение',
				type:  'select',
				values: {
					1: {
						t: 'Отображать'
					},
					0: {
						t: 'Не отображать'
					}
				},
				selected: data['display']
			},
			'define': {
				title: 'Определение',
				type:  'input',
				value: data['action']
			},
			'desc': {
				title: 'Краткое описание',
				type:  'input',
				value: data['desc']
			},
			'pos': {
				title: 'Позиция',
				type: 'input',
				value: data['pos']
			},
			'moduleID': {
				type:  'hidden',
				value: data['moduleID']
			},
			'actionID': {
				type:  'hidden',
				value: data['actionID']
			},
		}, {
			'ok': {
				title:   'Сохранить',
				action:  'admin.action.edit',
				onClick: function(data){
					n("Действие успешно изменено", "top", "success");
					
					$("#" + data['id'] + " .subtitle")
						.text(data['title']);
					
					$("#" + data['id'] + " .desc")
						.text(data['desc']);
					
					$("#" + data['id'] + " .action-define")
						.text(data['action']);
						
					$("#" + data['id'] + " .display")
						.html("<span class='" + (data['display'] == 1?"green":"red-text") + "'>" + (data['display'] == 1?"Отображается":"Не отображается") + "</span>");
						
					if(data['display'] == 1 && data['TPL_EDIT'] == 1){
						$("#" + data['id'] + " .display")
							.append(" <a href='javascript:actionTplEdit(" + data['id'] + ", " + data['moduleID'] + ");' class='sky'>[ Шаблон ]</a>");
					}
						
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

/* users */

function userAdd(){
	modal("Создание пользователя", {
		'soName': {
			title: 'Фамилия',
			type:  'input'
		},
		'name': {
			title: 'Имя',
			type:  'input'
		},
		'login': {
			title: 'Логин',
			type:  'input'
		},
		'password': {
			title: 'Пароль',
			type:  'password'
		},
		'email': {
			title: 'Email',
			type:  'input'
		},
		'phone': {
			title: 'Телефон',
			type:  'input'
		},
		'group': {
			title: 'Группа',
			type:  'select',
			data: {
				act: 'admin.get.group.list'
			}
		}
	}, {
		'ok': {
			title:  'Добавить',
			action: 'admin.user.add',
			onClick: function(data){
				n("Пользователь успешно создан", "top", "success");
				
				$(".dataRow:last")
					.after(data['row']);
					
				$("#users-count")
					.text( parseInt($("#users-count").text()) + 1 );
			
				$.modal().close();
			}
		},
		'cancel': {
			title: 'Отмена'
		}
	});
}

function userEdit(id){
	modalAjaxLoader({
		act: 'admin.user.info',
		id:  id
	}, function(data){
		modal("Редактирование пользователя [" + data['soName'] + " " + data['name'] + "]", {
			'id': {
				title: 'ID пользователя',
				type: 'hidden',
				value: data['id']
			},
			'soName': {
				title: 'Фамилия',
				type:  'input',
				value: data['soName']
			},
			'name': {
				title: 'Имя',
				type:  'input',
				value: data['name']
			},
			'login': {
				title: 'Логин',
				type:  'hidden',
				value: data['login']
			},
			'email': {
				title: 'Email',
				type:  'input',
				value: data['email']
			},
			'phone': {
				title: 'Телефон',
				type:  'input',
				value: data['phone']
			},
			'group': {
				title: 'Группа',
				type:  'select',
				data: {
					act: 'admin.get.group.list'
				},
				selected: data['group']
			},
			'password': {
				title: 'Новый пароль',
				type:  'password'
			},
			'repeatPassword': {
				title: 'Подтвердите новый пароль',
				type:  'password'
			}
		}, {
			'ok': {
				title:   'Сохранить',
				action:  'admin.user.edit',
				onClick: function(data){
					window.location.href = './admin.php?act=users';
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

function userDelete(id){
	modalAjaxLoader({
		act: 'admin.user.info',
		id:  id
	}, function(data){
		modal("Удаление пользователя [" + data['soName'] + " " + data['name'] + "]", {
			'id': {
				title: 'ID пользователя',
				type:  'hidden',
				value: data['id']
			},
			'info': {
				title: 'Информация',
				type:  'information',
				value: 'Вы действительно хотите удалить этого пользователя?'
			}
		}, {
			'ok': {
				title:   'Да',
				action:  'admin.user.delete',
				onClick: function(data){
					$("#user-" + id + "-action")
						.html('<a href="javascript:userRestore(' + id + ');" class="sky">Восстановить</a>');
						
					$("#user-" + id + "-delete")
						.html(" [ Удалён ]");
					
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

function userRestore(id){
	modalAjaxLoader({
		act: 'admin.user.info',
		id:  id
	}, function(data){
		modal("Восстановление пользователя [" + data['soName'] + " " + data['name'] + "]", {
			'id': {
				title: 'ID пользователя',
				type:  'hidden',
				value: data['id']
			},
			'info': {
				title: 'Информация',
				type:  'information',
				value: 'Вы действительно хотите восстановить этого пользователя?'
			}
		}, {
			'ok': {
				title:   'Да',
				action:  'admin.user.restore',
				onClick: function(data){
					$("#user-" + id + "-action")
						.html('<a href="javascript:userDelete(' + id + ');" class="red">Удалить</a>');
						
					$("#user-" + id + "-delete")
						.html("");
					
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

/* groups */

function groupDelete(id){
	modalAjaxLoader({
		act: "admin.group.info",
		id:  id
	}, function(data){
		modal("Удаление группы [" + data['gName'] + "]", {
			'id': {
				type: 'hidden',
				value: id
			},
			'info': {
				type: 'information',
				value: 'Вы действительно хотите удалить эту группу?'
			}
		}, {
			'ok': {
				title: 'Да, удалить',
				action: 'admin.group.delete',
				onClick: function(data){
					window.location.href = './admin.php?act=groups';
				}
			},
			'cancel': {
				title: 'Нет, не удалять'
			}
		});
	});
}

function groupAdd(){
	modal("Создание новой группы", {
		'title': {
			title: 'Название группы',
			type:  'input'
		},
		'desc': {
			title: 'Описание группы',
			type:  'input'
		}
	}, {
		'ok': {
			title:   'Создать',
			action:  'admin.group.add',
			onClick: function(data){
				n("Группа успешно добавлена", "top", "success");
				
				$(".dataRow:last")
					.after(data['row']);
				
				$("#groups-count")
					.text( parseInt($("#groups-count").text()) + 1 );
				
				$.modal().close();
			}
		},
		'cancel': {
			title: 'Отмена'
		}
	});
}

function groupSettings(groupID){
	modalAjaxLoader({
		act: "admin.group.settings",
		id:  groupID
	}, function(data){
		modal("Изменение прав группы", {
			'data': {
				data: data['modules'],
				type: 'multidata'
			},
			'groupID': {
				value: groupID,
				type:  'hidden'
			}
		}, {
			'ok': {
				title:   'Сохранить',
				action:  'admin.group.settings.apply',
				onClick: function(data){
					n("Настройки успешно сохранены", "top", "success");
					
					$.modal().close();
				}
			},
			'cancel': {
				title: 'Отмена'
			}
		});
	});
}

/* logs */

function showLog(id){
	modalAjaxLoader({
		act: 'admin.view.log',
		id:  id
	}, function(data){
		modal("Просмотр лога", {
			'id': {
				title:    'ID',
				value:    data['data']['id'],
				type:     'input',
				readonly: true
			},
			'desc': {
				title:    'Описание',
				value:    data['data']['desc'],
				type:     'input',
				readonly: true
			},
			'module': {
				title:    'Модуль',
				value:    data['data']['module'],
				type:     'input',
				readonly: true
			},
			'action': {
				title:    'Действие',
				value:    data['data']['action'],
				type:     'input',
				readonly: true
			},
			'ip': {
				title:    'IP',
				value:    data['data']['ip'],
				type:     'input',
				readonly: true
			},
			'time': {
				title:    'Время',
				value:    data['data']['time'],
				type:     'input',
				readonly: true
			},
			'userID': {
				title:    'ID пользователя',
				value:    data['data']['userID'],
				type:     'input',
				readonly: true
			},
			'json': {
				title:    'JSON',
				value:    print_o($.parseJSON(data['data']['json'])),
				type:     'textarea',
				readonly: true
			}
		}, {
			'ok': {
				title: 'Закрыть',
				noAct: true
			}
		});
	});
}

/* useful staff */

function adminSwitchAccess(aclID){
	var val = $("#acl_" + aclID).val();
	
	if(val === "false"){
		$("#td_" + aclID)
			.html('<span class="sky">Разрешено</span>');
		
		$("#acl_" + aclID).val(true);
	}
	else{
		$("#td_" + aclID)
			.html('<span class="red">Запрещено</span>');
		
		$("#acl_" + aclID).val(false);
	}
}

function print_o(obj){
	var output = "";

	if(obj instanceof Object) {
		for(var key in obj) {
			output += "[" + key + "] => " + obj[key] + "\n";
		}
	}
	else{
		output = obj.toString();
	}
	
	return output;
}