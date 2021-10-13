$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showLoadingScreen() {
	$("div.spanner").addClass("show");
 	$("div.overlay").addClass("show");
}

function hideLoadingScreen() {
	$("div.spanner").removeClass("show");
 	$("div.overlay").removeClass("show");
}

function logout() {
	bootbox.alert({
        message: ("Authetication not required in this trial project. I'm just here for the design :)"),
        size: 'large',
        backdrop: true,
        centerVertical: true,
    });
}

function displayTable(searchItem,items,table) {

    searchItem = searchItem.toLowerCase().trim() || ""; // check if has search item or not
    table = table || $('<table />').append($('<thead />')).append($('<body />')); // get the table, inserting if none
    
    if (items) { // if has items, will replace and populate the table contents
	    items = items.sort((a,b)=>Date.parse(a.createdAt)-Date.parse(b.createdAt));
	    items = items.filter((a)=>searchItem=="" || a.name.toLowerCase().includes(searchItem) || a.name.toLowerCase().includes(searchItem));
	    table.children('tbody').html('');
	    items.forEach((a)=>{
	        let $tr = $("<tr />");
	        a.td.forEach((b)=>{
	            $tr.append("<td>" + b + "</td>");
	        });
	        table.children('tbody').append($tr);
	    });
	} else { // if no item specified, use existing table contents
		Array.from(table.children('tbody').children('tr')).forEach((a)=>{ // loop through table contents
			let toSearch = "";
			Array.from($(a).children('td')).forEach((b)=>{ // remove tags from each <td>, then concatenate every <td> in <tr>
				toSearch = toSearch + $(b).html().replace(/<\/?[^>]+(>|$)/g, "") + " ";
			});
			if (toSearch.toLowerCase().includes(searchItem.toLowerCase())) { // check if data content has search item
				$(a).css('display','');
			} else {
				$(a).css('display','none');
			}
		});
	}
	return table;
}

function getListOfStocks() {
	showLoadingScreen();
	let val = null;
	$.ajax({
		method: 'GET',
		url: 'api/stockslist',
		async: false,
	})
	.done((d,s,j) => {
		if (d.error) {
			bootbox.alert({
	            message: (d.message?d.message:(d.msg?d.msg:"Unhandled error occurred. Please contact the developer.")),
	            size: 'large',
	            backdrop: true,
	            centerVertical: true,
	        });
	        val = [];
		} else {
			val = [];
			d.forEach((a)=>{ // loop through all fetched data
				val.push({
					originalData: a,
					td: [ // create array of <td> to be inserted in the table
						a.name,
						a.stocks,
						a.price,
						'<button class="btn btn-sm btn-outline-secondary" onclick="changePrice(' + a.id + ',' + a.price + ',this)">Change Price</button> <button class="btn btn-sm btn-outline-secondary" onclick="changeStock(' + a.id + ',' + a.stocks + ',this)">Change Stock</button>',
					]
				});
			});
		}
	})
	.fail((j,s,e) => {
		bootbox.alert({
            message: e,
            size: 'large',
            backdrop: true,
            centerVertical: true,
        });
        val = [];
	})
	.always((a,b,c) => {
		hideLoadingScreen();
	});

	return val;
}

function getListOfStocksForSale() {
	showLoadingScreen();
	let val = null;
	$.ajax({
		method: 'GET',
		url: 'api/stockslist',
		async: false,
	})
	.done((d,s,j) => {
		if (d.error) {
			bootbox.alert({
	            message: (d.message?d.message:(d.msg?d.msg:"Unhandled error occurred. Please contact the developer.")),
	            size: 'large',
	            backdrop: true,
	            centerVertical: true,
	        });
	        val = [];
		} else {
			val = [];
			d.forEach((a)=>{ // loop through all fetched data
				if (a.stocks>0) {
					val.push({
						originalData: a,
						td: [ // create array of <td> to be inserted in the table
							a.name,
							a.stocks,
							a.price,
							'<button class="btn btn-sm btn-outline-secondary" onclick="addToCart({id:' + a.id + ',stocks:' + a.stocks + ',price:' + a.price + '},this)">Add to Cart</button>',
						]
					});
				}
			});
		}
	})
	.fail((j,s,e) => {
		bootbox.alert({
            message: e,
            size: 'large',
            backdrop: true,
            centerVertical: true,
        });
        val = [];
	})
	.always((a,b,c) => {
		hideLoadingScreen();
	});

	return val;
}

function changeStock(id, currentStock, obj) {
	$(obj).attr('disabled',true);

	// create input for the new stocks
	$input = $('<input type="text" onkeypress="return event.charCode>=48 && event.charCode<=57" />');

	// create modal and show it
	$('<div />').html('New Stocks:<br>').append($input).append('<br><br>').append('Current Stocks: ' + currentStock).dialog({
		modal: true,
		title: 'Update Stocks',
		height: 240,
		width: 272,
		beforeClose: ( event, ui )=>($(obj).attr('disabled',false)),
		buttons: {
			'Update': function () {
				var newStocks = $input.val().trim();
				if (newStocks=="" || newStocks < 0) {
					bootbox.alert({
			            message: ("Invalid new stocks input!"),
			            size: 'small',
			            backdrop: true,
			            centerVertical: true,
			        });
				} else {
					showLoadingScreen();
					$.ajax({
						method: 'POST',
						url: 'api/updateStocks',
						data: {
							id: id,
							stocks: newStocks,
							"_token": $('meta[name="csrf-token"]').attr('content')
						}
					})
					.done((d,s,j) => {
						if (d.error) {
							bootbox.alert({
					            message: (d.message?d.message:(d.msg?d.msg:"Unhandled error occurred. Please contact the developer.")),
					            size: 'large',
					            backdrop: true,
					            centerVertical: true,
					        });
						} else {
							$($(obj).parent().parent().children('td')[1]).html(newStocks);
							toastr.info('Stocks updated!');
						}
					})
					.fail((j,s,e) => {
						bootbox.alert({
				            message: e,
				            size: 'large',
				            backdrop: true,
				            centerVertical: true,
				        });
					})
					.always((a,b,c) => {
						$(this).dialog('close');
						hideLoadingScreen();
					});
				}
			},
			'Cancel': function () {
				$(obj).attr('disabled',false);
				$(this).dialog('close');
			}
		}
	});
}

function changePrice(id, currentPrice, obj) {
	$(obj).attr('disabled',true);

	// create input for the new stocks
	$input = $('<input type="text" onkeypress="return event.charCode>=48 && event.charCode<=57" />');

	// create modal and show it
	$('<div />').html('New Price:<br>').append($input).append('<br><br>').append('Current Price: ' + currentPrice).dialog({
		modal: true,
		title: 'Update Price',
		height: 240,
		width: 272,
		beforeClose: ( event, ui )=>($(obj).attr('disabled',false)),
		buttons: {
			'Update': function () {
				var newPrice = $input.val().trim();
				if (newPrice=="" || newPrice < 0) {
					bootbox.alert({
			            message: ("Invalid new price input!"),
			            size: 'small',
			            backdrop: true,
			            centerVertical: true,
			        });
				} else {
					showLoadingScreen();
					$.ajax({
						method: 'POST',
						url: 'api/updatePrice',
						data: {
							id: id,
							price: newPrice,
							"_token": $('meta[name="csrf-token"]').attr('content')
						}
					})
					.done((d,s,j) => {
						if (d.error) {
							bootbox.alert({
					            message: (d.message?d.message:(d.msg?d.msg:"Unhandled error occurred. Please contact the developer.")),
					            size: 'large',
					            backdrop: true,
					            centerVertical: true,
					        });
						} else {
							$($(obj).parent().parent().children('td')[2]).html(newPrice);
							toastr.info('Price updated!');
						}
					})
					.fail((j,s,e) => {
						bootbox.alert({
				            message: e,
				            size: 'large',
				            backdrop: true,
				            centerVertical: true,
				        });
					})
					.always((a,b,c) => {
						$(this).dialog('close');
						hideLoadingScreen();
					});
				}
			},
			'Cancel': function () {
				$(obj).attr('disabled',false);
				$(this).dialog('close');
			}
		}
	});
}

function refreshSalesTable(listOfSales) {
	$('#salesTable').children('tbody').html('');
	totalQty = 0;
	totalPrice = 0;
	listOfSales.forEach((v,k)=>{
		$stock = $listOfStocks.find((o)=>(o.originalData.id==k));
		let $tr = $('<tr />');
		$tr.append($('<td />').html($stock.originalData.name || '???'));
		$btnPlus = $('<button class="btn btn-sm btn-outline-secondary" onclick="adjustSales('+k+',1)">+</button>');
		$btnMinus = $('<button class="btn btn-sm btn-outline-secondary" onclick="adjustSales('+k+',-1)">-</button>');
		$td = $('<td />');
		$td.append($btnMinus);
		$td.append(' ' +v+ ' ');
		$td.append($btnPlus);
		$tr.append($td);
		$tr.append($('<td />').html(v*($stock.originalData.price || 0)));
		$('#salesTable').children('tbody').append($tr);
		totalPrice += v*($stock.originalData.price || 0);
		totalQty += v;
	});
	let $tr = $('<tr />');
	$tr.append($('<td />').html('<strong>Total</strong>'));
	$tr.append($('<td />').html(totalQty));
	$tr.append($('<td />').html(totalPrice));
	$('#salesTable').children('tbody').append($tr);
}

function addToCart(data,obj) {
	$stock = $listOfStocks.find((o)=>(o.originalData.id==data.id));
		adjustSales(data.id,1);
}

function confirmSales() {
	if ($listOfSales.length<=0) {
		bootbox.alert({
            message: "Your cart is empty!",
            size: 'large',
            backdrop: true,
            centerVertical: true,
        });
	} else {
		showLoadingScreen();
		$.ajax({
			method: 'POST',
			url: 'api/transactSales',
			data: {
				data: $listOfSales,
				"_token": $('meta[name="csrf-token"]').attr('content')
			}
		})
		.done((d,s,j) => {
			if (d.error) {
				bootbox.alert({
		            message: (d.message?d.message:(d.msg?d.msg:"Unhandled error occurred. Please contact the developer.")),
		            size: 'large',
		            backdrop: true,
		            centerVertical: true,
		        });
			} else {
				totalPrice = 0;
				totalQty = 0;
				$div = $('<div />').html('<strong>Order Number: ' + (d.or||0) + '</strong><br><hr>');
				d.boughtStocks.forEach((a,i)=>{
					totalQty += a.qty;
					totalPrice += a.price;
					item = '<strong>' + a.name.slice(0,30).padEnd(30," ") + "</strong>  price: " + a.price + '<br>';
					item += 'x' + a.qty + ' @ ' + (a.price/a.qty) + ' e.a.<br>';
					item += 'remaining stock: ' + d.allStocks[i].stocks + '<br>';
					$div.append(item);
				});
				$div.append('<hr>Total Price: ' + totalPrice);
				$div.dialog({
					modal: true,
					title: 'Sales Summary',
					height: 480,
					width: 272,
					beforeClose: ( event, ui )=>(location.reload()),
				});
			}
		})
		.fail((j,s,e) => {
			bootbox.alert({
	            message: e,
	            size: 'large',
	            backdrop: true,
	            centerVertical: true,
	        });
		})
		.always((a,b,c) => {
			hideLoadingScreen();
		});
	}
}

function adjustSales(id,val) {
	$stock = $listOfStocks.find((o)=>(o.originalData.id==id));
	if ($stock.originalData.stocks-val >= 0) {
		$listOfSales[id] = ($listOfSales[id] || 0) + val;
		$stock.originalData.stocks -= val;
		$stock.td[1] -= val;
		if ($listOfSales[id] <= 0) {
			delete $listOfSales[id];
		}
		displayTable($('#nptSearch').val(),$listOfStocks,$('#stocksTable'));
		refreshSalesTable($listOfSales);
	} else {
		bootbox.alert({
            message: ("Current stock is 0!"),
            size: 'small',
            backdrop: true,
            centerVertical: true,
        });
	}
}