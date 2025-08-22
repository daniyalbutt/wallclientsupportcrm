@php
if (Auth::user()->is_employee == 2) {
    $layout = 'layouts.app-admin';
} elseif (Auth::user()->is_employee == 6) {
    $layout = 'layouts.app-manager';
} elseif (Auth::user()->is_employee == 0) {
    $layout = 'layouts.app-sale';
}
@endphp

@extends($layout)
@section('content')
<style>
    .btn-sm{
        margin: 2px;
    }
    .status-select{
        width: fit-content;
    }
    .btn-remove-agent{
        background: peachpuff !important;
        border-color: peachpuff !important;
        color: #000 !important;
    }
    .btn-remove-manager{
        background: darkmagenta !important;
        border-color: darkmagenta !important;
    }
</style>
<div class="breadcrumb">
    <h1 class="mr-2"></h1>
</div>
<div class="breadcrumb row">
    <div class="col-md-6">
        <h1>Lead Generation</h1>
    </div>
    @if(Auth::user()->is_employee == 2)
    <div class="col-md-6 text-right">
        <a href="/admin/leads/create" class="btn btn-primary">Add New Lead</a>
    </div>
    @endif
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-body">
            <h4 class="card-title mb-3">Lead Data Info</h4>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="all-lead-tab" data-toggle="tab" href="#all-lead" role="tab" aria-controls="all-lead" aria-selected="true">All</a></li>
                <li class="nav-item"><a class="nav-link" id="pending-lead-tab" data-toggle="tab" href="#pending-lead" role="tab" aria-controls="pending-lead" aria-selected="true">Pending</a></li>
                <li class="nav-item"><a class="nav-link" id="contacted-lead-tab" data-toggle="tab" href="#contacted-lead" role="tab" aria-controls="contacted-lead" aria-selected="false">Contacted</a></li>
                <li class="nav-item"><a class="nav-link" id="follow-up-lead-tab" data-toggle="tab" href="#follow-up-lead" role="tab" aria-controls="follow-up-lead" aria-selected="false">Follow-Up</a></li>
                <li class="nav-item"><a class="nav-link" id="re-engagged-lead-tab" data-toggle="tab" href="#re-engagged-lead" role="tab" aria-controls="re-engagged-lead" aria-selected="false">Re-Engaged</a></li>
                <li class="nav-item"><a class="nav-link" id="interested-lead-tab" data-toggle="tab" href="#interested-lead" role="tab" aria-controls="interested-lead" aria-selected="false">Interested</a></li>
                <li class="nav-item"><a class="nav-link" id="proposal-sent-lead-tab" data-toggle="tab" href="#proposal-sent-lead" role="tab" aria-controls="proposal-sent-lead" aria-selected="false">Proposal Sent</a></li>
                <li class="nav-item"><a class="nav-link" id="lost-lead-tab" data-toggle="tab" href="#lost-lead" role="tab" aria-controls="lost-lead" aria-selected="false">Lost</a></li>
                <li class="nav-item"><a class="nav-link" id="converted-lead-tab" data-toggle="tab" href="#converted-lead" role="tab" aria-controls="converted-lead" aria-selected="false">Converted</a></li>
                <li class="nav-item" style="background: #0E9ABB;color: #fff;border-top-left-radius: 5px;border-top-right-radius: 5px;"><a class="nav-link" id="notes-lead-tab" data-toggle="tab" href="#notes-lead" role="tab" aria-controls="notes-lead" aria-selected="false">Lead Notes</a></li>
            </ul>
            <div class="tab-content pr-0 pl-0" id="myTabContent">
                
                <div class="tab-pane fade show active" id="all-lead" role="tabpanel" aria-labelledby="all-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($leads as $lead)
                                    <tr>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone }}</td>
                                        <td>
                                            @foreach($lead->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                        
                                        <td><button class="btn {{ !empty($lead->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $lead->user->name ?? 'Not Assigned' }}{{ $lead->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $lead->created_at }}
                                        </td>
                                        <td>
                                            @if($lead->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $lead->id }}, {{ $lead->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($lead->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $lead->id }}, {{ $lead->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($lead->sale_agent == null && $lead->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $lead->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $lead->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                            </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($leads as $lead)
                                        @if($lead->status !== "Converted" || ($lead->status === "Converted" && $lead->manager_assign == 1))
                                            <tr>
                                                <td>
                                                    <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                                </td>
                                                <td>
                                                    <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                                </td>
                                                <td>
                                                    @foreach($lead->services as $service)
                                                        <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                    @endforeach
                                                </td>
                                                <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                                <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                                <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                                <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                                <td>
                                                    <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                        <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                        <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                        <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                        <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                        <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                        <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                        <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                        <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    {{ $lead->assigned_on }} 
                                                </td>
                                                @if($lead->status === "Converted")
                                                    @if($lead->manager_assign == 1)
                                                    <td>
                                                        <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                        <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                        <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                    </td>
                                                    @endif
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                    </tr>
                                        @endif
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($leads as $lead)
                                    <tr>
                                        <td>
                                            <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                        </td>
                                        <td>
                                            <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                        </td>
                                        <td>
                                            <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                        </td>
                                        <td>
                                            @foreach($lead->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $lead->assigned_on }} 
                                        </td>
                                        <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade show" id="pending-lead" role="tabpanel" aria-labelledby="pending-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($pending as $pending_leads)
                                    <tr>
                                        <td>{{ $pending_leads->name }}</td>
                                        <td>{{ $pending_leads->email }}</td>
                                        <td>{{ $pending_leads->phone }}</td>
                                        <td>
                                            @foreach($pending_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $pending_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $pending_leads->brand->name ?? 'N/A' }}</button></td>
                                        
                                        <td><button class="btn {{ !empty($pending_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $pending_leads->user->name ?? 'Not Assigned' }}{{ $pending_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($pending_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $pending_leads->sale->name ?? 'Not Assigned' }} {{ $pending_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $pending_leads->id }})">
                                                <option value="Pending" {{ $pending_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $pending_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $pending_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $pending_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $pending_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $pending_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option value="Converted" {{ $pending_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $pending_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $pending_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($pending_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $pending_leads->id }}, {{ $pending_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($pending_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $pending_leads->id }}, {{ $pending_leads->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($pending_leads->sale_agent == null && $pending_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $pending_leads->id }}, {{ $pending_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $pending_leads->id }}, {{ $pending_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $pending_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $pending_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                            </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($pending as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($pending as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            
                <div class="tab-pane fade" id="contacted-lead" role="tabpanel" aria-labelledby="contacted-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered" id="zero_configuration_table_1" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($contacted as $contacted_leads)
                                        <tr>
                                        <td>{{ $contacted_leads->name }}</td>
                                        <td>{{ $contacted_leads->email }}</td>
                                        <td>{{ $contacted_leads->phone }}</td>
                                        <td>
                                            @foreach($contacted_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $contacted_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $contacted_leads->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn {{ !empty($contacted_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $contacted_leads->user->name ?? 'Not Assigned' }}{{ $contacted_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($contacted_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $contacted_leads->sale->name ?? 'Not Assigned' }} {{ $contacted_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $contacted_leads->id }})">
                                                <option value="Pending" {{ $contacted_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $contacted_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $contacted_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $contacted_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $contacted_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $contacted_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option style="{{ $contacted_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $contacted_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $contacted_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $contacted_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $contacted_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($contacted_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $contacted_leads->id }}, {{ $contacted_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($contacted_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $contacted_leads->id }}, {{ $contacted_leads->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($contacted_leads->sale_agent == null && $contacted_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $contacted_leads->id }}, {{ $contacted_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $contacted_leads->id }}, {{ $contacted_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $contacted_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $contacted_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($contacted as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($contacted as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="follow-up-lead" role="tabpanel" aria-labelledby="follow-up-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($follow_up as $follow_up_leads)
                                        <tr>
                                        <td>{{ $follow_up_leads->name }}</td>
                                        <td>{{ $follow_up_leads->email }}</td>
                                        <td>{{ $follow_up_leads->phone }}</td>
                                        <td>
                                            @foreach($follow_up_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $follow_up_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $follow_up_leads->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn {{ !empty($follow_up_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $follow_up_leads->user->name ?? 'Not Assigned' }}{{ $follow_up_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($follow_up_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $follow_up_leads->sale->name ?? 'Not Assigned' }} {{ $follow_up_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $follow_up_leads->id }})">
                                                <option value="Pending" {{ $follow_up_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $follow_up_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $follow_up_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $follow_up_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $follow_up_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $follow_up_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option style="{{ $follow_up_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $follow_up_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $follow_up_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $follow_up_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $follow_up_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($follow_up_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $follow_up_leads->id }}, {{ $follow_up_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($follow_up_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $follow_up_leads->id }}, {{ $follow_up_leads->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($follow_up_leads->sale_agent == null && $follow_up_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $follow_up_leads->id }}, {{ $follow_up_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $follow_up_leads->id }}, {{ $follow_up_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $follow_up_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $follow_up_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($follow_up as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($follow_up as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="re-engagged-lead" role="tabpanel" aria-labelledby="re-engagged-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($re_engagged as $re_engagged_leads)
                                    <tr>
                                        <td>{{ $re_engagged_leads->name }}</td>
                                        <td>{{ $re_engagged_leads->email }}</td>
                                        <td>{{ $re_engagged_leads->phone }}</td>
                                        <td>
                                            @foreach($re_engagged_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $re_engagged_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $re_engagged_leads->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn {{ !empty($re_engagged_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $re_engagged_leads->user->name ?? 'Not Assigned' }}{{ $re_engagged_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($re_engagged_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $re_engagged_leads->sale->name ?? 'Not Assigned' }} {{ $re_engagged_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $re_engagged_leads->id }})">
                                                <option value="Pending" {{ $re_engagged_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $re_engagged_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $re_engagged_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $re_engagged_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $re_engagged_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $re_engagged_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option style="{{ $re_engagged_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $re_engagged_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $re_engagged_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $re_engagged_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $re_engagged_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($re_engagged_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $re_engagged_leads->id }}, {{ $re_engagged_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($re_engagged_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $re_engagged_leads->id }}, {{ $re_engagged_leads->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($re_engagged_leads->sale_agent == null && $re_engagged_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $re_engagged_leads->id }}, {{ $re_engagged_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $re_engagged_leads->id }}, {{ $re_engagged_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $re_engagged_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $re_engagged_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($re_engagged as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($re_engagged as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="interested-lead" role="tabpanel" aria-labelledby="interested-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($interested as $interested_leads)
                                        <tr>
                                    <td>{{ $interested_leads->name }}</td>
                                    <td>{{ $interested_leads->email }}</td>
                                    <td>{{ $interested_leads->phone }}</td>
                                    <td>
                                        @foreach($interested_leads->services as $service)
                                            <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                        @endforeach
                                    </td>
                                    <td><button class="btn btn-warning btn-sm">{{ $interested_leads->medium }}</button></td>
                                    <td><button class="btn btn-info btn-sm">{{ $interested_leads->brand->name ?? 'N/A' }}</button></td>
                                    <td><button class="btn {{ !empty($interested_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $interested_leads->user->name ?? 'Not Assigned' }}{{ $interested_leads->user->last_name ?? '' }}</button></td>
                                    <td><button class="btn {{ !empty($interested_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $interested_leads->sale->name ?? 'Not Assigned' }} {{ $interested_leads->sale->last_name ?? '' }}</button></td>
                                    <td>
                                        <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $interested_leads->id }})">
                                            <option value="Pending" {{ $interested_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                            <option value="Contacted" {{ $interested_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                            <option value="Follow-Up" {{ $interested_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                            <option value="Re-Engaged" {{ $interested_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                            <option value="Interested" {{ $interested_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                            <option value="Proposal Sent" {{ $interested_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                            <option style="{{ $interested_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $interested_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $interested_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                            <option value="Lost" {{ $interested_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                        </select>
                                    </td>
                                    <td>
                                        {{ $interested_leads->created_at }}
                                    </td>
                                    <td>
                                        @if($interested_leads->sale_agent == null)
                                            <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                        @else
                                            <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $interested_leads->id }}, {{ $interested_leads->sale_agent }})">Remove Agent</button>
                                        @endif
                                        @if($interested_leads->user_id == null)
                                            <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                        @else
                                            <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $interested_leads->id }}, {{ $interested_leads->user_id }})">Remove Manager</button>
                                        @endif
                                        @if($interested_leads->sale_agent == null && $interested_leads->user_id == null)
                                            <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $interested_leads->id }}, {{ $interested_leads->brand_id }})">Assign Lead</button>
                                        @else
                                            <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $interested_leads->id }}, {{ $interested_leads->brand_id }})">Reassign Lead</button>
                                        @endif
                                        <a href="/admin/leads/edit/{{ $interested_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                        <a href="/admin/leads/delete/{{ $interested_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                    </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($interested as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($interested as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="proposal-sent-lead" role="tabpanel" aria-labelledby="proposal-sent-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($proposal_sent as $proposal_sent_leads)
                                        <tr>
                                    <td>{{ $proposal_sent_leads->name }}</td>
                                    <td>{{ $proposal_sent_leads->email }}</td>
                                    <td>{{ $proposal_sent_leads->phone }}</td>
                                    <td>
                                        @foreach($proposal_sent_leads->services as $service)
                                            <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                        @endforeach
                                    </td>
                                    <td><button class="btn btn-warning btn-sm">{{ $proposal_sent_leads->medium }}</button></td>
                                    <td><button class="btn btn-info btn-sm">{{ $proposal_sent_leads->brand->name ?? 'N/A' }}</button></td>
                                    <td><button class="btn {{ !empty($proposal_sent_leads->user->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $proposal_sent_leads->user->name ?? 'Not Assigned' }}{{ $proposal_sent_leads->user->last_name ?? '' }}</button></td>
                                    <td><button class="btn {{ !empty($proposal_sent_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $proposal_sent_leads->sale->name ?? 'Not Assigned' }} {{ $proposal_sent_leads->sale->last_name ?? '' }}</button></td>
                                    <td>
                                        <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $proposal_sent_leads->id }})">
                                            <option value="Pending" {{ $proposal_sent_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                            <option value="Contacted" {{ $proposal_sent_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                            <option value="Follow-Up" {{ $proposal_sent_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                            <option value="Re-Engaged" {{ $proposal_sent_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                            <option value="Interested" {{ $proposal_sent_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                            <option value="Proposal Sent" {{ $proposal_sent_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                            <option style="{{ $proposal_sent_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $proposal_sent_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $proposal_sent_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                            <option value="Lost" {{ $proposal_sent_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                        </select>
                                    </td>
                                    <td>
                                        {{ $proposal_sent_leads->created_at }}
                                    </td>
                                    <td>
                                        @if($proposal_sent_leads->sale_agent == null)
                                            <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                        @else
                                            <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $proposal_sent_leads->id }}, {{ $proposal_sent_leads->sale_agent }})">Remove Agent</button>
                                        @endif
                                        @if($proposal_sent_leads->user_id == null)
                                            <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                        @else
                                            <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $proposal_sent_leads->id }}, {{ $proposal_sent_leads->user_id }})">Remove Manager</button>
                                        @endif
                                        @if($proposal_sent_leads->sale_agent == null && $proposal_sent_leads->user_id == null)
                                            <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $proposal_sent_leads->id }}, {{ $proposal_sent_leads->brand_id }})">Assign Lead</button>
                                        @else
                                            <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $proposal_sent_leads->id }}, {{ $proposal_sent_leads->brand_id }})">Reassign Lead</button>
                                        @endif
                                        <a href="/admin/leads/edit/{{ $proposal_sent_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                        <a href="/admin/leads/delete/{{ $proposal_sent_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                    </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($proposal_sent as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($proposal_sent as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="lost-lead" role="tabpanel" aria-labelledby="lost-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($lost as $lost_leads)
                                    <tr>
                                        <td>{{ $lost_leads->name }}</td>
                                        <td>{{ $lost_leads->email }}</td>
                                        <td>{{ $lost_leads->phone }}</td>
                                        <td>
                                            @foreach($lost_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lost_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $lost_leads->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn {{ !empty($lost_leads->sale->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $lost_leads->user->name ?? 'Not Assigned' }}{{ $lost_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($lost_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lost_leads->sale->name ?? 'Not Assigned' }} {{ $lost_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lost_leads->id }})">
                                                <option value="Pending" {{ $lost_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $lost_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $lost_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $lost_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $lost_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $lost_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option style="{{ $lost_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $lost_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $lost_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $lost_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $lost_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($lost_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $lost_leads->id }}, {{ $lost_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($lost_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $lost_leads->id }}, {{ $lost_leads->user_id }})">Remove Manager</button>
                                            @endif
                                            @if($lost_leads->sale_agent == null && $lost_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $lost_leads->id }}, {{ $lost_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $lost_leads->id }}, {{ $lost_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $lost_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $lost_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($lost as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            @if($lead->status === "Converted")
                                                @if($lead->manager_assign == 1)
                                                <td>
                                                    <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                            @else
                                            <td>
                                                <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                            </td>
                                            @endif
                                    </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($lost as $lead)
                                        <tr>
                                            <td>
                                                <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                            </td>
                                            <td>
                                                <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                            </td>
                                            <td>
                                                @foreach($lead->services as $service)
                                                    <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                @endforeach
                                            </td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                            <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                            <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                            <td>
                                                <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                    <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                    <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                    <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                    <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                    <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                    <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                    <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                    <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                </select>
                                            </td>
                                            <td>
                                                {{ $lead->assigned_on }} 
                                            </td>
                                            <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="converted-lead" role="tabpanel" aria-labelledby="converted-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init" style="width:100%">
                            @if(Auth::user()->is_employee == 2)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Manager</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 6)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Agent</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            @elseif(Auth::user()->is_employee == 0)
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service</th>
                                        <th>Medium</th>
                                        <th>Brand</th>
                                        <th>Assigned By</th>
                                        <th>Status</th>
                                        <th>Assigned On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody>
                                @if(Auth::user()->is_employee == 2)
                                    @foreach($converted as $converted_leads)
                                    <tr>
                                        <td>{{ $converted_leads->name }}</td>
                                        <td>{{ $converted_leads->email }}</td>
                                        <td>{{ $converted_leads->phone }}</td>
                                        <td>
                                            @foreach($converted_leads->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $converted_leads->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $converted_leads->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn {{ !empty($converted_leads->sale->name) ? 'btn-warning' : 'btn-danger' }} btn-sm">{{ $converted_leads->user->name ?? 'Not Assigned' }}{{ $converted_leads->user->last_name ?? '' }}</button></td>
                                        <td><button class="btn {{ !empty($converted_leads->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $converted_leads->sale->name ?? 'Not Assigned' }} {{ $converted_leads->sale->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $converted_leads->id }})">
                                                <option value="Pending" {{ $converted_leads->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $converted_leads->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $converted_leads->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $converted_leads->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $converted_leads->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $converted_leads->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option style="{{ $converted_leads->status == 'Converted' ? 'color: #fff' : '' }}" class="{{ $converted_leads->status == 'Converted' ? 'bg-success' : '' }}" value="Converted" {{ $converted_leads->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $converted_leads->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $converted_leads->created_at }}
                                        </td>
                                        <td>
                                            @if($converted_leads->sale_agent == null)
                                                <button disabled class="btn btn-sm btn-info btn-remove-agent">Remove Agent</button>
                                            @else
                                                <button class="btn btn-sm btn-info btn-remove-agent" onclick="removeAccess({{ $converted_leads->id }}, {{ $converted_leads->sale_agent }})">Remove Agent</button>
                                            @endif
                                            @if($converted_leads->user_id == null)
                                                <button disabled class="btn btn-sm btn-danger btn-remove-manager">Remove Manager</button>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-remove-manager" onclick="removeAccess({{ $converted_leads->id }}, {{ $converted_leads->user_id }})">Remove Manager</button>
                                                <button class="btn btn-sm {{ $converted_leads->manager_assign == 0 ? 'btn-success' : 'btn-danger' }} btn-access-manager" onclick="assignAccess({{ $converted_leads->id }}, {{ $converted_leads->user_id }})">{{ $converted_leads->manager_assign == 0 ? 'Enable Assign Access' : 'Remove Assign Access' }} </button>
                                            @endif
                                            @if($converted_leads->sale_agent == null && $converted_leads->user_id == null)
                                                <button class="btn btn-sm btn-success btn-assign" onclick="openReassign({{ $converted_leads->id }}, {{ $converted_leads->brand_id }})">Assign Lead</button>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-assign" onclick="openReassign({{ $converted_leads->id }}, {{ $converted_leads->brand_id }})">Reassign Lead</button>
                                            @endif
                                            <a href="/admin/leads/edit/{{ $converted_leads->id }}" class="btn btn-sm btn-info">Edit Lead</a>
                                            <a href="/admin/leads/delete/{{ $converted_leads->id }}" class="btn btn-sm btn-danger">Delete Lead</a>
                                        </td>
                                </tr>
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 6)
                                    @foreach($converted as $lead)
                                        @if($lead->status !== "Converted" || ($lead->status === "Converted" && $lead->manager_assign == 1))
                                            <tr>
                                                <td>
                                                    <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                                </td>
                                                <td>
                                                    <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                                    <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                                </td>
                                                <td>
                                                    @foreach($lead->services as $service)
                                                        <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                                    @endforeach
                                                </td>
                                                <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                                <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                                <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                                <td><button class="btn {{ !empty($lead->sale->name) ? 'btn-success' : 'btn-danger' }} btn-sm">{{ $lead->sale->name ?? 'Not Assigned' }} {{ $lead->sale->last_name ?? '' }}</button></td>
                                                <td>
                                                    <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                        <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                        <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                        <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                        <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                        <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                        <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                        <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                        <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    {{ $lead->assigned_on }} 
                                                </td>
                                                @if($lead->status === "Converted")
                                                    @if($lead->manager_assign == 1)
                                                    <td>
                                                        <button class="btn btn-sm btn-danger btn-assign"  onclick="openReassign({{ $lead->id }}, {{ $lead->brand_id }})">Assign</button>
                                                        <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                        <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                    </td>
                                                    @endif
                                                @else
                                                <td>
                                                    <button class="btn btn-sm btn-disabled">Access Denied</button>
                                                    <button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button>
                                                </td>
                                                @endif
                                    </tr>
                                        @endif
                                    @endforeach
                                @elseif(Auth::user()->is_employee == 0)
                                    @foreach($converted as $lead)
                                    <tr>
                                        <td>
                                            <span class="hidden-data" id="name-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="name-visible-{{ $lead->id }}" style="display: none;">{{ $lead->name }}</span>
                                        </td>
                                        <td>
                                            <span class="hidden-data" id="email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                        </td>
                                        <td>
                                            <span class="hidden-data" id="phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="visible-data" id="phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                        </td>
                                        <td>
                                            @foreach($lead->services as $service)
                                                <button class="btn btn-info btn-sm">{{ $service->name }}</button>
                                            @endforeach
                                        </td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lead->medium }}</button></td>
                                        <td><button class="btn btn-info btn-sm">{{ $lead->brand->name ?? 'N/A' }}</button></td>
                                        <td><button class="btn btn-warning btn-sm">{{ $lead->assignedBy->name ?? '' }}{{ $lead->assignedBy->last_name ?? '' }}</button></td>
                                        <td>
                                            <select id="status-select" class="form-control status-select" onchange="updateStatusColor(event, {{ $lead->id }})">
                                                <option value="Pending" {{ $lead->status == "Pending" ? 'selected' : '' }}>Pending</option>
                                                <option value="Contacted" {{ $lead->status == "Contacted" ? 'selected' : '' }}>Contacted</option>
                                                <option value="Follow-Up" {{ $lead->status == "Follow-Up" ? 'selected' : '' }}>Follow-Up</option>
                                                <option value="Re-Engaged" {{ $lead->status == "Re-Engaged" ? 'selected' : '' }}>Re-Engaged</option>
                                                <option value="Interested" {{ $lead->status == "Interested" ? 'selected' : '' }}>Interested</option>
                                                <option value="Proposal Sent" {{ $lead->status == "Proposal Sent" ? 'selected' : '' }}>Proposal Sent</option>
                                                <option value="Converted" {{ $lead->status == "Converted" ? 'selected' : '' }}>Converted</option>
                                                <option value="Lost" {{ $lead->status == "Lost" ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ $lead->assigned_on }} 
                                        </td>
                                        <td><button class="btn btn-danger btn-sm reveal-btn" data-id="{{ $lead->id }}">Reveal Data</button></td>
                                    </tr>
                                    @endforeach
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="notes-lead" role="tabpanel" aria-labelledby="notes-lead-tab">
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered datatable-init-note" style="width:100%">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Note</th>
                                <th>Action</th>
                            </thead>
                                @foreach($leads as $lead)
                                    @php
                                        $notes = json_decode($lead->notes, true);
                                    @endphp
                                @if($notes)
                                    @if(Auth::user()->is_employee == 2)
                                    <tr>
                                        <td>
                                            {{ $lead->name }}
                                        </td>
                                        <td>
                                            {{ $lead->email }}
                                        </td>
                                        <td>
                                            {{ $lead->phone }}
                                        </td>
                                        @elseif(Auth::user()->is_employee == 6 || Auth::user()->is_employee == 0)
                                        <td>
                                            {{ $lead->name }}
                                        </td>
                                        <td>
                                            <span class="note-hidden-data" id="note-email-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="note-visible-data" id="note-email-visible-{{ $lead->id }}" style="display: none;">{{ $lead->email }}</span>
                                        </td>
                                        <td>
                                            <span class="note-hidden-data" id="note-phone-{{ $lead->id }}"><button class="btn btn-danger btn-sm">****</button></span>
                                            <span class="note-visible-data" id="note-phone-visible-{{ $lead->id }}" style="display: none;">{{ $lead->phone }}</span>
                                        </td>
                                        @endif
                                        <td>
                                            @if($notes)
                                                @foreach($notes as $userId => $noteData)
                                                    @php
                                                        $user = \App\Models\User::find($userId);
                                                    @endphp
                                                    @if($user)
                                                        <strong>{{ $user->name }} ( {{ $noteData['date'] }} ):</strong></br> {{ $noteData['note'] }}</br>
                                                    @endif
                                                @endforeach
                                            @else
                                                No notes available.
                                            @endif
                                        </td>
                                        <td>
                                            @if(Auth::user()->is_employee == 2)
                                            <button type="button" class="btn btn-primary btn-sm pop-note-modal" data-lead-id="{{ $lead->id }}" data-route="/admin/leads">
                                                Write Note
                                            </button>
                                            @elseif(Auth::user()->is_employee == 6)
                                            <button type="button" class="btn btn-primary btn-sm pop-note-modal" data-lead-id="{{ $lead->id }}" data-route="/leads">
                                                Write Note
                                            </button>
                                            <button class="btn btn-danger btn-sm reveal-btn-note" data-lead-id="{{ $lead->id }}">Reveal Data</button>
                                            @elseif(Auth::user()->is_employee == 0)
                                            <button type="button" class="btn btn-primary btn-sm pop-note-modal" data-lead-id="{{ $lead->id }}" data-route="/sale/leads">
                                                Write Note
                                            </button>
                                            <button class="btn btn-danger btn-sm reveal-btn-note" data-lead-id="{{ $lead->id }}">Reveal Data</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Assign Agent</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @if(Auth::user()->is_employee == 2)
            <form action="{{ route('admin.assign.agent') }}" method="post">
            @elseif(Auth::user()->is_employee == 6)
            <form action="{{ route('manager.assign.agent') }}" method="post">
            @endif
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="lead_id" id="lead_id">
                    <div class="form-group">
                        <label class="col-form-label" for="agent-name-wrapper">Name:</label>
                        <select name="sale_agent" id="agent-name-wrapper" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary ml-2" type="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--NOTE MODAL-->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-3" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add/Update Note</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="noteForm" action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="note">Note:</label>
                        <textarea class="form-control" id="note" name="notes" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="noteForm">Save</button>
            </div>

        </div>
    </div>
</div>
@endsection



@push('scripts')
<script>
    $(document).ready(function(){
        if($('.datatable-init').length != 0){
            $('.datatable-init').DataTable({
                order: [[8, "desc"]],
                responsive: true,
            });
        }
        
        if($('.datatable-init-note').length != 0){
            $('.datatable-init-note').DataTable({
                order: [[4, "desc"]],
                responsive: true,
            });
        }
    });
    
    $('.pop-note-modal').on('click', function(){
        var id = $(this).attr("data-lead-id");
        var route = $(this).attr("data-route");
        var formRoute = `${route}/${id}/update-note`;
        $('#noteForm').attr('action', formRoute);
        $('#noteModal').modal('show');
    });
</script>
@if(Auth::user()->is_employee == 2)
<script>
    function updateStatusColor(event, lead_id) {
        if (!event) {
            console.error('Event object is not passed correctly');
            return;
        }
        var status = event.target.value;
        var leadId = lead_id;
        var route = '/admin/leads/update/status/' + leadId;
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                id: leadId,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.message,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Failed to update status',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                }
            },
            error: function(error) {
                swal({
                    type: 'error',
                    title: 'Error!',
                    text: 'Failed to update status',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-success',
                    timer: 2000
                });
            }
        });
    }
    
    function removeAccess(lead_id, user_id) {
        var leadId = lead_id;
        var userId = user_id;
        $.ajax({
            type:'POST',
            url: '/admin/remove/user',
            data: {
                lead_id: lead_id,
                user_id: userId,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.message,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Failed to update status',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                }
            },
        });
    }
    
    function assignAccess(lead_id, user_id) {
        var leadId = lead_id;
        var userId = user_id;
        $.ajax({
            type:'POST',
            url: '/admin/manager-access',
            data: {
                lead_id: lead_id,
                user_id: userId,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.message,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Failed to update status',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                }
            },
        });
    }
    
    
</script>
<script>
    function openReassign(lead_id, brand_id){
        $('#agent-name-wrapper').html('');
        $.ajax({
            type:'GET',
            url: '/admin/get/lead/users',
            data: {
                lead_id: lead_id,
                brand_id: brand_id
            },
            success:function(data) {
                var getData = data.data;
                for (var i = 0; i < getData.length; i++) {
                    var fullName = getData[i].name + ' ' + getData[i].last_name;
                    var role = getData[i].is_employee == 6 ? 'Sale Manager' : 'Sale Agent';
                    $('#agent-name-wrapper').append('<option ' + (getData[i].is_employee == 6 ? 'class="text-danger"' : 'style="color: #0F9ABB"') + ' value="' + getData[i].id + '">' + fullName + ' (' + role + ')</option>');
                }
            }
        });
        $('#assignModel').find('#lead_id').val(lead_id);
        $('#assignModel').modal('show');
    }
</script>

@endif
@if(Auth::user()->is_employee == 6)
<script>
    function updateStatusColor(event, lead_id) {
        if (!event) {
            console.error('Event object is not passed correctly');
            return;
        }
        var status = event.target.value;
        var leadId = lead_id;
        var route = '/leads/update/status/' + leadId;
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                id: leadId,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.message,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Failed to update status',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                }
            },
            error: function(error) {
                swal({
                    type: 'error',
                    title: 'Error!',
                    text: 'Failed to update status',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-success',
                    timer: 2000
                });
            }
        });
    }
</script>
<script>
    function openReassign(lead_id, brand_id){
        $('#agent-name-wrapper').html('');
        $.ajax({
            type:'GET',
            url: '/get/lead/users',
            data: {
                lead_id: lead_id,
                brand_id: brand_id
            },
            success:function(data) {
                var getData = data.data;
                for (var i = 0; i < getData.length; i++) {
                    $('#agent-name-wrapper').append('<option value="'+getData[i].id+'">'+getData[i].name+ ' ' +getData[i].last_name+'</option>');
                }
            }
        });
        $('#assignModel').find('#lead_id').val(lead_id);
        $('#assignModel').modal('show');
    }
    
    $('.reveal-btn').on('click', function() {
        var id = $(this).data('id');
        
        $('#email-' + id).hide();
        $('#email-visible-' + id).show();
        
        $('#phone-' + id).hide();
        $('#phone-visible-' + id).show();
        
        $.ajax({
            url: '/lead-viewed',
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                
            },
            error: function(error) {
                
            }
        });
        $('#name-' + id).hide();
        $('#name-visible-' + id).show();
    });
    
    $('.reveal-btn-note').on('click', function() {
        var id = $(this).data('lead-id');
        
        $('#note-email-' + id).hide();
        $('#note-email-visible-' + id).show();
        
        $('#note-phone-' + id).hide();
        $('#note-phone-visible-' + id).show();
        
        $.ajax({
            url: '/lead-viewed',
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                
            },
            error: function(error) {
                
            }
        });
    });
</script>
@endif
@if(Auth::user()->is_employee == 0)
<script>
    function updateStatusColor(event, lead_id) {
        if (!event) {
            console.error('Event object is not passed correctly');
            return;
        }
        var status = event.target.value;
        var leadId = lead_id;
        var route = '/sale/leads/update/status/' + leadId;
        $.ajax({
            url: route,
            type: 'POST',
            data: {
                id: leadId,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.message,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: 'Failed to update status',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-lg btn-success',
                        timer: 2000
                    });
                }
            },
            error: function(error) {
                swal({
                    type: 'error',
                    title: 'Error!',
                    text: 'Failed to update status',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-success',
                    timer: 2000
                });
            }
        });
    }
    
    $('.reveal-btn').on('click', function() {
        var id = $(this).data('id');
        $('#name-' + id).hide();
        $('#name-visible-' + id).show();
        
        $('#email-' + id).hide();
        $('#email-visible-' + id).show();
        
        $('#phone-' + id).hide();
        $('#phone-visible-' + id).show();
        
        $.ajax({
            url: '/sale/lead-viewed',
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                
            },
            error: function(error) {
                
            }
        });
    });
</script>
@endif

@endpush
