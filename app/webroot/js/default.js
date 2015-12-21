$(document).ready(function(){		
	$('#gebruiker_toevoegen').on('click', function(event){
		var url = $(this).parents('form').attr('action');
		var form_data = $(this).parents('form').serialize();
		var UsersUserOk = $('#UsersUserOk').val();
		var UsersPasswordOk = $('#UsersPasswordOk').val();
		if (UsersUserOk != 1 || UsersPasswordOk != 1){
			event.preventDefault();			
		}		
	});
	
	/*$('#werkaanvraag_toevoegen_form').on('submit', function(event){
		event.preventDefault();
		var hrefl = $(this).attr('action');
		var gegevens = $(this).serialize();
		$.post(hrefl, gegevens, function(json){
		}, 'json')
	});*/
	
	$('#UsersUsername').on('keyup', function(event){
		var username = $(this).val();
		var hrefl = $(this).parents('form').attr('action');
		var url = hrefl.replace('add', 'username_check/'+username);
		if(username != ''){
			$.get(url, function(json){
				if(json.user!=null){
					$('#UsersUsername').css('border-color', '#7F0D0D');
					$('#UsersUserOk').val('0');
					$('#UsernameOkText').show().text('Deze gebruikersnaam is reeds ingebruik.').css('color', '#7F0D0D');
				} else {
					$("#UsersUsername").css('border-color', '#00AA00');
					$('#UsersUserOk').val('1');
					$('UsernameOkText').hide();
				}
			}, 'json');
		}		
	});
	
	$('#UsersRepeatPassword').parent().css('display', 'inline');
	$('#UsersRepeatPassword').on('keyup', function(event){
		var password = $('#UsersPassword').val();
		var password_repeat = $(this).val();
		if (password == password_repeat){
			$('#UsersPasswordOk').val('1');
			$('#passwordOkText').hide().text('');
			$(this).css('border-color', '#00AA00');
		} else {
			$('#UsersPasswordOk').val('0');
			$(this).css('border-color', '#7F0D0D');
			$('#passwordOkText').show().text('De gegeven wachtwoorden komen niet overeen.').css('color', '#7F0D0D');
		}
	});
	$('.button').on('click', function(event){
		var hrefl = $(this).children('a').attr('href');
		window.location.replace(hrefl);
	});		
	$('.status').popover({'placement': 'bottom'});	
	$(window).bind("popstate", function(event) {
	    var data = event.originalEvent.state;
	    
	});
	var viewport_h = $('#scrollbar1 .viewport').css('height');
	$(window).scroll(function(){
		var top = $(document).scrollTop();
		var table_w = $('#permanentie_tabel').width();
		
		if(top >= 135){
			$('#scrollbar1').css('position', 'fixed').css('top', '0px').css('height', '96%');
			var scrlbr_h = $('#scrollbar1').height();
			$('#scrollbar1 .viewport').height(scrlbr_h);
			$('#scrollbar1').tinyscrollbar_update();
			
			if(!$('#permanentie_tabel2').hasClass('cloned')){
				$('#permanentie_tabel').clone().addClass('cloned').attr('id', 'permanentie_tabel2').prependTo('.span9').removeClass('table-striped').css('position', 'fixed').css('top', '0px').css('width', table_w);
				$('#permanentie_tabel2 tbody').hide();	
				$('#permanentie_tabel2 tbody tr').removeAttr('id');	
			}
		} else {
			$('#scrollbar1 .viewport').height(viewport_h);
			$('#scrollbar1').css('position', 'inherit').css('height', 'auto');
			$('#scrollbar1').tinyscrollbar_update();
			$('#permanentie_tabel2.cloned').remove();
		}
		
	});
});

