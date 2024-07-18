$(document).ready(function () {

   $("body").on("click", ".btn-cobrar", function (e) {
		e.preventDefault();
		let name = $(this).attr("data-name");
		let total = $(this).attr("data-total");
		let id_order = $(this).attr("data-id");
		let id_client = $(this).attr("data-id_client");

		let numOrders = orders_client(id_client);

		$("#nameClient").html(name);
		$("#modal-total").html(total);
		$("#numPedidos").html(numOrders);
		$("#idOrder").val(id_order);
		$("#modal-cobro").modal("show");
	});
	$("body").on("click", ".btn-calc", function (e) {
		let monto = "";
		let num = $(this).attr("data-num");
		let inputMonto = $("#inputMontoPago");

		monto = inputMonto.val() + num;
		$(inputMonto).val(monto).change();
	});

   $("#inputMontoPago").on("keyup", function () {
		cambio($(this).val());
	});
	$("#inputMontoPago").on("change", function () {
		cambio($(this).val());
	});

   $("#BorrarCalc").click(function () {
      let val = $("#inputMontoPago");
      let result = "";
      let txt = val.val();
    
      result = txt.slice(0,-1);
      val.val(result).change();

		/* $("#inputMontoPago").val("").change();

		$("#MontoCambio").html("0.00"); */
	});

   $("#modal-cobro").on("hidden.bs.modal", function () {
		$("#inputMontoPago").val("").change();
	});
/*    $("#modal-assign").on("hidden.bs.modal", function () {
      
	}); */

   function orders_client(id) {
		var orders = "";
		$.ajax({
			type: "post",
			url: base_url + "Orders/ajax_get_orders_client",
			data: { id_client: id },
			async: false,
			dataType: "json",
			success: function (response) {
				orders = response.orders;
			},
		});
		return orders.length + 1;
	}
 
   function cambio(val) {
		var total = parseFloat($("#modal-total").html());
		var pago = parseFloat($("#inputMontoPago").val());

		if (val != "") {
			let operacion = pago - total;
			let result = pago < total ? "Pago insuficiente" : operacion + " pesos";

			$("#inputMontoCambio").val(result);
			$("#MontoCambio").html(result);
		} else {
			$("#inputMontoCambio").val("");
			$("#MontoCambio").html("0.00");
		}
	}
});