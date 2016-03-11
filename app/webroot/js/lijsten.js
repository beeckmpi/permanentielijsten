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
					$('.element .glyphicon-pencil').remove();	 
					$('.element .glyphicon-info-sign').remove();	
					$('.element .glyphicon-move').hide().remove();	
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
	$(document).on('click', 'li .glyphicon-pencil', function(e){
		var naam = $(this).siblings('.naam').text();
		var gsm = $(this).siblings('.GSM').text();
		var vlimpersnummer = $(this).siblings('.vlimpersnummer').text();
		$('#vlimpersnummer_bewerken').val(vlimpersnummer);
		$('#vlimpersnummer_old').val(vlimpersnummer);
		$('#personeel_bewerken').val(naam);
		$('#GSM_bewerken').val(gsm);
		$('#personeel_old').val(naam);
		$('#GSM_old').val(gsm);
		$('#myModal_personeel').modal('show');		
	});
	$(document).on('click', '#addPersonen', function(event){
	  $('#myModal_add_personeel').modal('show');
	});
	$(document).on('click', '.btn-group-label', function(event){
    var active = $(this).hasClass('active');
    if (active) {
      $(this).removeClass('btn-info').addClass('btn-default');
      $(this).children('input[type="checkbox"]').val('off').removeAttr('checked');
    } else {
      $(this).removeClass('btn-default').addClass('btn-info');         
      $(this).children('input[type="checkbox"]').val('on').attr('checked','checked');
    }
  });
  $(document).on('click', '.select-all', function(event){    
    event.preventDefault();
    $(this).parent().next().children('.btn-group-label').children('input[type="checkbox"]').val('on').attr('checked','checked');
    $(this).parent().next().children('.btn-group-label').addClass('active').addClass('btn-info').removeClass('btn-default');
  });
  $(document).on('click', '.deselect-all', function(event){
    event.preventDefault();
    $(this).parent().next().children('.btn-group-label').children('input[type="checkbox"]').val('off').prop('checked', false).removeAttr('checked');
    $(this).parent().next().children('.btn-group-label').removeClass('active').removeClass('btn-info').addClass('btn-default');
  });
  $(document).on('click', '.save_personeel_modal', function(event){
    event.preventDefault();
    var personeel = $('#personeel_buttons').serialize(); 
    var url = window.location.href;
    var url_arr = url.split('/lijsten/');
    var id= $('#hidden_id').val();
    var href = url_arr[0]+'/lijsten/personeel/list/'+id;
    $.post(href, personeel, function(json){
       $.each(json['personeelsleden'], function(index, value){
         $('#werknemers').append(value);
       });
       $('#personeel_buttons_group label.active').remove();
       $('[data-toggle="tooltip"]').tooltip();  
    },'json');
    $('#myModal_add_personeel').modal('hide');
  });
	$(document).on('click', '.bewerk_personeel', function(event){
	  event.preventDefault();
	  var href = $(this).attr('href');
	  $.getJSON(href, function(json){
	    var personeelslid = json.personeelslid_arr;
	    $('#vlimpersnummer_bewerken, #vlimpersnummer_old').val(personeelslid.vlimpersnummer);
	    $('#personeel_bewerken, #personeel_old').val(personeelslid.naam);
	    $('#GSM_bewerken, #GSM_old').val(personeelslid.GSM);
	    $('#hidden_id').val(personeelslid._id);
	    $('#Districtscode option[value='+personeelslid.districtcode+']').attr('selected', 'selected');
	  });
	  $('#myModal_personeel_bewerken').modal('show');   
	});
	$(document).on('click', '.remove_personeel', function(event){
    event.preventDefault();
    var href = $(this).attr('href');
    if (confirm('Bent u zeker dat u dit personeelslid uit de de databank wilt verwijderen?')){
      $.getJSON(href, function(json){
        $('#'+json.id).fadeOut();
      });
    } 
  });
	$(document).on('click', '.save_modal_personeel', function(event){
	  var form = $('#wn_edit_form').serialize();
	  var href = $('#wn_edit_form').attr('action');
	  var id = $('#hidden_id').val();
	  $.ajax({
	    type: "POST",
	    url: href+id, 
	    data: form,
	    dataType: 'json'}).done(function(json){
	      var personeel = json.personeel_data;
	      if(json.personeel){
	         $('#myModal_personeel_bewerken').modal('hide');  
	         $('#'+json.id+'_vlimpers').html(personeel.vlimpersnummer);
	         $('#'+json.id+'_naam').html(personeel.naam);
	         $('#'+json.id+'_GSM').html(personeel.GSM);
	         $('#'+json.id+'_provincie').html(personeel.provincie);
	         $('#'+json.id+'_district').html(personeel.district);
	         $('#'+json.id).css('background-color', 'rgb(192, 239, 201)');
	         setTimeout(function(){ $('#'+json.id).css('background-color', '#fff');; }, 5000);
	         
	      }
	  });
	});
	$(document).on('keyup', '#naamFilter, #GSMFilter', function(e){
	  getPersoneel();
	});
	$(document).on('change', '#selectDistrict', function(e){
	  getPersoneel();
	});
	function getPersoneel(){
	  var href = window.location.href;
    var naam = $('#naamFilter').val();
    if (naam === ''){
      naam = 'empty';
    }
    var GSM = $('#GSMFilter').val();
    if (GSM === ''){
      GSM = 'empty';
    }
    var district = $('#selectDistrict option:selected').val();
    if (district === '0' || district === undefined){
      district = 'empty';
    }
    $.get(href+'/'+naam+'/'+GSM+'/'+district, function(html){
      $('#personeelsleden_tabel tbody').html(html);
    });
	}
	$(document).on('change', '#UPMMaand', function(e){
	  var lijstID = $('#lijstID').val();
	  var option = $('#UPMMaand option:selected').val();
	  var options = option.split('-');
	  var url = window.location.href;
	  var url_arr = url.split('/'+lijstID+'/');
	  var new_url = url_arr[0]+'/'+lijstID+'/'+options[0]+'/'+options[1];
	  window.location = new_url;
	});
	$(document).on('click', '#toggleHidden', function(e){
	  $('tr.hiddenPersoneel').toggle();
	});
	$(document).on('click', '.btn.plus, li a.add_row', function(e){
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
		$('#tussendatum').datepicker({ dateFormat: 'dd/mm/yy', firstDay: 1, constrainInput: true, minDate: (new Date(begin_datum_array[2]+','+begin_datum_array[1]+','+begin_datum_array[0])), maxDate: new Date(eind_datum_array[2]+','+eind_datum_array[1]+','+eind_datum_array[0]) });
		$('#myModal_add_row').modal('show');		
	});
	$(document).on('click', 'li .glyphicon-remove-sign', function(e){
		var $clicked = e.target;
		var gsm = $(this).siblings('.GSM').text();
		var naam = $(this).siblings('.naam').text();
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var deleteURL = $('#deleteurl').text();
		var href = url_arr[0]+'/lijsten/'+ deleteURL;
		var id = deleteURL.split('/');
		var naam = $(this).prev('.naam').text();
		$.post(href, {'naam': naam, 'GSM': gsm}, function(json){
			$($clicked).parent().fadeOut('slow', function(){			
				$($clicked).remove();		
				$('#scrollbar1').tinyscrollbar_update('relative');	
			});	
			$('#personeel_buttons_group').append('<label class="btn btn-default btn-xs btn-group-label" style="margin: 0 6px 12px 0px"><input type="checkbox" autocomplete="off" id="'+json.persID+'" value="off" name="personeel['+json.persID+']" checked>'+naam+'</label>');
		},'json');	
			
	});
	$(document).on('keydown', '#tussendatum', function(event){
		event.preventDefault();
	});	
	$(document).on('click', 'td div .glyphicon-remove-sign', function(e){
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
	$('[data-toggle="tooltip"]').tooltip();	
	$(document).on('submit', '#personeel_toevoegen, #wn_form', function(e){
		e.preventDefault();
		var url = window.location.href;
		var url_arr = url.split('/lijsten/');
		var href = url_arr[0]+'/lijsten/'+$(this).attr('action');
		var vn = $('#vlimpersnummer').val();
		var wn = $('#personeel').val();
		var pn = 'Vlimpersnummer: '+vn+' - GSM: '+ $('#GSM').val();
		if(pn==undefined)
		{
			pn='';
		}
		$.post(href, {'vlimpersnummer': vn, 'naam': wn, 'GSM': pn}, function(json){
			
		},'json');		
		if (wn!= ''){
			if(pn!=''){			
				var test = $('<li class="drag well" draggable="true"><span class="glyphicon glyphicon-move"></span>&nbsp;<span class="naam">'+wn+'</span><span class="glyphicon glyphicon-remove-sign"></span><span class="glyphicon glyphicon-pencil"></span><a class="glyphicon glyphicon-info-sign" style="color: #333" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'+pn+'"></a><div class="hidden GSM">'+pn+'</div></li>').appendTo('#werknemers').hide().fadeIn('slow');
			} else {
				var test = $('<li class="drag well" draggable="true"><span class="glyphicon glyphicon-move"></span>&nbsp;<span class="naam">'+wn+'</span><span class="glyphicon glyphicon-remove-sign"></span><span class="glyphicon glyphicon-pencil"></span></li>').appendTo('#werknemers').hide().fadeIn('slow');
			}
			$('.glyphicon-info-sign').tooltip();
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
				if(json.message !== ''){
				  
				  $('#message').html('<span class="alert alert-danger" style="margin-bottom: 25px">'+json.message+'</span>').css('display', 'inherit');
				} else {
				  $('#message').css('display', 'none').html('');
				}
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
			$('#calamiteiten-provinciaal-coordinator-lijst, #calamiteiten-districtmedewerkers-lijst').html('');
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
							$('#calamiteiten-provinciaal-coordinator-lijst').append('<li>'+link+'</li>');
						} else if (json.calamiteiten[index].subtype == 'EM'){
							var link = '<a href="'+hrefl+'view/calamiteiten/'+json.calamiteiten[index].subtype+'/'+json.calamiteiten[index].districtscode+'/'+startdatum+'">Beurtrol Permanentie Sectie EM ('+json.calamiteiten[index].provincie+') ('+startdatum+'-'+einddatum+')</a>';
							$('#calamiteiten-provinciaal-coordinator-lijst').append('<li>'+link+'</li>');
						}
					} else {	
						var link = '<a href="'+hrefl+'view/calamiteiten/'+json.calamiteiten[index].subtype+'/'+json.calamiteiten[index].districtscode+'/'+startdatum+'">Beurtrol '+subtype+' '+json.calamiteiten[index].districtscode+' '+json.calamiteiten[index].district+' ('+startdatum+'-'+einddatum+')</a>';
						$('#calamiteiten-districtmedewerkers-lijst').append('<li>'+link+'</li>');
					}
					
				});
			}
			$('#winterdienst-lijst').html('');
			if (json.winterdienst){
			  $('#winterdienst-provinciaal-coordinator-lijst, #winterdienst-leidinggevenden-lijst, #winterdienst-districtmedewerkers-lijst').html('');
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
						$('#winterdienst-provinciaal-coordinator-lijst').append('<li>'+link+'</li>');	
					} else {
					  if (json.winterdienst[index].subtype == 'leidinggevenden'){
						  var link = '<a href="'+hrefl+'view/winterdienst/'+json.winterdienst[index].subtype+'/'+json.winterdienst[index].districtscode+'/'+startdatum+'">Beurtrol '+subtype+' '+json.winterdienst[index].districtscode+' '+json.winterdienst[index].district+' ('+startdatum+'-'+einddatum+')</a>';
						  $('#winterdienst-leidinggevenden-lijst').append('<li>'+link+'</li>');
						} else if (json.winterdienst[index].subtype == 'medewerkers'){
              var link = '<a href="'+hrefl+'view/winterdienst/'+json.winterdienst[index].subtype+'/'+json.winterdienst[index].districtscode+'/'+startdatum+'">Beurtrol '+subtype+' '+json.winterdienst[index].districtscode+' '+json.winterdienst[index].district+' ('+startdatum+'-'+einddatum+')</a>';
              $('#winterdienst-districtmedewerkers-lijst').append('<li>'+link+'</li>');
            }
						
					}					
					
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
				$(this).siblings('.glyphicon-info-sign').data('original-title', gsmnummer);
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
	var vlimpersnummer = $("#wn_edit_form input[name='vlimpersnummer']").val();
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
				$(this).siblings('.glyphicon-info-sign').data('original-title', gsmnummer);
			}
		});			
	},"json");
	window.setTimeout(removehighlite, 4000);
	$('#myModal_personeel').modal('hide');
}

function removehighlite(){
	$('li, span, div, tr').removeClass('highlight').removeClass('highlight2');
}
