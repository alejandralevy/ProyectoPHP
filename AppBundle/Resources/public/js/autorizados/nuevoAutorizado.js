function Rutas(){}

Rutas.prototype = {
		rutaValidarAutorizado : "",
		rol : "",
		lote: "",
		id_usuario_editado : "",
		rutaAutorizados: "",
		
		init: function(){
			var self = this;
			
			$("#cancelar").click(function(){
				window.location.href = self.rutaAutorizados;
			});
			
			$("#submit").click(function(){
				  if (self.rol == 1){
					  self.lote = $("#lote").val();
				  	}
				  
				  if(self.validarCampos()){
				$.post(self.rutaValidarAutorizado,
					{
						"dni": $("#dni").val(),
						"lote": self.lote,
						"apellido": $("#apellido").val(),
						"nombre": $("#nombre").val(),
						"patente": $("#patente").val(),
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
								    message: "El lote ingresado no existe",
								    backdrop: true
								});
							}
							if(data == 3){
								bootbox.alert({
								    message: "El autorizado se ha creado correctamente",
								    backdrop: true
								});
								window.location.href = self.rutaAutorizados;
							}
							if(data == 4){
								bootbox.alert({
								    message: "El autorizado se ha actualizado correctamente",
								    backdrop: true
								});
								window.location.href = self.rutaAutorizados;
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
		 validarCampos : function(){
			nombre = $("#nombre").val(); 
			apellido = $("#apellido").val(); 
			dni = $("#dni").val(); 
			  if (this.rol == 1){
				  lote = $("#lote").val(); 
				  if(nombre != "" & apellido!= "" & dni!="" & lote != "" ){
					  return true
				  }else{
					  return false
				  }
			  }else{
				  if(nombre != "" & apellido!= "" & dni!="" ){
					  return true
				  }else{
					  return false
				  }
			  }
			
		}

}