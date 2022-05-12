function base_url() {
	return "http://" + location.hostname + "/POSv2/";
}	

// setInterval(() => {
//     isOnline();
// }, 5000);

$(document).ready(function() {

	priceAjax.init();
	cropImage.init();
	chartJs.init();
	damageItem.init();
	orderAjax.init();

	$(".ui.dropdown").dropdown();
	$(".menu .item").tab();

	$('.ui.checkbox').checkbox();

	$('[id=popup]')
		.popup({
			inline: false
		});

	$('[id="popup_link"]')
		.popup({
			inline     : true,
		    hoverable  : true,
		    position   : 'top left',
		});

	$('#dataTable').DataTable({
		"pageLength" : 25
	});

	$('#dataTable2').DataTable({
		"pageLength" : 25
	});

	$('#dataTable3').DataTable({
		"pageLength" : 25
	});

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

	$("[id=datepicker2]").datetimepicker({
		timepicker: false,
		format: "Y-m-d",
		formatDate: "Y-m-d",
		closeOnDateSelect: true,
		scrollInput: false
	});

	// Discount Type
	$("#dType").on("change", function(){
		if($(this).val() == 'DP' || $(this).val() == 'DA'){
			$("#dRate").removeClass('disabled');
			$("#dRate input").attr('required', 'required');
		}else{
			$("#dRate").addClass('disabled');
			$("#dRate input").removeAttr('required');
		}
	});

	$("#disType").on("change", function(){
		var typeId = $(this).val();

		$.ajax({
			url: base_url() + 'ignite/getDiscount',
			type: 'POST',
			crossDomain: 'TRUE',
			data: {
				'disId' : typeId
			},
			success: function(res){
				let obj = JSON.parse(res);
				var total = $("#disType").data('total');
				console.log($("#disType").data('total'));
				if(obj.discountType == "DF"){
					$("#disAmt").removeClass('disabled');
					$("#disAmt input").html(0).focus().select();
				}
					else if(obj.discountType == "DP") {

						$("#disAmt").addClass('disabled');
						$("#disAmt input").val(parseInt(total * (obj.discountRate / 100)));
						console.log((obj.discountRate / 100));
					}
						else{
							$("#disAmt").addClass('disabled');
							$("#disAmt input").val(obj.discountRate);
						}
			}
		});
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

		var maxqty = parseInt($("#itemQty").attr('data-balance'));
		var qty = parseInt($("#itemQty").val());
		if(key == 113){
			$("#itemSearch").modal('show');

		}
			else if (key == 115){
				console.log('f3 pressed');
			}
				else if(key == 13){

					var code = $("#saleCode").val();
					var name = $("#saleCode").attr('data-name');
					var qty = parseInt($("#itemQty").val());
					var price = $("#itemPrice").val();

					// console.log('code='+code+'/name='+name+'/qty='+qty);
					if(code == '' || qty == '' || price == '' || qty == NaN || name == undefined){

						$.alert({
						    title: 'Invalid Input',
						    content: 'Item Code, Qty and Price must not be null !',
						    backgroundDismiss: false,
							columnClass: "custom-confirm-box",
							animation: 'zoom',
				    		closeAnimation: 'opacity',
				    		animationBounce: 1.5,
							theme: "modern",
							type: "red",
							icon: "ui icon",
							useBootstrap: false,
							boxWidth: '30%',
						});
					}
						else if(code != '' && (qty == '' || price == '')){
							orderAjax.saleCode(code);
						}
							else{
									if(qty > maxqty){
										console.log('Qty' + qty + '/ MaxQty: ' + maxqty);
										$("#itemQty").parent().addClass('error');
										$.alert({
										    title: 'Invalid Input',
										    content: 'Available Quantity is ' + maxqty + ' !',
										    backgroundDismiss: false,
											columnClass: "custom-confirm-box",
											animation: 'zoom',
								    		closeAnimation: 'opacity',
								    		animationBounce: 1.5,
											theme: "modern",
											type: "red",
											icon: "ui icon",
											useBootstrap: false,
											boxWidth: '30%',
										});
									}else{
										if($("#itemQty").parent().hasClass('error')){
											$("#itemQty").parent().removeClass('error');
										}
										// console.log(key);
										orderAjax.addItem(code, name, qty, price);
									}
								}
				}
	});


	// Scanner is on

	$("#scanner").on('change', function(){
		if($(this).prop('checked')){
			$("#code")
				.removeAttr('readonly')
				.val('');

			setTimeout(function() { 
				$('input[name="code"]').focus() 
			}, 1000);
		}else{
			$("#code").attr('readonly', 'readonly');
			var cat = $("#cat").val();
			var id = String($('#code').data('itemid'));
			var iCode = format(5, id);

			if(cat != ''){

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
			}
		}
	})

	/*
	 * Delete Confirmation
	 */
	$("[id=delete]").on("click", function() {
		var url = $(this).data("url");
		$.confirm({
			animation: 'zoom',
    		closeAnimation: 'opacity',
    		animationBounce: 1.5,
			theme: "modern",
			type: "red",
			icon: "ui icon",
			useBootstrap: false,
			boxWidth: '30%',
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
		var iCode = format(6, id);

		$('#code').val(iCode);
		// $.ajax({
		// 	url: base_url() + 'ignite/getLetterCode',
		// 	type: 'POST',
		// 	crossDomain: 'TRUE',
		// 	data: {
		// 		'catId' : cat
		// 	},
		// 	success: function(res){
		// 		let obj = JSON.parse(res);
		// 		$('#code').val(obj.code + "-" + iCode);
		// 	}
		// });


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
		// console.log(lc);
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

	// Get Count Type
	$("#item").on('change', function(){
		var itemId = $("#item").val();

		console.log(itemId);
		$.ajax({
			url: base_url() + 'ignite/getCountType',
			type: 'GET',
			crossDomain: true,
			data: {
				'itemId': itemId
			},
			success: function(res){
				$("#countType").html(res);
			}
		});
	});


	// Check Qty..
	$("#qtyIssue").on('change', function() {
		var qty = $(this).val();
		var warehouse = $("#warehouseIssue").val();
		var item = $('#itemIssue').val();

		$("#qtyErr").html('<div class="ui active inline loader"></div>');
		setTimeout(function() {
			// console.log(qty + '/' + warehouse + '/' + item);
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
				animation: 'zoom',
	    		closeAnimation: 'opacity',
	    		animationBounce: 1.5,
				theme: "modern",
				type: "red",
				icon: "ui icon",
				useBootstrap: false,
				boxWidth: '30%',
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
				animation: 'zoom',
	    		closeAnimation: 'opacity',
	    		animationBounce: 1.5,
				theme: "modern",
				type: "red",
				icon: "ui icon",
				useBootstrap: false,
				boxWidth: '30%',
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

function purchase_price_modal() {
	$('.tiny.modal.purchase')
		.modal({closable: false})
		.modal('show');
}

function sale_price_modal() {
	$('.tiny.modal.sale')
		.modal({closable: false})
		.modal('show');
}

function discountModal() {
	$('.mini.modal.sale')
		.modal('show');
}

function viewImg(path) {
	var html = '<img src="' + path + '" class="ui centered image" />';
	$('#imgContent').html(html);
	$('.modal.imgPreview').modal('show');
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

function openModal(name){
	$('#' + name).modal({closable: false}).modal('show');
}

function readURL(input) {
    if (input.files && input.files[0]) 
    {
        var reader = new FileReader();

        reader.onload = function (e) 
        {
            $('#previewImg')
                .attr('src', e.target.result)
                .css('width','75%');
        };

        reader.readAsDataURL(input.files[0]);
    }
}


/*
* Order Ajax
*/
var orderAjax = function(){
	let newOrders = [];
	let orderObject = {};
	let temp = [];
	let tempNew = [];

	// Default
	let byCustomer = 0;
	let saleType = 'R';
	let paymentType = 'CSH';
	let customerId = 0;
	let deposit = 0;
	let referId = 0;

	// Check Path
	let thisPath = function(){
		let currentPath = window.location.pathname;
		let arr = currentPath.split('/');
		let invId = arr[arr.length - 1];
		if (currentPath.indexOf('POSv2/refer-invoice') > 0) {
			getInvoice(invId);
		} else {
			// console.log(currentPath.indexOf(base_url() + 'refer-invoice'));
		}
	};

	let getInvoice = function(invId) {
		$.ajax({
			url: base_url() + 'get-invoice',
			type: 'GET',
			crossDomain: 'TRUE',
			data:{
				'invId' : invId
			},
			success: function(res) {
				var obj = $.parseJSON(res);
				byCustomer = obj.invoice.byCustomer;
				saleType = obj.invoice.saleType;
				paymentType = obj.invoice.paymentType;
				customerId = obj.invoice.customerId;
				deposit = obj.invoice.depositAmt;
				newOrders = (obj.detail);
				referId = obj.invoice.invoiceId;

				templateStructure();

				// console.log(saleType);
			}
		});
	}

	let createItem = function(code, name, qty, price){
		orderObject = {
			code: code,
			name: name,
			qty: qty,
			price: price
		}

		if (newOrders.length > 0) {
			newOrders.forEach(function(x) {
				if (x.code == code) {
					tempNew.push(x.code);
				}
			});

			if (tempNew.length > 0) {
				newOrders = newOrders.map(y => {
					if (y.code == code) {
						y.qty += qty;
						return y;
					} else {
						return y;
					}
				});
			} else {
				newOrders.push(orderObject);
			}

		} else {
			newOrders.push(orderObject);
		}


		templateStructure();

		$('#saleCode').focus();
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
					<td><button class="ui button icon tiny circular red" onclick="orderAjax.removeItem(${y})"><i class="remove icon"></i></button></td>
					</tr>`;
			});

			$("#btnCheckOut").removeClass('disabled');
			$("#discountBtn").removeClass('disabled');
		}
		else{
			$("#btnCheckOut").addClass('disabled');
			$("#discountBtn").addClass('disabled');
		}

		let gTotal = total - deposit;
		$("#vr_preview").html(html);
		$("#subTotal").html(total);
		$('#depositAmt').html(deposit);
		$("#grandTotal").html(gTotal);
		$("#saleCode").val('');
		$("#itemQty").val('');
		$("#itemPrice").val('');
	};

	let checkOut = function(){
		if(paymentType == "CRD"){
			if(customerId == 0){
				$.alert({
				    title: 'Customer missing !',
				    content: 'Customer should not be null, Please select customer.',
				    backgroundDismiss: false,
					columnClass: "custom-confirm-box",
					animation: 'zoom',
		    		closeAnimation: 'opacity',
		    		animationBounce: 1.5,
					theme: "modern",
					type: "red",
					icon: "ui icon",
					useBootstrap: false,
					boxWidth: '30%',
				});
				$('#customer').parent().addClass('error');
			}
				else {
					$.ajax({
						url: base_url() + 'ignite/checkOut',
						type: "POST",
						crossDomain: "TRUE",
						data: {
							'saleType' : saleType,
							'customer' : byCustomer,
							'customerId' : customerId,
							'paymentType' : paymentType,
							'depositAmt' : deposit,
							'referId' : referId,
							'order' : JSON.stringify(newOrders)
						},
						success: function(res){
							// console.log(res);
							location.href = 'preview/' + res;
							
						}
					});
				}
		}
			else{

				$.ajax({
					url: base_url() + 'ignite/checkOut',
					type: "POST",
					crossDomain: "TRUE",
					data: {
						'saleType' : saleType,
						'customer' : byCustomer,
						'customerId' : customerId,
						'paymentType' : paymentType,
						'depositAmt' : deposit,
						'referId' : referId,
						'order' : JSON.stringify(newOrders)
					},
					success: function(res){
						// console.log(res);
						location.href = 'preview/' + res;
						
					}
				});
			}
	};

	let removeItemFunc = function(index){
		newOrders.splice(index, 1);
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
				'keyword' : keyword,
				'saleType' : saleType
			},
			success: function(result){
				let html;
				if(result.length > 0){
					// console.log(result);
					result.forEach(function(x){
						html += `<tr onclick="orderAjax.itemSelect('${x.itemName.replace('"','&quot;')}', '${x.itemModel}', '${x.codeNumber}', ${x.price}, ${x.qty})" class="item">
								<td>${x.itemName}</td>
								<td>${x.itemModel}</td>
								<td class="ui right aligned">${Intl.NumberFormat().format(x.price)}</td>
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

	let selectItem = function(name, model, code, price, qty){
		
		$("#saleItemSearch").val('');
		$("#searchContent").html('');
		$("#saleCode").val(code).attr('data-name', name);
		$("#itemSearch").modal('hide');
		$("#itemQty").val(1).attr('data-balance', qty);
		$("#itemPrice").val(price);
		setTimeout(function() { 
				$('input[name="itemQty"]').focus().select();
			}, 1000);
	}

	let getItemByCode = function(code){
		
		$.ajax({
			url: base_url() + 'ignite/getItemByCode',
			type: 'GET',
			crossDomain: 'TRUE',
			data:{
				code: code
			},
			success: function(res){
				if($("#wholesale").prop('checked')){
					$("#saleCode").data('name', res.itemName);
					$("#itemQty").val(1).data('balance', res.qty);
					$("#itemPrice").val(res.wholesalePrice);
					setTimeout(function() { 
						$('input[name="itemQty"]').focus().select();
					}, 1000);
				}else{
					$("#saleCode").data('name', res.itemName);
					$("#itemQty").val(1).data('balance', res.qty);
					$("#itemPrice").val(res.retailPrice);
					setTimeout(function() { 
						$('input[name="itemQty"]').focus().select();
					}, 1000);
				}
			}
		});
	}

	let getCredit = function(e){
		customerId = e.value;

		console.log(customerId);

		if(customerId != '' && customerId != 0){
			$.ajax({
				url: base_url() + 'ignite/getCreditByCustomer',
				type: 'GET',
				crossDomain: 'TRUE',
				data: {
					customerId : customerId
				},
				success: function(res){
					$("#credit").val(res)
					.parent()
					.removeClass('disabled');
				}
			});

			if($('#customer').parent().hasClass('error')){
				$('#customer').parent().removeClass('error');
			}

			setTimeout(function(){
				$("#saleCode").focus();
			},1000);
		}
	}

	let inCash = function(ev){
		key = event.keyCode;
		if(key == 13){
			deposit = ev.value;
			$('#depositModal').modal('hide');
			ev.value = '';
			templateStructure();
		}
	}

	let formSubmit = function(form){
		$("#"+form).submit();
	}

	let discountAdd = function(total, invId){
		var discount = $("#disAmt input").val();
		
		$.ajax({
			url: base_url() + 'add-discount',
			type: 'POST',
			crossDomain: 'TRUE',
			data: {
				invId : invId,
				discount : discount
			},
			success: function(res){
				if(res == 'success'){
					location.href = 'preview/' + invId;
				}
			}
		});
	}

	let asCustomer = function(event) {
		if(event.checked == true){
			byCustomer = 1;
			$("#customer").parent().removeClass('disabled');
			if($("#customer").val() != ''){
				if($("#credit").parent().hasClass('disabled')){
					$("#credit").parent().removeClass('disabled');
				}
			}
		}
			else{
				byCustomer = 0;
				$("#customer").parent().addClass('disabled');
				if(!$("#credit").parent().hasClass('disabled')){
					$("#credit").parent().addClass('disabled');
				}
			}

	}

	let sellWholeSale = function(e) {
		if(e.checked == true){
			saleType = 'W';
			if($.trim($("#vr_preview").html()) == ''){
				$("#saleItemSearch").attr('onkeyup', "orderAjax.itemSearch('wholesale')");

				$("#saleCode").val('');
				$("#itemQty").val('');
				$("#itemPrice").val('');
				setTimeout(function(){
					$("#saleCode").focus();
				},1000);
			}else{
				$.alert({
				    title: 'Alert !',
				    content: 'You have already selected items, please remove items first.',
				    backgroundDismiss: false,
					columnClass: "custom-confirm-box",
					animation: 'zoom',
		    		closeAnimation: 'opacity',
		    		animationBounce: 1.5,
					theme: "modern",
					type: "red",
					icon: "ui icon",
					useBootstrap: false,
					boxWidth: '30%',
				});
				$(this).prop('checked', false);
			}
		}
			else{
				saleType = 'R';
				if($.trim($("#vr_preview").html()) == ''){
					$("#saleItemSearch").attr('onkeyup', "orderAjax.itemSearch('retail')");

					$("#saleCode").val('');
					$("#itemQty").val('');
					$("#itemPrice").val('');
					setTimeout(function(){
						$("#saleCode").focus();
					},1000);
				}else{
					$.alert({
					    title: 'Alert !',
					    content: 'You have already selected items, please remove items first.',
					    backgroundDismiss: false,
						columnClass: "custom-confirm-box",
						animation: 'zoom',
			    		closeAnimation: 'opacity',
			    		animationBounce: 1.5,
						theme: "modern",
						type: "red",
						icon: "ui icon",
						useBootstrap: false,
						boxWidth: '30%',
					});
					$(this).prop('checked', true);
				}
			}

			console.log(saleType);
	}

	let pType = function(e) {
		if(e.checked == true){
			paymentType = e.value;
			if(paymentType == "CRD"){
				byCustomer = 1;
				$("#deposit").removeClass('disabled');
				$("#byCustomer").prop('checked', true);
				$("#customer").parent().removeClass('disabled');
				if($("#customer").val() != ''){
					if($("#credit").parent().hasClass('disabled')){
						$("#credit").parent().removeClass('disabled');
					}
				}
				setTimeout(function(){
					$("#saleCode").focus();
				},1000);
			}
				else{
					$("#deposit").addClass('disabled');
					deposit = 0;
					byCustomer = 0;
					$("#byCustomer").prop('checked', false);
					$("#customer").parent().addClass('disabled');
					if(!$("#credit").parent().hasClass('disabled')){
						$("#credit").parent().addClass('disabled');
					}
					templateStructure();
					setTimeout(function(){
						$("#saleCode").focus();
					},1000);
				}
		}
		
	}


	return {
		init: function() {
			thisPath();
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
		itemSelect: function(name, model, code, price, qty){
			selectItem(name, model, code, price, qty);
		},
		saleCode: function(code){
			getItemByCode(code);
		},
		credit: function(e){
			getCredit(e);
		},
		addCash: function(ev){
			inCash(ev);
		},
		submitForm: function(form){
			formSubmit(form);
		},
		addDiscount: function(total, invId){
			discountAdd(total, invId);
		},
		byCustomer: function(e) {
			asCustomer(e);
		},
		wholeSale: function(e) {
			sellWholeSale(e);
		},
		payment: function(e) {
			pType(e);
		}
	}
}();

// *************** Price Ajax *****************

var priceAjax = function(){

	let purchasePrice = [];
	let purchaseObj = {};
	let salePrice = [];
	let saleObj = {};
	let allPrice = [];

	let thisPath = function(){
		let currentPath = window.location.pathname;
		let arr = currentPath.split('/');
		let itemId = arr[arr.length - 2];
		if (currentPath.indexOf('POSv2/define-price') > 0) {
			// console.log(getPrices(itemId));
			getPrices(itemId);
		} else {
			// console.log("don't have")
		}
	};

	let getPrices = function(itemId){
		fetch(base_url() + 'get-defined-price/' + itemId).then(function(res) {
			return res.json();
		}).then(function(data) {
			console.log(data);
			data.forEach(function(x){
				if(x.type == 'P'){

					purchaseObj = {
						type: x.type,
						countType : x.count_type,
						qty : x.qty,
						price : x.price,
						remark : x.remark
					}

					purchasePrice.push(purchaseObj);
					priceTemplate(x.type);
				}
					else {
						saleObj = {
							type: x.type,
							countType : x.count_type,
							qty : x.qty,
							price : x.price,
							remark : x.remark
						}

						salePrice.push(saleObj);
						priceTemplate(x.type);
					}
			});
			
		});
	}

	let newPrice = function(type){
		if(type == 'P'){
			let p_type = $('#p_countType').val();
			let p_qty = $('#p_qty').val();
			let p_price = $("#p_price").val();
			let p_remark = $("#p_remark").val();

			if(p_type == ''){
				$("#p_countType").parent().addClass('error');
			}else{
				if($("#p_countType").parent().hasClass('error')){
					$("#p_countType").parent().removeClass('error');
				}
			}

			if(p_qty == ''){
				$('#p_qty').parent().addClass('error');
			}else{
				if($("#p_qty").parent().hasClass('error')){
					$("#p_qty").parent().removeClass('error');
				}
			}

			if(p_price == ''){
				$("#p_price").parent().addClass('error');
			}else{
				if($("#p_price").parent().hasClass('error')){
					$("#p_price").parent().removeClass('error');
				}
			}

			if(p_type != '' && p_qty != '' && p_price != ''){
				purchaseObj = {
					type: 'P',
					countType : p_type,
					qty : p_qty,
					price : p_price,
					remark : p_remark
				}

				purchasePrice.push(purchaseObj);

				priceTemplate('P');
			}else{
				console.log('false');
			}
		}else{
			let s_type = $('#s_countType').val();
			let s_qty = $('#s_qty').val();
			let s_price = $("#s_price").val();
			let s_remark = $("#s_remark").val();
			let s_saleType = $('input[name="saleType"]:checked').val();

			if(s_type == ''){
				$("#s_countType").parent().addClass('error');
			}else{
				if($("#s_countType").parent().hasClass('error')){
					$("#s_countType").parent().removeClass('error');
				}
			}

			if(s_qty == ''){
				$('#s_qty').parent().addClass('error');
			}else{
				if($("#s_qty").parent().hasClass('error')){
					$("#s_qty").parent().removeClass('error');
				}
			}

			if(s_price == ''){
				$("#s_price").parent().addClass('error');
			}else{
				if($("#s_price").parent().hasClass('error')){
					$("#s_price").parent().removeClass('error');
				}
			}

			if(s_type != '' && s_qty != '' && s_price != ''){
				saleObj = {
					type: s_saleType,
					countType : s_type,
					qty : s_qty,
					price : s_price,
					remark : s_remark
				}

				salePrice.push(saleObj);

				priceTemplate('R');
			}else{
				console.log('false');
			}
		}
	}

	let priceTemplate = function(type){
		if(purchasePrice.length > 0 || salePrice.length > 0){
				$("#definePriceBth").removeClass('disabled');
			}else{
				$("#definePriceBth").addClass('disabled');
			}

		if(type == 'P'){
			let html = '';

			if(purchasePrice.length > 0){
				purchasePrice.forEach(function(x,y){
					html += `<tr>
						<td>${x.countType}</td>
						<td class="ui right aligned">${x.qty}</td>
						<td class="ui right aligned">${x.price}</td>
						<td><button class="ui button icon tiny circular red" onclick="priceAjax.removeItem(${y}, 'P')"><i class="remove icon"></i></button></td>
					</tr>`;
				});
			}
				else{
					html += `<tr>
						<td>-</td>
						<td class="ui right aligned">-</td>
						<td class="ui right aligned">-</td>
						<td>-</td>
					</tr>`;
				}

			$("#p_body").html(html);
			$(".tiny.modal.purchase").modal('hide');
		}
			else {
				let html = '';

				if(salePrice.length > 0){
					salePrice.forEach(function(x,y){
						html += `<tr>
							<td>${x.countType}</td>
							<td class="ui right aligned">${x.qty}</td>
							<td class="ui right aligned">${x.price}</td>
							<td><button class="ui button icon tiny circular red" onclick="priceAjax.removeItem(${y}, 'S')"><i class="remove icon"></i></button></td>
						</tr>`;
					});
				}
					else{
						html += `<tr>
							<td>-</td>
							<td class="ui right aligned">-</td>
							<td class="ui right aligned">-</td>
							<td>-</td>
						</tr>`;
					}

				$("#s_body").html(html);
				$(".tiny.modal.sale").modal('hide');
			}
	}

	let removeItemFunc = function(index, type){
		if(type == 'P'){
			purchasePrice.splice(index, 1);
		}
			else {
				salePrice.splice(index, 1);
			}

		priceTemplate(type);
	}

	let savePriceFunc = function(item){
		let temp_obj = {};

		if(purchasePrice.length > 0){
			purchasePrice.forEach(function(x){
				temp_obj = {
					itemId: item,
					type: x.type,
					countType: x.countType,
					qty: x.qty,
					price: x.price,
					remark: x.remark
				}

				allPrice.push(temp_obj);
			});
		}

		if(salePrice.length > 0){
			salePrice.forEach(function(y){
				temp_obj = {
					itemId: item,
					type: y.type,
					countType: y.countType,
					qty: y.qty,
					price: y.price,
					remark: y.remark
				}

				allPrice.push(temp_obj);
			});
		}

		// console.log(allPrice);
		fetch('ignite/addPrice/' + item, {
			method: 'POST', // or 'PUT'
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(allPrice),
		})
		.then(response => response.json())
		.then(data => {
			allItems = [];
			console.log(data);
			window.location.href = 'items-price/0';
		})
		.catch((error) => {
			console.log('Error:', error);
		});
	}

	return {
		init: function(){
			thisPath();
		},
		addPrice: function(type){
			newPrice(type);
		},
		removeItem: function(index, type){
			removeItemFunc(index, type);
		},
		savePrice: function(item){
			savePriceFunc(item);
		}
	};
}();

var igniteAjax = function (){

	let invDetail = function (invId){
		$.ajax({
			url: base_url() + 'ignite/getInvoice',
			type: 'GET',
			crossDomain: 'TRUE',
			data: {
				invId : invId
			},
			success: function(res){
				let html = '';
				var inv = $.parseJSON(res);
				var i = 1;
				var dis = 0;
				let total = 0;
				let invId = '';
				let invDate = '';
				inv.forEach(function(x){
					let amount = x.itemQty * x.itemPrice;
					total += amount;
					dis = x.discountAmt;
					invDate = x.created_date + '( '+x.created_time+' )';
					invId = x.invoiceSerial;
					html += `<tr>
							<td>${i}</td>
							<td>${x.itemName}</td>
							<td class="ui right aligned">${Intl.NumberFormat().format(x.itemPrice)}</td>
							<td class="ui right aligned">${x.itemQty}</td>
							<td class="ui right aligned">${Intl.NumberFormat().format(amount)}</td>
						</tr>`;
					i++;
				});

				html += `<tr>
						<td colspan="4" class="ui right aligned"><strong>Total</strong></td>
						<td class="ui right aligned"><strong>${Intl.NumberFormat().format(total)}</strong></td>
					</tr>`;

				html += `<tr>
						<td colspan="4" class="ui right aligned"><strong>Discount</strong></td>
						<td class="ui right aligned"><strong>${Intl.NumberFormat().format(dis)}</strong></td>
					</tr>`;

				html += `<tr>
						<td class="ui right aligned" colspan="4"><strong>Grand Total</strong></td>
						<td class="ui right aligned"><strong>${Intl.NumberFormat().format(total - dis)}</strong></td>
					</tr>`;

				$('#invDetailHead').html('Invoice Detail ( '+invId+' )');
				$('#invDate').html(invDate);
				$('#invDetailBody').html(html);
				$('#invDetail').modal().modal('show');
			}
		});
	}

	let itemCheck = function(itemId) {
		$.ajax({
			url: base_url() + 'ignite/getItem',
			type: 'POST',
			crossDomain: 'TRUE',
			data: {
				itemId : itemId.value
			},
			success: function(res){
				console.log(res);
				var obj = $.parseJSON(res);
				$('#dQty').attr('data-qty', parseInt(obj.balance));
				if(obj.imgPath == ""){
					var path = 'assets/imgs/preview.png';
				}else{
					path = obj.imgPath;
				}
				var html = `<table class="ui purple striped table">
					<tr>
						<td rowspan="6">
							<img src=" ${path} " class="ui image centered rounded" style="width: 300px" />
						</td>
						<td>Item Name :</td>
						<td>${obj.itemName}</td>
					</tr>
					<tr>
						<td>Item Code</td>
						<td>${obj.itemCode}</td>
					</tr>
					<tr>
						<td>Model Number</td>
						<td>${obj.model}</td>
					</tr>
					<tr>
						<td>Sell Price</td>
						<td>${obj.sellPrice}</td>
					</tr>
					<tr>
						<td>Purchase Price</td>
						<td>${obj.purchasePrice}</td>
					</tr>
					<tr>
						<td>Remaining Balance</td>
						<td>${obj.balance}</td>
					</tr>
				</table>`;

				$('.itemDetail').html(html);
			}
		});
	}

	let checkBalance = function(input) {
		let balance = Number($('#dQty').attr('data-qty'));
		let qty = Number(input.value);
		if(qty > balance){
			$.alert({
				title: 'Alert !',
				content: 'Your Remaining Balance is ' + balance + ' !',
				backgroundDismiss: false,
				columnClass: "custom-confirm-box",
			});

			input.value = balance;
			input.focus();
		}
	}

	let confirmDelivered = function(id) {
		$.confirm({
			animation: 'right',
			animationBounce: 1.5,
    		closeAnimation: 'scale',
			theme: "Modern",
			type: "orange",
			icon: "ui icon",
			useBootstrap: false,
			boxWidth: '30%',
			title: "<i class='ui icon shipping fast yellow'></i> Cash on Delivery",
			backgroundDismiss: false,
			columnClass: "custom-confirm-box",
			content: "This action can not be undone, the data you selected will be changed permanently.",
			buttons: {
				Receive_Payment: {
					btnClass: "ui button green",
					action: function() {
						$(location).attr("href", 'update-delivery/' + id);
					}
				},
				Cancel: {
					btnClass: "ui button orange",
					action: function() {
					//
					}
				}
			}
		});
	}

	let allPurchaseActive = function(vrId) {
		$.confirm({
			animation: 'right',
			animationBounce: 1.5,
    		closeAnimation: 'scale',
			theme: "Modern",
			type: "orange",
			icon: "ui icon",
			useBootstrap: false,
			boxWidth: '30%',
			title: "<i class='ui icon shipping fast yellow'></i> Purchase Arrives",
			backgroundDismiss: false,
			columnClass: "custom-confirm-box",
			content: "Pleas confirm, All purchase have been arrived.",
			buttons: {
				Arrived: {
					btnClass: "ui button green",
					action: function() {
						$(location).attr("href", 'set-all-purchase/' + vrId);
					}
				},
				Cancel: {
					btnClass: "ui button orange",
					action: function() {
					//
					}
				}
			}
		});
	}

	let activatePurchase = function(pId, itemName) {
		$.confirm({
			animation: 'right',
			animationBounce: 1.5,
    		closeAnimation: 'scale',
			theme: "Modern",
			type: "orange",
			icon: "ui icon",
			useBootstrap: false,
			boxWidth: '30%',
			title: "<i class='ui icon shipping fast yellow'></i> Purchase Arrives",
			backgroundDismiss: false,
			columnClass: "custom-confirm-box",
			content: "Pleas confirm, this purchase <strong class='text-red'>'"+ itemName +"'</strong> have been arrived.",
			buttons: {
				Arrived: {
					btnClass: "ui button green",
					action: function() {
						$(location).attr("href", 'set-purchase/' + pId);
					}
				},
				Cancel: {
					btnClass: "ui button orange",
					action: function() {
					//
					}
				}
			}
		});
	}

	let paymentReceived = function(invId) {
		$.confirm({
			animation: 'right',
			animationBounce: 1.5,
    		closeAnimation: 'scale',
			theme: "Modern",
			type: "orange",
			icon: "ui icon",
			useBootstrap: false,
			boxWidth: '30%',
			title: "<i class='ui icon hourglass outline yellow'></i> Received Payment",
			backgroundDismiss: false,
			columnClass: "custom-confirm-box",
			content: "This action can not be undone, the data you selected will be changed permanently.",
			buttons: {
				Receive_Payment: {
					btnClass: "ui button green",
					action: function() {
						$(location).attr("href", 'update-payment/' + invId);
					}
				},
				Cancel: {
					btnClass: "ui button orange",
					action: function() {
					//
					}
				}
			}
		});
	}

	let paymentRefund = function(e, inv) {
		if(e.keyCode == 13){
			let total = $('#gTotal').html();
			total = parseInt(total.replace(',',''));
			console.log(total);

			if(e.target.value == ''){
				$.alert({
				    title: 'Invalid Input',
				    content: 'Payment should not be NULL !',
				    backgroundDismiss: false,
					columnClass: "custom-confirm-box",
					animation: 'top',
		    		closeAnimation: 'opacity',
		    		animationBounce: 1.5,
					theme: "modern",
					type: "red",
					icon: "ui icon",
					useBootstrap: false,
					boxWidth: '30%',
				});
			}
				else if(e.target.value < total){
					$.alert({
					    title: 'Invalid Input',
					    content: 'Payment should not be less than Total Amount !',
					    backgroundDismiss: false,
						columnClass: "custom-confirm-box",
						animation: 'top',
			    		closeAnimation: 'opacity',
			    		animationBounce: 1.5,
						theme: "modern",
						type: "red",
						icon: "ui icon",
						useBootstrap: false,
						boxWidth: '30%',
					});
					$('#payment').focus().select();
				}
					else{
						let payment = e.target.value;
						$.ajax({
							url: base_url() + 'payment-refund',
							type: 'POST',
							crossDomain: 'TRUE',
							data: {
								'payment' : payment,
								'invId' : inv
							},
							success: function(res){
								location.href = 'preview/' + inv;
							}
						});
					}
		}
	}

	let payModalOpen = function(cId) {
		$("#payCrd").modal({closable: false}).modal('show');
		$("#paymentBtn").attr('onclick', 'igniteAjax.addPayment('+cId+')');
	}

	let paymentAdd = function(cId) {
		var payAmt = $("#payAmt").val();
		var remark = $("#payRemark").val();
		if(payAmt == "") {
			$.alert({
			    title: 'Invalid Input',
			    content: 'Payment should not be Null !',
			    backgroundDismiss: false,
				columnClass: "custom-confirm-box",
				animation: 'top',
	    		closeAnimation: 'opacity',
	    		animationBounce: 1.5,
				theme: "modern",
				type: "red",
				icon: "ui icon",
				useBootstrap: false,
				boxWidth: '30%',
			});

			$("#payAmt").focus();
		}
			else{
				
				$.ajax({
					url: base_url() + 'pay-credit',
					type: 'POST',
					crossDomain: 'TRUE',
					data: {
						'customerId' : cId,
						'payAmt' : payAmt,
						'remark' : remark
					},
					success: function(res){
						$("#payCrd").modal('hide');
						// console.log(res);
						location.href = 'customers';
					}
				});
			}

	}

	return {
		init: function (){
			thisPath();
		},
		detailInv: function (invId){
			invDetail(invId);
		},
		checkItem: function (itemId){
			itemCheck(itemId);
		},
		balanceCheck: function(input){
			checkBalance(input);
		},
		delivered: function(id){
			confirmDelivered(id);
		},
		setAllPurchaseActive: function(vrId) {
			allPurchaseActive(vrId);
		},
		setPurchaseActive: function(pId, itemName) {
			activatePurchase(pId, itemName);
		},
		receivePayment: function(invId) {
			paymentReceived(invId)
		},
		refundPayment: function(e, inv) {
			paymentRefund(e, inv);
		},
		payModal: function(cId) {
			payModalOpen(cId);
		},
		addPayment: function(cId) {
			paymentAdd(cId);
		}
	}
}();
// 

let damageItem = (function () {

	let thisPath = function(){
		let currentPath = window.location.pathname;
		let arr = currentPath.split('/');
		let itemId = arr[arr.length - 2];
		if (currentPath.indexOf('POSv2/modify-damage') > 0) {
			return true;
		} else {
			return false;
		}
	};

	function itemDamage() {
		if(thisPath()){
			let itemId = document.getElementById("dmgItem");
			igniteAjax.checkItem(itemId);
		}
	}

	function init(){
		itemDamage();
	}

	return {
		init: init
	};
})();

var cropImage = (function() {
	function crop(){
		$image_crop = $('#img_prev').croppie({
                enableExif: true,
                viewport: {
                    width: 220,
                    height: 220,
                    type: 'square' // square
                },
                boundary: {
                    width: 320,
                    height: 320
                }
            });

            $('#img-crop').on('change', function () {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function () {
                        
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('.modal.crop').modal({closable: false}).modal('show');
            });

            $('.crop_my_image').click(function (event) {
                $image_crop.croppie('result', {
                    type: 'blob',
                    size: 'viewport'
                }).then(function (response) {
                	let reader = new FileReader();
					reader.readAsDataURL(response); // converts the blob to base64 and calls onload
					reader.onload = function() {
						var data = reader.result;
                		console.log(data);
                		html = '<img src="' + data + '" class="ui centered image rounded" />';
                    	$('.preview').html(html);
					}

                    $('.modal.crop').modal('hide');
                    // $.ajax({
                    //     type: 'POST',
                    //     url: "ignite/uploadImage",
                    //     data: {
                    //         "image": response
                    //     },
                    //     success: function (data) {
                    //         console.log(data);
                    //         $('.modal.crop').modal('hide');
                    //         $('#imgpth').val(data);
                    //         html = '<img src="' + data + '" class="ui centered image rounded" />';
                    //         $('.preview').html(html);
                    //     }
                    // })
                });
            });
        
	}

	function init() {
		crop();
	}

	return {
		init: init
	};
})();

let chartJs = (function() {

	let thisPath = function(){
		let currentPath = window.location.pathname;
		let arr = currentPath.split('/');
		let itemId = arr[arr.length - 2];
		if (currentPath.indexOf('POSv2/reports') > 0) {
			return true;
		} else {
			return false;
		}
	};

	function getChart() {
		if(thisPath()){

			// Daily Report Charts
			var dt = new Date();
			$.ajax({
				url: base_url() + 'ignite/getDailyChart',
				type: 'POST',
				crossDomain: 'TRUE',
				data:{
					month: $("#dailyChart").data('month'),
					year: dt.getFullYear()
				},
				success: function(res){
					var obj = JSON.parse(res);
					var xValues = obj.days;
					var yValues = obj.datas;

					new Chart("dailyChart", {
					  type: "bar",
					  data: {
					    labels: xValues,
					    datasets: [
					    	{
					      		backgroundColor: 'lightgreen',
					      		data: obj.datas
					    	},
					    	{
					      		backgroundColor: 'slategrey',
					      		data: obj.gross
					    	},
					    	{
					    		backgroundColor: 'orange',
					    		data: obj.net
					    	}
					    ]
					  },
					  options: {
					    legend: {display: false},
					    scales: {
					      yAxes: [{
					        ticks: {
					        	stepSize: 5000,
					          	beginAtZero: true
					        }
					      }],
					    }
					  }
					});
				}
			});

			// Monthly Report Charts
			var dt = new Date();
			$.ajax({
				url: base_url() + 'ignite/getMonthlyChart',
				type: 'GET',
				crossDomain: 'TRUE',
				data:{
					month: dt.getMonth(),
					year: dt.getFullYear()
				},
				success: function(res){
					var obj = JSON.parse(res);
					var xValues = obj.months;
					var barColors = "lightgreen";

					new Chart("mChart", {
					  type: "line",
					  data: {
					    labels: xValues,
					    datasets: [
						    {
						      	borderColor: barColors,
						      	data: obj.datas,
						      	fill: false
						    },
						    {
						    	borderColor: "slategrey",
						      	data: obj.gross,
						      	fill: false
						    },
						    {
					    		borderColor: 'orange',
					    		data: obj.net,
					    		fill: false
					    	}
					    ]
					  },
					  options: {
					    legend: {display: false},
					    scales: {
					      yAxes: [{
					        ticks: {
					        	stepSize : 10000,
					          	beginAtZero: true
					        }
					      }],
					    }
					  }
					});
				}
			});

			// Yearly Report Charts
			var dt = new Date();
			$.ajax({
				url: base_url() + 'ignite/getYearlyChart',
				type: 'GET',
				crossDomain: 'TRUE',
				data:{
					month: dt.getMonth(),
					year: dt.getFullYear()
				},
				success: function(res){
					var obj = JSON.parse(res);
					var xValues = obj.years;
					var yValues = obj.data;
					var barColors = "blueviolet";

					new Chart("yChart", {
					  type: "line",
					  data: {
					    labels: xValues,
					    datasets: [
						    { 
						    	borderColor: "lightgreen",
						      	data: obj.data,
						      	fill: false
						  	},
						  	{ 
						    	borderColor: "slategrey",
						      	data: obj.gross,
						      	fill: false
						  	},
						  	{
					    		borderColor: "orange",
					    		data: obj.net,
					    		fill: false
					    	}
					    ]
					  },
					  options: {
					    legend: {display: false},
					    scales: {
					      yAxes: [{
					        ticks: {
					        	stepSize : 50000,
					          	beginAtZero: true
					        }
					      }],
					    }
					  }
					});
				}
			});
		}
	}

	function init(){
		getChart();
	}

	return {
		init: init
	};
})();

var referAjax = function() {
	// init values
	let newOrders = [];
	let orderObject = {};
	let temp = [];
	let tempNew = [];

	// Default
	let byCustomer = false;
	let saleType = 'R';
	let paymentType = 'CSH';
	let customerId = 0;
	let deposit = 0;
	

	return {
		init: function() {
			thisPath();
		}
	}
}();

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