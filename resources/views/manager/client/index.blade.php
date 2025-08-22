@extends('layouts.app-manager')
   
@section('content')
<style>
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

<div class="breadcrumb row">
    <div class="col-md-6">
        <h1>Clients List</h1>
        <ul>
            <li><a href="#">Clients</a></li>
            <li>Clients List</li>
        </ul>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('salemanager.client.create') }}" class="btn btn-info">Create Client</a>
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
                <form action="{{ route('salemanager.client.index') }}" method="GET">
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
                            <label for="contact">Search Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" value="">
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
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Client Details</h6>
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
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Link</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($data as $client)
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
                            <h6 class="mb-0 text-sm"><span class="btn btn-info bg-gradient-dark shadow-dark btn-sm">{{$client->added_by->name}} {{$client->added_by->last_name}} </span></h6>
                            <p class="text-xs text-secondary mb-0">{{$client->added_by->email}}</p>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><a href="{{ route('manager.generate.payment', $client->id) }}" class="btn btn-glow btn-primary btn-sm">Generate Payment</a></h6>
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
                      <td class="align-middle">
                        <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">
                                <a href="{{route('manager.client.edit', $client->id)}}" class="btn btn-glow btn-primary btn-icon btn-sm">
                                    <span class="ul-btn__icon"><i class="i-Edit"></i></span>
                                    <span class="ul-btn__text">Edit</span>
                                </a>    
                            </h6>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $data->links("pagination::bootstrap-4") }}
              </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    
@endpush