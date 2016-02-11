$(function() {
	$('.drag').attr('draggable', true);
	var dragElement = null;
	$(document).on('dragstart', '.drag', function(e){					
		dragElement = $(this).html();		
	    e.originalEvent.dataTransfer.effectAllowed = 'copy';
	   	e.originalEvent.dataTransfer.setData('text/html', this.innerHTML);
	});	
	$('#scrollbar1').tinyscrollbar();	
	
	$(document).on({
		'dragover': 
			function(e) 
			{
			    e.preventDefault();
			    return false;
			}, 
		'drop': 
			function(e) 
			{				
				var text = $(dragElement).text();
				var duplicate = true;
				$(this).children('.element').each(function(index, value){
					if(text == $(this).text()){
						duplicate = false;
						alert('De persoon is reeds op deze datum in deze kolom toegevoegd.');
					}
				});
				if(duplicate){
					var element = $(this).append('<div class="element drag">'+dragElement+'</div>');	
					var url = window.location.href;
					var url_arr = url.split('/lijsten/');
					var href = url_arr[0]+'/lijsten/'+$('#permanentie_tabel').data('url');
					
					var data = new Object;
					data.type = $('#permanentie_tabel').data('type');
					data.subtype = $('#permanentie_tabel').data('subtype');
					data.naam = $(this).children('.element:last').children('.naam').text();
					data.week = $(this).parents('tr').attr('id');
					if(data.subtype == "leidinggevenden" || data.subtype == "provinciaal"){					
						data.GSM = $(this).children('.element:last').children('.GSM').html();
					} else if(data.type == "calamiteiten" && data.subtype == "medewerkers"){
						data.GSM = $(this).children('.element:last').children('.GSM').html();
					} else if(data.type == "winterdienst" && data.subtype == "medewerkers"){	
						if ($(this).hasClass('wegentoezichters-vroeg')){
							data.personeelstype = 'wegentoezichters-vroeg';
						} else if ($(this).hasClass('wegentoezichters-laat')){
							data.personeelstype = 'wegentoezichters-laat';
						} else if ($(this).hasClass('arbeiders-vroeg')){
							data.personeelstype = 'arbeiders-vroeg';
						} else if ($(this).hasClass('arbeiders-laat')){
							data.personeelstype = 'arbeiders-laat';
						}
					}				
					$.post(href, data, function(json){
						if (json.login == false){
							alert('U bent niet meer ingelogd op de site. Gelieve u op opnieuw aan te melden. Anders zullen de wijzigingen niet meer worden opgeslagen.');
							window.location.href="login";
						}
					}, 'json');
					if($(this).children('.element:last').children('.GSM').html() != undefined){
						$(this).next('td').append('<div class="element GSM_element">'+$(this).children('.element:last').children('.GSM').html()+'</div>');
					}
					$('.drag').attr('draggable', true);		
					$('.element .icon-pencil').remove();	 
					$('.element .icon-info-sign').remove();	
					$('.element .icon-move').hide().remove();	
				}			
			}
		}, '.dropable'
	);
	$(document).on('mouseenter','.element, .drag', function(){
		$(this).children('.dicht').css('visibility', 'visible');
	});
	$(document).on('mouseleave', '.element, .drag', function(){
		$(this).children('.dicht').css('visibility', 'hidden');
	});	
	$(document).on('click', '.close_modal', function(e){
		$('.modal').modal('hide');		
	});	
	$(document).on('submit', '#wn_edit_form', function(e){
		e.preventDefault();
		editPersoneel();
	});	
	$('.save_modal').click(function(e){
		$(this).parents('.modal-footer').siblings('.modal-body').children('form').trigger('submit');
	});	
	$(document).on('click', 'li .icon-pencil', function(e){
		var naam = $(this).siblings('.naam').text();
		var gsm = $(this).siblings('.GSM').text();
		$('#personeel_bewerken').val(naam);
		$('#GSM_bewerken').val(gsm);
		$('#personeel_old').val(naam);
		$('#GSM_old').val(gsm);
		$('#myModal_personeel').modal('show');		
	});
	$(document).on('click', 'button.plus, li a.add_row', function(e){
		$("#tussendatum").datepicker("destroy");
		var id = $(this).parents('tr').attr('id');	
		var $row = $(this);	
		var begin_datum_min = $('#'+id+' td.van').text();
		var eind_datum_max = $('#'+id+' td.tot').text();
		var begin_datum_array = new Array();
		var eind_datum_array = new Array();
		begin_datum_array = begin_datum_min.split('/');
		eind_datum_array = eind_datum_max.split('/');
		$('#begin_datum_min').text(begin_datum_min);
		$('#eind_datum_max').text(eind_datum_max);
		$('#begin_datum').val(begin_datum_min);
		$('#eind_datum').val(eind_datum_max);
		$('#tussendatum').val(begin_datum_min);
		$('#week_add_form').children('#week').val(id);
		begin_datum_array[0] = parseInt(begin_datum_array[0])+1;
		eind_datum_array[0] = parseInt(eind_datum_array[0])-1;
		if (!Modernizr.inputtypes['date']) {
			$('#tussendatum').datepicker({ dateFormat: 'dd/mm/yy', firstDay: 1, constrainInput: true, minDate: (new Date(begin_datum_array[2]+','+begin_datum_array[1]+','+begin_datum_array[0])), maxDate: new Date(eind_datum_array[2]+','+eind_datum_array[1]+','+eind_datum_array[0]) });
		}
		$('#myModal_add_row').modal('show');
	});
	$(document).on('click', 'li .icon-remove-sign', function(e){
		var $clicked = e.target;
		var gsm = $(this).siblings('.GSM').text();
		var naam = $(this).siblings('.naam').text();
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var href = url_arr[0]+'/lijsten/'+$('#deleteurl').text();
		$.post(href, {'naam': naam, 'GSM': gsm}, function(json){
			$($clicked).parent().fadeOut('slow', function(){			
				$($clicked).remove();		
				$('#scrollbar1').tinyscrollbar_update('relative');	
			});	
		},'json');	
			
	});
	$(document).on('keydown', '#tussendatum', function(event){
		event.preventDefault();
	});	
	$(document).on('click', 'td div .icon-remove-sign', function(e){
		var gsm = $(this).siblings('.GSM').text();
		var row_id = $(this).parents('tr').attr('id');
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var href = url_arr[0]+'/lijsten/'+ $('#permanentie_tabel').data('deleteurl');
		var data = new Object;
		data.type = $('#permanentie_tabel').data('type');
		data.subtype = $('#permanentie_tabel').data('subtype');
		data.naam = $(this).siblings('.naam').text();
		data.week = $(this).parents('tr').attr('id');
		if(data.subtype == "leidinggevenden" || data.subtype == "provinciaal"){					
			data.GSM = $(this).siblings('.GSM').text();
		} else if(data.type == "calamiteiten" && data.subtype == "medewerkers"){
			if ($(this).parents('td').hasClass('medewerker')){
				data.personeelstype = 'medewerker';
			} else {
				data.personeelstype = 'arbeider';
			}
		} else if(data.type == "winterdienst" && data.subtype == "medewerkers"){	
			if ($(this).parents('td').hasClass('wegentoezichters-vroeg')){
				data.personeelstype = 'wegentoezichters-vroeg';
			} else if ($(this).parents('td').hasClass('wegentoezichters-laat')){
				data.personeelstype = 'wegentoezichters-laat';
			} else if ($(this).parents('td').hasClass('arbeiders-vroeg')){
				data.personeelstype = 'arbeiders-vroeg';
			} else if ($(this).parents('td').hasClass('arbeiders-laat')){
				data.personeelstype = 'arbeiders-laat';
			}
		}				
		$.post(href, data, function(json){
			
		}, 'json');
		$('#'+row_id+' td .GSM_element').each(function(){
			if ($(this).text()==gsm){
				$(this).fadeOut('slow', function(){
					$(this).remove();
				});
			}
		});
		$(this).parent().fadeOut('slow', function(){			
			$(this).remove();		
		});		
	});
	$('.icon-info-sign').tooltip();	
	$(document).on('submit', '#personeel_toevoegen, #wn_form', function(e){
		e.preventDefault();
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var href = url_arr[0]+'/lijsten/'+$(this).attr('action');
		var wn = $('#personeel').val();
		var pn = $('#GSM').val();
		if(pn==undefined)
		{
			pn='';
		}
		$.post(href, {'naam': wn, 'GSM': pn}, function(json){
			
		},'json');		
		if (wn!= ''){
			if(pn!=''){			
				var test = $('<li class="drag well" draggable="true"><span class="icon-move"></span>&nbsp;<span class="naam">'+wn+'</span><span class="icon-remove-sign"></span><span class="icon-pencil"></span><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'+pn+'"></a><div class="hidden GSM">'+pn+'</div></li>').appendTo('#werknemers').hide().fadeIn('slow');
			} else {
				var test = $('<li class="drag well" draggable="true"><span class="icon-move"></span>&nbsp;<span class="naam">'+wn+'</span><span class="icon-remove-sign"></span><span class="icon-pencil"></span></li>').appendTo('#werknemers').hide().fadeIn('slow');
			}
			$('.icon-info-sign').tooltip();
			$('#personeel').val('').focus();
			$('#GSM').val('');
			$('#scrollbar1').tinyscrollbar_update('bottom');
		}
	});
	$('li.open').click(function(event){
		var $open=$(event.target); // get the element clicked		
		if ($open.children('ul').css('visibility') == 'hidden'){
			$open.children('ul').css('visibility', 'visible');
		} else {
			$open.children('ul').css('visibility', 'hidden');
		}
		$(document).bind('click', function(e) {
			var $clicked=$(e.target); // get the element clicked
		    if($clicked.is('li.open')) {
		    	
		    } else {		    	
		    	$open.children('ul').css('visibility', 'hidden');		    	
		    }
		});
	});
	$(document).on('submit', '#week_add_form', function(event){
		event.preventDefault();
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var hrefl = url_arr[0]+'/lijsten/'+$(this).attr('action');
		var data = new Object;
		data.begindatum = $(this).children('#begin_datum').val();
		data.einddatum = $(this).children('#eind_datum').val();
		data.tussendatum = $(this).children('#tussendatum').val();
		data.week = $(this).children('#week').val();
		$.post(hrefl, data, function(json){
			$('#'+data.week).after(json.row);
			$('#'+data.week).children('.tot').text(json.datum);
			$('#myModal_add_row').modal('hide');
			window.setTimeout(removehighlite, 4000);
		}, 'json');
	});
	$(document).on('click', 'a.remove_row', function(event){
		event.preventDefault();
		var hrefl = $(this).attr('href');
		var row = $(this).parents('tr').attr('id');
		var data = new Object;
		data.week = row;
		$.post(hrefl, data, function(json){
			$('#'+row).remove();
			$('#'+json.row_einddatum).children('td.tot').html(json.einddatum);
		}, 'json');
	});
	$(document).on('ready', function(event){
		changeFilter();
	});
	
	$(document).on('change', 'select[name="type"], select[name="provincie"], select[name="District"]', function(event){
		changeFilter();
	});
	var d = new Date();
	var n = d.getFullYear();
	$('#Jaar option[value="2016"]').prop('selected', true);
	$(document).on('change', 'select[name="Jaar"]', function(event){
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var hrefl = url_arr[0]+'/lijsten/get_feestdagen/'+$('select[name="Jaar"] option:selected').val()+'/';
		$.getJSON(hrefl, function(json){
			$.each(json.feestdagen.data, function(name, date){
				$('input[name="'+name+'"]').val(date);
				$('#message').html('<span style="color:red; border: 1px solid red; padding: 2px 5px">'+json.message+'</span>');
			});
		});
	});
	function changeFilter(){
		var url = window.location.href;
		var url_arr = url.split('/lijsten');
		var hrefl = url_arr[0]+'/lijsten/';
		var jaar = $('select[name="type"] option:selected').val();
		var provincie = $('select[name="provincie"] option:selected').val();
		var district = $('select[name="District"] option:selected').val();
		$.getJSON(hrefl+'/index/'+jaar+'/'+provincie+'/'+district, function(json){
			$('#calamiteiten-lijst').html('');
			if (json.calamiteiten){
				$.each(json.calamiteiten, function(index){
					var startdatum = toDateYear(json.calamiteiten[index].Startdatum);
					var einddatum = toDateYear(json.calamiteiten[index].Einddatum);		
					if(json.calamiteiten[index].subtype == 'medewerkers') {
						var subtype = 'districtsmedewerkers';
					} else {
						var subtype = json.calamiteiten[index].subtype;
					}
					if( json.calamiteiten[index].districtscode == 'AD_ANT' || (json.calamiteiten[index].district.indexOf('Alle districten') !== -1)){	
						if (json.calamiteiten[index].subtype == 'leidinggevenden'){	
							var link = '<a href="'+hrefl+'view/calamiteiten/'+json.calamiteiten[index].subtype+'/'+json.calamiteiten[index].districtscode+'/'+startdatum+'">Beurtrol leidinggevenden calamiteiten wachtdienst ('+json.calamiteiten[index].provincie+') ('+startdatum+'-'+einddatum+')</a>';
						} else if (json.calamiteiten[index].subtype == 'EM'){
							var link = '<a href="'+hrefl+'view/calamiteiten/'+json.calamiteiten[index].subtype+'/'+json.calamiteiten[index].districtscode+'/'+startdatum+'">Beurtrol Permanentie Sectie EM ('+json.calamiteiten[index].provincie+') ('+startdatum+'-'+einddatum+')</a>';
						}
					} else {	
						var link = '<a href="'+hrefl+'view/calamiteiten/'+json.calamiteiten[index].subtype+'/'+json.calamiteiten[index].districtscode+'/'+startdatum+'">Beurtrol '+subtype+' '+json.calamiteiten[index].districtscode+' '+json.calamiteiten[index].district+' ('+startdatum+'-'+einddatum+')</a>';
					}
					$('#calamiteiten-lijst').append('<li>'+link+'</li>');
				});
			}
			$('#winterdienst-lijst').html('');
			if (json.winterdienst){
				$.each(json.winterdienst, function(index){
					var startdatum = toDateYear(json.winterdienst[index].Startdatum);
					var einddatum = toDateYear(json.winterdienst[index].Einddatum);
					if(json.winterdienst[index].subtype == 'medewerkers') {
						var subtype = 'districtsmedewerkers';
					} else {
						var subtype = json.winterdienst[index].subtype;
					}
					if( json.winterdienst[index].subtype == 'provinciaal' || json.winterdienst[index].district == 'Alle districten'){
						var link = '<a href="'+hrefl+'view/winterdienst/'+json.winterdienst[index].subtype+'/'+json.winterdienst[index].districtscode+'/'+startdatum+'">Beurtrol Provinciaal Coördinator ('+json.winterdienst[index].provincie+') ('+startdatum+'-'+einddatum+')</a>';	
					} else {
						var link = '<a href="'+hrefl+'view/winterdienst/'+json.winterdienst[index].subtype+'/'+json.winterdienst[index].districtscode+'/'+startdatum+'">Beurtrol '+subtype+' '+json.winterdienst[index].districtscode+' '+json.winterdienst[index].district+' ('+startdatum+'-'+einddatum+')</a>';
						
					}					
					$('#winterdienst-lijst').append('<li>'+link+'</li>');
				});
			}
		});
	}
	$('ul#werknemers li').hover(function(e){	
		var naam = $(this).children('span.naam').text();
		var gsmnummer = $(this).children('div.GSM').text();
		$('#permanentie_tabel tbody tr td .naam').each(function(index){
			if ($(this).text() == naam){
				$(this).text(naam);
				$(this).parent('div').addClass('highlight2');
			}
		});
		$('#werknemers li .naam').each(function(index){
			if ($(this).text() == naam){
				$(this).text(naam);
				$(this).parent('li').addClass('highlight2');
				$(this).siblings('.GSM').text(gsmnummer);
				$(this).siblings('.icon-info-sign').data('original-title', gsmnummer);
			}
		});
	}, function(event) {
		removehighlite();
	});
});

window.toDateYear = function(Time){
	var dDate = new Date(); 
	var mEpoch = parseInt(Time); 
	if(mEpoch<10000000000) 
		mEpoch *= 1000; // convert to milliseconds (Epoch is usually expressed in seconds, but Javascript uses Milliseconds) 
		
	dDate.setTime(mEpoch);
	
	return dDate.getFullYear(); 
};
function editPersoneel()
{
	var url = window.location.href;
	var url_arr = url.split('/lijsten/');
	var href = url_arr[0]+'/lijsten/'+$('#wn_edit_form').attr('action');
	
	var data = $("#wn_edit_form").serialize();
	var naam = $("#wn_edit_form input[name='naam']").val();
	var old_naam = $("#wn_edit_form input[name='old_naam']").val();
	var gsmnummer = $("#wn_edit_form input[name='gsmnummer']").val();
	var old_gsmnummer = $("#wn_edit_form input[name='old_gsmnummer']").val();
	var gsmnummer = $("#wn_edit_form input[name='gsmnummer']").val();
	$.post(href, data, function(json){
		
		$('#permanentie_tabel tbody tr td .naam').each(function(index){
			var td_naam = $(this).text();
			if (td_naam == old_naam){
				$(this).text(naam);
				$(this).addClass('highlight');
				
				if ($(this).parents('td').siblings('td.GSM').children('.GSM_element').text() == old_gsmnummer) {
					$(this).parents('td').siblings('td.GSM').children('.GSM_element').text(gsmnummer).addClass('highlight');
					
				};
			}
		});		
		$('#werknemers li .naam').each(function(index){
			if ($(this).text() == old_naam){
				$(this).text(naam);
				$(this).parents('li').addClass('highlight');
				$(this).siblings('.GSM').text(gsmnummer);
				$(this).siblings('.icon-info-sign').data('original-title', gsmnummer);
			}
		});			
	},"json");
	window.setTimeout(removehighlite, 4000);
	$('#myModal_personeel').modal('hide');
}

function removehighlite(){
	$('li, span, div, tr').removeClass('highlight').removeClass('highlight2');
}
