$(document).ready(function () {
   $('#tableCustomer').DataTable({
      "dom": "<'row'<'col-md-6'<'col-lg-12'fB>><'col-md-6 text-right'l>><'row'<'col-md-12'<'ovhidden't>>><'row'<'col-md-12'i>><'row'<'col-md-12'p>>",
      "processing": true,
      "language": { "url": base_url + "assets/vendors/dataTables/Spanish.json" },
      "retrieve": true,
      "serverSide": true,
      "order": [],
      "buttons": ['excel'],
      "ajax": {
         url: base_url + "App/getDataTables/User/getCustomers",
         type: "POST"
      },
      "columnDefs": [{
         "className": "d-phone-none",
         "targets": [0,2, 3, 4, 5]
      }],
      drawCallback: function () {
         $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
         })
      }
   });
   $("#formCustomer").submit(function (e) {
      e.preventDefault();
      const submit = $("#formCustomer button[type=submit]");
      const id = $("#customerId").val();
      const method = $("#methodForm").val();
      const url = method == "insert" ? "insert/customer" : "update/customer/" + id;
      const toasTitle = method == "insert" ? "Registro exitoso" : "Se han guardado los cambios";
      const addmore = $("#addmore").val() == 'true' ? true : false; 

      if(!addmore){ loader(submit) }

      AjaxFormData(`App/${url}`, '#formCustomer')
         .then(result => {
            if (result > 0) {
               SuccessForm('#formCustomer', '#tableCustomer',toasTitle, submit, addmore)
            } else {
               resetSubmit(submit);
               if (method == 'insert') {
                  ToastMessage("error", "Hubo un error con el registro.")
               } else {
                  $(".modal").modal('hide')
                  ToastMessage("info", "No hubo cambios.")
               }
            }
         })
         .catch(err => {
            console.log(err)
         });
   });
   $("body").on("click", ".update-customer", function (e) {
      $("#modalCustomer").modal('show');
      $("#modalCustomer h5").html('Editar contacto');
      $("#btnAddMore").hide();
      $("#methodForm").val('update');
      $("#customerId").val($(this).data('id'))
      $("#formCustomer input[name=name]").val($(this).data('name'));
      $("#formCustomer input[name=phone]").val($(this).data('phone'));
      $("#formCustomer textarea[name=address]").val($(this).data('address'));
   });
   $("body").on("click", ".delete-customer", function (e) {
      const id = $(this).data('id');
      AjaxDelete({
         dataTables:true,
         param: "#tableCustomer",
         message:'Contacto eliminado',
         method: "App/delete/customer/"+id,
      })
   });
   $('#modalCustomer').on('hidden.bs.modal', function () {
      $("#methodForm").val('insert');
      $("#btnAddMore").show();
      $("#customerId").val('');
      $("#formCustomer")[0].reset();
      $("#modalCustomer h5").html('Nuevo Contacto');
   });
});
