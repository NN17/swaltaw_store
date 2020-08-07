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

	$('[id=popup]')
		.popup({
			inline: false
		});

	$('#dataTable').DataTable();

	/*
	 * DateTime Picker
	 */
	$("[id=datepicker]").datetimepicker({
		timepicker: false,
		format: "Y-m-d",
		formatDate: "Y-m-d",
		closeOnDateSelect: true,
		scrollInput: false,
		value: new Date()
	});

	// Autofocus
	$(function(){
		let val = $('#saleCode').val();
		if(val == ''){
			$('#saleCode').focus();
		}
	})

	// F2 Shoutcut
	$("#saleCode, #itemQty, #itemPrice").on('keydown', function(event){
		var key = event.keyCode;
		if(key == 113){
			$("#itemSearch").modal('show');
		}
	});

	// Sale Item Search..
	$('#saleItemSearch').on('keyup', function(){
		let keyword = $(this).val();

		$.ajax({
			url: base_url() + 'ignite/saleItemSearch',
			type: "GET",
			crossDomain: "TRUE",
			data: {
				'keyword' : keyword
			},
			success: function(result){
				let html;
				result.forEach(function(x){
					html += `<tr onclick="selectItem('${x.codeNumber}', ${x.retailPrice})" class="item">
							<td>${x.itemName}</td>
							<td>${x.itemModel}</td>
							<td class="ui right aligned">${Intl.NumberFormat().format(x.purchasePrice)}</td>
							<td class="ui right aligned">${Intl.NumberFormat().format(x.retailPrice)}</td>
							<td class="ui right aligned">${Intl.NumberFormat().format(x.wholesalePrice)}</td>
						</tr>`;
				});
				$("#searchContent").html(html);
				
			}
		});
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
			content: "This action can not be undone, the data you selected will be delete permanently.",
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

	$('[id=curDefault]').on('change', function() {
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
	$('#cat').on('change', function() {
		// var bCode = "0000";
		var cat = $(this).val();
		var id = String($('#code').data('itemid'));
		var iCode = format(5, id);

		$.ajax({
			url: base_url() + 'ignite/getLetterCode',
			type: 'POST',
			crossDomain: 'TRUE',
			data: {
				'catId' : cat
			},
			success: function(res){
				let obj = JSON.parse(res);
				$('#code').val(obj.code + "-" + iCode);
			}
		});


	});

	// $('#brand').on('change', function() {
	// 	var catCode = "0000";
	// 	var cat = String($('#cat').val());
	// 	var brand = String($(this).val());
	// 	var id = String($('#code').data('itemid'));
	// 	var bCode = format(4, brand);
	// 	var iCode = format(5, id);

	// 	if (cat != "") {
	// 		catCode = format(4, cat);
	// 	}

	// 	$('#code').val(catCode + "-" + bCode + "-" + iCode);
	// });

	$("#warehouseIssue").on('change', function() {
		var warehouse = $(this).val();
		// console.log(warehouse);

		$.ajax({
			url: base_url() + 'ignite/getItemsByWarehouse/' + warehouse,
			type: 'GET',
			crossDomain: 'TRUE',
			success: function(res) {
				$(".ui.dropdown").removeClass('disabled');
				$("#itemIssue").html(res);
			}
		})
	});

	$("#itemIssue").on('change', function() {
		var code = $(this).val();
		$("#itemCode").val(code);
	});

	$("#destination").on('change', function() {
		var destination = $(this).val();
		var source = $("#warehouseIssue").val();

		$("#destErr").html('<div class="ui active inline loader"></div>');

		setTimeout(function() {
			if (destination == source) {
				$("#destErr").html('<i class="icon exclamation triangle large orange"></i> Source and Destination must not be same !').attr('data-status', false);
			} else {
				$("#destErr").html('<i class="icon check circle large green"></i>').attr('data-status', true);
			}
		}, 1000);
	});

	// Check Letter Code ..
	$("#LC_check").on('keyup', function(event) {
		var lc = $(this).val().toUpperCase();
		var key = event.keyCode;
		console.log(lc);
		$("#LC_err").html('<div class="ui active inline loader"></div>');
		setTimeout(function() {
			if (lc != '') {
				$.ajax({
					url: base_url() + 'ignite/checkLetterCode',
					type: 'POST',
					crossDomain: true,
					data: {
						'character': lc
					},
					success: function(res) {
						let obj = JSON.parse(res);
						if (obj.status == true) {
							$("#LC_err").html('<i class="icon check circle large green"></i>').attr('data-status', obj.status);
						} else {
							$("#LC_err").html('<i class="icon exclamation triangle large orange"></i>').attr('data-status', obj.status);
						}
					}
				})
			} else {
				$("#LC_err").html('<i class="icon exclamation triangle large orange"></i>');
			}
		}, 1000);
		$(this).val(lc);
	});


	// Check Qty..
	$("#qtyIssue").on('change', function() {
		var qty = $(this).val();
		var warehouse = $("#warehouseIssue").val();
		var item = $('#itemIssue').val();

		$("#qtyErr").html('<div class="ui active inline loader"></div>');
		setTimeout(function() {
			console.log(qty + '/' + warehouse + '/' + item);
			if (warehouse == "" || item == "") {
				$("#qtyErr").html('<i class="ui icon orange large exclamation triangle"></i>');
				$("#qtyIssue").attr('placeholder', 'Warhouse & Item must not be NULL ..');
				$("#qtyIssue").val('');
				$("#qtyIssue").focus();
			} else {
				$.ajax({
					url: base_url() + 'ignite/checkQty/' + qty + '/' + warehouse + '/' + item,
					type: 'GET',
					crossDomain: 'TRUE',
					success: function(res) {
						// $("#qtyErr").html(res);
						console.log(res);
						var obj = JSON.parse(res);
						if (obj.status == true) {
							$("#qtyErr").html('<i class="icon check circle large green"></i>').attr('data-status', obj.status);
						} else {
							$("#qtyErr").html('<i class="icon exclamation triangle large orange"></i> Only ' + obj.quantity + ' Remain..').attr('data-status', obj.status);
						}
					}
				});
			}
		}, 1000);

	});

	// Stock Out Form submit
	$("#formStockOut").on('submit', function(event) {
		var qtyStatus = $('#qtyErr').attr('data-status');
		var destStatus = $('#destErr').attr('data-status');

		console.log(qtyStatus + '/' + destStatus);
		if (destStatus == 'false' || qtyStatus == 'false') {
			event.preventDefault();
			$.alert({
				title: 'Invalid Input!',
				content: 'Please check form again!',
				backgroundDismiss: false,
				columnClass: "custom-confirm-box",
			});
		}

		qtyStatus = '';
		destStatus = '';
	});

	$("#catSubmit").on('submit', function(event){
		let err = $("#LC_err").attr('data-status');
		if (err == 'false') {
			event.preventDefault();
			$.alert({
				title: 'Invalid Input!',
				content: 'The Code letter is has been exist or not be null. Please Try again .. !',
				backgroundDismiss: false,
				columnClass: "custom-confirm-box",
			});
			$("#LC_err").focus();
		}
	})


}); // End of document ready function

function category_modal() {
	$('.tiny.modal.category')
		.modal('show');
}

function brand_modal() {
	$('.tiny.modal.brand')
		.modal('show');
}

function supplier_modal() {
	$('.tiny.modal.supplier')
		.modal('show');
}

function warehouse_modal() {
	$('.tiny.modal.warehouse')
		.modal('show');
}

function item_modal() {
	$('.tiny.modal.item')
		.modal('show');
}

function changePriceModal(itemId) {
	$.ajax({
		url: base_url() + 'ignite/getPrice/' + itemId,
		type: 'POST',
		crossDomain: "TRUE",
		success: function(res) {
			var price = $.parseJSON(res);
			$('#changePriceForm').attr('action', 'ignite/changePrice/' + itemId);
			$("#itemName").html(price.itemName);
			$("#purchase").val(price.purchasePrice);
			$("#retail").val(price.retailPrice);
			$("#wholesale").val(price.wholesalePrice);
		}
	});
	$('.tiny.modal.changePrice')
		.modal('show');
}

function format(size, num) {
	var i = 0;
	var str = '';
	while (i < (size - num.length)) {
		str += "0";
		i++;
	}
	var fStr = str + num;
	return fStr;
}

function selectItem(code, price){
	$("#saleCode").val(code);
	$("#itemSearch").modal('hide');
	$("#itemQty").val(1);
	$("#itemPrice").val(price);
	$("#itemQty").focus();
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