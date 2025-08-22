<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ProjectWall - INV-{{$_getInvoiceData->invoice_number}}</title>
   
</head>
<style>
body {
    font-family: 'sans-serif';
}

.text-color {
    color: #fc544b;
}

.pdf-css {
    font-family: "DejaVu Sans", sans-serif;
    font-size: 14px;
    font-weight: normal;
}

.main-table {
    vertical-align: top;
    width: 100%;
}

.invoice-customer-detail {
    vertical-align: top;
}

.invoice-date-table {
    width: 100%;
    text-align: right;
}

.invoice-date-table table {

}

.invoice-number, .app-logo {
    width: 50%;
}

.text-right {
    text-align: right;
}

.m-0 {
    margin: 0;
}

.font-weight-bold {
    font-weight: bold;
}

.text-uppercase {
    text-transform: uppercase;
}

.thead-light {
    background: #f8f9fa;
}

.invoice-sales-items th, .invoice-sales-items td {
    padding: 5px 10px;
    border: 1px solid #dee2e6;
    min-width: 20vh;
}

.table {
    border-collapse: collapse;
}

.mb-2 {
    margin-bottom: 16px;
}

.text-muted {
    color: #6c757d;
}

.text-center {
    text-align: center;
}

.invoice-footer-table {
    width: 100%;
}

.invoice-footer-table td {
    border-bottom: 1px solid #dee2e6;
    padding: 5px 0;
}

.mt-2 {
    margin-top: 16px;
}

.payments-table {
    width: 100%;
}

.payments-table thead {
    border-bottom: 1px solid #dee2e6;
}

.payments-table td {
    padding: 5px 10px;
}

.font-weight-normal {
    font-weight: 400 !important;
}

.badge {
    display: inline-block;
    padding: .25em .4em;
    font-size: 75%;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.badge-secondary {
    color: #fff;
    background-color: #6c757d;
}

.table-data {
    width: 300px;
    word-break: break-all;
    word-wrap: break-word;
}

.invoice-sales-items .rate {
    width: 100px;
}

.invoice-sales-items .total-amount {
    width: 110px;
}
{"mode":"full","isActive":false}
</style>
<body>

<table class="main-table">
    <tr>
		<td class="app-logo">
			<p>
            <img src="{{ asset($_getBrand->logo) }}" class="img-fluid" width="60%">
            </p>
        </td>
        <td class="text-right invoice-number">
            <h2 class="text-uppercase">Invoices</h2>
            <p><em>Invoice No. INV-{{$_getInvoiceData->invoice_number}}</em></p>
            <p><em>Invoice Date. {{ $_getInvoiceData->invoice_date }}</em></p>
            <p><em>Invoice Due-Date. {{ $_getInvoiceData->created_at->format('d M, y h:i a') }}</em></p>
        </td>
    </tr>
    <tr>
        <td class="invoice-customer-detail">
            <p class="font-weight-bold m-0">{!! $_getBrand->address !!}</p>
            <br><br>
            <h2>Bill From: </h2>
            <p><strong>Name: </strong> <em>{{ $_getInvoiceData->sale->name }}</em></p>
            <p><strong>Email: </strong> <em>{{ $_getInvoiceData->sale->email }}</em></p>
            <p><strong>Invoice Contact: </strong> <em>{{ $_getInvoiceData->sale->contact }}</em></p>
        </td>
        <td class="text-right mt-2">
            <br><br><br>
            <h2>Bill To: </h2>
            <p><strong>Name: </strong> <em>{{ $_getInvoiceData->name }}</em></p>
            <p><strong>Email: </strong> <em>{{ $_getInvoiceData->email }}</em></p>
            <p><strong>Contact: </strong> <em>{{ $_getInvoiceData->contact }}</em></p>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table width="100%" class="table table-bordered invoice-sales-items mt-2">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Service</th>
                    <th scope="col">Payment Type</th>
                    <th scope="col" class="text-right total-amount">Amount( <span class="pdf-css">{{$_getInvoiceData->currency_show->sign}}</span>)</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>
                            @if($_getInvoiceData->package == 0)
                            {{ $_getInvoiceData->custom_package }}
                            @else
                            {{ $_getInvoiceData->package }}
                            @endif
                        </td>
                        <td>{{$_getBrand->name}}</td>
                        <td>
                            @php
                            $service_list = explode(',', $_getInvoiceData->service);
                            @endphp
                            @for($i = 0; $i < count($service_list); $i++)
                            <span class="btn btn-info btn-sm">{{ $_getInvoiceData->services($service_list[$i])->name }}</span>
                            @endfor
                            
                        </td>
                        <td>{{ $_getInvoiceData->payment_type_show() }}</td>
                        <td class="text-right">
                            <span class="pdf-css">{{$_getInvoiceData->currency_show->sign}}</span>
                            {{ $_getInvoiceData->amount }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="float-right">
            <table class="table text-right invoice-footer-table mt-2">
                <tbody>
                <tr>
                    <td>
                        Sub Total: 
                    </td>
                    <td>
                        <span class="pdf-css">{{$_getInvoiceData->currency_show->sign}}</span>
                        {{ $_getInvoiceData->amount }}
                    </td>
                </tr>
                <tr>
                    <td>Grand Total: </td>
                    <td>
                        <span class="pdf-css">{{$_getInvoiceData->currency_show->sign}}</span>
                        {{ $_getInvoiceData->amount }}
                    </td>
                </tr>
				<!-- Bagx Bunny  End -->
                </tbody>
            </table>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<div class="container">
	<div class="row">	
		<p>Note: <span style="color:red; margin-top:20px">This is a computer-generated invoice. No signature is required.</span></p>
	</div>
</div>
</body>
</html>
