@extends('layouts.app-admin')
   
@section('content')
<div class="breadcrumb row">
    <div class="col-md-8">
        <h1>Brand Leads</h1>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-body">
                <h4 class="card-title mb-3">Leads</h4>
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered" id="zero_configuration_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>IP</th>
                                <th>Message</th>
                                <th>URL</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $brand_Lead)
                                <tr>
                                    @if(isset($brand_Lead->brand_logo))
                                        <td><img src="/{{ $brand_Lead->brand_logo }}" width="100"></td>
                                    @else
                                        <td>{{ $brand_Lead->brand_name }}</td>
                                    @endif
                                    <td>{{ $brand_Lead->name }}</td>
                                    <td>{{ $brand_Lead->email }}</td>
                                    <td>{{ $brand_Lead->phone }}</td>
                                    <td>{{ $brand_Lead->ip_address }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($brand_Lead->message, 100) }}</td>
                                    <td>{{ $brand_Lead->url }}</td>
                                    <td>{{ $brand_Lead->created_at }}</td>
                                    <!--<td>{{ Str::limit($brand_Lead->url, $limit = 50, $end = '...') }}</td>-->
                                    <!--<td><button type="button" id="details" class="btn btn-primary details" data-id="{{ $brand_Lead->id }}" >Show Details</button></td>-->
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
    
    
@endpush