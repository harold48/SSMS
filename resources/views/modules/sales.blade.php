@extends('layout.app')

@section('content')

<div class="content">
    <div class="row" style="margin-top: 8px;">
        <div class="col-md-6">
        	<h2>Sales</h2><br>

        	<table id="salesTable" class="table">
        		<thead>
		            <tr>
		                <th>Product</th>
		                <th>Qty</th>
		                <th>Price</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<tr>
		        		<td>Total</td>
		        		<td>0</td>
		        		<td>0</td>
		        	</tr>
		        </tbody>
        	</table>
        	<button class="btn btn-outline-secondary pull-right" onclick="confirmSales()">Confirm</button>
        </div>
        <div class="col-md-6">
        	<h2>List of Available Products</h2>

			<input type="text" id="nptSearch" onkeyup="displayTable(this.value,null,$('#stocksTable'))" class="form form-input float-right" placeholder="Search..."><br>
        	<table id="stocksTable" class="table">
        		<thead>
		            <tr>
		                <th>Product</th>
		                <th>Stocks</th>
		                <th>Price</th>
		                <th></th>
		            </tr>
		        </thead>
		        <tbody></tbody>
        	</table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
    	$listOfSales = [];

        $table = $('#stocksTable');

        $listOfStocks = getListOfStocksForSale();
        do {

        } while ($listOfStocks===undefined);

        displayTable($('#nptSearch').val(),$listOfStocks,$table);
    });
</script>

@endsection