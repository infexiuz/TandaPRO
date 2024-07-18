const switchCheckbox = () =>{
   $("#checkNewTanda").prop('checked', function(i, checked) {
      $("#formTanda input[name=status]").val(!checked==true?"1":"0");
      return !checked;
   });
}
const calculateDate = () =>{
   let start = $("#start").val()
   let numbers = parseInt($("#numbers").val())
   let frecuency = parseInt($("#frecuency").val())
   AjaxGet(`App/get_by_param/Tanda/calculateDate`, {start:start, numbers:numbers, frecuency:frecuency})
   .then(result => {
      if (result.end) {
         $("#formTanda input[name=end]").val(result.end)
         $("#formTanda input[name=nextpayment]").val(result.next)
      }
   })
   .catch(err => {
      console.log(err)
   });
}
const makeCodebyId = () =>{
   AjaxGet(`App/get_by_param/Tanda/makeCodebyId`)
   .then(result => {
      $("#formTanda input[name=code]").val(result)
   })
   .catch(err => {
      console.log(err)
   });
}
const generatePayments = (id) =>{
   $("#tbodyPayments").html(loadTable)
   AjaxGet(`App/get_by_param/Tanda/generatePayments/`, {id:id})
   .then(result => {
      $("#tbodyPayments").html(result)
   })
   .catch(err => {
      console.log(err)
   });
}
function frecuencyName(number){
   let frecuency = '';
   if(number == '30'){
      frecuency = 'Semana' 
   }else if($(this).data('frecuency') == '15'){ 
      frecuency = 'Quincena';
   }else{
      frecuency = 'Semana';
   }
   return frecuency;
}
$(document).ready(function () {
   $('#modalPreview').modal({backdrop: 'static', keyboard: false})  
   $("#selectCustomer").select2({
      placeholder: "Seleccionar contacto",
      allowClear: true,
      dropdownParent: $("#modalTanda")
   });
   $('#tableTanda').DataTable({
      "dom": "<'row'<'col-md-6'<'col-lg-12'fB>><'col-md-6 text-right'l>><'row'<'col-md-12'<'ovhidden't>>><'row'<'col-md-12'i>><'row'<'col-md-12'p>>",
      "processing": true,
      "language": { "url": base_url + "assets/vendors/dataTables/Spanish.json" },
      "retrieve": true,
      "serverSide": true,
      "order": [],
      "buttons": ['excel'],
      "ajax": {
         url: base_url + "App/getDataTables/Tanda/getTandas",
         type: "POST"
      },
      "columnDefs": [
         { className: "d-phone-none", targets: [0,2, 3, 4, 5] },
         { className: "text-center", targets: [5]},
         { orderable: false, targets: [3,4,5] }
      ],
      drawCallback: function () {
         $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
         })
      }
   });
   $("#formPayment").submit(function (e) {
      e.preventDefault();
      const submit = $("#formPayment button[type=submit]");
      loader(submit)
      
      AjaxFormData(`App/insert_custom/Tanda/createPayments`, '#formPayment')
         .then(result => {
            if (result > 0) {
               ToastRight.fire({
                  icon: "success",
                  title: 'Se han guardado los cambios',
                  willClose: () => {
                     $("#btnSubmitUpdate").hide();
                     $("#btnUpdateTanda").show();
                     $(".checkPayment, .thcheckPayment").addClass('d-none');
                     generatePayments(result)
                     resetSubmit(submit);
                  },
               });
            } else {
               $("#btnSubmitUpdate").hide();
               $("#btnUpdateTanda").show();
               $(".checkPayment, .thcheckPayment").addClass('d-none');
               ToastMessage("info", "No hubo cambios.")
            }
            resetSubmit(submit);
         })
         .catch(err => {
            console.log(err)
         });
   });
   $("#formTanda").submit(function (e) {
      e.preventDefault();
      const submit = $("#formTanda button[type=submit]");
      const method = $("#methodForm").val();
      const toasTitle = method == "insert" ? "Registro exitoso" : "Se han guardado los cambios";

      loader(submit)

      AjaxFormData(`App/insert_custom/Tanda/createTanda`, '#formTanda')
         .then(result => {
            if (result > 0) {
               SuccessForm('#formTanda', '#tableTanda',toasTitle, submit)
               $("#selectCustomer").val('').trigger('change')
               $("#formTanda input[name=start]").val($("#today").val())
               history.replaceState(null, null, base_url+'tandas');
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
   $("#btnUpdateTanda").click(function(e){
      $(this).hide()
      $("#btnSubmitUpdate").show();
      $(".checkPayment, .thcheckPayment").removeClass('d-none');
   }) 
   $("body").on('click','.preview', function(e){
      e.preventDefault()
      if(!parseInt($(this).data('status'))){
         ToastMessage('info', 'La tanda no está activa.')
         $("#btnUpdateTanda").hide();
      }

      let tanda_id = $(this).data('tanda_id');
      let frecuency = frecuencyName($(this).data('numbers'));
      let subHeaderTanda = `<p><b><i class="fal fa-hand-holding-usd"></i> $${$(this).data('amount')}</b> | ${$(this).data('numbers')} números | ${frecuency}</p>`;

      $(".title-preview").html(`Tanda <span>#</span><small>${$(this).data('code')}</small><br>${subHeaderTanda}`)  
      $(".v_start").html(date_format($(this).data('start')))
      $(".v_end").html(date_format($(this).data('end')))
      $(".customer").html(`<i class="fal fa-user"></i> ${$(this).data('customer')}`)
      $("#formPayment input[name=customer_id]").val($(this).data('customer_id'))
      $("#formPayment input[name=tanda_id]").val(tanda_id)
      $(".customerInformation").html(`${$(this).data('address')} - ${$(this).data('phone')}`)
      $("#modalPreview").modal('show');

      const pid = new URLSearchParams(window.location.search).get("tanda");
      if(!pid){
         history.pushState('state', null, base_url + 'tanda/preview');
      }
      
      generatePayments(tanda_id)
   })
   $("body").on('click','.confirm-tanda', function(){
      $("#modalTanda .modal-title").html('Confirmar tanda <small class="text-light fs-sm">'+$(this).data('customer')+'</small>')
      $("#methodForm").val('update')
      $(this).find('input').prop('checked', false);

      $("#status").val('1')
      $("#tandaId").val($(this).data('tanda_id'))
      $("#code").val($(this).data('code'))
      $("#end").val($(this).data('end'))
      $("#start").val($(this).data('start'))
      $("#numbers").val($(this).data('numbers'))
      $("#frecuency").val($(this).data('frecuency'))
      $("#amount").val($(this).data('amount'))
      $("#description").val($(this).data('description'))
      $("#selectCustomer").val($(this).data('customer_id')).trigger('change')

      $(".switch-modal").hide()
      $("#formTanda button[type=submit]").html('Empezar');
      $("#modalTanda").modal('show')
   })
   $("body").on("click",'#tablePayments tbody tr', function() {
      if(!$(this).find(".checkPayment").hasClass('d-none')){
         var status = $(this).find(".checkPayment").find('.statusPayment');
         var checkbox = $(this).find(".checkPayment").find('input[type="checkbox"]');
         checkbox.prop("checked", !checkbox.prop("checked"));
         if(checkbox.prop("checked")){
            status.val('1')
         }else{
            status.val('0')
         }
      }
   });

   $('#modalTanda').on('hidden.bs.modal', function () {
      makeCodebyId();
      $("#modalTanda .modal-title").html('Formulario de nueva tanda');
      $("#status").val('0');
      $("#tandaId").val('');
      $("#methodForm").val('insert');
      $("#selectCustomer").val('').trigger('change')
      $("#formTanda")[0].reset();
      $(".switch-modal").show()
      $("#formTanda button[type=submit]").html('Guardar');
   });
   $('#modalPreview').on('hidden.bs.modal', function () {
      $("#btnSubmitUpdate").hide();
      $("#btnUpdateTanda").show();
      $(".checkPayment, .thcheckPayment").addClass('d-none');
   });
});
window.addEventListener('popstate', function(event) {
   console.log(event.state)
   if(event.state === null){
      $("#modalPreview").modal('hide')
   }else{
      $("#modalPreview").modal('show')
   }
});