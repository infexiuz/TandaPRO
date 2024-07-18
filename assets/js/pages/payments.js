$(document).ready(function () {
   $('#tablePayment').DataTable({
      "dom": "<'row'<'col-md-6'<'col-lg-12'fB>><'col-md-6 text-right'l>><'row'<'col-md-12'<'ovhidden't>>><'row'<'col-md-12'i>><'row'<'col-md-12'p>>",
      "processing": true,
      "language": { "url": base_url + "assets/vendors/dataTables/Spanish.json" },
      "retrieve": true,
      "serverSide": true,
      "order": [],
      "buttons": ['excel'],
      "ajax": {
         url: base_url + "App/getDataTables/Payment/getPayments",
         type: "POST"
      },
      "columnDefs": [
         { className: "d-phone-none", targets: [1, 2, 3,4] },
      ],
      drawCallback: function () {
         $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
         })
      }
   });
});