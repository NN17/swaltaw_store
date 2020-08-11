function base_url() {
	return "http://" + location.hostname + "/inventory/";
}

// setInterval(() => {
//     isOnline();
// }, 5000);

$(document).ready(function() {
	$(".ui.dropdown").dropdown();
	$(".menu .item").tab();

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
		var maxqty = $("#itemQty").data('balance');
		var qty = $("#itemQty").val();
		if(key == 113){
			$("#itemSearch").modal('show');
		}else if(key == 13){
			var code = $("#saleCode").val();
			var name = $("#saleCode").data('name');
			var qty = $("#itemQty").val();
			var price = $("#itemPrice").val();
			if(code == '' || qty == '' || price == ''){
				$.alert({
				    title: 'Invalid Input',
				    content: 'Item Code, Qty and Price must not be null !',
				    backgroundDismiss: false,
					columnClass: "custom-confirm-box",
					animation: "zoom",
					theme: "material"
				});
			}
				else if(qty > maxqty){
					$("#itemQty").parent().addClass('error');
					$.alert({
					    title: 'Invalid Input',
					    content: 'Available Quantity is ' + maxqty + ' !',
					    backgroundDismiss: false,
						columnClass: "custom-confirm-box",
						animation: "zoom",
						theme: "material"
					});
				}
					else{
						if($("#itemQty").parent().hasClass('error')){
							$("#itemQty").parent().removeClass('error');
						}
						orderAjax.addItem(code, name, qty, price);
					}
		}
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


/*
* Order Ajax
*/
var orderAjax = function(){
	let newOrders = [];
	let orderObject = {};

	let createItem = function(code, name, qty, price){
		orderObject = {
			code: code,
			name: name,
			qty: qty,
			price: price
		}

		newOrders.push(orderObject);

		console.log(newOrders);

		templateStructure();
	};

	let templateStructure = function(){
		let html = '';
		let total = 0;

		if(newOrders.length > 0){
			newOrders.forEach(function(x,y){
				let amount = (x.qty * x.price);
				total += amount;
				html += `<tr>
					<td class="ui right aligned">${y+1}</td>
					<td>${x.name}</td>
					<td class="ui right aligned">${Intl.NumberFormat().format(x.price)}</td>
					<td class="ui right aligned">${x.qty}</td>
					<td class="ui right aligned">${Intl.NumberFormat().format(amount)}</td>
					<td><i class="trash alternate icon red" onclick="orderAjax.removeItem(${y})"></i></td>
					</tr>`;
			});

		}

		$("#vr_preview").html(html);
		$("#subTotal").html(Intl.NumberFormat().format(total));
		$("#saleCode").val('').focus();
		$("#itemQty").val('');
		$("#itemPrice").val('');
	};

	let checkOut = function(){

	};

	let removeItemFunc = function(index){
		newOrders.splice(index);
		if($("#itemQty").parent().hasClass('error')){
			$("#itemQty").parent().removeClass('error');
		}
		templateStructure();
	};

	let searchItem = function(){
		let keyword = $("#saleItemSearch").val();

		$.ajax({
			url: base_url() + 'ignite/saleItemSearch',
			type: "GET",
			crossDomain: "TRUE",
			data: {
				'keyword' : keyword
			},
			success: function(result){
				let html;
				if(result.length > 0){

					result.forEach(function(x){
						html += `<tr onclick="orderAjax.itemSelect('${x.itemName}', '${x.codeNumber}', ${x.retailPrice}, ${x.qty})" class="item">
								<td>${x.itemName}</td>
								<td>${x.itemModel}</td>
								<td class="ui right aligned">${Intl.NumberFormat().format(x.purchasePrice)}</td>
								<td class="ui right aligned">${Intl.NumberFormat().format(x.retailPrice)}</td>
								<td class="ui right aligned">${Intl.NumberFormat().format(x.wholesalePrice)}</td>
								<td class="ui right aligned">${x.qty}</td>
							</tr>`;
					});
				}else{
					html = '';
				}
				$("#searchContent").html(html);
				
			}
		});
	};

	let selectItem = function(name, code, price, qty){
		$("#saleItemSearch").val('');
		$("#searchContent").html('');
		$("#saleCode").val(code).data('name', name);
		$("#itemSearch").modal('hide');
		$("#itemQty").val(1).data('balance', qty);
		$("#itemPrice").val(price);
		$("#itemQty").focus();
	}

	return {
		init: function() {
			return;
		},
		addItem: function(code, name, qty, price) {
			createItem(code, name, qty, price);
		},
		checkOutOrder: function() {
			checkOut();
		},
		removeItem: function(index) {
			removeItemFunc(index);
		},
		itemSearch: function(){
			searchItem();
		},
		itemSelect: function(name, code, price, qty){
			selectItem(name, code, price, qty);
		}
	}
}();


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