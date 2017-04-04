function Rutas(){}

Rutas.prototype = {
		rutaValidarPropietario : "",
		rutaPropietarios: "",
		id_usuario_editado : "",
		
		init: function(){
			
			var self = this;
			
			$('#fechaNacimiento').datepicker({
				 format: 'dd/mm/yyyy',
				  startDate: '-120y',
				    endDate: '-20y',
			});
			
			$("#cancelar").click(function(){
				window.location.href = self.rutaPropietarios;
			});
			
			$("#submit").click(function(){
				if(self.validarCampos()){
					
					$.post(self.rutaValidarPropietario,
							{
						
								"apellido": $("#apellido").val(),
								"nombre": $("#nombre").val(),
								"dni": $("#dni").val(),
								"lote": $("#lote").val(),
								"mail": $("#mail").val(),
								"interno": $("#interno").val(),
								"telefono1": $("#telefono1").val(),
								"telefono2": $("#telefono2").val(),
								"celular1": $("#celular1").val(),
								"celular2": $("#celular2").val(),
								"fecha": $("#fechaNacimiento").val(),
								"patente": $("#patente").val(),
								"password": $("#password").val(),
								"id_usuario_editado": self.id_usuario_editado,
								
								},
								function(data){
									if(data == 1){
										bootbox.alert({
										    message: "El DNI ya ha sido registrado",
										    backdrop: true
										});
									}
									if(data == 2){
										bootbox.alert({
											 message: "El propietario se ha creado correctamente",
										    backdrop: true
										});
										window.location.href = self.rutaPropietarios;
									}
									if(data == 3){
										bootbox.alert({
											message: "El operario se ha actualizado correctamente",
										    backdrop: true
										});
										window.location.href = self.rutaPropietarios;
									}
								});

					
				}else{
					bootbox.alert({
					    message: "Complete todos los campos obligatorios antes de continuar",
					    backdrop: true
				});
				
			}
				
			});
			
		},
		
		validarCampos: function(){
			nombre = $("#nombre").val(); 
			apellido = $("#apellido").val(); 
			dni = $("#dni").val(); 
			lote = $("#lote").val(); 
			mail = $("#mail").val(); 
			interno = $("#interno").val(); 
			telefono1 = $("#telefono1").val(); 
			fecha = $("#fechaNacimiento").val(); 
			password = $("#password").val(); 
			
			if(nombre != "" & apellido!= "" & dni!="" & password != "" & fecha != "" &
				lote != "" & mail != "" & interno != "" & telefono1 != ""){
				  return true
			  }else{
				  return false
			  }
		}

}