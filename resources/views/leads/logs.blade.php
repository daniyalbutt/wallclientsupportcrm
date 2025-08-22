@extends('layouts.app-admin')

@section('content')

<div class="breadcrumb row">
    <div class="col-md-6">
        <h1>Lead Generation Logs</h1>
    </div>
</div>

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-body">
                <h4 class="card-title mb-3">Lead Details</h4>
                
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered" id="lead_data_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action Applied</th>
                                <th>Old Data</th>
                                <th>New Data</th>
                                <th>Acted On</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->user->name }} {{ $log->user->last_name }}</td>
                                <td>{{ $log->action_applied }}</td>
                                <td>
                                    @if($log->lead_data_old != null)
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Manager</th>
                                                <th>Agent</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $lead_data_old = json_decode($log->lead_data_old, true);
                                            @endphp
                                            <tr>
                                                <td>{{ $lead_data_old['name'] }}</td>
                                                <td>{{ $lead_data_old['email'] }}</td>
                                                <td>{{ $lead_data_old['phone'] }}</td>
                                                <td>{{ $lead_data_old['status'] ?? 'Not Available' }}</td>
                                                <td>
                                                    @foreach($users as $user)
                                                        @if(array_key_exists('user_id', $lead_data_old))
                                                            @if($user->id == $lead_data_old['user_id'])
                                                                {{ $user->name }} {{ $user->last_name }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($users as $user)
                                                        @if(array_key_exists('sale_agent', $lead_data_old))
                                                            @if($user->id == $lead_data_old['sale_agent'])
                                                                {{ $user->name }} {{ $user->last_name }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endif
                                </td>
                                <td>
                                    @if($log->lead_data_new != null)
                                        <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Manager</th>
                                                <th>Agent</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $lead_data_new = json_decode($log->lead_data_new, true);
                                            @endphp
                                            <tr>
                                                <td>{{ $lead_data_new['name'] }}</td>
                                                <td>{{ $lead_data_new['email'] }}</td>
                                                <td>{{ $lead_data_new['phone'] }}</td>
                                                <td>{{ $lead_data_new['status'] ?? 'Not Available' }}</td>
                                                <td>
                                                    @foreach($users as $user)
                                                        @if(array_key_exists('user_id', $lead_data_new))
                                                            @if($user->id == $lead_data_new['user_id'])
                                                                {{ $user->name }} {{ $user->last_name }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($users as $user)
                                                        @if(array_key_exists('sale_agent', $lead_data_new))
                                                            @if($user->id == $lead_data_new['sale_agent'])
                                                                {{ $user->name }} {{ $user->last_name }}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endif
                                </td>
                                <td>{{ $log->created_at }}</td>
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
    $('#lead_data_table').DataTable({
            order: [[4, "desc"]],
            responsive: true,
        });
</script>

@endpush
