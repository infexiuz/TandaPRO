const loading = "<span class='spinner-border spinner-border-sm mr-1' role='status' aria-hidden='true'></span> Cargando..";
const loader = (submit) => {
   submit
      .html(loading)
      .attr("disabled", "disabled")
}
const resetSubmit = (submit) => {
   submit.html("Guardar").prop('disabled', false);
   $("#btnAddMore").html('Guardar sin cerrar').prop('disabled', false);
}
const loadTable = `<tr><td colspan="10" class="text-center">${loading}</td></tr>`;

// Función para extraer los parámetros de la URL
const getURLParameter  = (name) => {
   var currentURL = window.location.href;
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(currentURL);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/* --------------------------------------------- */
/* Ajax helpers */
/* --------------------------------------------- */
const SwalMessage = (icon, title, message) => {
   Swal.fire({
      title: `<strong>${title}</strong>`,
      icon: icon,
      text: message,
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
      timer: 2500,
   })
}
const ToastMessage = (icon, message) => {
   ToastRight.fire({
      icon: icon,
      title: message
   });
}
const SuccessForm = (form,table,message,submit, addmore=false) => {
   
   if(!addmore){
      $(".modal").modal('hide')
   }

   ToastRight.fire({
      icon: "success",
      title: message,
      willClose: () => {
         submit.html('Guardar').prop('disabled', false)
         $(form)[0].reset();
         $(table).DataTable().ajax.reload(null, false);
         if(addmore){
            $("#btnAddMore").html('Guardar sin cerrar').prop('disabled', false)
         }
      },
   });
}
const AjaxFormData = (method, form) => {
   const formdata = new FormData($(form)[0]);

   return new Promise((resolve, reject) => {
      $.ajax({
         type: "post",
         url: base_url + method,
         data: formdata,
         dataType: "json",
         processData: false,
         contentType: false,
         success: resolve,
         error: reject
      })
      .fail(function () {
         ToastMessage("error", "Error de servidor, contacta al administrador")
      });
   });
}
const AjaxDelete = ({ method, param, dataTables=null, message = null }) => {
   Swal.fire({
      title: "¿Estás seguro?",
      text: "No se puede revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Si, eliminar!",
      confirmButtonColor: '#1652f0',
   }).then((result) => {
      if (result.value) {
         $.ajax({
            type: "POST",
            url: base_url + method,
            dataType: 'json',
         }).done(function (response) {
            if (response > 0) {
               if (message != null) {
                  ToastRight.fire({
                     icon: "success",
                     title: message,
                     willClose: () => {
                        $(param).DataTable().ajax.reload(null, false);
                     },
                  });
                  return;
               }
               if(dataTables != null){
                  $(param).DataTable().ajax.reload(null, false);
               }else{
                  param();
               }
            }
         });
      }
   });
}
const AjaxGet = (method, data = {}) => {
   return new Promise((resolve, reject) => {
      $.ajax({
         type: "post",
         url: base_url + method,
         data: data,
         dataType: "json",
         success: resolve,
         error: reject
      });
   });
}
const addMore = (form, button) =>{
   $("#addmore").val('true');
   if ($(form)[0].checkValidity()) {
      loader($(button))
      $(form).submit();
    }else{
      $("#addmore").val('false');
    }
}


var AjaxPost = (method, data) => {

   return new Promise((resolve, reject) => {
      $.ajax({
         type: "post",
         url: base_url + method,
         data: data,
         dataType: "json",
         processData: false,
         contentType: false,
         success: resolve,
         error: reject
      });
   });
}
var AjaxSend = (method, data) => {

   return new Promise((resolve, reject) => {
      $.ajax({
         type: "post",
         url: base_url + method,
         data: data,
         dataType: "json",
         success: resolve,
         error: reject
      });
   });
}

