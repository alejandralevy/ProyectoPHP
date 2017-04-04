function Rutas(){}

Rutas.prototype = {
		rutaReportesSource : "",
		rutaIndex : "",
		rutaExportar: "",
		init: function(){
			$('.dropdown-toggle').dropdown();
			var self = this;
			
			$('#desde, #hasta').datepicker({
				 format: 'dd/mm/yyyy',
				  startDate: '-100y',
			});
			
			var table = $('#table').DataTable({
				"pagingType": "numbers",
				"bProcessing": true,
		        "bServerSide": true,
		        "order": [[ 1, "desc" ]],
		        select: true,
		        "initComplete": function(settings, json) {
		        	$("#button_container").html("<button id='volver' class='btn btn-primary'>Volver</button> &nbsp; &nbsp;" +
		        			"<button id='exportar' class='btn btn-primary'>Exportar</button>");
					
					$("#volver").click(function(){
						window.location.href = self.rutaIndex;
																});
					
					$('#exportar').click(function(){
						$.post(self.rutaExportar,
								function(data){
							console.log(data);
							
						});
						
					});
		        },
		        "pageLength": 10,
		        "language" : {
		        	"processing":     "Procesando..",
		            "zeroRecords":    "No se encontraron resultados",
		        	       },
		        "lengthChange": false,
		        "searching": false,
		        "info": false,
		        "ajax": {
		            "url": self.rutaReportesSource,
		            "data": function(d){
			            d.dni_buscado = $('#dni').val();
			            d.patente_buscado = $('#patente').val();
			            d.desde_buscado = $('#desde').val();
			            d.hasta_buscado = $('#hasta').val();
			            d.apellido_buscado = $('#apellido').val();
			            d.nombre_buscado = $('#nombre').val();
			            d.lote_buscado = $('#lote').val();
			            d.tipo_buscado = $("#tipo option:selected").attr("id")
		            },
		            "dataSrc": '',
		            "type" : "post"
		        },
		        columns: [
		                  { data: 'id' },
		                  { data: 'fecha' },
		                  { data: 'tipo' },
		                  { data: 'tipo_persona' },
		                  { data: 'apellido' },
		                  { data: 'nombre' },
		                  { data: 'dni' },
		                  { data: 'patente' },
		                  { data: 'lote' },
		                 
		              ],
		              
		        "columnDefs": [
		                         {
		                            "targets": [ 0 ],
		                             "visible": false,
		                             "searchable": false
		                             },
		                             
		                             {
				                            "targets": [ 2 ],
				                             "searchable": false,
				                             }
		                             
		                         ]
												});
			$('#submit').click(function(e) {
				
				var desde_date = Date.parse($('#desde').val());
				var hasta_date = Date.parse($('#hasta').val());
				
				if(desde_date > hasta_date){
					e.preventDefault();
					bootbox.alert({
				    message: "La fecha ingresada en el campos 'desde' no puede ser mayor a la fecha ingresada en el campos 'hasta'",
				    backdrop: true
				});
					
				}else{
					e.preventDefault();
					table.ajax.reload();
					
				}
					
			});
			
			
			
		},

}

