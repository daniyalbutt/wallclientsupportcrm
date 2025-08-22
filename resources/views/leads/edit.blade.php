@extends('layouts.app-manager')
@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Edit Lead</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <form action="/admin/leads/update/{{ $lead->id }}" method="POST">
            @csrf
            <div class="row">
                
                
                <div class="mb-3 col-md-6">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control" 
                        value="{{ old('name', $lead->name) }}" 
                        required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control" 
                        value="{{ old('email', $lead->email) }}" 
                        required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="phone">Phone</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-control" 
                        value="{{ old('phone', $lead->phone) }}" 
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="medium">Medium <span>*</span></label>
                    <select name="medium" id="medium" class="form-control" required>
                        <option value="" disabled>Select Medium</option>
                        <option {{ old('medium', $lead->medium) == 'Web-Form' ? 'selected' : '' }} value="Web-Form">Web-Form</option>
                        <option {{ old('medium', $lead->medium) == 'Chat' ? 'selected' : '' }} value="Chat">Chat</option>
                        <option {{ old('medium', $lead->medium) == 'Direct-Call' ? 'selected' : '' }} value="Direct-Call">Direct-Call</option>
                        <option {{ old('medium', $lead->medium) == 'SMM' ? 'selected' : '' }} value="SMM">SMM</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brand-id">Brand <span>*</span></label>
                    <select name="brand_id" id="brand-id" class="form-control" required>
                        <option value="" disabled>Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $lead->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group mb-3">
                    <label class="col-form-label" for="agent-name-wrapper">Sale Agent: <span>*</span></label>
                    <select name="user_id" id="agent-name-wrapper" class="form-control" required>
                        <option value="" disabled>Select Agent</option>
                        @foreach($users as $user)
                        @php
                            $role = $user->is_employee == 6 ? 'Sale Manager' : 'Sale Agent';
                        @endphp
                        <option class="{{ $user->is_employee == 6 ? 'text-danger': '' }}" style="{{ $user->is_employee == 6 ? '' : 'color: #0F9ABB' }}" value="{{ $user->id }}" {{ old('user_id', $lead->user_id) == $user->id ? 'selected' : '' }} {{ old('sale_agent', $lead->sale_agent) == $user->id ? 'selected' : '' }} >{{ $user->name }} {{ $user->last_name }} ({{ $role }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service">Services <span>*</span></label>
                    <select name="service[]" class="form-control select2" required multiple="multiple">
                        <option value="" disabled>Select Services</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ in_array($service->id, old('service', $lead->services->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/leads" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    $('#brand-id').on('change', function() {
        var brand_id = $(this).val();
        console.log(brand_id);
        getBrandUsers(brand_id);
    });

    function getBrandUsers(brand_id) {
        $('#agent-name-wrapper').html('<option value="">Select Agent</option>');
        if (brand_id) {
            $.ajax({
                type: 'GET',
                url: '/admin/get/brand/users',
                data: {
                    brand_id: brand_id
                },
                success: function(data) {
                    var getData = data.data;
                    for (var i = 0; i < getData.length; i++) {
                        var fullName = getData[i].name + ' ' + getData[i].last_name;
                        var role = getData[i].is_employee == 6 ? 'Sale Manager' : 'Sale Agent';
                        $('#agent-name-wrapper').append('<option ' + (getData[i].is_employee == 6 ? 'class="text-danger"' : 'style="color: #0F9ABB"') + ' value="' + getData[i].id + '">' + fullName + ' (' + role + ')</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    }
});
</script>

@endpush