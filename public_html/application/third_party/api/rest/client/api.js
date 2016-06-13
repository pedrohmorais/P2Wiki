/**
 * AJAX long-polling
 *
 * 1. sends a request to the server (without a timestamp parameter)
 * 2. waits for an answer from server.php (which can take forever)
 * 3. if server.php responds (whenever), put data_from_file into #response
 * 4. and call the function again
 *
 * @param timestamp
 */
function getContent(timestamp)
{
	var boxexists = $('.sidebar-right-notify:visible').size();
	var queryString = {'timestamp' : timestamp};
	
	$.ajax(
        {
            type: 'GET',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/list-notifications',
            data: queryString,
			error : function(xhr, timeout){
				//alert(xhr);
				//alert(timeout);
			},
			success: function(data, status){
				
				if(data)
				{
					// put result data into "obj"
					var objectF = jQuery.parseJSON(data);
					var objectS = jQuery.parseJSON(objectF.data_from_file);
					
					var dados = '';
					var count = objectS.length;
					$.each(objectS, function(a) {
						
						//arruma data e hora
						var data = objectS[a].data_entrada;
						data = data.split(' ');
						data_d = data[0].split('-');
						data_h = data[1].split(':');
						data_d_full = data_d[2]+'/'+data_d[1]+'/'+data_d[0];
						data_h_full = data_h[0]+':'+data_h[1];
						
						dados += '<li class="trigger-'+a+'"><label id="'+objectS[a].id+'" title="Marcar como lido" class="view hide"></label>';
						dados += '<div><label class="image" style="margin-top:4px;">'+objectS[a].image+'</label>';
						dados += '<div style="display:table;"><span style="color:#036DAF;font-weight:bold;">';
						dados += objectS[a].nome_solicitante+'</span> ';
						dados += objectS[a].acao+' <br/>Em '+data_d_full+' às '+data_h_full+'Hs</div></li>';
					
					});
					
					$('#response').html(dados);
					
					for(var x=0;x<=count;x++)
					{
						$('.trigger-'+x).mouseover(function(){$('label.view', this).removeClass('hide');});
						$('.trigger-'+x).mouseout(function(){$('label.view', this).addClass('hide');});
						
						$('.trigger-'+x).on('click', function(event){
							event.preventDefault();
							alert($('label.view', this).attr('id'));
						});
					}
					
					//changeTitle();
					
					if(boxexists>0)
					{
						//call the function again, this time with the timestamp we just got from server.php
						getContent(objectF.timestamp);
					}
					
				}else{
					if(boxexists>0)
					{
						//call the function again, this time with the timestamp we just got from server.php
						getContent(timestamp);
					}
				}
			}
        }
    );
}

function getCountContent(timestamp)
{
	var queryString = {'timestamp' : timestamp};
	
	$.ajax(
        {
            type: 'GET',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/count-notifications',
            data: queryString,
			error : function(xhr, timeout){
				//alert(xhr);
				//alert(timeout);
			},
			success: function(data, status){
				
				if(data)
				{
					// put result data into "obj"
					var objectF = jQuery.parseJSON(data);
					
					//get access level
					var level_access = document.getElementsByName('C_DATA_REQUEST')[0].value;
					var logged_user_id = document.getElementsByName('U_DATA_REQUEST')[0].value;
					
					if(objectF.data_from_file!='')
					{
						var objectS = jQuery.parseJSON(objectF.data_from_file);
						
						var count_usuario = 0;
						var count_notificacao = 0;
						var count_comentario_logo_client = 0;
						var count_comentario_logo_admin = 0;
						
						for(var a=0;a<objectS.length;a++)
						{
							switch(objectS[a].acao)
							{
								case 'usuario':
									if(level_access == objectS[a].nivel_acesso)
									{
										count_usuario++;
									}
								break;
								case 'notificacao':
									if(level_access == objectS[a].nivel_acesso)
									{
										count_notificacao++;
									}
								break;
								case 'comentario_logo':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'comentario_site':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'start_site':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'envio_site':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'envio_logo':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'envio_manutencao':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'inicio_manutencao':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'finalizacao_manutencao':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'aprovacao_logo':
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && objectS[a].id_usuario == logged_user_id){
										
										count_comentario_logo_client++;
									}
								break;
								case 'envio_alteracao':
									var id_usuario_destino = objectS[a].id_usuario_destino;
									id_usuario_destino.indexOf(",");
									if(id_usuario_destino>0)
									{
										id_usuario_destino = id_usuario_destino.split(',');
										id_usuario_destino1 = (id_usuario_destino[0]) ? id_usuario_destino[0] : objectS[a].id_usuario;
										id_usuario_destino2 = (id_usuario_destino[1]) ? id_usuario_destino[1] : objectS[a].id_usuario;
									}else{
										id_usuario_destino1 = objectS[a].id_usuario;
										id_usuario_destino2 = objectS[a].id_usuario;
									}
									
									if(objectS[a].nivel_acesso != 1 && level_access >= objectS[a].nivel_acesso)
									{
										count_comentario_logo_admin++;
									}else if(level_access == objectS[a].nivel_acesso && (id_usuario_destino1 == logged_user_id || id_usuario_destino2 == logged_user_id)){
										
										count_comentario_logo_client++;
									}
								break;
							}
							
						}
						
						if(count_usuario>0)
						{
							$('.sidebar-count-people').replaceWith('<span class="label label-default sidebar-count-people">'+count_usuario+'</span>');
						}else{
							$('.sidebar-count-people').replaceWith('<span class="sidebar-count-people"></span>');
						}
						if(count_notificacao>0)
						{
							$('.sidebar-count-notification').replaceWith('<span class="label label-default sidebar-count-notification">'+count_notificacao+'</span>');
						}else{
							$('.sidebar-count-notification').replaceWith('<span class="sidebar-count-notification"></span>');
						}
						if(count_comentario_logo_client>0)
						{
							$('.sidebar-count-messages').replaceWith('<span class="label label-default sidebar-count-messages">'+count_comentario_logo_client+'</span>');
						}else if(count_comentario_logo_admin>0)
						{
							$('.sidebar-count-messages').replaceWith('<span class="label label-default sidebar-count-messages">'+count_comentario_logo_admin+'</span>');
						}else{
							$('.sidebar-count-messages').replaceWith('<span class="sidebar-count-messages"></span>');
						}
						
					}
					
					//call the function again, this time with the timestamp we just got from server.php
					getCountContent(objectF.timestamp);
				}else{
					getCountContent(timestamp);
				}
			}
        }
    );
}

function getCountPeople()
{
	$.ajax(
        {
            type: 'GET',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/count-people-notifications',
            error : function(xhr, timeout){
				//alert(xhr);
				//alert(timeout);
			},
			success: function(data, status){
				
				if(data)
				{
					// put result data into "obj"
					var objectF = jQuery.parseJSON(data);
					
					//get access level
					var level_access = document.getElementsByName('C_DATA_REQUEST')[0].value;
					var textHTML = '';
					
					if(objectF.data_from_file!='')
					{
						var objectS = jQuery.parseJSON(objectF.data_from_file);
						
						if(objectS)
						{
							for(var a=0;a<objectS.length;a++)
							{
								textHTML += '<li class="triggerPeople-'+a+'"><label id="'+objectS[a].id+'" title="Marcar como lido" class="viewPeople hide"></label>';
								textHTML += '<i class="icon-user-plus3 text-success"></i>';
								textHTML += '<div>';
								textHTML += '<a href="http://www.centralliguesite.com.br/meuweb/user/edituser/'+objectS[a].id+'">'+objectS[a].nome+'</a> agora faz parte do Painel';
								textHTML += '<span>'+objectS[a].data_extenso+'</span>';
								textHTML += '<div>';
								textHTML += '</li>';
							}
							
							var element = document.getElementById('content-people-notification');
							element.innerHTML = textHTML;
							
							for(var a=0;a<objectS.length;a++)
							{
								$('.triggerPeople-'+a).mouseover(function(){$('label.viewPeople', this).removeClass('hide');});
								$('.triggerPeople-'+a).mouseout(function(){$('label.viewPeople', this).addClass('hide');});
								
								$('.triggerPeople-'+a+' label.viewPeople').on('click', function(event){
									event.preventDefault();
									var id_update = $(this).attr('id');
									updateCountNotificationView(id_update, 'usuarios', this);
								});
							}
						}else{
							textHTML += '<li>';
							textHTML += '<i class="icon-user-plus3 text-info"></i>';
							textHTML += '<div>';
							textHTML += 'Nenhum usuário novo';
							textHTML += '<div>';
							textHTML += '</li>';
							
							var element = document.getElementById('content-people-notification');
							element.innerHTML = textHTML;
						}
					}
				}
			}
        }
    );
}

function getContentMessages()
{
	$.ajax(
        {
            type: 'GET',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/content-messages-notifications',
            error : function(xhr, timeout){
				//alert(xhr);
				//alert(timeout);
			},
			success: function(data, status){
				
				if(data)
				{
					// put result data into "obj"
					var objectF = jQuery.parseJSON(data);
					
					//get access level
					var level_access = document.getElementsByName('C_DATA_REQUEST')[0].value;
					var textHTML = '';
					
					if(objectF.data_from_file!='')
					{
						var objectS = jQuery.parseJSON(objectF.data_from_file);
						
						if(objectS)
						{
							var class_unread = '';
							var class_unread_icon = '';
							
							for(var a=0;a<objectS.length;a++)
							{
								if(objectS[a].visualizado>0)
								{
									class_unread = 'unread_exception';
									class_unread_icon = '';
								}else{
									class_unread = '';
									class_unread_icon = 'color:#E93C3E;';
								}
								textHTML += '<li class="unread '+class_unread+'">';
								textHTML += '<a class="triggerMessage-'+a+'" id="'+objectS[a].id+'" rel="'+objectS[a].link+'">';
								textHTML += '<div style="margin-left:0px;"><i class="icon-feed2 user-face" style="width:23px !important;margin-top:10px;'+class_unread_icon+'"></i></div>';
								textHTML += '<div style="margin-left:0px;display:table;">'+objectS[a].small_text;
								textHTML += '<br/><span>'+objectS[a].data_extenso+'</span></div>';
								textHTML += '</a>';
								textHTML += '</li>';
							}
							
							var element = document.getElementById('content-messages-notification');
							element.innerHTML = textHTML;
							
							for(var a=0;a<objectS.length;a++)
							{
								$('.triggerMessage-'+a).mouseover(function(){$('label.viewMessage', this).removeClass('hide');});
								$('.triggerMessage-'+a).mouseout(function(){$('label.viewMessage', this).addClass('hide');});
								
								$('.triggerMessage-'+a).on('click', function(event){
									event.preventDefault();
									
									//limpa contador
									var id_update = $(this).attr('id');
									updateMessageNotificationView(id_update, 'notificacoes_count', this);
									
									//resgata url
									$url = $(this).attr('rel');
									
									//redireciona
									setTimeout(function(){
										window.location.href = $url;
									},500);
									
								});
							}
						}else{
							textHTML += '<li class="unread triggerMessage-'+a+'">';
							textHTML += '<a href="#"><i class="icon-feed2 user-face" style="width:23px !important;margin-top:2px;height:24px;"></i>';
							textHTML += '<strong>Nenhuma mensagem nova</strong>';
							textHTML += '</a>';
							textHTML += '</li>';
							
							var element = document.getElementById('content-messages-notification');
							element.innerHTML = textHTML;
						}
					}
				}
			}
        }
    );
}

function updateCountNotificationView(id_update, table, action)
{
	$.ajax(
        {
            type: 'POST',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/update-count-notification-view',
			data: {
				 'id' : id_update
				,'table': table
			},
            success: function(data, status){
				$(action).parent().fadeOut('slow',function(){
					$(this).remove();
					/*if($(action).parent().parent().find('li').size() < 1)
					{
						textHTML = '<li>';
						textHTML += '<i class="icon-user-plus3 text-info"></i>';
						textHTML += '<div>';
						textHTML += 'Nenhum usuário novo';
						textHTML += '<div>';
						textHTML += '</li>';
						
						var element = document.getElementById('content-people-notification');
						element.innerHTML = textHTML;
					}*/
				});
				return true;
			}
        }
    );
}

function updateMessageNotificationView(id_update, table, action)
{
	$.ajax(
        {
            type: 'POST',
            url: 'http://www.centralliguesite.com.br/meuweb/rest/update-message-notification-view',
			data: {
				 'id' : id_update
				,'table': table
			},
            success: function(data, status){
				/*$(action).parent().fadeOut('slow',function(){
					if($('.content-messages-notification li').size()==1)
					{
						//$(this).remove();
						textHTML = '<li class="unread">';
						textHTML += '<a href="#"><i class="icon-feed2 user-face" style="width:23px !important;margin-top:2px;height:24px;"></i>';
						textHTML += '<strong>Nenhuma mensagem nova</strong>';
						textHTML += '</a>';
						textHTML += '</li>';
						
						var element = document.getElementById('content-messages-notification');
						element.innerHTML = textHTML;
					}else{
						$(this).remove();
					}
				});*/
				return true;
			}
        }
    );
}

// initialize jQuery
$(function() {
	getCountContent();
	//getContent();
});

var activePage = true;
function changeTitle() 
{
	$.titleAlert("New chat message!", {
		requireBlur:false,
		stopOnFocus:false,
		duration:0,
	});
	
	setTimeout("changeTitle();", 2000);
}

function getChangeTitle() 
{
	changeTitle();
};

function setCookie(cname,cvalue,exdays)
{	
	var d = new Date();
	d.setTime(d.getTime()+(exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	
	if(cvalue=='')
	{
		expires = "Thu, 01 Jan 1970 00:00:00 GMT"
	}
	
	document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname)
{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) 
	  {
	  var c = ca[i].trim();
	  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	  }
	return "";
}