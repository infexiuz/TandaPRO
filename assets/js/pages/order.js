$(document).ready(function () {
   OrderList();

   //Order Form
   $("#service").select2({
      placeholder: "Seleccionar servicio",
      allowClear: true,
   });
   $("#client").select2({
      placeholder: "Seleccionar cliente",
      allowClear: true,
   });
   $("#user").select2({
      placeholder: "Seleccionar empleada",
      allowClear: true,
   });
   $("#service").on("change", function () {
      var service = $("#service option:selected").text();
      var price = $("#service option:selected").attr("data-custom-properties");

      if (service == "Personalizado") {
         $("#InputPrice").prop("readonly", false).val("");
      } else {
         $("#InputPrice").prop("readonly", true).val(price);
      }
   });

   $("body").on("click", ".btn-eliminar", function (e) {
      var id_order = $(this).attr("data-id");
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
               autocomplete: "off",
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
                           OrderList();
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
   $("body").on("click", ".btn-remove", function (e) {
      e.preventDefault();
      let total_services = parseFloat($("#total_services").html().substring(1));
      let price_service = $(this).attr("data-priceservice");
      let total = total_services - price_service;
      $("#total_services").html("$" + total);

      $(this).closest("div").parent().closest(".service").remove();
   });
   $("body").on("click", ".btn-asign", function (e) {
      e.preventDefault();
      var id_order = $(this).attr("data-id_order");
      var id_user = $(this).attr("data-id_user");

      $("#id_orderAssign").val(id_order);
      $("#select_userAssign").prop("selectedIndex", 0);

      if (id_user > 0) {
         $(".modal-header-assign")
            .removeClass("bg-dark")
            .addClass("bg-primary text-white");

         $("#title_assign").html("Pedido asignado").addClass('text-white');
         $("#iconTitle_assign").html('<i class="fas fa-check-circle"></i>');

         $("#select_userAssign").val(id_user).change();

      } else {
         $(".modal-header-assign")
            .removeClass("bg-primary")
            .addClass("bg-dark text-white");


         $("#select_userAssign").prop("selectedIndex", 0);
         $("#title_assign").html("Asigna una empleada").addClass('text-white');;

         $("#iconTitle_assign").html('<i class="fas fa-exclamation-circle"></i>');
      }

      $("#modal-assign").modal("show");
   });
   $('#select_userAssign').on('change', function () {
      var submit = $(this);
      var id_order = $("#id_orderAssign").val();
      var id_user = $("#select_userAssign").val();

      $.ajax({
         type: "post",
         url: base_url + "Orders/ajax_assign_order",
         data: { id_user: id_user, id_order: id_order },
         success: function (response) {
            if (response === "success") {
               OrderList();
               setTimeout(function () {$("#modal-assign").modal("hide") }, 200) ;

            } else {
               Toast.fire({
                  icon: "error",
                  title: "Pedido no fué asignado",
               });
            }
         },
      });
   });

   $(".add").click(function () {
      add($("#InputQuantity"));
   });
   $(".minus").click(function () {
      minus($("#InputQuantity"));
   });

   $("#BtnAddService").click(function () {
      var suma = 0;
      var id_client = $("#client").val();
      var id_service = $("#service").val();
      var total_services = parseFloat($("#total_services").html().substring(1));

      if ((id_client <= 0) | (id_service <= 0)) {
         Toast.fire({
            icon: "error",
            title: "Elige un cliente y/o servicio primero",
         });
         return;
      } else {
         var price = $("#InputPrice").val();
         var quantity = $("#InputQuantity").val();
         var service = $("#service option:selected").text();
         var total = quantity * price;

         var row = `
         <div class="row service"> 
            <div class="col-5 col-xl-4">
               <a href='' class='btn-remove remove-${id_service}' data-priceservice='${total}' data-idservice='${id_service}'>
                  <i class='fas fa-times'></i>
               </a>
               ${service}
               <input type='hidden' name='service[]' value='${id_service}' class='inputService-${id_service}'>
            </div> 
            <div class="col-2 col-xl-4 text-center">
               <input type='text' readonly name='cant[]' value='${quantity}' class='unset-input inputQty-${id_service} text-center'>
            </div> 
            <div class="col-5 col-xl-4 text-end">
               <input type='text' readonly name='total[]' value='${total}' class='unset-input inputTotal-${id_service} text-end'>
            </div> 
         </div>`;

         if ($(".service").length) {
            if ( $(`.inputService-${id_service}`).val()) {
               let sum_total = parseFloat($(`.inputTotal-${id_service}`).val()) + total;
               let sum_quantity = parseFloat($(`.inputQty-${id_service}`).val()) + parseFloat(quantity);

               $(`.inputQty-${id_service}`).val(sum_quantity);   
               $(`.inputTotal-${id_service}`).val(sum_total);   
               $(`.remove-${id_service}`).removeAttr("data-priceservice").attr("data-priceservice", sum_total);                
            } else {
               $(row).appendTo("#item");
            }
         } else {
            $(row).appendTo("#item");
         }

         suma = total_services + total;

         $("#service").val("").trigger("change");
         $("#InputQuantity").val("1");
         $("#InputPrice").val("0.00");
         $("#total_services").html("$" + suma + "");
      }
   });
   $("#btnSubmitCobro").click(function () {
      var submit = $(this);
      var id = $("#idOrder").val();

      $("#modal-cobro").modal("hide");

      $.ajax({
         type: "post",
         url: base_url + "Orders/ajax_submit_cobro",
         data: { id: id },
         success: function (response) {
            if (response === "success") {
               OrderList();
               Toast.fire({
                  icon: "success",
                  title: "Pago confirmado",
               });
            } else {
               Toast.fire({
                  icon: "error",
                  title: "Hubo un error con el pago",
               });
            }
         },
      });
   });
   $("#FormOrder").submit(function (e) {
      e.preventDefault();
      var formData = new FormData($(this)[0]);
      var submit = $("#btnSubmitOrder");

      if ($.trim($("#item").html()).length) {
         $.ajax({
            type: "POST",
            url: base_url + "Orders/ajax_new_order",
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
                  $("#btnSubmitOrder").html("Guardar");
                  Toast.fire({
                     icon: "success",
                     title: "Se ha creado una orden",
                     willClose: () => {
                        $("#item").html("");
                        $("#client").val("").trigger("change");
                        $("#service").val("").trigger("change");
                        $("#InputPrice").val("0.00");
                        $("#total_services").html("0.00");
                        $("#btnSubmitOrder").prop("disabled", false);
                        $("#status_check").prop("checked", false);
                        OrderList();
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
      } else {
         Toast.fire({
            icon: "error",
            title: "No hay servicios en la lista",
         });
      }
   });

   function OrderList(params) {
      $.ajax({
         type: "post",
         url: base_url + "Orders/ajax_get_orders",
         dataType: "json",
         success: function (data) {
            var orders_html = "";
            var orders = data.orders;
            var suma = 0;

            orders.forEach(function (row) {
               suma += parseFloat(row.total);
               const id = row.id_order;
               const name = row.Client + " " + row.last_name;
               const btnCobro = row.status != 1 ? "btn-cobrar" : "";
               const employee = row.id_user <= 0 ? "Sin asignar" : row.User;
               const suborders =row.sub_orders;
                  
               var sub_orders_html =`
                  <span class='badge text-dark badge-sub'>Servicio</span> 
                  <span class='badge text-dark badge-sub'>Cant</span> 
                  <span class='badge text-dark badge-sub'>Total</span><br>
               `;
               suborders.forEach(function (row){ 
                  sub_orders_html +=`
                     <span class="badge badge-sub bg-light-secondary text-ellipsis">${row.name}</span> 
                     <span class="badge badge-sub bg-light-secondary text-ellipsis">${row.quantity}</span> 
                     <span class="badge badge-sub bg-light-success text-ellipsis">${row.price}</span><br>
                  `;
               })
               orders_html += `
               <div href='#' class='list-group-item list-group-item-action card-orders'>
                  <div class='d-flex w-100 justify-content-between mb-1rem'>
                     <h4 class='mb-1 text-blue text-capitalize'>
                        <span class='absolute'><img class='img-order'src='${base_url}${row.image}'></span> 
                        <span class='ml-name'>${name}</span><br class="desktop-show">
                        <span class='fst-italic fz-payment'>
                           <span class='dot ${row.status != 1 ? "" : "dot-success"}'></span>
                           ${row.status != 1 ? "No pagado" : "Pagado"}
                        <span>
                     </h4>
                     <div class='btn-group d-block'>
                        <a href='javascript:void(0)' class='btn btn-sm btn-dark br-rounded ${btnCobro}'
                           data-id="${id}" 
                           data-name="${name}" 
                           data-total="${row.total}" 
                           data-id_client="${row.id_client}">
                           $${row.total}
                        </a>
                        <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='dropdownMenuLink'>
                           <li>
                              <a class='dropdown-item btn-asign' href='javascript:void(0)'
                              data-id_order="${id}"data-id_user="${row.id_user}"> 
                              ${employee}
                              </a>
                           </li>
                           <div class="dropdown-divider"></div>
                           <li>
                              <a class='dropdown-item ${btnCobro}' href='javascript:void(0)'
                                 data-id="${id}" 
                                 data-name="${name}" 
                                 data-total="${row.total}" 
                                 data-id_client="${row.id_client}">
                                 Cobrar
                              </a>
                           </li>
                           <li>
                              <a class='dropdown-item btn-eliminar' href='javascript:void(0)'
                                 data-id="${id}" data-id_client="${row.id_client}">Eliminar 
                              </a>
                           </li>
                           <li><a class='dropdown-item btn-historial' href='javascript:void(0)'>Historial</a></li>
                        </ul>
                     </div>
                  </div>

                  <p class='mb-1'>${sub_orders_html}</p>
                  
                  <div class='d-flex w-100 justify-content-between mt-3'>
                     <small class='fst-italic'>Comentario: ${row.comment}</small>
                     <span class="asign-icon point btn-asign"
                        data-tooltip="${employee}" 
                        data-tooltip-position="left"
                        data-id_order="${id}"
                        data-id_user="${row.id_user}"
                     >
                        ${row.id_user <= 0
                           ? "<i class='fas fa-user-alt-slash'></i>"
                           : "<i class='fas fa-user-alt'></i>"}
                     </span>
                  </div>
                  
               </div>`;
            });
            $("#list_order").html(orders_html);
            $("#total_orders").html("$" + suma + " MXN");
            $("#count_orders").html("( " + orders.length + " )");
         },
      });
   }
   function get_ord(id) {
      var sub_orders_html =
         `<span class='badge text-dark badge-sub'>Servicio</span> 
         <span class='badge text-dark badge-sub'>Cant</span> 
         <span class='badge text-dark badge-sub'>Total</span><br>`;

      $.ajax({
         type: "post",
         url: base_url + "Orders/ajax_get_order",
         data: { id_order: id },
         async: false,
         dataType: "json",
         success: function (response) {
            sub_orders = response.sub_orders;

            for (let i = 0; i < sub_orders.length; i++) {
               const name = sub_orders[i]["name"];
               const cant = sub_orders[i]["quantity"];
               const price = sub_orders[i]["price"];
               sub_orders_html +=
                  "" +
                  "<span class='badge badge-sub bg-light-secondary text-ellipsis'>" +
                  name +
                  "</span> " +
                  "<span class='badge badge-sub bg-light-secondary text-ellipsis'>" +
                  cant +
                  "</span> " +
                  "<span class='badge badge-sub bg-light-success text-ellipsis'>" +
                  price +
                  "</span><br>";
            }
         },
      });
      return sub_orders_html;
   }
   function add(cant) {
      var oldValue = cant.val();
      var sum = 0;

      if (oldValue < 1) {
         sum = 1;
      } else {
         sum = parseInt(oldValue) + 1;
      }
      cant.val(sum);
   }
   function minus(cant) {
      var oldValue = cant.val();
      var sum = 0;

      if (oldValue <= 1) {
         sum = 1;
      } else {
         sum = parseInt(oldValue) - 1;
      }
      cant.val(sum);
   }
});
