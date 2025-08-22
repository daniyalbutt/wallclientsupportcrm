
@extends('layouts.app-manager')
@section('content')
<style>
    .icon-circle {
      height: 3rem;
      width: 3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }
    
    .icon-circle-brand{
        height: 4rem;
      width: 7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
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
</style>
<div class="breadcrumb">
    <h1 class="mr-2">Dashboard</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row gx-5">
    <div class="col-xxl-3 col-md-4 mb-3">
        <div class="card card-raised border-start border-primary border-4">
            <div class="card-body px-4">
                <a href="{{ route('salemanager.client.index') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5" style="color: #663399;">{{ $clients_count }}</div>
                            <div class="card-text" style="color: #663399;">Clients</div>
                        </div>
                        <div class="icon-circle bg-primary text-white"><i class="material-icons">account_circle</i></div>
                    </div>
                </a>
                <!--<div class="card-text">-->
                <!--    <div class="d-inline-flex align-items-center">-->
                <!--        <i class="material-icons icon-xs text-success">arrow_upward</i>-->
                <!--        <div class="caption text-success fw-500 me-2">3%</div>-->
                <!--        <div class="caption">from last month</div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-4 mb-3">
        <div class="card card-raised border-start border-info border-4">
            <div class="card-body px-4">
                <a href="{{ route('manager.my.invoices') }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-info">{{ $my_invoice }}</div>
                            <div class="card-text text-info">My Invoices</div>
                        </div>
                        <div class="icon-circle bg-info text-white"><i class="material-icons">paid</i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-4 mb-3">
        <div class="card card-raised border-start border-warning border-4">
            <div class="card-body px-4">
                <a href="/manager/invoice?package=&invoice=&user=&status=2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-warning">{{ $paid_invoice }}</div>
                            <div class="card-text text-warning">Paid Invoices</div>
                        </div>
                        <div class="icon-circle bg-warning text-white"><i class="material-icons">paid</i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-4 mb-5">
        <div class="card card-raised border-start border-secondary border-4">
            <div class="card-body px-4">
                <a href="/manager/invoice?package=&invoice=&user=&status=1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-secondary">{{ $un_paid_invoice }}</div>
                            <div class="card-text text-secondary">UnPaid Invoices</div>
                        </div>
                        <div class="icon-circle bg-secondary text-white"><i class="material-icons">paid</i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-4 mb-5">
        <div class="card card-raised border-start border-warning border-4">
            <div class="card-body px-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="me-2">
                        <div class="display-5" id="paid-total-dummy">*****</div>
                        <div class="display-5" id="paid-total" style="display: none;">${{ $paid_invoice_total }}</div>
                        <div class="card-text">Paid Total</div>
                    </div>
                    <div class="icon-circle bg-success text-white paid-div">
                        <a href="javascript:;" id="paid-total-show" style="color: #fff;display: flex;"><i class="material-icons">visibility</i></a>
                        <a href="javascript:;" id="paid-total-hide" style="color: #fff;display: none;"><i class="material-icons">visibility_off</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-4 mb-5">
        <div class="card card-raised border-start border-secondary border-4">
            <div class="card-body px-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="me-2">
                        <div class="display-5" id="unpaid-total-dummy">*****</div>
                        <div class="display-5" id="unpaid-total" style="display: none;">${{ $unpaid_invoice_total }}</div>
                        <div class="card-text">UnPaid Total</div>
                    </div>
                    <div class="icon-circle bg-success text-white unpaid-div">
                        <a href="javascript:;" id="unpaid-total-show" style="color: #fff;display: flex;"><i class="material-icons">visibility</i></a>
                        <a href="javascript:;" id="unpaid-total-hide" style="color: #fff;display: none;"><i class="material-icons">visibility_off</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach(Auth::user()->brands as $brands)
        <div class="col-xxl-3 col-md-2 mb-5">
            <div class="card card-raised border-start border-secondary border-4">
                <div class="card-body px-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-6"><span class="btn btn-primary bg-gradient-dark shadow-dark btn-sm">{{ implode('', array_map(function($v) { return $v[0]; }, explode(' ', $brands->name))) }}</span></div>
                        </div>
                        <div class="icon-circle-brand text-white">
                            <a href="{{ asset($brands->url) }}" target="_blank" style="color: #fff;display: flex;"><img src="{{ asset($brands->logo) }}" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach 
</div>


<div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Recent Clients</h6>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Full Name</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added By</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                </tr>
              </thead>
              <tbody>
              @foreach($recent_clients as $client)
                <tr>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $client->id }}</h6>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $client->name }} {{ $client->last_name }} </h6>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $client->email }}</h6>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><span class="btn btn-info bg-gradient-dark shadow-dark btn-sm">{{ $client->brand->name }} </span></h6>
                        <p class="text-xs text-secondary mb-0">{{ $client->brand->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{$client->added_by->name}} {{$client->added_by->last_name}}</h6>
                        <p class="text-xs text-secondary mb-0">{{$client->added_by->email}}</p>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        @if($client->status == 1)
                            <h6 class="mb-0 text-sm"><span class="btn btn-success btn-sm">Active</span></h6>
                        @else
                            <h6 class="mb-0 text-sm"><span class="btn btn-danger btn-sm">Deactive</span><br></h6>
                        @endif
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>



@endsection

@push('scripts')

<script>
    $(document).ready(function(){
       $('#paid-total-show').click(function(){
           $(this).css('display','none');
           $('#paid-total-hide').css('display','flex');
           $('.paid-div').removeClass('bg-success');
           $('.paid-div').addClass('bg-danger');
           $('#paid-total-dummy').css('display','none');
           $('#paid-total').css('display','block');
           
       });
       $('#paid-total-hide').click(function(){
           $(this).css('display','none');
           $('#paid-total-show').css('display','flex');
           $('.paid-div').removeClass('bg-danger');
           $('.paid-div').addClass('bg-success');
           $('#paid-total-dummy').css('display','block');
           $('#paid-total').css('display','none');
       });
       $('#unpaid-total-show').click(function(){
           $(this).css('display','none');
           $('#unpaid-total-hide').css('display','flex');
           $('.unpaid-div').removeClass('bg-success');
           $('.unpaid-div').addClass('bg-danger');
           $('#unpaid-total-dummy').css('display','none');
           $('#unpaid-total').css('display','block');
           
       });
       $('#unpaid-total-hide').click(function(){
           $(this).css('display','none');
           $('#unpaid-total-show').css('display','flex');
           $('.unpaid-div').removeClass('bg-danger');
           $('.unpaid-div').addClass('bg-success');
           $('#unpaid-total-dummy').css('display','block');
           $('#unpaid-total').css('display','none');
       });
    });
    
    
</script>


@endpush