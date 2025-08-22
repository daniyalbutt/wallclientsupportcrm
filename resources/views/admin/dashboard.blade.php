@extends('layouts.app-admin')
@section('title', 'Dashboard')
@push('styles')
<style>
    #BarChart2{
        height: 294px !important;
    }
</style>

@if(Auth::user()->email == 'admin@syncwavecrm.com')
@endif
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

@endpush
@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Dashboard</h1>
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row gx-5">
    <div class="col-xxl-3 col-md-3 mb-3">
        <div class="card card-raised border-start border-primary border-4">
            <div class="card-body px-4">
                <a href="{{ route('category.index') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5" style="color: #663399;">{{$category_count}}</div>
                            <div class="card-text" style="color: #663399;">Category</div>
                        </div>
                        <div class="icon-circle bg-primary text-white"><span class="material-icons">dns</span></div>
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
    <div class="col-xxl-3 col-md-3 mb-3">
        <div class="card card-raised border-start border-info border-4">
            <div class="card-body px-4">
                <a href="{{ route('brand.index') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-info">{{$brand_count}}</div>
                            <div class="card-text text-info">Brand</div>
                        </div>
                        <div class="icon-circle bg-info text-white"><span class="material-icons">auto_mode</span></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-3 mb-3">
        <div class="card card-raised border-start border-success border-4">
            <div class="card-body px-4">
                <a href="{{ route('currency.index') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-success">{{$currency_count}}</div>
                            <div class="card-text text-success">Currency</div>
                        </div>
                        <div class="icon-circle bg-success text-white"><span class="material-icons">paid</span></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-3 mb-3">
        <div class="card card-raised border-start border-secondary border-4">
            <div class="card-body px-4">
                <a href="{{ route('admin.user.sales') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-secondary">{{$member_count}}</div>
                            <div class="card-text text-secondary">Sales</div>
                        </div>
                        <div class="icon-circle bg-secondary text-white"><span class="material-icons">stars</span></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-3 mb-3">
        <div class="card card-raised border-start border-secondary border-4">
            <div class="card-body px-4">
                <a href="{{ route('admin.client.index') }}" >
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-secondary">{{$leads_count}}</div>
                            <div class="card-text text-secondary">Leads</div>
                        </div>
                        <div class="icon-circle bg-secondary text-white"><span class="material-icons">rocket_launch</span></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-md-3 mb-3">
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
    <div class="col-xxl-3 col-md-3 mb-5">
        <div class="card card-raised border-start border-info border-4">
            <div class="card-body px-4">
                <a href="/manager/invoice?package=&invoice=&user=&status=1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="me-2">
                            <div class="display-5 text-info">{{ $un_paid_invoice }}</div>
                            <div class="card-text text-info">UnPaid Invoices</div>
                        </div>
                        <div class="icon-circle bg-info text-white"><i class="material-icons">paid</i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row pb-3">
    <div class="col-lg-6 col-md-6">
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
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Full Name</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                </tr>
              </thead>
              <tbody>
              @foreach($recent_clients as $client)
                <tr>
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
    <div class="col-md-6">
        <div class="card card-chart-bottom o-hidden my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Invoice Payments</h6>
              </div>
            </div>
            <div class="card-body">
                <div id="invoiceChart" style="height: 477px !important;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card card-chart-bottom o-hidden mb-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                      <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Monthly Invoice Stats</h6>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                        <div class="row">
                            <div class="icon-circle bg-success text-white paid-div">
                                <a href="javascript:;" id="paid-total-show" style="color: #fff;display: flex;"><i class="material-icons">visibility</i></a>
                                <a href="javascript:;" id="paid-total-hide" style="color: #fff;display: none;"><i class="material-icons">visibility_off</i></a>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="text-muted">{{ $invoice_month }}, {{ $invoice_year }} Sales</div>
                                <p class="mb-4 text-primary text-24" id="totalAmount" style="display: none;">${{ $totalAmount }}</p>
                                <p class="mb-4 text-primary text-24" id="paid-total-dummy">*****</p>
                            </div>
                        </div>
                            <form id="invoiceForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                      <div class="form-group">
                                        <label for="start_date">Start Date:</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                      </div>  
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 text-center">
                                        <button type="button" class="btn btn-primary mt-4" id="fetchInvoices">Fetch Data</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="container">
                            <div id="echartBar" style="height: 440px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
  var myChart = echarts.init(document.getElementById('invoiceChart'));

  // Fetch data from Laravel API
  fetch('/admin/get-invoice-data')
    .then(response => response.json())
    .then(data => {
      let dates = data.map(item => item.created_at);
      let amountPaid = data.map(item => item.paid);
      let amountUnpaid = data.map(item => item.unpaid);

      let option = {
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['Paid', 'Unpaid']
        },
        xAxis: {
          type: 'category',
          data: dates
        },
        yAxis: {
          type: 'value',
          max: 15000
        },
        series: [
          {
            name: 'Paid',
            type: 'line',
            data: amountPaid,
            color: 'green'
          },
          {
            name: 'Unpaid',
            type: 'line',
            data: amountUnpaid,
            color: 'red'
          }
        ]
      };

      myChart.setOption(option);
    })
    .catch(error => console.error('Error fetching data:', error));
</script>
<script>
    $('#paid-total-show').click(function(){
       $(this).css('display','none');
       $('#paid-total-hide').css('display','flex');
       $('.paid-div').removeClass('bg-success');
       $('.paid-div').addClass('bg-danger');
       $('#paid-total-dummy').css('display','none');
       $('#totalAmount').css('display','block');
       
   });
   $('#paid-total-hide').click(function(){
       $(this).css('display','none');
       $('#paid-total-show').css('display','flex');
       $('.paid-div').removeClass('bg-danger');
       $('.paid-div').addClass('bg-success');
       $('#paid-total-dummy').css('display','block');
       $('#totalAmount').css('display','none');
   });
</script>
<script>
        $(document).ready(function () {
    $('#fetchInvoices').click(function () {
        var formData = $('#invoiceForm').serialize();
        $.ajax({
            url: "{{ route('revenue.invoices') }}",
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                updateChartWithData(data.invoices);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    function updateChartWithData(data) {
        // Assuming data is an array of invoices with 'amount' and 'invoice_date' properties
        var data_array = [];
        var data_day = [];
        var sum = 0; // Initialize sum variable

        // Update data_array, data_day, and calculate sum with the received data
        for (var i = 0; i < data.length; i++) {
            data_array.push(data[i].amount);
            data_day.push(data[i].invoice_date);
            sum += parseFloat(data[i].amount); // Assuming 'amount' is a numeric value
        }

        // Update the total amount in the specified div
        $('#totalAmount').text('$' + sum.toFixed(2)); // Format the sum as currency

        // Rest of your chart initialization code
        var echartElemBar = document.getElementById('echartBar');

        if (echartElemBar) {
            var echartBar = echarts.init(echartElemBar);
            echartBar.setOption({
                xAxis: [{
                    type: 'category',
                    data: data_day,
                    axisTick: {
                        alignWithLabel: true
                    },
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: true
                    }
                }],
                series: [{
                    name: '',
                    data: data_array,
                    label: {
                        show: false,
                        color: '#0168c1'
                    },
                    type: 'bar',
                    barGap: 0,
                    color: '#bcbbdd',
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                }]
            });

            $(window).on('resize', function () {
                setTimeout(function () {
                    echartBar.resize();
                }, 500);
            });
        }
    }
});
    </script>
<script>
    var data = {!! json_encode($data->toArray()) !!};
    var data_array = [];
    var data_day = [];
    for(var i = 0; i < data.length; i++){
        data_array.push(data[i].amount);
        data_day.push(data[i].invoice_date);
    }

    $(document).ready(function() {
        // Chart in Dashboard version 1
        var echartElemBar = document.getElementById('echartBar');

        if (echartElemBar) {
            var echartBar = echarts.init(echartElemBar);
            echartBar.setOption({
                legend: {
                    borderRadius: 0,
                    orient: 'horizontal',
                    x: 'right'
                },
                grid: {
                    left: '8px',
                    right: '8px',
                    bottom: '0',
                    containLabel: true
                },
                tooltip: {
                    show: true,
                    backgroundColor: 'rgba(0, 0, 0, .8)'
                },
                xAxis: [{
                    type: 'category',
                    data: data_day,
                    axisTick: {
                        alignWithLabel: true
                    },
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: true
                    }
                }],
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        formatter: '${value}'
                    },
                    axisLine: {
                        show: false
                    },
                    splitLine: {
                        show: true,
                        interval: 'auto'
                    }
                }],
                series: [{
                    name: '',
                    data: data_array,
                    label: {
                        show: false,
                        color: '#0168c1'
                    },
                    type: 'bar',
                    barGap: 0,
                    color: '#bcbbdd',
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                }]
            });
            $(window).on('resize', function() {
                setTimeout(function() {
                    echartBar.resize();
                }, 500);
            });
        }
    });
</script>
@endpush