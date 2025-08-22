@extends('layouts.app-admin')
@section('title', 'Invoices')
@section('content')
<style>
    .col{
        padding-right: 10px;
    }
    
    #flashing {
      text-shadow: none;
      color: #fff;
      font-size: 8px;
      opacity: 1;
      position: relative;
      cursor: pointer;
      animation: flash 1.5s ease-in-out infinite alternate;
    }
    
    @keyframes flash {
      0% {
        opacity: 0.5;
        color: #fff;
        text-shadow: none;
      }
      10% {
        opacity: 1;
        color: #fff;
        text-shadow: 0 0 10px #dd2121, 0 0 15px #dd2121, 0 0 20px #a01414, 0 0 25px #a01414;
      }
      55% {
        opacity: 0.5;
        color: #fff;
        text-shadow: none;
      }
      85% {
        opacity: 1;
        color: #fff;
        text-shadow: 0 0 10px #dd2121, 0 0 15px #dd2121, 0 0 20px #a01414, 0 0 25px #a01414;
      }
    }
</style>
<style>
    .table th, .table td{
        padding: 0.55rem;
    }
    .card{
      box-shadow:2px 2px 20px rgba(0,0,0,0.3); border:none; margin-bottom:30px;
    }
    .bg-gradient-dark {
      background-image: linear-gradient(195deg,#42424a,#191919);
    }
    .border-radius-lg {
      border-radius: .5rem;
    }
    .card-header:first-child {
      border-radius: var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius) 0 0;
    }
    .ps-3 {
      padding-left: 1rem !important;
    }
    .align-middle {
      vertical-align: middle !important;
    }
    .btn-glow {
      border-radius: 0;
      border-bottom: 3px solid;
      overflow: hidden;
      position: relative;
    }
    .btn-glow:before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      border-top: 33px solid rgba(255, 255, 255, 0.4);
      border-right: 40px solid transparent;
      -webkit-transition: all 200ms ease;
      -moz-transition: all 200ms ease;
      transition: all 200ms ease;
      -moz-transform: scale(0.9999);
      /* FF rough edge fix */
    }
    .btn-lg:before {
      border-top-width: 45px;
      border-right-width: 60px;
    }
    .btn-sm:before {
      border-top-width: 30px;
      border-right-width: 35px;
    }
    .btn-xs:before {
      border-top-width: 20px;
      border-right-width: 26px;
    }
    .btn-link:before {
      display: none;
    }
    .btn-glow:hover:before {
      left: -100px;
    }
    .btn-default {
      border-color: #b5b5b5;
    }
    .btn-primary {
      border-color: #00294d;
    }
    .btn-info {
      border-color: #206376;
    }
    .btn-success {
      border-color: #2d662d;
    }
    .btn-warning {
      border-color: #a86404;
    }
    .btn-danger {
      border-color: #88201c;
    }
    .btn-inverse {
      border-color: black;
    }
    .btn-glow {
      border-radius: 250px;
    }
    .btn-primary {
      border-color: #00294d;
    }
    .btn-primary:hover {
      background-color: #0088ff !important;
    }
    
    /* lite-purple.min.css | https://projectwall.net/newglobal/css/lite-purple.min.css */
    
    .modal-content {
      /* background-color: #fff; */
      /* border-radius: 0.3rem; */
      background: #464646;
      border-radius: 1.3rem;
    }
    
    .table-bordered th, .table-bordered td {
      color: #fff;
    }
    
    /* Inline #15 | https://projectwall.net/manager/invoice?package=&invoice=&user=&status=1 */
    
    #detailsModalLabel {
      color: #fff;
    }
    
    /* Element | https://projectwall.net/manager/invoice?package=&invoice=&user=&status=1 */
    
    tr.p-3:nth-child(1) > th:nth-child(1) > b:nth-child(1) {
      color: #fff;
    }
</style>
<div class="breadcrumb">
    <h1 class="mr-2">Invoices</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Search Invoice</h6>
              </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.invoice') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 form-group mb-3">
                            <label for="package">Search Package</label>
                            <input type="text" class="form-control" id="package" name="package" value="{{ Request::get('package') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="invoice">Search Invoice Number</label>
                            <input type="text" class="form-control" id="invoice" name="invoice" value="{{ Request::get('invoice') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="customer">Search Customer Name</label>
                            <input type="text" class="form-control" id="customer" name="customer" value="{{ Request::get('customer') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="customer">Search Customer Email</label>
                            <input type="text" class="form-control" id="customer_email" name="customer_email" value="{{ Request::get('customer_email') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="agent">Search Agent Name</label>
                            <input type="text" class="form-control" id="agent" name="agent" value="{{ Request::get('agent') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="brand">Select Brand</label>
                            <select class="form-control select2" name="brand" id="brand">
                                <option value="0" {{ Request::get('brand') == 0 ? 'selected' : ''}} >Any</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ Request::get('brand') == $brand->id ? 'selected' : ''}} >{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="status">Select Status</label>
                            <select class="form-control select2" name="status" id="status">
                                <option value="0" {{ Request::get('status') == 0 ? 'selected' : ''}} >Any</option>
                                <option value="2" {{ Request::get('status') == 2 ? 'selected' : '' }}>Paid</option>
                                <option value="1" {{ Request::get('status') == 1 ? 'selected' : '' }}>Unpaid</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-glow btn-primary">Search Result</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Invoice Details <span> Total: {{ $data->total() }} <span></h6>
              </div>
            </div>
            
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 display" id="display">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">INV#</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Agent</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Merchant</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th> 
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $datas)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                        <div class="d-flex flex-column justify-content-center">
                                            <span class="btn btn-sm btn-dark">#{{ $datas->invoice_number }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                          {{ $datas->client->name }} <br> {{ $datas->client->last_name }}
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                          {{ $datas->client->email }}
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            @if($datas->sales_agent_id != 0)
                                                {{ $datas->sale->name }} {{ $datas->sale->last_name }}
                                            @else
                                                From Website
                                            @endif
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                        <button class="btn btn-sm btn-secondary">{{ $datas->brands->name }}</button>
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            {{ $datas->currency_show->sign }}{{ $datas->amount }}
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            {{ $datas->merchant->name }}
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            <span class="btn btn-{{ App\Models\Invoice::STATUS_COLOR[$datas->payment_status] }} btn-sm" style="display: flex;justify-content: center;font-size: 0.813rem;">
                                                {{ App\Models\Invoice::PAYMENT_STATUS[$datas->payment_status] }}
                                                @if($datas->payment_status == 1)
                                                <form method="post" action="{{ route('admin.invoice.paid', $datas->id) }}">
                                                    @csrf
                                                    <button type="submit" class="mark-paid btn btn-glow btn-danger p-0 ml-1" style="font-size: 12px;padding: 0 5px 0 5px !important;">Mark Payed</button>
                                                </form>
                                                @endif
                                            </span>
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            <button class="btn btn-sm btn-secondary mb-1">{{ date('g:i a', strtotime($datas->created_at)) }}</button>
                                            <button class="btn btn-sm btn-secondary">{{ date('d M, Y', strtotime($datas->created_at)) }}</button>
                                      </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                                      <div class="d-flex flex-column justify-content-center">
                                            <div>
                                                <a href="javascript:;" class="mb-2 btn btn-glow btn-primary btn-icon btn-sm mr-1 more-detail" data-id="{{ $datas->id }}">
                                                    <span class="ul-btn__icon"><i class="i-Memory-Card-2"></i></span>
                                                    <span class="ul-btn__text">Details</span>    
                                                    <span class="badge badge-danger" id="flashing">New</span>
                                                </a>
                                                <a href="{{ route('admin.link', $datas->id) }}" class="btn btn-glow btn-info btn-icon btn-sm">
                                                    <span class="ul-btn__icon"><i class="i-Eye-Visible"></i></span>
                                                    <span class="ul-btn__text">View</span>
                                                </a>
                                            </div>
                                      </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="ajax-loading"><img src="{{ asset('newglobal/images/loader.gif') }}" /></div>
                    <!-- {{ $data->appends(['package' => Request::get('package'), 'invoice' => Request::get('invoice'), 'customer' => Request::get('customer'), 'agent' => Request::get('agent'), 'status' => Request::get('status')])->links("pagination::bootstrap-4") }} -->
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="detailsModalLabel"></h4>
        <button type="button" class="btn-close btn btn-primary" data-bs-dismiss="modal" aria-label="Close" style="font-size: 22px;padding: 0;background: none;color: #000;border: none;"><i class="i-Close"></i></button>
      </div>
      <div class="modal-body">
        <table class="table-responsive table-bordered detail-body" style="padding: 0px 30px 0px 30px;border: none;">
            
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    var SITEURL = "{{ url('/') }}";
    var page = 2;
    load_more(page);
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 1) {
            page++;
            load_more(page); //load content   
        }
    });
    function load_more(page){
        $.ajax({
            url: SITEURL + "/admin/invoice?"+window.location.search.substr(1)+'&page='+ page,
            type: "get",
            datatype: "html",
            beforeSend: function(){
                $('.ajax-loading').show();
            }
        })
        .done(function(data){
            if(data.length == 0){
                $('.ajax-loading').html("No more records!");
                return;
            }
            $('.ajax-loading').hide();
            $("#display tbody").append(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
        });
    }
</script>

<script>

const CLIENT_PAYMENT_STATUS = {
    1: 'Unpaid',
    2: 'Paid',
    3: 'Partially Paid'
};

const STATUS_COLOR = {
    0: 'warning',
    1: 'danger',
    2: 'success',
    3: 'info',
    4: 'danger'
};

const PAYMENT_STATUS = {
    0: 'Drafted',
    1: 'Unpaid',
    2: 'Paid',
    3: 'Partially Paid',
    4: 'Cancelled'
};

$('.btn-close').on('click', function(){
    $('#detailsModal').modal('hide');
})
    $(document).on('click', '.more-detail', function(){
        var inv_id = $(this).data('id');
        var btn = $(this);
        btn.attr("disabled", true);
        btn.text("Loading...");
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            type:'POST',
            url: "{{ route('admin.invoice.details') }}",
            data: {id: inv_id},
            success:function(data) {
                let detailBody = $(".detail-body");
                detailBody.empty();
        
                $('#detailsModalLabel').text('Invoice#'+ data.invoice_number);
                
                var _package = (data.package == 0) ? data.custom_package : data.package;
                let paymentStatus = PAYMENT_STATUS[data.payment_status] || 'Unknown';
                let clientPaymentStatus = CLIENT_PAYMENT_STATUS[data.payment_status] || 'Unknown';
                let statusColor = STATUS_COLOR[data.payment_status] || 'default';
                if (data.payment_status == 1) {
                    markPaidForm = `
                        <form method="post" action="/manager/invoice/paid/${data.id}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="mark-paid btn btn-glow btn-danger p-0 ml-1" style="font-size: 12px;padding: 0 5px 0 5px !important;">Click</button>
                        </form>
                    `;
                }
                let createdAt = new Date(data.created_at);
                let formattedTime = createdAt.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
                let formattedDate = createdAt.toLocaleDateString('en-US', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                
                let actionButton = '';
                if (data.payment_status == 2) {
                    let clientUserExists = data.client && data.client.user ? true : false;
                    let btnClass = clientUserExists ? 'success' : 'primary';
                    let authStatus = clientUserExists ? 1 : 0;
                    let password = clientUserExists ? data.client.user.password : '';
                    let buttonText = clientUserExists ? 'Reset Pass' : 'Click Here';
        
                    actionButton = `
                        <a href="javascript:;" class="btn btn-${btnClass} btn-sm auth-create" 
                           data-id="${data.client ? data.client.id : ''}" 
                           data-auth="${authStatus}" 
                           data-password="${password}">${buttonText}</a>
                    `;
                } else {
                    actionButton = `<span class="btn btn-info btn-sm">Payment Pending</span>`;
                }
                
                let servicesHtml = '';

                if (data.service_names.length > 0) {
                    data.service_names.forEach(service => {
                        servicesHtml += `<span class="btn btn-info btn-sm mb-1">${service}</span> `;
                    });
                } else {
                    servicesHtml = `<span class="text-muted">No Services</span>`;
                }
                
                
                let tableContent = `
                    <tr class="p-3"><th class="col p-3 text-center"><b style="font-size: 18px;">Invoice Details</b></th><td></td></tr>
                    
                    <tr class="p-3"><th class="col p-3">Invoice#</th><td class="p-3">${data.invoice_number}</td></tr>
                    <tr class="p-3"><th class="col p-3">Name</th><td class="p-3">${data.name}</td></tr>
                    <tr class="p-3"><th class="col p-3">Email</th><td class="p-3">${data.email}</td></tr>
                    <tr class="p-3"><th class="col p-3">Amount</th><td class="p-3">${data.currency_show.sign}${data.amount}</td></tr>
                    <tr class="p-3"><th class="col p-3">Package Name</th><td class="p-3">${_package}</td></tr>
                    <tr class="p-3"><th class="col p-3">Services</th><td class="p-3">${servicesHtml}</td></tr>
                    <tr class="p-3"><th class="col p-3">Payment Status</th><td class="p-3">
                    <div style="text-align: center;" class="d-flex py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <span class="btn btn-${statusColor} btn-sm" style="display: flex;justify-content: center;font-size: 0.813rem;">
                            ${paymentStatus}
                            ${data.payment_status == "1" ? markPaidForm : ''}
                        </span>
                      </div>
                    </div>
                    </td></tr>
                    <tr class="p-3"><th class="col p-3">Created At</th><td class="p-3"><button class="btn btn-sm btn-secondary">${formattedTime}</button><button class="btn btn-sm btn-secondary">${formattedDate}</button></td></tr>
                    
                    
                    <tr class="p-3"><th class="col p-3 text-center"><b style="font-size: 18px;">Client Details</b></th><td></td></tr>
                    
                    <tr class="p-3"><th class="col p-3">Client Name</th><td class="p-3">${data.client.name} ${data.client.last_name}</td></tr>
                    <tr class="p-3"><th class="col p-3">Client Contact</th><td class="${data.client.contact == null ? 'text-danger' : ''} p-3">${data.client.contact == null ? 'Not Defined' : data.client.contact}</td></tr>
                    <tr class="p-3"><th class="col p-3">Client Email</th><td class="p-3">${data.client.email}</td></tr>
                    
                    <tr class="p-3"><th class="col p-3 text-center"><b style="font-size: 18px;">Others</b></th><td></td></tr>
                    
                    <tr class="p-3"><th class="col p-3">Brand</th><td class="p-3"><span class="btn btn-primary btn-sm">${data.brands.name}</span></td></tr>
                    <tr class="p-3"><th class="col p-3">Agent</th><td class="p-3">${data.sale.name} ${data.sale.last_name}</td></tr>
                    <tr class="p-3"><th class="col p-3">Merchant</th><td class="p-3">${data.merchant.name}</td></tr>
                    <tr class="p-3"><th class="col p-3">Create Login</th><td class="p-3">${actionButton}</td></tr>
                `;
        
                detailBody.append(tableContent);
                
                $('#detailsModal').modal('show');
                btn.removeAttr('disabled');
                btn.text("Details");
            }
        });
    })
</script>
@endpush