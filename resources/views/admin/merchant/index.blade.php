@extends('layouts.app-admin')
@section('title', 'Merchants')
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
        <h1 class="mr-2">Merchants</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('admin.merchant.create') }}" class="btn btn-glow btn-primary">Create Merchant</a>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Merchant's Details <span> Total: {{ count($data) }} <span></h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
  <div class="table-responsive p-0">
    <table class="table align-items-center mb-0">
      <thead>
          <tr>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Public Key</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Secret Key</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Merchant</th>
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
                            {{$datas->name}}
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center" style="overflow-wrap: anywhere;">
                        {{$datas->public_key}}
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center" style="overflow-wrap: anywhere;">
                        {{$datas->secret_key}}
                    </div>
                    </div>
                </td>
                <td class="align-middle">
                    <div class="d-flex px-2 py-1" style="justify-content: center;text-align: center;">
                      <div class="d-flex flex-column justify-content-center">
                        @if($datas->is_authorized == 0)
                        <button class="btn btn-sm btn-secondary">STRIPE</button>
                        @elseif($datas->is_authorized == 4)
                        <button class="btn btn-sm btn-secondary">SQUAREUP</button>
                        @elseif($datas->is_authorized == 3)
                        <button class="btn btn-sm btn-secondary">NMI</button>
                        @elseif($datas->is_authorized == 2)
                        <button class="btn btn-sm btn-secondary">AUTHORIZE.NET</button>
                        @endif
                      </div>
                    </div>
                </td>
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
                        <a href="{{ route('admin.merchant.edit', $datas->id) }}" class="btn btn-glow btn-primary btn-icon btn-sm">
                            <span class="ul-btn__icon"><i class="i-Edit"></i></span>
                            <span class="ul-btn__text">Edit</span>
                        </a>
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
            
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')

@endpush