function Rutas(){}
Rutas.prototype = {
		rutaIndex : "",
		rutaBuscarIngreso : "",
		rutaRegistrarEgreso : "",
		id_persona : "",
		id_ingreso: "",
		
		init: function(){
			var self = this;
			$('.dropdown-toggle').dropdown();
			
			$("#cancelar").click(function(){
				window.location.href = self.rutaIndex;
									});
			
			$("#limpiar").click(function(){
				$("#dni, #nombre, #apellido, #patente, #lote, #fechaIngreso").val('');
				$( "#dni, #nombre, #apellido, #patente" ).prop( "disabled", false );
				self.id_ingreso = '';
				self.id_persona = '';
													});
			
			$("#eventual").click(function(){
				$("#limpiar").click();
				$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", false );
				self.id_persona = -1;
				self.id_ingreso = -1;
				var date = new Date();
				$("#titulo").html("Registrar Egreso Eventual");
								});
			
			$(document).on('click', 'input[type=checkbox]', function(e){
				var s = this.checked;
				if(s){
				$("input[type=checkbox]").prop('checked', false);
				$(this).prop('checked', true);}
				});
			
			$("#egreso").click(function(){
				if(self.validarCamposEgreso()){
					$.post(self.rutaRegistrarEgreso,
							{"id_persona" : self.id_persona,
							"dni": $("#dni").val(),
							"apellido": $("#apellido").val(),
							"nombre": $("#nombre").val(),
							"patente": $("#patente").val(),
							"lote": $("#lote").val(),
							"id_ingreso": self.id_ingreso,
						},
							function(data){
								if(data == 1){
									bootbox.alert({
									    message: "Se ha registrado el egreso correctamente",
									    backdrop: true
									});
									$("#limpiar").click();
									$("#titulo").html("Registrar Egreso");
									
								}
								
								if(data == 2){
									bootbox.alert({
									    message: "El lote que ha ingresado no existe",
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
			
			$("#submit").click(function(){
				$("#titulo").html("Registrar Egreso");
				$( "#lote" ).prop( "disabled", true );
				if(self.validarCamposBusqueda()){
					$.post(self.rutaBuscarIngreso,
							{
								"dni": $("#dni").val(),
								"apellido": $("#apellido").val(),
								"nombre": $("#nombre").val(),
								"patente": $("#patente").val(),
								},
								function(data){
									var size = data.length;
									if(data == 1){
										bootbox.alert({
										    message: "No se encontro la persona que se desea ingresar",
										    backdrop: true
									});}
										else if(size == 1){
											self.id_ingreso = parseInt(data[0].id_ingreso);
											$("#dni").val(data[0].dni);
											$("#nombre").val(data[0].nombre);
											$("#apellido").val(data[0].apellido);
											$("#patente").val(data[0].patente);
											$("#lote").val(data[0].lote);
											self.id_persona = data[0].id;
											$( "#dni, #nombre, #apellido, #patente, #lote" ).prop( "disabled", true );
			
											if(self.id_ingreso == -1){
											bootbox.alert({
												    message: "La persona buscada no tienen egreso para registrar",
												    backdrop: true
												});
										
											}else{
												$("#fechaIngreso").val(data[0].fecha);
											}
					
											
										}else if (size > 1){
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
											    title: "Seleccione la persona que desea ingresar",
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
													$("#submit").click();
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
	
	validarCamposEgreso: function(){
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