function Rutas(){}
Rutas.prototype = {
		rutaIndex : "",
		rutaBuscarAutorizado : "",
		rutaRegistrarIngreso : "",
		id_ingreso : "",
		
		init: function(){
			var self = this;
			$('.dropdown-toggle').dropdown();
			
			$("#cancelar").click(function(){
				window.location.href = self.rutaIndex;
									});
			
			$("#eventual").click(function(){
				$("#limpiar").click();
				$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", false );
				self.id_ingreso = -1;
				$("#titulo").html("Registrar Ingreso Eventual");
								});
			
			$("#rechazo").click(function(){
				$("#limpiar").click();
				$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", false );
				self.id_ingreso = -2;
				$("#titulo").html("Registrar Rechazo");
														});
			$("#ingreso").click(function(){
				if(self.validarCamposIngreso()){
					$.post(self.rutaRegistrarIngreso,
							{"id" : self.id_ingreso,
							"dni": $("#dni").val(),
							"apellido": $("#apellido").val(),
							"nombre": $("#nombre").val(),
							"patente": $("#patente").val(),
							"lote": $("#lote").val(),
						},
							function(data){
								if(data == 1){
									bootbox.alert({
									    message: "Se ha registrado el ingreso correctamente",
									    backdrop: true
									});
									$("#limpiar").click();
									$("#titulo").html("Registrar Ingreso");
									
								}
								
								if(data == 2){
									bootbox.alert({
									    message: "El lote al que se intenta ingresar no existe",
									    backdrop: true
									});
									
								}
								
								
							});
				}else{
					bootbox.alert({
					    message: "Debe completar todos los campos obligatorios",
					    backdrop: true
					});
				}
														});
				$("#limpiar").click(function(){
					$("#dni, #nombre, #apellido, #patente, #lote").val('');
					$( "#dni, #nombre, #apellido, #patente" ).prop( "disabled", false );
					self.id_ingreso = '';
														});
				
			
				
			$(document).on('click', 'input[type=checkbox]', function(e){
				var s = this.checked;
				if(s){
				$("input[type=checkbox]").prop('checked', false);
				$(this).prop('checked', true);}
				})
			
			$("#submit").click(function(){
				$("#titulo").html("Registrar Ingreso");
				$( "#lote" ).prop( "disabled", true );
				if(self.validarCamposBusqueda()){
					$.post(self.rutaBuscarAutorizado,
							{
								"dni": $("#dni").val(),
								"apellido": $("#apellido").val(),
								"nombre": $("#nombre").val(),
								"patente": $("#patente").val(),
								},
								function(data){
									var size = data.length;
									if (size == 0){
										bootbox.alert({
										    message: "No se encontraron resultados",
										    backdrop: true
										});
										
									}else if(size == 1){
										$("#dni").val(data[0].dni);
										$("#nombre").val(data[0].nombre);
										$("#apellido").val(data[0].apellido);
										$("#patente").val(data[0].patente);
										$("#lote").val(data[0].lote);
										self.id_ingreso = data[0].id;
										$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", true );
										if(data[0].tipo == '4'){
											$("#titulo").html("Registrar Ingreso Eventual");
											bootbox.alert({
											    message: "La persona encontrada es un autorizado eventual, recuerde " +
											    		"consultar al propietario del lote si permite su ingreso",
											    backdrop: true
											});
										}
									}else{
										var jsonArray = [];
										for (i = 0; i < data.length; i++){
											if(data[i].patente == "")
												var patente = '-';
											else
												var patente = data[i].patente;
											
											jsonArray.push({
												text: '<strong> DNI: </strong> ' + data[i].dni + ', <strong> Nombre: </strong>' + data[i].nombre +
												 ', <strong> Apellido: </strong> ' + data[i].apellido + ',<strong> Lote: </strong>' + data[i].lote +
												 ', <strong> Patente: </strong> ' + patente,
												value: i + '',
											});
										}
										 bootbox.prompt({
										    title: "Seleccione el autorizado",
										    inputType: 'checkbox',
										    inputOptions: jsonArray,
										    callback: function (result) {
										    	if(result != null){
										    	var i = parseInt(result);
										    	$("#dni").val(data[i].dni);
												$("#nombre").val(data[i].nombre);
												$("#apellido").val(data[i].apellido);
												$("#patente").val(data[i].patente);
												$("#lote").val(data[i].lote);
												$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", true );
												self.id_ingreso = data[i].id;
												
												if(data[0].tipo == '4'){
													$("#titulo").html("Registrar Ingreso Eventual");
													bootbox.alert({
													    message: "La persona encontrada es un autorizado eventual, recuerde " +
													    		"consultar al propietario del lote si permite su ingreso",
													    backdrop: true
														});
													}
										    	}
										    }
										});
									}
							});
					
				}else{
					bootbox.alert({
					    message: "Complete alguno de los campos con la cantidad minima de caracteres correspondiente",
					    backdrop: true
					});
				}
														});
		},
		
		validarCamposBusqueda: function(){
			nombre_size = $("#nombre").val().length;
			apellido_size = $("#apellido").val().length;
			dni_size = $("#dni").val().length;
			patente_size = $("#patente").val().length;
			
			if(nombre_size >= 3 || apellido_size >= 3 || dni_size >= 6 || patente_size >= 6){
				  return true
			  }else{
				  return false
			  }
		},
		
		validarCamposIngreso: function(){
			nombre = $("#nombre").val(); 
			apellido = $("#apellido").val(); 
			dni = $("#dni").val(); 
			lote = $("#lote").val(); 
			
			if(nombre != "" & apellido!= "" & dni!="" & lote != "" ){
				  return true
			  }else{
				  return false
			  }
		},
		

}
		
