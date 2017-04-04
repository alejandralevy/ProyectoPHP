function Rutas(){}

Rutas.prototype = {
		rutaValidarOperario : "",
		rutaOperarios: "",
		fechaDeNacimientoEditar: "",
		id_usuario_editado : "",
		
		init: function(){
			
			var self = this;
			
			$('#fechaNacimiento').datepicker({
				 format: 'dd/mm/yyyy',
				  startDate: '-120y',
				    endDate: '-20y',
			});
			
			$("#cancelar").click(function(){
				window.location.href = self.rutaOperarios;
			});
			
			$("#submit").click(function(){
				if(self.validarCampos()){
					
					$.post(self.rutaValidarOperario,
							{
								"dni": $("#dni").val(),
								"apellido": $("#apellido").val(),
								"nombre": $("#nombre").val(),
								"password": $("#password").val(),
								"fecha": $("#fechaNacimiento").val(),
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
											 message: "El operario se ha creado correctamente",
										    backdrop: true
										});
										window.location.href = self.rutaOperarios;
									}
									if(data == 3){
										bootbox.alert({
											message: "El operario se ha actualizado correctamente",
										    backdrop: true
										});
										window.location.href = self.rutaOperarios;
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
			password = $("#password").val(); 
			fecha = $("#fechaNacimiento").val(); 
			
			if(nombre != "" & apellido!= "" & dni!="" & password != "" & fecha != "" ){
				  return true
			  }else{
				  return false
			  }
		}

}