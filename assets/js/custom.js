function base_url() {
	return "http://" + location.hostname + "/inventory/";
}

// setInterval(() => {
//     isOnline();
// }, 5000);

$(document).ready(function() {
	$(".ui.dropdown").dropdown();
	$(".menu .item").tab();
	// $(":input[type='number']:enabled:visible:first").focus();

	$('.ui.checkbox').checkbox();

	/*
	 * DateTime Picker
	 */
	$("[id=datepicker]").datetimepicker({
		timepicker: false,
		format: "Y-m-d",
		formatDate: "Y-m-d",
		closeOnDateSelect: true
	});

	
	/*
	 * Delete Confirmation
	 */
	$("[id=delete]").on("click", function() {
		var url = $(this).data("url");
		$.confirm({
			theme: "light",
			icon: "fa fa-warning",
			title: "<i class='icon exclamation triangle yellow'></i> Are you sure want to DELETE",
			backgroundDismiss: false,
			columnClass: "custom-confirm-box",
			content:
				"This action can not be undone, the data you selected will be delete permanently.",
			buttons: {
				Delete: {
					btnClass: "btn-red",
					action: function() {
						$(location).attr("href", url);
					}
				},
				cancel: function() {
					//
				}
			}
		});
	});

	$('[id=curDefault]').on('change', function(){
		var currencyId = $(this).val();
		// console.log(currencyId);
		$.ajax({
			url: base_url() + "ignite/changeCurrency/" + currencyId,
			type: "GET",
			crossDomain: "TRUE",
			// success: function(res){
			// 	console.log(res);
			// }
			done: location.reload()
		});
	});

	// Generate Item Code
	$('#cat').on('change', function(){
		var bCode = "0000";
		var cat = String($(this).val());
		var brand = $('#brand').val();
		var id = String($('#code').data('itemid'));
		var catCode = format(4, cat);
		var iCode = format(5, id);

		// console.log(format(4, '1'));

		if(brand != ""){
			bCode = format(4, brand);
		}

		$('#code').val(catCode + "-" + bCode + "-" + iCode);
		
	});

	$('#brand').on('change', function(){
		var catCode = "0000";
		var cat = String($('#cat').val());
		var brand = String($(this).val());
		var id = String($('#code').data('itemid'));
		var bCode = format(4, brand);
		var iCode = format(5, id);

		if(cat != ""){
			catCode = format(4, cat);
		}

		$('#code').val(catCode + "-" + bCode + "-" + iCode);
	});


});// End of document ready function

function category_modal(){
	$('.tiny.modal.category')
	  .modal('show')
	;
}

function brand_modal(){
	$('.tiny.modal.brand')
	  .modal('show')
	;
}

function supplier_modal(){
	$('.tiny.modal.supplier')
	  .modal('show')
	;
}

function warehouse_modal(){
	$('.tiny.modal.warehouse')
	.modal('show')
  ;
}

function item_modal(){
	$('.tiny.modal.item')
	.modal('show')
  ;
}

function changePriceModal(itemId){
	$.ajax({
		url: base_url() + 'ignite/getPrice/' + itemId,
		type: 'POST',
		crossDomain: "TRUE",
		success: function(res){
			var price = $.parseJSON(res);
			$('#changePriceForm').attr('action', 'ignite/changePrice/' + itemId);
			$("#itemName").html(price.itemName);
			$("#purchase").val(price.purchasePrice);
			$("#retail").val(price.retailPrice);
			$("#wholesale").val(price.wholesalePrice);
		}
	});
	$('.tiny.modal.changePrice')
	  .modal('show')
	;
}

function format(size, num){
	var i = 0;
	var str = '';
	while(i < (size-num.length)){
		str += "0";
		i ++;
	}
	var fStr = str+num;
	return fStr;
}

// 

// function isOnline(no, yes) {
// 	var xhr = XMLHttpRequest
// 		? new XMLHttpRequest()
// 		: new ActiveXObject("Microsoft.XMLHttp");
// 	xhr.onload = function() {
// 		if (yes instanceof Function) {
// 			yes();
// 		}
// 	};
// 	xhr.onerror = function() {
// 		if (no instanceof Function) {
// 			no();
// 		}
// 	};
// 	xhr.open("GET", "anypage.php", true);
// 	xhr.send();
// }

// isOnline(
// 	function() {
// 		alert("Sorry, we currently do not have Internet access.");
// 	},
// 	function() {
// 		alert("Succesfully connected!");
// 	}
// );
