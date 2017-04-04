function Rutas(){}

Rutas.prototype = {
		rutaAutorizados : "",
		rutaOperarios : "",
		rutaPropietarios : "",
		rutaIngreso : "",
		rutaEgreso : "",
		rutaReportes : "",
		
		init: function(){
			
			var self = this;
			
			$("#btn_admin_autorizados").click(function(){
				window.location.href = self.rutaAutorizados;
				
														});
			
			$("#btn_admin_operarios").click(function(){
				window.location.href = self.rutaOperarios;
				
														});
			
			$("#btn_admin_propietarios").click(function(){
				window.location.href = self.rutaPropietarios;
				
														});
			
			$("#btn_reg_ingreso").click(function(){
				window.location.href = self.rutaIngreso;
				
														});
			
			$("#btn_reg_egreso").click(function(){
				window.location.href = self.rutaEgreso;
				
														});
			
			$("#btn_reporte").click(function(){
				window.location.href = self.rutaReportes;
				
														});
			
			
			
			
			
			
			
			
						}
			
		
				}













//function Login(){}
//
//Login.prototype = {
//		rutaValidar : "",
//		
//		init : function(){
//			
//			var self = this;
//			
//			$("#iniciar").click(function(){
//				self.validar();
//					});
//			
//							},
//
// 		validar : function(){
// 			console.log(this.rutaValidar);
// 			
// 			var username = $("#username").val();
// 			var password = $("#password").val();
// 			console.log(username);
// 			console.log(password);
// 			
// 			 $.post(this.rutaValidar,
// 				    {
// 				        username: username,
// 				        password: password,
// 				    },function(data){
// 				    	
// 				    	console.log(data);
// 				    	
// 				    }
// 				    
// 			 
// 			 );
// 							}
//		
//			}
