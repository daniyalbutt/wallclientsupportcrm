@extends('layouts.app-admin')
@section('title', 'Leads')
@push('styles')
<style>
    .select2-container {
        z-index: 9999;
        text-align: left;
    }
</style>

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
    
</style>
@endpush
@section('content')
<div class="breadcrumb row">
    <div class="col-md-6">
        <h1 class="mr-2">Clients</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('admin.client.create') }}" class="btn btn-glow btn-primary">Create Client</a>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Search Client</h6>
              </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.client.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 form-group mb-3">
                            <label for="name">Search Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Request::get('name') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="email">Search Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ Request::get('email') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="brand">Search From Brand</label>
                            <select name="brand" id="brand" class="form-control">
                                <option value="">Any</option>
                                @foreach($brands as $brand)
                                <option value="{{$brand->id}}" {{ Request::get('brand') ==  $brand->id ? 'selected' : ' '}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="status">Select Status</label>
                            <select class="form-control select2" name="status" id="status">
                                <option value="">Any</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
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
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Client's Details <span> Total: {{ $data->total() }} <span></h6>
              </div>
            </div>
            <div class="card-body">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Crate Login</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                                @php if(Auth()->user()->email == 'finance@projectwall.net' || Auth()->user()->email == 'admin@syncwavecrm.com' ){ @endphp
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Link</th>
                                @php } @endphp
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $datas)
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                        <span class="btn btn-sm btn-dark">{{$datas->id}}</span>
                                        </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                            <div class="d-flex flex-column justify-content-center">
                                                <a class="btn btn-secondary btn-glow" href="{{ route('admin.client.show', $datas->id) }}">{{$datas->name}} {{$datas->last_name}}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                                {{$datas->email}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                            <a href="javascript:;" class="btn btn-glow btn-{{ $datas->user_id == null ? 'primary' : 'success' }} btn-sm auth-create" data-id="{{ $datas->id }}" data-auth="{{ $datas->user_id == null ? 0 : 1 }}" data-password="{{ $datas->user_id == null ? '' : '' }}">{{ $datas->user_id == null ? 'Click Here' : 'Reset Pass' }}</a>
                                        </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                                <button class="btn btn-info btn-sm">{{$datas->brand->name}}</button>
                                                @if($datas->url != null)
                                                <button class="btn btn-secondary btn-sm">From Website</button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @php if(Auth()->user()->email == 'finance@projectwall.net' || Auth()->user()->email == 'admin@syncwavecrm.com' ){ @endphp
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('admin.invoice.index', $datas->id) }}" class="btn btn-glow btn-primary btn-sm">Generate Payment</a>
                                            </div>
                                        </div>
                                    </td>
                                    @php } @endphp
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                                @if($datas->status == 1)
                                                    <button class="btn btn-success btn-sm">Active</button><br>
                                                @else
                                                    <button class="btn btn-danger btn-sm">Deactive</button><br>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                                          <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('admin.client.edit', $datas->id) }}" class="btn btn-glow btn-primary btn-icon btn-sm">
                                                    <span class="ul-btn__icon"><i class="i-Edit"></i></span>
                                                    <span class="ul-btn__text">Edit</span>
                                                </a>
                                                <a href="{{ route('admin.client.show', $datas->id) }}" class="btn btn-glow btn-dark btn-icon btn-sm">
                                                    <span class="ul-btn__icon"><i class="i-Eyeglasses-Smiley"></i></span>
                                                    <span class="ul-btn__text">View</span>
                                                </a>
                                                <form method="POST" action="{{route('admin.client.destroy', $datas->id) }}">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button class="btn btn-danger btn-glow btn-icon btn-sm" type="submit">
                                                        <span class="ul-btn__icon"><i class="i-Delete-File"></i></span>
                                                        <span class="ul-btn__text">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->appends(['name' => Request::get('name'), 'email' => Request::get('email'), 'brand' => Request::get('brand'), 'status' => Request::get('status')])->links("pagination::bootstrap-4") }}
                </div>
                <!--<div class="table-responsive">-->
                <!--    <table class="display table table-striped table-bordered"  style="width:100%">-->
                <!--        <thead>-->
                <!--            <tr>-->
                <!--                <th>ID</th>-->
                <!--                <th>Full Name</th>-->
                <!--                <th>Email</th>-->
                <!--                <th>Crate Login</th>-->
                <!--                <th>Brand</th>-->
                <!--                @php if(Auth()->user()->email == 'finance@projectwall.net' || Auth()->user()->email == 'admin@syncwavecrm.com' ){ @endphp-->
                <!--                <th>Payment Link</th>-->
                <!--                @php } @endphp-->
                <!--                <th>Status</th>-->
                <!--                <th>Active</th>-->
                <!--            </tr>-->
                <!--        </thead>-->
                <!--        <tbody>-->
                <!--            @foreach($data as $datas)-->
                <!--            <tr>-->
                <!--                <td>{{$datas->id}}</td>-->
                <!--                <td><a href="{{ route('admin.client.show', $datas->id) }}">{{$datas->name}} {{$datas->last_name}}</a></td>-->
                <!--                <td>{{$datas->email}}</td>-->
                <!--                <td>-->
                <!--                    <a href="javascript:;" class="btn btn-{{ $datas->user_id == null ? 'primary' : 'success' }} btn-sm auth-create" data-id="{{ $datas->id }}" data-auth="{{ $datas->user_id == null ? 0 : 1 }}" data-password="{{ $datas->user_id == null ? '' : '' }}">{{ $datas->user_id == null ? 'Click Here' : 'Reset Pass' }}</a>-->
                <!--                </td>-->
                <!--                <td>-->
                <!--                    <button class="btn btn-info btn-sm">{{$datas->brand->name}}</button>-->
                <!--                    @if($datas->url != null)-->
                <!--                    <button class="btn btn-secondary btn-sm">From Website</button>-->
                <!--                    @endif-->
                <!--                </td>-->
                <!--                @php if(Auth()->user()->email == 'finance@projectwall.net' || Auth()->user()->email == 'admin@syncwavecrm.com' ){ @endphp-->
                <!--                <td><a href="{{ route('admin.invoice.index', $datas->id) }}" class="btn btn-primary btn-sm">Generate Payment</a></td>-->
                <!--                @php } @endphp-->
                <!--                <td>-->
                <!--                    @if($datas->status == 1)-->
                <!--                        <button class="btn btn-success btn-sm">Active</button><br>-->
                <!--                    @else-->
                <!--                        <button class="btn btn-danger btn-sm">Deactive</button><br>-->
                <!--                    @endif-->
                <!--                </td>-->
                <!--                <td>-->
                <!--                    <a href="{{ route('admin.client.edit', $datas->id) }}" class="btn btn-primary btn-icon btn-sm">-->
                <!--                        <span class="ul-btn__icon"><i class="i-Edit"></i></span>-->
                <!--                        <span class="ul-btn__text">Edit</span>-->
                <!--                    </a>-->
                <!--                    <a href="{{ route('admin.client.show', $datas->id) }}" class="btn btn-dark btn-icon btn-sm">-->
                <!--                        <span class="ul-btn__icon"><i class="i-Eyeglasses-Smiley"></i></span>-->
                <!--                        <span class="ul-btn__text">View</span>-->
                <!--                    </a>-->
                <!--                    <form method="POST" action="{{route('admin.client.destroy', $datas->id) }}">-->
                <!--                        {{ method_field('DELETE') }}-->
                <!--                        {{ csrf_field() }}-->
                <!--                        <button class="btn btn-danger btn-icon btn-sm" type="submit">-->
                <!--                            <span class="ul-btn__icon"><i class="i-Delete-File"></i></span>-->
                <!--                            <span class="ul-btn__text">Delete</span>-->
                <!--                        </button>-->
                <!--                    </form>-->
                <!--                </td>-->
                <!--            </tr>-->
                <!--            @endforeach-->
                            
                <!--        </tbody>-->
                <!--        <tfoot>-->
                <!--            <tr>-->
                <!--                <th>ID</th>-->
                <!--                <th>Full Name</th>-->
                <!--                <th>Email</th>-->
                <!--                <th>Crate Login</th>-->
                <!--                <th>Brand</th>-->
                <!--                <th>Payment Link</th>-->
                <!--                <th>Status</th>-->
                <!--                <th>Active</th>-->
                <!--            </tr>-->
                <!--        </tfoot>-->
                <!--    </table>-->
                <!--    {{ $data->appends(['name' => Request::get('name'), 'email' => Request::get('email'), 'brand' => Request::get('brand'), 'status' => Request::get('status')])->links("pagination::bootstrap-4") }}-->
                <!--</div>-->
            </div>
        </div>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    let htmlTag = new Promise((resolve) => {
        setTimeout(() => {
            $.ajax({
                type:'POST',
                url: "{{ route('admin.client.agent') }}",
                success:function(data) {
                    var getData = data.data;
                    htmlTag = '<select id="MySelect" class="form-control select2">';
                    for (var i = 0; i < getData.length; i++) {
                        htmlTag += '<option value="'+getData[i].id+'">'+getData[i].name + ' ' + getData[i].last_name +'</option>'
                    }
                    htmlTag += '</select>';
                    resolve(htmlTag);
                }
            });
        }, 1000)
    })
    function getAgent(){
        
    }

    function assignAgent(id){
        getAgent()
        console.log(htmlTag);
        swal({
            title: 'Select Agent',
            html: htmlTag,
            showCancelButton: true,
            onOpen: function () {
                $('.select2').select2();
            },
            inputValidator: function (value) {
                return new Promise(function (resolve, reject) {
                    if (value !== '') {
                        resolve();
                    } else {
                        resolve('You need to select a Tier');
                    }
                });
            }
            }).then(function (result) {
                let agent_id = $('#MySelect option:selected').val();
                $.ajax({
                    type:'POST',
                    url: "{{ route('admin.client.update.agent') }}",
                    data: {id: id, agent_id:agent_id},
                    success:function(data) {
                        if(data.success == true){
                            swal("Agent Assigned", "Page will be loaded in order to reflect data", "success");
                            setTimeout(function () {
                                location.reload(true);
                            }, 3000);
                        }else{
                            return swal({
                                title:"Error",
                                text: "There is an Error, Plase Contact Administrator",
                                type:"danger"
                            })
                        }
                    }
                });
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        html: 'You selected: ' + result.value
                    });
                }
            });
    }
    function generatePassword() {
        var length = 16,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
        for (var i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        return retVal;
    }
    $('.auth-create').on('click', function () {
        var auth = $(this).data('auth');
        var id = $(this).data('id');
        var pass = generatePassword();
        if(auth == 0){
            swal({
                title: "Enter Password",
                input: "text",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Enter Password",
                inputValue: pass
                }).then(function (inputValue) {
                if (inputValue === false){
                    return swal({
                        title:"Field cannot be empty",
                        text: "Password Not Inserted/Updated because it is Empty",
                        type:"danger"
                    })
                }
                if (inputValue === "") {
                    return swal({
                        title:"Field cannot be empty",
                        text: "Password Not Inserted/Updated because it is Empty",
                        type:"danger"
                    })
                }
                $.ajax({
                    type:'POST',
                    url: "{{ route('admin.client.createauth') }}",
                    data: {id: id, pass:inputValue},
                    success:function(data) {
                        if(data.success == true){
                            swal("Auth Created", "Password is : " + inputValue, "success");
                        }else{
                            return swal({
                                title:"Error",
                                text: "There is an Error, Plase Contact Administrator",
                                type:"danger"
                            })
                        }
                    }
                });
            });
        }else{
            swal({
                title: "Enter Password",
                input: "text",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Enter Password",
                inputValue: pass
                }).then(function (inputValue) {
                if (inputValue === false){
                    return swal({
                        title:"Field cannot be empty",
                        text: "Password Not Inserted/Updated because it is Empty",
                        type:"danger"
                    })
                }
                if (inputValue === "") {
                    return swal({
                        title:"Field cannot be empty",
                        text: "Password Not Inserted/Updated because it is Empty",
                        type:"danger"
                    })
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $.ajax({
                    type:'POST',
                    url: "{{ route('admin.client.updateauth') }}",
                    data: {id: id, pass:inputValue},
                    success:function(data) {
                        if(data.success == true){
                            swal("Auth Created", "Password is : " + inputValue, "success");
                        }else{
                            return swal({
                                title:"Error",
                                text: "There is an Error, Plase Contact Administrator",
                                type:"danger"
                            })
                        }
                    }
                });
            });
        }
    });
</script>
@endpush