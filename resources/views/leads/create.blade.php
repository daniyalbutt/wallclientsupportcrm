@extends('layouts.app-manager')
@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Create Lead</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <form action="/admin/leads/store" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name">Name <span>*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Client Name">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email <span>*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="Client Email">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone">Phone <span>*</span></label>
                    <input type="text" name="phone" class="form-control" required placeholder="Client Phone">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="medium">Medium <span>*</span></label>
                    <select name="medium" id="medium" class="form-control" required>
                        <option value="" disabled selected>Select Medium</option>
                        <option value="Web-Form">Web-Form</option>
                        <option value="Chat">Chat</option>
                        <option value="Direct-Call">Direct-Call</option>
                        <option value="SMM">SMM</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="brand-id">Brand <span>*</span></label>
                    <select name="brand_id" id="brand-id" class="form-control" required>
                        <option value="" disabled selected>Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group mb-3">
                    <label class="col-form-label" for="agent-name-wrapper">Sale Agent: <span>*</span></label>
                    <select name="user_id" id="agent-name-wrapper" class="form-control" required>
                        <option value="" disabled selected>Select Agent</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service">Services <span>*</span></label>
                    <select name="service[]" class="form-control select2" required multiple="multiple">
                        <option value="" disabled>Select Services</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="notes">Note</label>
                    <textarea name="notes" class="form-control" placeholder="Write note here.."></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
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
