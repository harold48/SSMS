@extends('layout.app')

@section('content')

<div class="content">
    <div class="row" style="margin-top: 8px;">
        <div class="col-md-12">
        	<h2>Stocks</h2>

			<input type="text" id="nptSearch" onkeyup="displayTable(this.value,null,$('#stocksTable'))" class="form form-input float-right" placeholder="Search..."><br>
        	<table id="stocksTable" class="table">
        		<thead>
		            <tr>
		                <th>Product</th>
		                <th>Stock</th>
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
        $table = $('#stocksTable');

        let items = getListOfStocks();
        do {

        } while (items===undefined);

        displayTable($('#nptSearch').val(),items,$table);
    });
</script>

@endsection