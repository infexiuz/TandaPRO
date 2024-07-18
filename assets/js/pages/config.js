$(document).ready(function () {

   function get_services() {
		$.ajax({
			type: "post",
			url: base_url+"Config/ajax_get_services",
			dataType: "json",
			success: function (data) {
				var services_html = "";
            var data = data.data;
				for (let i = 0; i < data.length; i++) {
					const id = data[i]["id_service"];
					const name = data[i]["name"];
					const price = data[i]["price"];
					const commission = data[i]["commission"];

					services_html += `
               <tr>
                  <td>${name}</td>
                  <td>${price}</td>
                  <td>${commission}</td>
                  <td>
                     <button 
                        class="btn-ellipsis btn-edit-service" 
                        data-id="${id}" 
                        data-name="${name}" 
                        data-price="${price}" 
                        data-commission="${commission}">
                        <i class="fas fa-ellipsis-v"></i>
                     </button>
                  </td>
               </tr>
               `;
                 
				}
				$("#tbody_services").html(services_html);
			
			},
		});
	}get_services();

   $("body").on("click", ".btn-edit-service", function (e) {
		
		let id = $(this).attr("data-id");
		let name = $(this).attr("data-name");
		let price = $(this).attr("data-price");
		let commission = $(this).attr("data-commission");

      $("#modal-service-title").html(name);
		$("#id_service").val(id);
		$("#name_service").val(name);
		$("#price_service").val(price);
		$("#commission_service").val(commission);

		$("#modal-service").modal("show");
	});
   $("#formEditService").submit(function (e) {
		e.preventDefault();
		var formData = new FormData($(this)[0]);
		var submit = $("#btnEditService");
		
      $.ajax({
         type: "POST",
         url: base_url + "Config/ajax_edit_service",
         data: formData,
         cache: false,
         contentType: false,
         processData: false,
         beforeSend: function () {
            submit
               .html(
                  `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando..`
               )
               .attr("disabled", "disabled");
         },
         success: function (response) {
            if (response === "success") {

		         $("#modal-service").modal("hide");
               get_services();
               Toast.fire({
                  icon: "success",
                  title: "Se ha actualizado la información",
                  willClose: () => {
                     submit.html("Guardar").removeAttr("disabled", "disabled");
                     
                  },
               });
            } else {
               Toast.fire({
                  icon: "error",
                  title: "Hubo un error con el registro",
               });
            }
         },
      }).fail(function () {
         Toast.fire({
            icon: "error",
            title: "Error de servidor, intenta denuevo",
         });
      });
		
	});
});