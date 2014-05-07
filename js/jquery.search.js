var lastVal;
var searchTerm;
var searchCurrPage = 1;

function advancedSearchWindow(){
	modal("Расширенный поиск", {
		'contractNum': {
			title: 'Номер договора',
			type:  'input'
		},
		'contractStart': {
			title: 'Дата договора [От]',
			type:  'input',
			special: 'dateSelector'
		},
		'contractEnd': {
			title: 'Дата договора [До]',
			type:  'input',
			special: 'dateSelector'
		},
		'customerNum': {
			title: 'Номер договора контрагента',
			type:  'input'
		},
		'officeStart': {
			title: 'Поступил в канцелярию [От]',
			type:  'input',
			special: 'dateSelector'
		},
		'officeEnd': {
			title: 'Поступил в канцелярию [До]',
			type:  'input',
			special: 'dateSelector'
		},
		'changeStart': {
			title: 'Изменение договора [От]',
			type:  'input',
			special: 'dateSelector'
		},
		'changeEnd': {
			title: 'Изменение договора [До]',
			type:  'input',
			special: 'dateSelector'
		},
		'fileStart': {
			title: 'Изменение файлов [От]',
			type:  'input',
			special: 'dateSelector'
		},
		'fileEnd': {
			title: 'Изменение файлов [До]',
			type:  'input',
			special: 'dateSelector'
		},
		'startTimeFrom': {
			title: 'Дата начала [От]',
			type:  'input',
			special: 'dateSelector'
		},
		'startTimeTo': {
			title: 'Дата начала [До]',
			type:  'input',
			special: 'dateSelector'
		},
		'withArchive': { /* Edit */
			title: 'С архивом',
			type:  'text'
		},
		'priceFrom': {
			title: 'Сумма договора [От]',
			type:  'input'
		},
		'priceTo': {
			title: 'Сумма договора [До]',
			type:  'input'
		},
		'contractObject': {
			title: 'Из предмета договора',
			type:  'input'
		},
		'customer': {
			title: 'Контрагент',
			type:  'input',
			special: 'autocomplete',
			specialData: 'index.get.customers'
		},
		'initiator': {
			title: 'Инициатор сделки',
			type:  'input',
			special: 'autocomplete',
			specialData: 'index.get.initiators'

		},
		'department': {
			title: 'Ответственный отдел',
			type:  'input',
			special: 'autocomplete',
			specialData: 'index.get.departments'
		}
	}, {
		'ok': {
			title:  'Найти',
			action: 'index.advanced.search',
			onClick: function(data){
				
			}
		},
		'cancel': {
			title: 'Не искать'
		}
	});
}

function searchPagination(action, currPage, setPage){
	if(action == 'index.search'){
		if(searchTerm.length > 0){
			$.ajax({
				url: '_ajax.php',
				type: 'POST',
				data: {
					act:     'index.search',
					term:    searchTerm,
					setPage: setPage
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
								$(".dataRow")
									.remove();
								
								var preData = $(".preData");
								if(!$.isEmptyObject(data['results'])){
									$.each(data['results'], function(key, html){
										$(preData)
											.after(html);
									});
								
									$(".onPage")
										.text(data['onPage']);
								
									$("#pagination")
										.html(data['pagination']);
								}
								else{
									$("#pagination")
										.html("");
									$(".onPage")
										.text(0);
									$(preData)
										.after("<tr class='dataRow'><td colspan='7' style='text-align: center;'>Найдено 0 результатов</td></tr>");
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
	}
}

function search(){
	var val = $("#fastSearch").val();
	
	if(val.length > 0){ /* Sad but true */
		$.ajax({
			url: '_ajax.php',
			type: 'POST',
			beforeSend: function(){
				searchTerm = val;
			},
			data: {
				act:     'index.search',
				term:    val
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
							$(".dataRow")
								.remove();
								
							var preData = $(".preData");
							if(!$.isEmptyObject(data['results'])){
								$.each(data['results'], function(key, html){
									$(preData)
										.after(html);
								});
								
								$(".onPage")
									.text(data['onPage']);
								
								$("#pagination")
									.html(data['pagination']);
							}
							else{
								$("#pagination")
									.html("");
								$(".onPage")
									.text(0);
								$(preData)
									.after("<tr class='dataRow'><td colspan='7' style='text-align: center;'>Найдено 0 результатов</td></tr>");
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
}

function resetSearch(){
	$("#fastSearch")
		.val("");
	$.ajax({
		url: '_ajax.php',
		type: 'POST',
		data: {
			act:  'index.reset.search'
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
						$(".dataRow")
							.remove();
								
						var preData = $(".preData");
						if(!$.isEmptyObject(data['data'])){
							$.each(data['data'], function(key, html){
								$(preData)
									.after(html);
							});
							
							$(".onPage")
								.text(data['onPage']);
							
							$("#pagination")
								.html(data['pagination']);
						}
						else{
							$(".onPage")
								.text(0);
							$("#pagination")
								.html("");
							$(preData)
								.after("<tr class='dataRow'><td colspan='7' style='text-align: center;'>Найдено 0 результатов</td></tr>");
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

function initFastSearch(){
	$("#fastSearch")
		.on("keyup", function(e){
			clearTimeout($.data(this, 'timer'));
			
			var val = ($(this).val()).replace(/^\s+/,'');
			
			$(this)
				.val(val);
				
			if(val.length > 0){
				$(this)
					.data('timer', setTimeout(search, 100));
			}
			else{
				$(this)
					.data('reset', setTimeout(resetSearch, 100));
			}
		});
}