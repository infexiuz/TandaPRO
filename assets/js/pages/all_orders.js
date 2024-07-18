$(document).ready(function () {
   $("#table_orders").DataTable({
      responsive: true,
      ajax: {
         url: base_url + "Orders/ajax_get_all_orders",
         type: "POST",
      },
      columns: [
         {
            data: "image",
            render: function (avatar) {
               return (
                  `<img width='30px' style='border-radius:50px' src='${base_url}${avatar}'>`

               );
            },
         },
         {
            data: "Client",
            render: function (name, t, row) {
               return name + " " + row.last_name;
            },
         },

         {
            data: "phone",
            render: function (phone) {

               return `${phone}`
            },
         },
         {
            data: "date_order",
            render: function (date_order) {
               return `${date_format(date_order)}`
               return `${date_order}`

            },
         },
         {
            data: "id_user",
            render: function (id, t, row) {
               let employee = id > 0 ? row.User : "Sin asignar";
               return employee;
            },
         },
         {
            data: "status",
            render: function (status) {
               let dot = status === "1" ? `dot-success` : "";
               let state = status === "1" ? `Pagado` : "No pagado";

               let paiout = `<span class="fst-italic fz-payment"><span class="dot ${dot}"></span>  <span>${state}
               </span></span>` ;

               return paiout;
            },
         },
         {
            data: "total",
            render: function (total) {
               return `<h5><span class="badge badge-total"><i class="fas fa-dollar-sign"></i> ${total}</span></h5>`
            },
         },
         {
            data: "id_order",
            render: function (id, t, row) {

               let buttonCobro = row.status === "1" ? `` : "btn-cobrar";
               return `
             
               <button class="btn-ellipsis btn-edit-order fs-3" type="button" data-bs-toggle="modal" data-bs-target="#modal-panel-order"
                  data-id_order="${id}" 
                  data-total="${row.total}" 
                  data-status="${row.status}" 
                  data-id_client="${row.id_client}"
                  data-id_employee="${row.id_user}"
                  data-comment="${row.comment}"
                  data-name="${row.Client} ${row.last_name}"
               >
               <i class="bi bi-three-dots-vertical"></i>
            </button>
         
               
               
               `;
               /*   <div class="dropdown">
                   <button class="btn btn-light dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-bars"></i>
                   </button>
                   <div class="dropdown-menu" style="min-width: 10rem;">
                      <a class="dropdown-item ${buttonCobro}" href="javascript:void(0)"
                         data-id="${id}" 
                         data-name="${row.name}" 
                         data-total="${row.total}" 
                         data-id_client="${row.id_client}">
                         <i class="fas fa-hand-holding-usd"></i> 
                         Cobrar
                      </a>
                      <a data-id="${id}" class="dropdown-item btn-resume" href="javascript:void(0)"><i class="fas fa-file-invoice"></i> Resumen</a>
                      <a data-id="${id}" class="dropdown-item btn-eliminar" href="javascript:void(0)"><i class="fas fa-trash"></i> Eliminar</a>
                   </div>
                </div>  */
            },
         },
      ],
      columnDefs: [
         { className: "text-center", "targets": [0, 7] },
         { orderable: false, "targets": [6, 7, 2, 0, 4] }
      ]
   });
   $("#btnSubmitCobro").click(function () {
      var submit = $(this);
      var id = $("#idOrder").val();

      $.ajax({
         type: "post",
         url: base_url + "Orders/ajax_submit_cobro",
         data: { id: id },

         beforeSend: function () {
            submit
               .html(
                  `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando..`
               )
               .attr("disabled", "disabled");
         },
         success: function (response) {
            if (response === "success") {
               success(submit);
               $("#table_orders").DataTable().ajax.reload(null, false);

            } else {
               error(submit);
            }
         },
      });
   });
   $("body").on("click", ".btn-eliminar", function (e) {
      var id_order = $(this).attr("data-id_order");
      var id_client = $(this).attr("data-id_client");

      const deleteOrder = async function (id_client, id_order) {
         const { value: password } = await Swal.fire({
            title: "¿Deseas eliminar?",
            input: "password",
            inputLabel: "Esta acción está protegida",
            inputPlaceholder: "Introduzca la contraseña",
            inputAttributes: {
               maxlength: 10,
               autocapitalize: "off",
               autocorrect: "off",
               showCancelButton: true,
            },
         });

         if (password) {
            $.ajax({
               type: "post",
               url: base_url + "Orders/ajax_delete_order",
               data: {
                  id_order: id_order,
                  id_client: id_client,
                  password: password,
               },
               success: function (response) {
                  if (response === "success") {
                     Swal.fire({
                        icon: "success",
                        title: "Pedido eliminado",
                        showConfirmButton: false,
                        timer: 1500,
                        willClose: () => {
                           $("#table_orders").DataTable().ajax.reload(null, false);
                           $("#modal-panel-order").modal('hide');
                        },
                     });
                  } else {
                     Swal.fire(`Contraseña incorrecta`);
                  }
               },
            });
         }
      };

      deleteOrder(id_client, id_order);
   });
   $("body").on("click", ".btn-edit-order", function (e) {

      var name = $(this).attr("data-name");
      var status = $(this).attr("data-status");
      var comment = $(this).attr("data-comment");
      var id_order = $(this).attr("data-id_order");
      var id_employee = $(this).attr("data-id_employee");
      var id_client = $(this).attr("data-id_client");

      if (status > 0) {
         $("#success-payment").prop('checked', true);
         $("#not-payment").prop('checked', false);

      } else {
         $("#not-payment").prop('checked', true);
         $("#success-payment").prop('checked', false);
      }

      $("#name-client").html(name);
      $("#comment_order").html(comment);
      $("#id_order_modal").val(id_order);
      $("#edit_employee_order").val(id_employee).change();
      $(".btn-eliminar").attr({"data-id_order": id_order, "data-id_client": id_client});

      $.ajax({
         type: "post",
         url: "Orders/ajax_get_order",
         data: { id_order: id_order },
         dataType: 'json',
         success: function (response) {

            var sub_orders = response.sub_orders;
            var sub_orders_html = "";
            for (let i = 0; i < sub_orders.length; i++) {
               const name = sub_orders[i]["name"];
               const cant = sub_orders[i]["quantity"];
               const price = sub_orders[i]["price"];
               sub_orders_html +=
                  "" +
                  "<span class='badge badge-sub bg-light-primary text-ellipsis wbadge-sub'>" +
                  name +
                  "</span> " +
                  "<span class='badge badge-sub bg-light-primary text-ellipsis wbadge-sub'>" +
                  cant +
                  "</span> " +
                  "<span class='badge badge-sub bg-light-primary text-ellipsis wbadge-sub'>" +
                  price +
                  "</span><br>";
            }
            $("#suborders").html(sub_orders_html)
         }
      });
   });
   $("body").on("click", ".btn-status-modal", function (e) {
      const id_order = $("#id_order_modal").val()
      const status = $(this).attr('data-status');

      $.post(base_url + "Orders/ajax_edit_cobro_order", { id_order: id_order, status:status })
         .done(function (data) {
            $("#table_orders").DataTable().ajax.reload(null, false);
         });
   })
   $("body").on("click", ".btn-leave-modal-order", function (e){
      $("#modal-panel-order").modal('hide')
    
   })
   $("#edit_employee_order").change(function () {

      const id_employee = $(this).val();
      const id_order = $("#id_order_modal").val()

      $.post(base_url + "Orders/ajax_assign_order", { id_order: id_order, id_user: id_employee })
         .done(function (data) {
            $("#table_orders").DataTable().ajax.reload(null, false);
         });

      if (id_employee > 0) {
         $("#edit_employee_order").removeClass('is-invalid').addClass('is-valid');
      } else {
         $("#edit_employee_order").removeClass('is-valid').addClass('is-invalid');
      }
   })

   function success(submit) {
      submit
         .html('<i class="fas fa-check"></i> Completado')
         .addClass("btn-success")
         .removeClass("btn-primary");

      setTimeout(() => {
         $("#modal-cobro").modal("hide");

         submit
            .html('<i class="far fa-paper-plane"></i> Guardar')
            .addClass("btn-primary")
            .removeClass("btn-success")
            .removeAttr("disabled");
      }, 2000);
   }
   function error(submit) {
      submit
         .html('<i class="fa fa-times"></i> Error')
         .addClass("btn-danger")
         .removeClass("btn-primary");

      setTimeout(() => {
         submit
            .html('<i class="far fa-paper-plane"></i> Guardar')
            .addClass("btn-primary")
            .removeClass("btn-danger")
            .removeAttr("disabled");
      }, 2000);
   }
});