function Rutas(){}
Rutas.prototype = {
		rutaSourcePropietarios : "",
		rutaEliminarPropietario : "",
		rutaAdministrarPropietario : "",
		rutaIndex : "",
		init: function(){
			var self = this;
			$('.dropdown-toggle').dropdown();
			
			var table = $('#table').DataTable({
				"pagingType": "numbers",
				"bProcessing": true,
		        "bServerSide": true,
		        "order": [[ 1, "asc" ]],
		        select: true,
		        "initComplete": function(settings, json) {
		        	$("#button_container").html("<button id='volver' class='btn btn-primary'>Volver</button> &nbsp; &nbsp;" +
		        			"<button id='nuevo' class='btn btn-primary'>Nuevo propietario</button>");
					 
					$("#nuevo").click(function(){
						window.location.href = self.rutaAdministrarPropietario;
																});
					
					$("#volver").click(function(){
						window.location.href = self.rutaIndex;
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
		            "url": self.rutaSourcePropietarios,
		            "data": function(d){
			            d.dni_buscado = $('#dni').val();
			            d.apellido_buscado = $('#apellido').val();
			            d.nombre_buscado = $('#nombre').val();
			            d.lote_buscado = $('#lote').val();
		            },
		            "dataSrc": '',
		            "type" : "post"
		        },
		        columns: [
		                  { data: 'id' },
		                  { data: 'apellido' },
		                  { data: 'nombre' },
		                  { data: 'dni' },
		                  { data: 'lote' },
		                  { data: 'telefono' },
		                  { data: 'mail' },
		                  { defaultContent: '<button class="btn btn-success btn-sm editar">Editar</button> <button class="btn btn-primary btn-sm eliminar">Eliminar</button>' }
		                 
		              ],
		              
		        "columnDefs": [
		                         {
		                            "targets": [ 0 ],
		                             "visible": false,
		                             "searchable": false
		                             },
		                             
		                             {
				                         	"targets" :[7],
				                         	"orderable": false
				                         }
		                         ]
		              

												});
			
			
			$('#submit').click(function(e) {
				e.preventDefault();
				table.ajax.reload();
											});
			
			
			$('#table tbody').on('click', '.editar', function () {
				var nRow = $(this).parents('tr')[0];
				var selected = $("#table").dataTable().fnGetData(nRow);
				var id = selected.id;
				$('#id_editar').val(id);
				$('#my_form').submit();
		        
			});
			
			$('#table tbody').on( 'click', '.eliminar', function () {
				
				var nRow = $(this).parents('tr')[0];
				var selected = $("#table").dataTable().fnGetData(nRow);
				var id_eliminar = selected.id;
				
				bootbox.confirm({
					title: "Eliminar propietario",
					message: "Desea eliminar este propietario?",
					buttons: {
				        cancel: {
				            label: '<i class="fa fa-times"></i> Cancelar'
				        },
				        confirm: {
				            label: '<i class="fa fa-check"></i> Eliminar'
				        }
				    },
				
					callback: function(result){
					if(result){
					self.eliminarPropietario(id_eliminar);
					}
				    	}
				});
			});
			
			
		},
		
		eliminarPropietario : function(id_eliminar){
			
			$.get(this.rutaEliminarPropietario,
					{id : id_eliminar},
					function(data){
						$('#submit').click();
						if(data == 1){
								bootbox.alert({
							    message: "Se ha eliminado el propietario correctamente",
							    backdrop: true
								});
						}
						
						if(data == 2){
							bootbox.alert({
							    message: "No se ha podido eliminar el propietario",
							    backdrop: true
							});
						}
					});
			
		}
	}









