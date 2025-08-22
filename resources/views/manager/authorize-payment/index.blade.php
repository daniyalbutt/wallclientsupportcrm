@extends('layouts.app-manager')
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

<div class="breadcrumb row">
    <div class="col-md-6">
        <ul>
            <li><a href="#">Customer Charge</a></li>
            <li>Charge</li>
        </ul>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3" style="font-size: 1.75rem;">Charge Authorize Customer</h6>
              </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" value="" id="card_number" name="card_number">
                    <input type="hidden" value="" id="expiration_date" name="expiration_date">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Customer Profile</label>
                            <select id="transactionId" class="form-control" required>
                                <option value="">Select Transaction</option>
                                <option value="120003950534">TEST USER</option>
                                <!--@foreach ($transactions as $transaction)-->
                                <!--    <option value="{{ $transaction->transaction_id }}">{{ $transaction->name }} {{ $transaction->last_name }} ({{ $transaction->email }})</option>-->
                                <!--@endforeach-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer</label>
                            <select id="customerDetails" class="form-control" disabled required>
                                <option value="">Customer will appear on selecting transaction</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Charge Amount</label>
                            <input type="number" id="amount" class="form-control" placeholder="Enter Amount" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea rows="3" id="description" class="form-control" placeholder="Enter Description"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button id="chargeButton" class="btn btn-success">Charge Customer</button>
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
$(document).ready(function() {
    $('#transactionId').change(function() {
        let transactionId = $(this).val();
        if (transactionId) {
            $.ajax({
                url: "{{ route('manager.transaction.details') }}",
                type: "POST",
                data: { transaction_id: transactionId, _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        $('#customerDetails').html(`
                            <option value="${response.transaction_id}" selected>
                                ${response.email} - XXXX${response.card_number} - ${response.expiration}
                            </option>
                        `);
                        $('#expiration_date').val(response.expiration);
                        $('#card_number').val(response.card_number);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
    
    $('#chargeButton').click(function() {
        
        let transactionId = $('#transactionId').val();
        let amount = $('#amount').val();
        let description = $('#description').val();
        let card_number = $('#card_number').val();
        let expiration = $('#expiration_date').val();

        if (transactionId && amount && description) {
            $.ajax({
                url: "{{ route('manager.authorize.charge') }}",
                type: "POST",
                data: { 
                transaction_id: transactionId,
                amount: amount,
                description: description,
                card_number: card_number,
                expiration_date: expiration,
                _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        alert('Customer charged successfully! Transaction ID: ' + response.transaction_id);
                    } else {
                        alert('Payment failed: ' + response.error);
                    }
                }
            });
        } else {
            alert('Please enter all details.');
        }
    });
});
</script>

@endpush