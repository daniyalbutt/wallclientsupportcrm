@extends('layouts.app-admin')
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('global/css/fileinput.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('newglobal/css/image-uploader.min.css') }}">
<style>
    ul.task_main_list {
        padding: 0px;
        margin-bottom: 0px;
        list-style: none;
    }
    .task_main_list-wrapper p {
        margin-bottom: 0px;
    }
    .fade:not(.show) {
        display: none;
    }
</style>
@endpush
@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">{{$task->projects->name}} (ID: {{$task->id}})</h1>
    <ul>
        <li>Tasks</li>
        <li>Show Task</li>
    </ul>
    <div class="task-page ml-auto">
        {!!$task->project_status()!!}
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>

<section id="basic-form-layouts">
<div class="row mb-4">
        <div class="col-md-12">                        
            <div class="card text-left mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="task-overview-tab" data-toggle="tab" href="#task-overview" role="tab" aria-controls="task-overview" aria-selected="true">Task Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="message-show-tab" data-toggle="tab" href="#message-show" role="tab" aria-controls="message-show" aria-selected="false">Message</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes-show" role="tab" aria-controls="notes-show" aria-selected="false">Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="form-tab" data-toggle="tab" href="#form-show" role="tab" aria-controls="form-show" aria-selected="false">Brief Form</a>
                        </li>
                    </ul>
                </div>    
            </div>
            <div class="" id="myTabContent">
                <div class="tab-pane fade show active" id="task-overview" role="tabpanel" aria-labelledby="task-overview-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Task Details</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <ul class="task_main_list">
                                        <li>
                                            {!! $task->description !!}
                                        </li>
                                        <li>
                                            <div class="task_main_list-wrapper">
                                                <p>Due Date: {{ date('d M, y', strtotime($task->duedate)) }} | Created
                                                    At: {{ $task->created_at->format('d M, y h:i a') }}</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Update Files</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <input id="image-file" type="file" name="images[]" multiple
                                        data-browse-on-zone-click="true">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Files</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <div class="table-responsive">
                                        <table class="display table table-striped table-bordered"
                                            id="zero_configuration_table" style="width:100%">
                                            <thead>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Uploaded By</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($task->client_files as $client_files)
                                                @foreach($img_arr as $key => $presignedUrl)
                                                @if($client_files->id == $key)
                                                <tr>
                                                    <td>{{$client_files->id}}</td>
                                                    <td>
                                                        @php
                                                        $parsedUrl = parse_url($presignedUrl);

                                                        // Extract the file name and extension from the path
                                                        $pathInfo = pathinfo($parsedUrl['path']);
                                                        $fileName = $pathInfo['filename'];
                                                        $extension = $pathInfo['extension'];
                                                        @endphp
                                                        @if(($extension == 'jpg') || ($extension == 'png') ||
                                                        ($extension == 'jpeg') || ($extension == 'webp'))
                                                        <a id="all-images" href="#show_image_popup">
                                                            <img class="small-image" src="{{ $presignedUrl }}"
                                                                alt="{{ $fileName }}.{{$extension}}" width="100">
                                                        </a>
                                                        @else
                                                        <a href="{{ $presignedUrl }}" target="_blank">
                                                            {{ $fileName }}.{{$extension}}
                                                        </a>
                                                        @endif
                                                    </td>
                                                    <td>{{$client_files->name}}</td>
                                                    <td><button
                                                            class="btn btn-secondary btn-sm">{{$client_files->user->name}}</button>
                                                    </td>
                                                    <td><button
                                                            class="btn btn-primary btn-sm">{{$client_files->created_at->format('d M, y h:m a') }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group float-md-right ml-1">
                                                            <button class="btn btn-info dropdown-toggle btn-sm"
                                                                type="button" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false"><i
                                                                    class="i-Edit"></i></button>
                                                            <div class="dropdown-menu arrow">
                                                                <a class="dropdown-item" href="{{ $presignedUrl }}"
                                                                    target="_blank"> View</a>
                                                                <a class="dropdown-item" href="{{ $presignedUrl }}"
                                                                    download> Download</a>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="deleteFile({{$client_files->id}})">
                                                                    Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Create Sub Task</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <form class="form" action="{{route('admin.subtask.store')}}" method="POST"
                                        id="subtask" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                        <div class="form-body">
                                            <div class="form-group">
                                                <textarea id="description" rows="5" class="form-control border-primary"
                                                    name="description" placeholder="Sub Task Details"
                                                    required>{{old('description')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label for="">Due Date</label>
                                                <input type="date" class="form-control" name="duedate" id="duedate">
                                            </div>
                                        </div>
                                        <div class="form-actions pb-0">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h4 class="card-title mb-0">Sub Task</h4>
                                </div>
                            </div>
                            @foreach($task->sub_tasks as $sub_tasks)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="card-content collapse show">
                                        <div class="ul-widget__body">
                                            <div class="ul-widget3" id="subtask_show">
                                                <div class="ul-widget3-item">
                                                    <div class="ul-widget3-header">
                                                        <div class="ul-widget3-img">
                                                            @if($sub_tasks->user->image != '')
                                                            <img id="userDropdown"
                                                                src="{{ asset($sub_tasks->user->image) }}" alt=""
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            @else
                                                            <img id="userDropdown"
                                                                src="{{ asset('global/img/user.png') }}" alt=""
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            @endif
                                                        </div>
                                                        <div class="ul-widget3-info">
                                                            <a class="__g-widget-username" href="#">
                                                                <span class="t-font-bolder">{{ $sub_tasks->user->name }}
                                                                    {{ $sub_tasks->user->last_name }}</span>
                                                            </a>
                                                            <br>
                                                            <span
                                                                class="ul-widget-notification-item-time">{{ $sub_tasks->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <span class="ul-widget3-status text-success t-font-bolder"
                                                            style="display: flex;justify-content: end;gap: 20px;">
                                                            @if($sub_tasks->duedate != null)
                                                            <div class="left">
                                                                Due Date <br>
                                                                {{ date('d M, y', strtotime($sub_tasks->duedate)) }}
                                                            </div>
                                                            @endif
                                                            @if($sub_tasks->duedateChange != null)
                                                            <div class="right"
                                                                style="border-left: 1px solid #e5e5e5;padding-left: 15px;">
                                                                Changed By {{ $sub_tasks->duedateChange->user->name }}
                                                                {{ $sub_tasks->duedateChange->user->last_name }}<br>
                                                                From
                                                                {{ date('d M, y', strtotime($sub_tasks->duedateChange->duadate)) }}
                                                                to {{ date('d M, y', strtotime($sub_tasks->duedate)) }}
                                                            </div>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="ul-widget3-body">
                                                        {!! $sub_tasks->description !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="message-show" role="tabpanel" aria-labelledby="message-show-tab">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-primary ml-auto" id="write-message">Write A Message</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 message-box-wrapper">
                            @foreach($messages as $message)
                            <div class="card mb-3 {{ $message->role_id == 4 ? 'right-card' : 'left-card' }}">
                                <div class="card-body">
                                    <div class="card-content collapse show">
                                        <div class="ul-widget__body mt-0">
                                            <div class="ul-widget3 message_show">
                                                <div class="ul-widget3-item mt-0 mb-0">
                                                    <div class="ul-widget3-header">
                                                        <div class="ul-widget3-info">
                                                            <a class="__g-widget-username" href="#">
                                                                <span class="t-font-bolder">{{ $message->user->name }}
                                                                    {{ $message->user->last_name }}</span>
                                                            </a>
                                                        </div>
                                                        <button class="btn-sm btn btn-primary"
                                                            onclick="editMessage({{$message->id}})">Edit
                                                            Message</button>
                                                    </div>
                                                    <div class="ul-widget3-body">
                                                        <p>{!! nl2br($message->message) !!}</p>
                                                        <span class="ul-widget3-status text-success t-font-bolder">
                                                            {{ date('d M, y', strtotime($message->created_at)) }}
                                                        </span>
                                                    </div>
                                                    <div class="file-wrapper">
                                                        @if(count($message->sended_client_files) != 0)
                                                        @foreach($message->sended_client_files as $key => $client_file)
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-dark btn-sm">{{++$key}}</button>
                                                            </li>
                                                            <li>
                                                                @if(($client_file->get_extension() == 'webp') ||
                                                                ($client_file->get_extension() == 'jpg') ||
                                                                ($client_file->get_extension() == 'png') ||
                                                                (($client_file->get_extension() == 'jpeg')))
                                                                <a href="{{asset('files/'.$client_file->path)}}"
                                                                    target="_blank">
                                                                    <img src="{{asset('files/'.$client_file->path)}}"
                                                                        alt="{{$client_file->name}}" width="40">
                                                                </a>
                                                                @else
                                                                <a href="{{asset('files/'.$client_file->path)}}"
                                                                    target="_blank">
                                                                    {{$client_file->name}}.{{$client_file->get_extension()}}
                                                                </a>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <a href="{{asset('files/'.$client_file->path)}}"
                                                                    target="_blank">{{$client_file->name}}</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{asset('files/'.$client_file->path)}}"
                                                                    download>Download</a>
                                                            </li>
                                                        </ul>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="notes-show" role="tabpanel" aria-labelledby="notes-show-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Additional Notes</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <form action="{{ route('store.notes.by.manager') }}" id="additional-notes">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                        <div class="form-group">
                                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control"
                                                required>{{ $task->notes }}</textarea>
                                        </div>
                                        <div class="form-actions pb-0 text-right">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                    <div>
                                        <div>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="form-show" role="tabpanel" aria-labelledby="form-show-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    @php
                                        use App\Models\LogoForm;
                                        use App\Models\WebForm;
                                        use App\Models\SmmForm;
                                        use App\Models\ContentWritingForm;
                                        use App\Models\SeoForm;
                                        use App\Models\BookFormatting;
                                        use App\Models\BookWriting;
                                        use App\Models\AuthorWebsite;
                                        use App\Models\Proofreading;
                                        use App\Models\BookCover;
                                    @endphp
                                    @if($task->projects->form_checker == 0)
                                    <a href="javascript:;" class="btn btn-danger btn-sm">No-Form</a>
                                    @elseif($task->projects->form_checker == 1)
                                    @php $logo_form = LogoForm::find($task->projects->form_id); @endphp
                                    
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Details</div>
                                            <div class="row">
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="client_name">Client Name</label>
                                                    <input disabled class="form-control" name="client_name" id="client_name" type="text" placeholder="{{ $logo_form->user->name }} {{ $logo_form->user->last_name }}" value="{{ $logo_form->user->name }} {{ $logo_form->user->last_name }}" required readonly/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="agent_name">Agent Name</label>
                                                    <input disabled class="form-control" name="agent_name" id="agent_name" type="text" placeholder="{{ $logo_form->invoice->sale->name }} {{ $logo_form->invoice->sale->last_name }}" value="{{ $logo_form->invoice->sale->name }} {{ $logo_form->invoice->sale->last_name }}" readonly required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="brand_name">Brand Name</label>
                                                    <input disabled class="form-control" name="brand_name" id="brand_name" type="text" placeholder="{{ $logo_form->invoice->brands->name }}" value="{{ $logo_form->invoice->brands->name }}" readonly required/>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Logo Brief Form</div>
                                            <div class="row">
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="logo_name">Exact logo wording ( logo name )</label>
                                                    <input disabled class="form-control" name="logo_name" id="logo_name" type="text" placeholder="Enter Logo Name" value="{{ old('logo_name', $logo_form->logo_name) }}" required/>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="slogan">Slogan/Tagline if any (optional)</label>
                                                    <input disabled class="form-control" name="slogan" id="slogan" type="text" placeholder="Enter Slogan/Tagline" value="{{ old('slogan', $logo_form->slogan) }}" required/>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="business">Describe your business / service / organization</label>
                                                    <textarea disabled class="form-control" name="business" id="business" rows="5" required>{{ old('business', $logo_form->business) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Logo Categories</div>
                                            @php
                                                $logo_categories = json_decode($logo_form->logo_categories);
                                            @endphp
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label for="just_font">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="just_font" name="logo_categories[]" name="just_font" value="Just font" @if($logo_categories != null) {{ in_array('Just font', $logo_categories) ? ' checked' : '' }} @endif>
                                                                <h6>Just font</h6>
                                                                Just font without any symbolic intervention.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/just_font.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="handmade">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="handmade" name="logo_categories[]" name="Handmade" value="Handmade" @if($logo_categories != null) {{ in_array('Handmade', $logo_categories) ? ' checked' : '' }} @endif>
                                                                <h6>Handmade</h6>
                                                                A calligraphic, handwritten or script font.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/handmade.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="font_meaning">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="font_meaning" name="logo_categories[]" value="Font + meaning" @if($logo_categories != null) {{ in_array('Font + meaning', $logo_categories) ? ' checked' : '' }} @endif>
                                                                <h6>Font + meaning</h6>
                                                                A font with a tweak that simbolize company/ product/service
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/font_meaning.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="initials">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="initials" name="logo_categories[]" value="Initials" @if($logo_categories != null) {{ in_array('Initials', $logo_categories) ? ' checked' : '' }} @endif>
                                                                <h6>Initials</h6>
                                                                Monogram with Company name initials.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/initials.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="font_including_shape">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="font_including_shape" name="logo_categories[]" value="Font including in a shape" @if($logo_categories != null) {{ in_array('Font including in a shape', $logo_categories) ? ' checked' : '' }} @endif>
                                                                <h6>Font including in a shape</h6>
                                                                Company name inside / squares / ovals / rectangles or combined shapes.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/font_including_shape.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Icon Based Logo</div>
                                            @php
                                                $icon_based_logo = json_decode($logo_form->icon_based_logo);
                                            @endphp
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label for="abstract_graphic">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="abstract_graphic" name="icon_based_logo[]" value="Abstract graphic" @if($icon_based_logo != null) {{ in_array('Abstract graphic', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Abstract graphic</h6>
                                                                A sinthetic symbol that represents your Company in a subtle way
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/abstract_graphic.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="silhouet">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="silhouet" name="icon_based_logo[]" value="Silhouet" @if($icon_based_logo != null) {{ in_array('Silhouet', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Silhouet</h6>
                                                                A detailed illustrated silhouet
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/silhouet.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="geometric_symbol">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="geometric_symbol" name="icon_based_logo[]" value="Geometric symbol" @if($icon_based_logo != null) {{ in_array('Geometric symbol', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Geometric symbol</h6>
                                                                A geometric symbol that clearly represents an element.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/geometric_symbol.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="illustrated_symbol">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="illustrated_symbol" name="icon_based_logo[]" value="Illustrated symbol" @if($icon_based_logo != null) {{ in_array('Illustrated symbol', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Illustrated symbol</h6>
                                                                An illustrated symbol that clearly represents an element.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/illustrated_symbol.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="detailed_illustration">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="detailed_illustration" name="icon_based_logo[]" value="Detailed illustration" @if($icon_based_logo != null) {{ in_array('Detailed illustration', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Detailed illustration</h6>
                                                                A specific illustration.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/detailed_illustration.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="mascot">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="mascot" name="icon_based_logo[]" value="Mascot" @if($icon_based_logo != null) {{ in_array('Mascot', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Mascot</h6>
                                                                A character representing your Company.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/mascot.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="seals_and_crests">
                                                        <div class="formCheck">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="seals_and_crests" name="icon_based_logo[]" value="Seals and crests" @if($icon_based_logo != null) {{ in_array('Seals and crests', $icon_based_logo) ? ' checked' : '' }} @endif>
                                                                <h6>Seals and crests</h6>
                                                                A detailed crest or seal with just text or maybe including graphics.
                                                            </div>
                                                            <img src="{{ asset('newglobal/images/seals_and_crests.png') }}">
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Font Style</div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="comic" name="font_style" value="Comic" {{ $logo_form->font_style == 'Comic' ? 'checked' : '' }}>
                                                            <label for="comic" class="comic"><img src="{{ asset('newglobal/images/comic.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="stencil" name="font_style" value="Stencil" {{ $logo_form->font_style == 'Stencil' ? 'checked' : '' }}>
                                                            <label for="stencil" class="comic"><img src="{{ asset('newglobal/images/stencil.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="gothic" name="font_style" value="Gothic" {{ $logo_form->font_style == 'Gothic' ? 'checked' : '' }}>
                                                            <label for="gothic" class="comic"><img src="{{ asset('newglobal/images/gothic.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input" id="decorative" name="font_style" value="Decorative" {{ $logo_form->font_style == 'Decorative' ? 'checked' : '' }}>
                                                            <label for="decorative" class="comic"><img src="{{ asset('newglobal/images/decorative.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="typewriter" name="font_style" value="Typewriter" {{ $logo_form->font_style == 'Typewriter' ? 'checked' : '' }}>
                                                            <label for="typewriter" class="comic"><img src="{{ asset('newglobal/images/typewriter.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="eroded" name="font_style" value="Eroded" {{ $logo_form->font_style == 'Eroded' ? 'checked' : '' }}>
                                                            <label for="eroded" class="comic"><img src="{{ asset('newglobal/images/eroded.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="graffiti" name="font_style" value="Graffiti" {{ $logo_form->font_style == 'Graffiti' ? 'checked' : '' }}>
                                                            <label for="graffiti" class="comic"><img src="{{ asset('newglobal/images/graffiti.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="pixelated" name="font_style" value="Pixelated" {{ $logo_form->font_style == 'Pixelated' ? 'checked' : '' }}>
                                                            <label for="pixelated" class="comic"><img src="{{ asset('newglobal/images/pixelated.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="retro" name="font_style" value="Retro" {{ $logo_form->font_style == 'Retro' ? 'checked' : '' }}>
                                                            <label for="retro" class="comic"><img src="{{ asset('newglobal/images/retro.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="techno" name="font_style" value="Techno" {{ $logo_form->font_style == 'Techno' ? 'checked' : '' }}>
                                                            <label for="techno" class="comic"><img src="{{ asset('newglobal/images/techno.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="sansserif" name="font_style" value="Sans serif" {{ $logo_form->font_style == 'Sans serif' ? 'checked' : '' }}>
                                                            <label for="sansserif" class="comic"><img src="{{ asset('newglobal/images/sansserif.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="serif" name="font_style" value="Serif" {{ $logo_form->font_style == 'Serif' ? 'checked' : '' }}>
                                                            <label for="serif" class="comic"><img src="{{ asset('newglobal/images/serif.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="calligraphy" name="font_style" value="Calligraphy" {{ $logo_form->font_style == 'Calligraphy' ? 'checked' : '' }}>
                                                            <label for="calligraphy" class="comic"><img src="{{ asset('newglobal/images/calligraphy.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="formCheck font-box">
                                                        <div class="form-check">
                                                            <input disabled type="radio" class="form-check-input" id="script" name="font_style" value="Script" {{ $logo_form->font_style == 'Script' ? 'checked' : '' }}>
                                                            <label for="script" class="comic"><img src="{{ asset('newglobal/images/script.jpg') }}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                <div class="formCheck font-box">
                                                    <div class="form-check">
                                                        <input disabled type="radio" class="form-check-input" id="handwritten" name="font_style" value="Handwritten" {{ $logo_form->font_style == 'Handwritten' ? 'checked' : '' }}>
                                                        <label for="handwritten" class="comic"><img src="{{ asset('newglobal/images/handwritten.jpg') }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Additional information (optional)</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="additional_information">Describe your business / service / organization</label>
                                                    <textarea disabled name="additional_information" id="additional_information" class="form-control">{{ old('additional_information', $logo_form->additional_information) }}</textarea>
                                                </div>
                                                <!-- <div class="col-md-12 mt-1">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 2)
                                    @php $web_form = WebForm::find($task->projects->form_id); @endphp
                                    
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Details</div>
                                            <div class="row">
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="client_name">Client Name</label>
                                                    <input disabled class="form-control" name="client_name" id="client_name" type="text" placeholder="{{ $web_form->user->name }} {{ $web_form->user->last_name }}" value="{{ $web_form->user->name }} {{ $web_form->user->last_name }}" required readonly/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="agent_name">Agent Name</label>
                                                    <input disabled class="form-control" name="agent_name" id="agent_name" type="text" placeholder="{{ $web_form->invoice->sale->name }} {{ $web_form->invoice->sale->last_name }}" value="{{ $web_form->invoice->sale->name }} {{ $web_form->invoice->sale->last_name }}" readonly required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="brand_name">Brand Name</label>
                                                    <input disabled class="form-control" name="brand_name" id="brand_name" type="text" placeholder="{{ $web_form->invoice->brands->name }}" value="{{ $web_form->invoice->brands->name }}" readonly required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Web Brief Form</div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="business_name">Business Name</label>
                                                        <input disabled type="text" class="form-control" id="business_name" placeholder="Enter Business Name" name="business_name" required value="{{ old('business_name', $web_form->business_name) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="website_address">Website address – or desired Domain(s)</label>
                                                        <input disabled type="text" class="form-control" id="website_address" placeholder="Enter Website address – or desired Domain(s)" name="website_address" required value="{{ old('website_address', $web_form->website_address) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="address">Address</label>
                                                        <input disabled type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="{{ old('address', $web_form->address) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="decision_makers">Are there other decision makers? Please specify</label>
                                                        <textarea disabled name="decision_makers" id="decision_makers" class="form-control" rows="4">{{ old('decision_makers', $web_form->decision_makers) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Tell me about Your Company</div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="about_company">Please give me a brief overview of the company, what you do or produce?</label>
                                                        <textarea disabled name="about_company" id="about_company" class="form-control" rows="4">{{ old('about_company', $web_form->decision_makers) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $purpose = json_decode($web_form->purpose);
                                    @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">What is the purpose of this site?</div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="products_service"> 
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="products_service" name="purpose[]" value="Explain your products and services" @if($purpose != null) {{ in_array('Explain your products and services', $purpose) ? ' checked' : '' }} @endif>Explain your products and services
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="bring_client">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="bring_client" name="purpose[]" value="Bring in new clients to your business" @if($purpose != null) {{ in_array('Bring in new clients to your business', $purpose) ? ' checked' : '' }} @endif>Bring in new clients to your business
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="news">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="news" name="purpose[]" value="Deliver news or calendar of events" @if($purpose != null) {{ in_array('Deliver news or calendar of events', $purpose) ? ' checked' : '' }} @endif>Deliver news or calendar of events
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="certain_subject">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="certain_subject" name="purpose[]" value="Provide your customers with information on a certain subject" @if($purpose != null) {{ in_array('Provide your customers with information on a certain subject', $purpose) ? ' checked' : '' }} @endif>Provide your customers with information on a certain subject
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="blog">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="blog" name="purpose[]" value="Create a blog that addresses specific topics or interests" @if($purpose != null) {{ in_array('Create a blog that addresses specific topics or interests', $purpose) ? ' checked' : '' }} @endif>Create a blog that addresses specific topics or interests
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="sell_product">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="sell_product" name="purpose[]" value="Sell a product or products online" @if($purpose != null) {{ in_array('Sell a product or products online', $purpose) ? ' checked' : '' }} @endif>Sell a product or products online
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="provide_support">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="provide_support" name="purpose[]" value="Provide support for current clients" @if($purpose != null) {{ in_array('Provide support for current clients', $purpose) ? ' checked' : '' }} @endif>Provide support for current clients
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Do you have a time frame or deadline to get this site online?</div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="deadline">If you have a specific deadline, please state why?</label>
                                                        <textarea disabled name="deadline" id="deadline" class="form-control" rows="4">{{ old('deadline', $web_form->deadline) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Target market</div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="potential_clients">Who will visit this site? Describe your potential clients. (Young, old, demographics, etc) </label>
                                                        <textarea disabled name="potential_clients" id="potential_clients" class="form-control" rows="4">{{ old('potential_clients', $web_form->potential_clients) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="competitor">Why do you believe site visitors should do business with you rather than with a competitor? What problem are you solving for them?</label>
                                                        <textarea name="competitor" id="competitor" class="form-control" rows="4">{{ old('competitor', $web_form->competitor) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $user_perform = json_decode($web_form->user_perform);
                                    @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">What action(s) should the user perform when visiting your site?</div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="call_you">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="call_you" name="user_perform[]" value="Call you" @if($user_perform != null) {{ in_array('Call you', $user_perform) ? ' checked' : '' }} @endif> Call You
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="contact_form">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="contact_form" name="user_perform[]" value="Fill out a contact form" @if($user_perform != null) {{ in_array('Fill out a contact form', $user_perform) ? ' checked' : '' }} @endif> Fill out a contact form
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="quote_form">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="quote_form" name="user_perform[]" value="Fill out a quote form" @if($user_perform != null) {{ in_array('Fill out a quote form', $user_perform) ? ' checked' : '' }} @endif> Fill out a quote form
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="mailing_list">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="mailing_list" name="user_perform[]" value="Sign up for your mailing list" @if($user_perform != null) {{ in_array('Sign up for your mailing list', $user_perform) ? ' checked' : '' }} @endif> Sign up for your mailing list
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="search_information">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="search_information" name="user_perform[]" value="Search for information" @if($user_perform != null) {{ in_array('Search for information', $user_perform) ? ' checked' : '' }} @endif> Search for information
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="purchase_product">
                                                        <div class="formCheck purpose-box">
                                                            <div class="form-check">
                                                                <input disabled type="checkbox" class="form-check-input" id="purchase_product" name="user_perform[]" value="Purchase a product(s)" @if($user_perform != null) {{ in_array('Purchase a product(s)', $user_perform) ? ' checked' : '' }} @endif>Purchase a product(s)
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $pages = json_decode($web_form->pages);
                                    @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Content  <div class="form-group"> <label for=""> What are you offering? Make a list of all the sections/pages you think that you'll need. (Samples below are just an example to get you started, please fill this out completely.)</label></div></div>
                                            <div class="row">
                                                @if($pages == null)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="page_one">Page</label>
                                                        <input disabled type="text" class="form-control" id="page_one" placeholder="Enter Page Name" name="page[page_name][]" value="Home">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="content_notes_one">Content Notes</label>
                                                        <input disabled type="text" class="form-control" id="content_notes_one" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_two" placeholder="Enter Page Name" name="page[page_name][]" value="Contact Us">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_two" placeholder="Enter Content Notes" name="page[content_notes][]" value="Form needed?">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_three" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_three" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_four" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_four" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_five" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_five" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_six" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_six" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_seven" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_seven" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_eight" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_eight" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_nine" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_nine" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="page_ten" placeholder="Enter Page Name" name="page[page_name][]">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="content_notes_ten" placeholder="Enter Content Notes" name="page[content_notes][]">
                                                    </div>
                                                </div>
                                                @else
                                                <div class="col-md-6">
                                                @foreach($pages->page_name as $key => $page_name)
                                                    <div class="form-group">
                                                        @if($loop->first)
                                                        <label for="page_one">Page</label>
                                                        @endif
                                                        <input disabled type="text" class="form-control" id="page_{{$key}}" placeholder="Enter Page Name" name="page[page_name][]" value="{{ $page_name }}">
                                                    </div>
                                                @endforeach
                                                </div>
                                                <div class="col-md-6">
                                                @foreach($pages->content_notes as $key => $content_notes)
                                                    <div class="form-group">
                                                        @if($loop->first)
                                                        <label for="content_notes_{{$key}}">Content Notes</label>
                                                        @endif
                                                        <input disabled type="text" class="form-control" id="content_notes_{{$key}}" placeholder="Enter Content Notes" name="page[content_notes][]" value="{{ $content_notes }}">
                                                    </div>
                                                @endforeach
                                                </div>
                                                <div class="w-100"></div>
                                                @endif
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="written_content">Do you have the written content and images/photographs prepared for these pages? </label>
                                                        <input disabled type="text" class="form-control" id="written_content" name="written_content" value="{{ old('written_content', $web_form->written_content) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="copywriting_photography_services">If not, will you need copywriting and photography services?</label>
                                                        <select disabled name="copywriting_photography_services" class="form-control" id="copywriting_photography_services">
                                                            <option value="0" {{ $web_form->copywriting_photography_services == 0 ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{ $web_form->copywriting_photography_services == 1 ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cms_site">Are you willing to commit time/effort into learning how to use Content Management System (CMS) and edit your site?</label>
                                                        <select disabled name="cms_site" class="form-control" id="cms_site">
                                                            <option value="0" {{ $web_form->cms_site == 0 ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{ $web_form->cms_site == 1 ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="re_design">Is this a site re-design?</label>
                                                        <select disabled name="re_design" class="form-control" id="re_design">
                                                            <option value="0" {{ $web_form->re_design == 0 ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{ $web_form->re_design == 1 ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="working_current_site">If yes, can you please explain what is working and not working on your current site?</label>
                                                        <select disabled name="working_current_site" class="form-control" id="working_current_site">
                                                            <option value="0" {{ $web_form->working_current_site == 0 ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{ $web_form->working_current_site == 1 ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @php
                                                $going_to_need = json_decode($web_form->going_to_need);
                                                @endphp
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Are you going to need?</label>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <label for="going_to_need_ecommerce">
                                                                    <div class="formCheck purpose-box">
                                                                        <div class="form-check">
                                                                            <input disabled type="checkbox" class="form-check-input" id="going_to_need_ecommerce" name="going_to_need[]" value="Ecommerce (sell products)" @if($going_to_need != null) {{ in_array('Ecommerce (sell products)', $going_to_need) ? ' checked' : '' }} @endif> Ecommerce (sell products)
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <label for="going_to_need_membership">
                                                                    <div class="formCheck purpose-box">
                                                                        <div class="form-check">
                                                                            <input disabled type="checkbox" class="form-check-input" id="going_to_need_membership" name="going_to_need[]" value="Membership of any kind" @if($going_to_need != null) {{ in_array('Membership of any kind', $going_to_need) ? ' checked' : '' }} @endif> Membership of any kind
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="additional_features">Are there any additional features that you would like for your site or things that you would like to add in the future? Please be as specific and detailed as possible.</label>
                                                        <textarea disabled name="additional_features" id="additional_features" class="form-control" rows="4">{{ old('additional_features', $web_form->additional_features) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Design</div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="feel_about_company">People are coming to your new site for the first time. How do you want them to feel about your company?</label>
                                                        <textarea disabled name="feel_about_company" id="feel_about_company" class="form-control" rows="4">{{ old('feel_about_company', $web_form->feel_about_company) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="incorporated">Are there corporate colors, logo, fonts etc. <br>that should be incorporated? </label>
                                                        <textarea disabled name="incorporated" id="incorporated" class="form-control" rows="4">{{ old('incorporated', $web_form->incorporated) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="need_designed">If you do not already have a logo, are you going to need one designed?</label>
                                                        <textarea disabled name="need_designed" id="need_designed" class="form-control" rows="4">{{ old('need_designed', $web_form->need_designed) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="specific_look">Is there a specific look and feel that you have in mind?</label>
                                                        <textarea disabled name="specific_look" id="specific_look" class="form-control" rows="4">{{ old('specific_look', $web_form->specific_look) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $competition = json_decode($web_form->competition);
                                    @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Websites of your closest competition <div class="form-group"><label>Please include at least 3 links of sites of your competition. What do you like and don't like about them? What would you like to differently or better?</label></div></div>
                                            <div class="row">
                                                @if($competition == null)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="competition_one" placeholder="Enter Competition 1" name="competition[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="competition_two" placeholder="Enter Competition 2" name="competition[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="competition_three" placeholder="Enter Competition 3" name="competition[]">
                                                    </div>
                                                </div>
                                                @else
                                                @for($i = 0; $i < count($competition); $i++)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="competition_{{$i}}" placeholder="Enter Competition {{$i+1}}" name="competition[]" value="{{ $competition[$i] }}">
                                                    </div>
                                                </div>
                                                @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                    $websites_link = json_decode($web_form->websites_link);
                                    @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Websites that we like <div class="form-group"><label>Along with putting down the site address, please comment on what you like about each site, i.e. the look and feel, functionality, colors etc. These do not have to have anything to do with your business, but could have features you like. Please include at least 3 examples.</label></div></div>
                                            <div class="row">
                                                @if($competition == null)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="websites_link_one" placeholder="Enter Website 1" name="websites_link[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="websites_link_two" placeholder="Enter Website 2" name="websites_link[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="websites_link_three" placeholder="Enter Website 3" name="websites_link[]">
                                                    </div>
                                                </div>
                                                @else
                                                @for($i = 0; $i < count($websites_link); $i++)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input disabled type="text" class="form-control" id="websites_link_{{$i}}" placeholder="Enter Website {{$i+1}}" name="websites_link[]" value="{{ $websites_link[$i] }}">
                                                    </div>
                                                </div>
                                                @endfor
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Marketing the Site</div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="people_find_business">How do people find out about your business right now?</label>
                                                        <input disabled type="text" class="form-control" id="people_find_business" name="people_find_business" value="{{ old('people_find_business', $web_form->people_find_business) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="market_site">Have you thought about how you're going to market this site? </label>
                                                        <input disabled type="text" class="form-control" id="market_site" name="market_site" value="{{ old('market_site', $web_form->market_site) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="accounts_setup">Do you have any social network accounts setup? (Facebook etc)</label>
                                                        <input disabled type="text" class="form-control" id="accounts_setup" name="accounts_setup" value="{{ old('accounts_setup', $web_form->accounts_setup) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="links_accounts_setup">Do you want links to those accounts on your site?</label>
                                                        <input disabled type="text" class="form-control" id="links_accounts_setup" name="links_accounts_setup" value="{{ old('links_accounts_setup', $web_form->links_accounts_setup) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="service_account">Do you have a mail service account? (Constant Contact, MailChimpetc)</label>
                                                        <input disabled type="text" class="form-control" id="service_account" name="service_account" value="{{ old('service_account', $web_form->service_account) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="use_advertising">Will you want to build your mailing list and use it for advertising &amp; newsletters?</label>
                                                        <input disabled type="text" class="form-control" id="use_advertising" name="use_advertising" value="{{ old('use_advertising', $web_form->use_advertising) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="printed_materials">Will you want printed materials (business cards, catalog, etc.) produced as well?</label>
                                                        <input disabled type="text" class="form-control" id="printed_materials" name="printed_materials" value="{{ old('printed_materials', $web_form->printed_materials) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Domain and Hosting</div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="domain_name">Do you already own a domain name(s)? (www.mygreatsite.com)</label>
                                                        <input disabled type="text" class="form-control" id="domain_name" name="domain_name" value="{{ old('domain_name', $web_form->domain_name) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hosting_account">Do you have a hosting account already? (This is where the computer files live.)</label>
                                                        <input disabled type="text" class="form-control" id="hosting_account" name="hosting_account" value="{{ old('hosting_account', $web_form->hosting_account) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="login_ip">If yes, do you have the login/IP information? </label>
                                                        <input disabled type="text" class="form-control" id="login_ip" name="login_ip" value="{{ old('login_ip', $web_form->login_ip) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="domain_like_name">If no, what name(s) would you like?</label>
                                                        <input disabled type="text" class="form-control" id="domain_like_name" name="domain_like_name" value="{{ old('domain_like_name', $web_form->domain_like_name) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Maintenance</div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="section_regular_updating">Will there be sections that need regular updating? Which ones?</label>
                                                        <input disabled type="text" class="form-control" id="section_regular_updating" name="section_regular_updating" value="{{ old('section_regular_updating', $web_form->section_regular_updating) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="updating_yourself">Would you like to be able to do most of the updating yourself?</label>
                                                        <input disabled type="text" class="form-control" id="updating_yourself" name="updating_yourself" value="{{ old('updating_yourself', $web_form->updating_yourself) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="blog_written">If you’re planning on writing a blog do you already have several things written?</label>
                                                        <input disabled type="text" class="form-control" id="blog_written" name="blog_written" value="{{ old('blog_written', $web_form->blog_written) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="regular_basis">Do you already write on a regular basis?</label>
                                                        <input disabled type="text" class="form-control" id="regular_basis" name="regular_basis" value="{{ old('regular_basis', $web_form->regular_basis) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="fugure_pages">Are there any features/pages that you don’t need now but may want in the future? Please be as specific and future thinking as possible.</label>
                                                        <textarea disabled name="fugure_pages" id="fugure_pages" class="form-control" rows="4">{{ old('fugure_pages', $web_form->fugure_pages) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Additional information (optional)</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <textarea disabled name="additional_information" id="additional_information" class="form-control" rows="4">{{ old('additional_information', $web_form->additional_information) }}</textarea>
                                                </div>
                                                <!-- <div class="col-md-12 mt-1">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 3)
                                    @php $smm_form = SmmForm::find($task->projects->form_id); @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Social Media Marketing Form</div>
                                            <div class="row">
                                            @php
                                                $desired_results = json_decode($smm_form->desired_results);
                                            @endphp
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="desired_results">What are the desired results that you want to get generated from this Social Media project? ( Check one or more of the following )</label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="desired_results[]" value="Increase in Page Likes/Followers" @if($desired_results != null) {{ in_array('Increase in Page Likes/Followers', $desired_results) ? ' checked' : '' }} @endif>
                                                        <span>Increase in Page Likes/Followers </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="desired_results[]" value="Targeted Advertisement" @if($desired_results != null) {{ in_array('Targeted Advertisement', $desired_results) ? ' checked' : '' }} @endif>
                                                        <span>Targeted Advertisement </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="desired_results[]" value="Social Media Management" @if($desired_results != null) {{ in_array('Social Media Management', $desired_results) ? ' checked' : '' }} @endif>
                                                        <span>Social Media Management </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="desired_results[]" value="Brand Awareness" @if($desired_results != null) {{ in_array('Brand Awareness', $desired_results) ? ' checked' : '' }} @endif>
                                                        <span>Brand Awareness </span><span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Please provide the following information for your Business</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="">This information will be used to create your social profiles and establish the country of origin of your brand - In case the business in purely online, please provide P.O. Box address (however, mailing address remains mandatory)</label>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_name">Company/Business Name</label>
                                                    <input disabled class="form-control" name="business_name" id="business_name" type="text" value="{{ old('business_name', $smm_form->business_name) }}" required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_email_address">Business Email Address</label>
                                                    <input disabled class="form-control" name="business_email_address" id="business_email_address" type="email" value="{{ old('business_email_address', $smm_form->business_email_address) }}" required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_phone_number">Business Phone Number</label>
                                                    <input disabled class="form-control" name="business_phone_number" id="business_phone_number" type="text" value="{{ old('business_phone_number', $smm_form->business_phone_number) }}" required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_mailing_address">Business Mailing Address ( Verification Purposes )</label>
                                                    <input disabled class="form-control" name="business_mailing_address" id="business_mailing_address" type="email" value="{{ old('business_mailing_address', $smm_form->business_mailing_address) }}" required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_website_address">Business Website Address (URL)</label>
                                                    <input disabled class="form-control" name="business_website_address" id="business_website_address" type="text" value="{{ old('business_website_address', $smm_form->business_website_address) }}" required/>
                                                </div>
                                                <div class="col-md-4 form-group mb-3">
                                                    <label for="business_working_hours">Business Working Hours</label>
                                                    <input disabled class="form-control" name="business_working_hours" id="business_working_hours" type="text" value="{{ old('business_working_hours', $smm_form->business_working_hours) }}" required/>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="business_location">Business Location</label>
                                                    <input disabled class="form-control" name="business_location" id="business_location" type="text" value="{{ old('business_location', $smm_form->business_location) }}" required/>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="business_category">Business Category/Industry (Real Estate, Education, IT, Retail etc)</label>
                                                    <textarea disabled class="form-control" name="business_category" id="business_category" required>{{ old('business_category', $smm_form->business_category) }}</textarea>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    @php
                                                        $social_media_platforms = json_decode($smm_form->social_media_platforms);
                                                    @endphp
                                                    <label for="social_media_platforms">Please mention Social Media platforms that you want to opt <br>(consult your Account Manager)</label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="Facebook" @if($social_media_platforms != null) {{ in_array('Facebook', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>Facebook </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="Twitter" @if($social_media_platforms != null) {{ in_array('Twitter', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>Twitter </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="Instagram" @if($social_media_platforms != null) {{ in_array('Instagram', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>Instagram </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="Pinterest" @if($social_media_platforms != null) {{ in_array('Pinterest', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>Pinterest </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="YouTube" @if($social_media_platforms != null) {{ in_array('YouTube', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>YouTube </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="social_media_platforms[]" value="Linkedin" @if($social_media_platforms != null) {{ in_array('Linkedin', $social_media_platforms) ? ' checked' : '' }} @endif>
                                                        <span>Linkedin </span><span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    @php
                                                        $target_audience = json_decode($smm_form->target_audience);
                                                    @endphp
                                                    <label for="target_audience">Target Audience</label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="target_audience[]" value="Male" @if($target_audience != null) {{ in_array('Male', $target_audience) ? ' checked' : '' }} @endif>
                                                        <span>Male </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="target_audience[]" value="Female" @if($target_audience != null) {{ in_array('Female', $target_audience) ? ' checked' : '' }} @endif>
                                                        <span>Female </span><span class="checkmark"></span>
                                                    </label>
                                                    <label class="checkbox checkbox-primary mt-2">
                                                        <input disabled type="checkbox" name="target_audience[]" value="Both" @if($target_audience != null) {{ in_array('Both', $target_audience) ? ' checked' : '' }} @endif>
                                                        <span>Both </span><span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="target_locations">Target Locations (States, Cities)</label>
                                                    <input disabled class="form-control" name="target_locations" id="target_locations" type="text" value="{{ old('target_locations', $smm_form->target_locations) }}" required/>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="age_bracket">Age Bracket</label>
                                                    <input disabled class="form-control" name="age_bracket" id="age_bracket" type="text" value="{{ old('age_bracket', $smm_form->age_bracket) }}" required/>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="represent_your_business">Please mention search terms or services that best represent your business</label>
                                                    <textarea disabled class="form-control" name="represent_your_business" id="represent_your_business" required>{{ old('represent_your_business', $smm_form->represent_your_business) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="business_usp">What is your business USP (Unique Selling Points)?</label>
                                                    <textarea disabled class="form-control" name="business_usp" id="business_usp" required>{{ old('business_usp', $smm_form->business_usp) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="do_not_want_us_to_use">Are there any topics, websites, information or keywords that you DO NOT want us to use?</label>
                                                    <textarea disabled class="form-control" name="do_not_want_us_to_use" id="do_not_want_us_to_use" required>{{ old('do_not_want_us_to_use', $smm_form->do_not_want_us_to_use) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="competitors">Share pages of your competitors or other brands you are most inspired by</label>
                                                    <textarea disabled class="form-control" name="competitors" id="competitors" required>{{ old('competitors', $smm_form->competitors) }}</textarea>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Additional information (optional)</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="additional_comments">Describe your business / service / organization</label>
                                                    <textarea disabled name="additional_comments" id="additional_comments" class="form-control">{{ old('additional_comments', $smm_form->additional_comments) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 4)
                                    @php $content_form = ContentWritingForm::find($task->projects->form_id); @endphp
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Content Writing Form</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_name">Company Name</label>
                                                    <input disabled class="form-control" name="company_name" id="company_name" type="text" value="{{ old('company_name', $content_form->company_name) }}" required/>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_details">What is the origin, history, timeline, chronology, achievements, and future plans of your company? (Fill out as many as you can, please).</label>
                                                    <textarea disabled class="form-control" name="company_details" id="company_details" rows="5" required>{{ old('company_details', $content_form->company_details) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_industry">What is the industry that your Business caters to?</label>
                                                    <textarea disabled class="form-control" name="company_industry" id="company_industry" rows="5" required>{{ old('company_industry', $content_form->company_industry) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_reason">What is the reason behind what you do; passion? Heritage? Necessity?</label>
                                                    <textarea disabled class="form-control" name="company_reason" id="company_reason" rows="5" required>{{ old('company_reason', $content_form->company_reason) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_products">Please list all products and services you provide?</label>
                                                    <textarea disabled class="form-control" name="company_products" id="company_products" rows="5" required>{{ old('company_products', $content_form->company_products) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="short_description">Short description of your Business in your own words?</label>
                                                    <textarea disabled class="form-control" name="short_description" id="short_description" rows="5" required>{{ old('short_description', $content_form->short_description) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="mission_statement">What is your mission statement?</label>
                                                    <textarea disabled class="form-control" name="mission_statement" id="mission_statement" rows="5" required>{{ old('mission_statement', $content_form->mission_statement) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="keywords">List 10 or more keywords that best describe your Business</label>
                                                    <textarea disabled class="form-control" name="keywords" id="keywords" rows="5" required>{{ old('keywords', $content_form->keywords) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="competitor">List three or more of your top competitor</label>
                                                    <textarea disabled class="form-control" name="competitor" id="competitor" rows="5" required>{{ old('competitor', $content_form->competitor) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_business">In your words, what are the core strengths of your Business?</label>
                                                    <textarea disabled class="form-control" name="company_business" id="company_business" rows="5" required>{{ old('company_business', $content_form->company_business) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="customers_accomplish">What do you think your customers accomplish by using your product/services?</label>
                                                    <textarea disabled class="form-control" name="customers_accomplish" id="customers_accomplish" rows="5" required>{{ old('customers_accomplish', $content_form->customers_accomplish) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="company_sets">What sets your company apart from your competitors?</label>
                                                    <textarea disabled class="form-control" name="company_sets" id="company_sets" rows="5" required>{{ old('company_sets', $content_form->company_sets) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="existing_taglines">Do you have any existing/preferred taglines and/or slogans that you would like us to use?</label>
                                                    <textarea disabled class="form-control" name="existing_taglines" id="existing_taglines" rows="5" required>{{ old('existing_taglines', $content_form->existing_taglines) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 5)
                                    @php $seo_form = SeoForm::find($task->projects->form_id); @endphp
                                    <h4 class="card-title mb-3">SEO BRIEF FORM</h4>
                                    <div class="separator-breadcrumb border-top mb-3"></div>
                                    <div class="row">
                                        <!--BUSINESS DETAILS START-->
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="company_name">Company/Business Name</label>
                                            <input disabled class="form-control" name="company_name" id="company_name" type="text" value="{{ old('company_name', $seo_form->company_name) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="business_established">Business Address (To Display) </label>
                                            <input disabled class="form-control" name="business_established" id="business_established" type="text" value="{{ old('business_established', $seo_form->business_established) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="original_owner">Mailing Address (for verification purposes) </label>
                                            <input disabled class="form-control" name="original_owner" id="original_owner" type="email"  value="{{ old('original_owner', $seo_form->original_owner) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="age_current_site">Business City </label>
                                            <input disabled class="form-control" name="age_current_site" id="age_current_site" type="text"  value="{{ old('age_current_site', $seo_form->age_current_site) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="top_goals">Business State</label>
                                            <input disabled class="form-control" name="top_goals" id="top_goals" type="text"  value="{{ old('top_goals', $seo_form->top_goals) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="core_offer">Business Zip Code</label>
                                            <input disabled class="form-control" name="core_offer" id="core_offer" type="text"  value="{{ old('core_offer', $seo_form->core_offer) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="average_order_value">Business Phone Number</label>
                                            <input disabled class="form-control" name="average_order_value" id="average_order_value" type="text"  value="{{ old('average_order_value', $seo_form->average_order_value) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="selling_per_month">Business Contact Person Name</label>
                                            <input disabled class="form-control" name="selling_per_month" id="selling_per_month" type="text"  value="{{ old('selling_per_month', $seo_form->selling_per_month) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="client_lifetime_value">Business Contact Person Email Address</label>
                                            <input disabled class="form-control" name="client_lifetime_value" id="client_lifetime_value" type="text"  value="{{ old('client_lifetime_value', $seo_form->client_lifetime_value) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="supplementary_offers">Business Website Address (URL)</label>
                                            <input disabled class="form-control" name="supplementary_offers" id="supplementary_offers" type="text"  value="{{ old('supplementary_offers', $seo_form->supplementary_offers) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="getting_clients">Business Working Hours</label>
                                            <input disabled class="form-control" name="getting_clients" id="getting_clients" type="text"  value="{{ old('getting_clients', $seo_form->getting_clients) }}" required/>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="currently_spending">Business Category </label>
                                            <input disabled class="form-control" name="currently_spending" id="currently_spending" type="text"  value="{{ old('currently_spending', $seo_form->currently_spending) }}" required/>
                                        </div>
                                        <!--BUSINESS DETAILS END-->
                                        
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="monthly_visitors">Please share your URLs (If any) I don’t have any.</label>
                                            <textarea  disabled class="form-control" name="monthly_visitors" id="monthly_visitors" rows="3" required>{{ old('monthly_visitors', $seo_form->monthly_visitors) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="people_adding">Do you have a physical location for your business? (Yes/No)</label>
                                            <input  disabled class="form-control" name="people_adding" id="people_adding" type="text"  value="{{ old('people_adding', $seo_form->people_adding) }}" required/>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="monthly_financial">Which location(s) do you want to target?</label>
                                            <textarea disabled class="form-control" name="monthly_financial" id="monthly_financial" rows="3" required>{{ old('monthly_financial', $seo_form->monthly_financial) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="that_much">Do you use only one website for this business? If not, list out the others</label>
                                            <textarea disabled class="form-control" name="that_much" id="that_much" rows="3" required>{{ old('that_much', $seo_form->that_much) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="specific_target">Do you have the ability to make changes by yourself to your website? (Yes/No)</label>
                                            <textarea disabled class="form-control" name="specific_target" id="specific_target" rows="3" required>{{ old('specific_target', $seo_form->specific_target) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="competitors">Do you currently have access to your Google My Business page? (Yes/No)</label>
                                            <textarea disabled class="form-control" name="competitors" id="competitors" rows="3" required>{{ old('competitors', $seo_form->competitors) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="third_party_marketing">What specific cities or geographic area are most important for your business to be visible in?</label>
                                            <textarea disabled class="form-control" name="third_party_marketing" id="third_party_marketing" rows="3" required>{{ old('third_party_marketing', $seo_form->third_party_marketing) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="current_monthly_sales">Please mention the terms or services that are most important for your business to get found for on the internet (Use Comma To Separate)</label>
                                            <textarea disabled class="form-control" name="current_monthly_sales" id="current_monthly_sales" rows="3" required>{{ old('current_monthly_sales', $seo_form->current_monthly_sales) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="current_monthly_revenue">Which review websites does your business have most reviews on?</label>
                                            <textarea disabled class="form-control" name="current_monthly_revenue" id="current_monthly_revenue" rows="3" required>{{ old('current_monthly_revenue', $seo_form->current_monthly_revenue) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="target_region">Are there any keyword suggestions? Please list below!</label>
                                            <textarea disabled class="form-control" name="target_region" id="target_region" rows="3" required>{{ old('target_region', $seo_form->target_region) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="looking_to_execute">First off, tell us a little about your business. What do you offer, what sets you apart, etc.? </label>
                                            <textarea disabled class="form-control" name="looking_to_execute" id="looking_to_execute" rows="3" required>{{ old('looking_to_execute', $seo_form->looking_to_execute) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <h4>Target Audience/Vision:</h4>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="time_zone"> Who are you hoping to target with your social profiles? What about a target demographic/age range? </label>
                                            <textarea disabled class="form-control" name="time_zone" id="time_zone" rows="3" required>{{ old('time_zone', $seo_form->time_zone) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <h4>Content:</h4>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="time_zone">We like to include a balance of information about your business with interesting facts within the industry, and trending articles related to your industry and your customers. Do you have any topics that would be useful in starting a dialogue with your clients about?</label>
                                            <textarea disabled class="form-control" name="seo_content" id="seo_content" rows="3" required>{{ old('seo_content', $seo_form->seo_content) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <h4>Websites:</h4>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="time_zone">Are there any websites that you tend to use to find industry information or that you think we would find useful in gathering content from?</label>
                                            <textarea disabled class="form-control" name="inform_websites" id="inform_websites" rows="3" required>{{ old('inform_websites', $seo_form->inform_websites) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <h4>Things to Avoid:</h4>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="time_zone">Are there any topics, websites, or information that you DO NOT want us to use?</label>
                                            <textarea disabled class="form-control" name="things_to_avoid" id="things_to_avoid" rows="3" required>{{ old('things_to_avoid', $seo_form->things_to_avoid) }}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="time_zone">We also encourage you to send us any new information as we go. This allows us to keep our content up to date.</label>
                                            <textarea disabled class="form-control" name="new_information" id="new_information" rows="3" required>{{ old('new_information', $seo_form->new_information) }}</textarea>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 6)
                                    @php $data = BookFormatting::find($task->projects->form_id); @endphp
                                    <div class="row">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="card-title mb-3">Book Formatting & Publishing Brief Form</div>
                                                <div class="row">
                                                    <div class="col-md-4 form-group mb-3">
                                                        <label for="book_title">What is the title of the book? <span>*</span></label>
                                                        <input disabled class="form-control" name="book_title" id="book_title" type="text" value="{{ old('book_title', $data->book_title) }}" required/>
                                                    </div>
                                                    <div class="col-md-4 form-group mb-3">
                                                        <label for="book_subtitle">What is the subtitle of the book?</label>
                                                        <input disabled class="form-control" name="book_subtitle" id="book_subtitle" type="text" value="{{ old('book_subtitle', $data->book_subtitle) }}"/>
                                                    </div>
                                                    <div class="col-md-4 form-group mb-3">
                                                        <label for="author_name">What is the name of the author? <span>*</span></label>
                                                        <input disabled class="form-control" name="author_name" id="author_name" type="text"  value="{{ old('author_name', $data->author_name) }}" required/>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="contributors">Any additional contributors you would like to acknowledge? (e.g. Book Illustrator, Editor, etc.) <span>*</span></label>
                                                        <textarea disabled class="form-control" name="contributors" id="contributors" rows="5" required>{{ old('contributors', $data->contributors) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="card-title mb-3">Formatting Requirements</div>
                                                <p>Where do you want to? <span>*</span></p>
                                                @php
                                                $publish_your_book = json_decode($data->publish_your_book);
                                                @endphp
                                                <div class="row pl-4">
                                                    <div class="col-lg-2">
                                                        <label for="amazon_kdp" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="amazon_kdp" name="publish_your_book[]" value="Amazon KDP" @if($publish_your_book != null) {{ in_array('Amazon KDP', $publish_your_book) ? ' checked' : '' }} @endif data-value="Where do you want to?" data-name="required">Amazon KDP
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="barnes_noble" class="w-100">
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="barnes_noble" name="publish_your_book[]" value="Barnes & Noble" @if($publish_your_book != null) {{ in_array('Barnes & Noble', $publish_your_book) ? ' checked' : '' }} @endif>Barnes & Noble
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="google_books" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="google_books" name="publish_your_book[]" value="Google Books" @if($publish_your_book != null) {{ in_array('Google Books', $publish_your_book) ? ' checked' : '' }} @endif>Google Books
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="kobo" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="kobo" name="publish_your_book[]" value="Kobo" @if($publish_your_book != null) {{ in_array('Kobo', $publish_your_book) ? ' checked' : '' }} @endif>Kobo
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="ingram_spark" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="ingram_spark" name="publish_your_book[]" value="Ingram Spark" @if($publish_your_book != null) {{ in_array('Ingram Spark', $publish_your_book) ? ' checked' : '' }} @endif>Ingram Spark
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <p>Which formats do you want your book to be formatted on? <span>*</span></p>
                                                @php
                                                $book_formatted = json_decode($data->book_formatted);
                                                @endphp
                                                <div class="row pl-4">
                                                    <div class="col-lg-2">
                                                        <label for="ebook" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="ebook" name="book_formatted[]" value="eBook" @if($book_formatted != null) {{ in_array('eBook', $book_formatted) ? ' checked' : '' }} @endif data-value="Which formats do you want your book to be formatted on?" data-name="required">eBook
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="paperback" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="paperback" name="book_formatted[]" value="Paperback" @if($book_formatted != null) {{ in_array('Paperback', $book_formatted) ? ' checked' : '' }} @endif>Paperback
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <label for="hardcover" class="w-100"> 
                                                            <div class="formCheck purpose-box font-box">
                                                                <div class="form-check ml-0 pl-0">
                                                                    <input disabled type="checkbox" class="form-check-input" id="hardcover" name="book_formatted[]" value="Hardcover" @if($book_formatted != null) {{ in_array('Hardcover', $book_formatted) ? ' checked' : '' }} @endif>Hardcover
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <p>Which trim size do you want your book to be formatted on? <span>*</span></p>
                                                <div class="row pl-4">
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input disabled type="radio" class="form-check-input" id="trim_size_1" name="trim_size" value="5_8" {{ $data->trim_size == '5_8' ? 'checked' : '' }} data-value="Which trim size do you want your book to be formatted on?" data-name="required">
                                                                <label for="trim_size_1" class="comic">5″ X 8″</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input disabled type="radio" class="form-check-input" id="trim_size_2" name="trim_size" value="5.25_8" {{ $data->trim_size == '5.25_8' ? 'checked' : '' }}>
                                                                <label for="trim_size_2" class="comic">5.25″ X 8″</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input disabled type="radio" class="form-check-input" id="trim_size_3" name="trim_size" value="5.5_8.5" {{ $data->trim_size == '5.5_8.5' ? 'checked' : '' }}>
                                                                <label for="trim_size_3" class="comic">5.5″ X 8.5″</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input disabled type="radio" class="form-check-input" id="trim_size_4" name="trim_size" value="6_9" {{ $data->trim_size == '6_9' ? 'checked' : '' }}>
                                                                <label for="trim_size_4" class="comic">6″ X 9″</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input type="radio" class="form-check-input" id="trim_size_5" name="trim_size" value="8.5_11" {{ $data->trim_size == '8.5_11' ? 'checked' : '' }}>
                                                                <label for="trim_size_5" class="comic">8.5″ X 11″</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="formCheck font-box">
                                                            <div class="form-check pl-0">
                                                                <input disabled type="radio" class="form-check-input" id="trim_size_6" name="trim_size" value="Other" {{ $data->trim_size == 'Other' ? 'checked' : '' }}>
                                                                <label for="trim_size_6" class="comic">Other (Please specify)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 form-group mb-3">
                                                        <label for="trim_size_7">If you have selected Other please specify the trim size you want your book to be formatted on.</label>
                                                        <input disabled class="form-control" name="other_trim_size" id="trim_size_7" type="text"  value="{{ old('other_trim_size', $data->other_trim_size) }}"/>
                                                    </div>
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="additional_instructions">Any Additional Instructions that you would like us to follow?</label>
                                                        <textarea disabled class="form-control" name="additional_instructions" id="additional_instructions" rows="5">{{ old('additional_instructions', $data->additional_instructions) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    @elseif($task->projects->form_checker == 7)
                                    <a href="{{ route('admin.form', ['form_id' => $task->projects->form_id, 'check' => $task->projects->form_checker, 'id' => $task->projects->id]) }}" class="btn btn-secondary btn-sm">Book Writing</a>
                                    @elseif($task->projects->form_checker == 8)
                                    <a href="{{ route('admin.form', ['form_id' => $task->projects->form_id, 'check' => $task->projects->form_checker, 'id' => $task->projects->id]) }}" class="btn btn-secondary btn-sm">Author Website</a>
                                    @elseif($task->projects->form_checker == 9)
                                    @php $data = Proofreading::find($task->projects->form_id); @endphp
                                    
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="card-title mb-3">Editing & Proofreading Questionnaire</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="description">Can you provide a brief description of the book you would like to have edited and proofread? <span>*</span></label>
                                                    <textarea disabled class="form-control" name="description" id="description" rows="5" required>{{ old('description', $data->description) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="word_count">What is the word count of your book?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="word_count" id="word_count" rows="5" required>{{ old('word_count', $data->word_count) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="services">What type of editing and proofreading services are you looking for (e.g. developmental editing, line editing, copyediting, proofreading, etc.)?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="services" id="services" rows="5" required>{{ old('services', $data->services) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="completion">Do you have a specific deadline for the completion of the editing and proofreading services?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="completion" id="completion" rows="5" required>{{ old('completion', $data->completion) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="previously">Have you edited the book yourself or had it edited previously?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="previously" id="previously" rows="5" required>{{ old('previously', $data->previously) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="specific_areas">Are there any specific areas or elements that you would like the editor to pay close attention to (e.g. grammar, tone, character development, etc.)?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="specific_areas" id="specific_areas" rows="5" required>{{ old('specific_areas', $data->specific_areas) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="suggestions">Would you like to receive suggestions or feedback from the editor regarding the content of the book?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="suggestions" id="suggestions" rows="5" required>{{ old('suggestions', $data->suggestions) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="mention">Is there anything else you would like to mention or specify regarding the editing and proofreading services you require?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="mention" id="mention" rows="5" required>{{ old('mention', $data->mention) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="major">Are there any major plot points we need to know?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="major" id="major" rows="5" required>{{ old('major', $data->major) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="trigger">Are there any trigger warnings, or are there any sections we should avoid while editing?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="trigger" id="trigger" rows="5" required>{{ old('trigger', $data->trigger) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="character">Please provide the main character details or anything else we should know ahead of time.<span>*</span></label>
                                                    <textarea disabled class="form-control" name="character" id="character" rows="5" required>{{ old('character', $data->character) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="guide">Which style guide would you like us to use?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="guide" id="guide" rows="5" required>{{ old('guide', $data->guide) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="areas">Do you have any specific areas you'd like us to edit?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="areas" id="areas" rows="5" required>{{ old('areas', $data->areas) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($task->projects->form_checker == 10)
                                    @php $data = BookCover::find($task->projects->form_id); @endphp
                                    
                                    <div class="card mb-4">
                                        <div class="card-body mb-4">
                                            <div class="card-title mb-3">Book Cover Questionnaire</div>
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="title">Title of the book (Exact Wording) <span>*</span></label>
                                                    <input disabled type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="subtitle">Subtitle/Tagline if any (Optional)</label>
                                                    <input disabled type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $data->subtitle) }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="author">Name of the Author<span>*</span></label>
                                                    <input disabled type="text" name="author" class="form-control" value="{{ old('author', $data->author) }}" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="genre">What is the Genre of the book?</label>
                                                    <div class="row pl-4">
                                                        <div class="col-lg-2">
                                                            <div class="formCheck font-box mb-0">
                                                                <div class="form-check pl-0">
                                                                    <input disabled type="radio" class="form-check-input" id="genre_1" name="genre" value="fiction" {{ $data->genre == 'fiction' ? 'checked' : '' }}>
                                                                    <label for="genre_1" class="genre">Fiction</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <div class="formCheck font-box mb-0">
                                                                <div class="form-check pl-0">
                                                                    <input disabled type="radio" class="form-check-input" id="genre_2" name="genre" value="non-fiction" {{ $data->genre == 'non-fiction' ? 'checked' : '' }}>
                                                                    <label for="genre_2" class="genre">Non Fiction</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="isbn">Do you have an ISBN Number? Or do you need one?<span>*</span></label>
                                                    <input disabled type="text" name="isbn" class="form-control" value="{{ old('isbn', $data->isbn) }}" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="trim_size">Book Trim Size<span>*</span></label>
                                                    <input disabled type="text" name="trim_size" class="form-control" value="{{ old('trim_size', $data->trim_size) }}" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="explain">Explain your book cover concept that you would like us to follow?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="explain" id="explain" rows="5" required>{{ old('explain', $data->explain) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="information">Provide the information for Back Cover. This information will be added to the back cover.<span>*</span></label>
                                                    <textarea disabled class="form-control" name="information" id="information" rows="5" required>{{ old('information', $data->information) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="about">What is your book about?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="about" id="about" rows="5" required>{{ old('about', $data->about) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="keywords">Keywords that define your book.<span>*</span></label>
                                                    <textarea disabled class="form-control" name="keywords" id="keywords" rows="5" required>{{ old('keywords', $data->keywords) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="images_provide">Any images you would like us to use or provide for reference?<span>*</span></label>
                                                    <textarea disabled class="form-control" name="images_provide" id="images_provide" rows="5" required>{{ old('images_provide', $data->images_provide) }}</textarea>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="category">Select one of the style category that you want us to follow for your book cover<span>*</span></label>
                                                    <div class="row pl-4">
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/picture_based_1.jpg') }}" alt="Picture Based">
                                                                        <img src="{{ asset('newglobal/images/picture_based_2.jpg') }}" alt="Picture Based">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_1" name="category" value="picture_based" {{ $data->category == 'picture_based' ? 'checked' : '' }}>
                                                                    <label for="category_1" class="genre">Picture Based</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/text_based_1.jpg') }}" alt="Text Based">
                                                                        <img src="{{ asset('newglobal/images/text_based_2.jpg') }}" alt="Text Based">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_2" name="category" value="text_based" {{ $data->category == 'text_based' ? 'checked' : '' }}>
                                                                    <label for="category_2" class="genre">Text Based</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/picture_collage_1.jpg') }}" alt="Picture Collage">
                                                                        <img src="{{ asset('newglobal/images/picture_collage_2.jpg') }}" alt="Picture Collage">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_3" name="category" value="picture_collage" {{ $data->category == 'picture_collage' ? 'checked' : '' }}>
                                                                    <label for="category_3" class="genre">Picture Collage</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/illustration_1.jpg') }}" alt="Illustration">
                                                                        <img src="{{ asset('newglobal/images/illustration_2.jpg') }}" alt="Illustration">
                                                                    </div>
                                                                    <input type="radio" class="form-check-input" id="category_4" name="category" value="illustration" {{ $data->category == 'illustration' ? 'checked' : '' }}>
                                                                    <label for="category_4" class="genre">Illustration</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/abstract_1.jpg') }}" alt="Abstract">
                                                                        <img src="{{ asset('newglobal/images/abstract_2.jpg') }}" alt="Abstract">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_5" name="category" value="abstract" {{ $data->category == 'abstract' ? 'checked' : '' }}>
                                                                    <label for="category_5" class="genre">Abstract</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/notebook_1.jpg') }}" alt="Notebook">
                                                                        <img src="{{ asset('newglobal/images/notebook_2.jpg') }}" alt="Notebook">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_6" name="category" value="notebook" {{ $data->category == 'notebook' ? 'checked' : '' }}>
                                                                    <label for="category_6" class="genre">Notebook</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/fictional_1.jpg') }}" alt="Fictional">
                                                                        <img src="{{ asset('newglobal/images/fictional_2.jpg') }}" alt="Fictional">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_7" name="category" value="fictional" {{ $data->category == 'fictional' ? 'checked' : '' }}>
                                                                    <label for="category_7" class="genre">Fictional</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/vintage_1.jpg') }}" alt="Vintage">
                                                                        <img src="{{ asset('newglobal/images/vintage_2.jpg') }}" alt="Vintage">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_8" name="category" value="vintage" {{ $data->category == 'vintage' ? 'checked' : '' }}>
                                                                    <label for="category_8" class="genre">Vintage</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/religious_1.jpg') }}" alt="Religious">
                                                                        <img src="{{ asset('newglobal/images/religious_2.jpg') }}" alt="Religious">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_9" name="category" value="religious" {{ $data->category == 'religious' ? 'checked' : '' }}>
                                                                    <label for="category_9" class="genre">Religious</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="formCheck font-box">
                                                                <div class="form-check pl-0">
                                                                    <div class="category-image-wrapper">
                                                                        <img src="{{ asset('newglobal/images/creative_illustration_1.jpg') }}" alt="Creative Illustration">
                                                                        <img src="{{ asset('newglobal/images/creative_illustration_2.jpg') }}" alt="Creative Illustration">
                                                                    </div>
                                                                    <input disabled type="radio" class="form-check-input" id="category_10" name="category" value="creative_illustration" {{ $data->category == 'creative_illustration' ? 'checked' : '' }}>
                                                                    <label for="category_10" class="genre">Creative Illustration</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>

<div class="left-message-box-wrapper">
    <div class="left-message-box">
        <form class="form" action="{{ route('manager.message.send') }}" enctype="multipart/form-data" method="post">
            @csrf
            <input type="hidden" name="client_id" value="{{ $task->projects->client->client_id }}">
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <div class="form-body">
                <div class="form-group mb-0">
                    <h1>Write A Message <span id="close-message-left"><i class="nav-icon i-Close-Window"></i></span></h1>
                    <textarea id="message" rows="8" class="form-control border-primary" name="message" required placeholder="Write a Message">{{old('message')}}</textarea>
                    <div class="input-field">
                        <div class="input-images" style="padding-top: .5rem;"></div>
                    </div>
                    <!-- <table>
                        <tr>
                            <td colspan="3" style="vertical-align:middle; text-align:left;">
                                <div id="h_ItemAttachments"></div>
                                <input type="button" id="h_btnAddFileUploadControl" value="Add Attachment" onclick="Clicked_h_btnAddFileUploadControl()" class="btn btn-info btn_Standard" />
                                <div id="h_ItemAttachmentControls"></div>
                            </td>
                        </tr>
                    </table> -->
                    <div class="form-actions pb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                        <i class="la la-check-square-o"></i> Send Message
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>        
</div>

<!--  Modal -->
<div class="modal fade" id="exampleModalMessageEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Edit Message</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{ route('admin.message.update') }}" method="post">
                @csrf
                <input type="hidden" name="message_id" id="message_id">
                <div class="modal-body">
                    <textarea name="editmessage" id="editmessage" cols="30" rows="10" class="form-control"></textarea> 
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary ml-2" type="submit">Update changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('global/js/fileinput.js') }}"></script>
<script src="{{ asset('global/js/fileinput-theme.js') }}"></script>
<script src="{{ asset('newglobal/js/image-uploader.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>

function deleteFile(id){
    $.ajax({
        type: "POST",
        url: "{{ route('delete.files.admin') }}",
        data: {id:id},
        success: function(response) {
            if(response.success == true){
                $('.files').DataTable().row('.file-tr-'+id).remove().draw(false);
                toastr.success(response.data, 'Success', {timeOut: 5000})
                location.reload(true);
            }else{
                toastr.error('Please Contact your Administrator', 'Error Occured', {timeOut: 5000})
            }
        }
    });
}

    $(document).ready(function(){
        $('.input-images').imageUploader();
        $('#write-message').click(function(){
            $('.left-message-box-wrapper').addClass('fixed-option');
        });
        $('#close-message-left').click(function(){
            $('.left-message-box-wrapper').removeClass('fixed-option');
        })
        CKEDITOR.replace('editmessage');
        CKEDITOR.replace('message');
        CKEDITOR.replace('description');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#subtask' ).on('submit', function(e) {
            e.preventDefault();
            var description = CKEDITOR.instances.description.getData();
            var duedate = $(this).find('[name=duedate]').val();
            var action = $(this).attr('action');
            var task_id = $(this).find('[name=task_id]').val();
            if(description != ''){
                $.ajax({
                    type: "POST",
                    url: action,
                    data: { description:description, task_id:task_id, duedate:duedate}, 
                    success: function(response) {
                        var duedate = '';
                        if(response.duedate != null){
                            duedate = 'Due Date <br>' +  response.duedate
                        }
                        $('#subtask_show').prepend('<div class="ul-widget3-item">\
                                    <div class="ul-widget3-header">\
                                        <div class="ul-widget3-img">\
                                            <img id="userDropdown" src="http://127.0.0.1:8000/global/img/user.png" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\
                                        </div>\
                                        <div class="ul-widget3-info">\
                                            <a class="__g-widget-username" href="#">\
                                                <span class="t-font-bolder">'+response.user_name+' </span>\
                                            </a>\
                                            <br>\
                                            <span class="ul-widget-notification-item-time">'+response.created_at+'</span>\
                                        </div>\
                                        <span class="ul-widget3-status text-success t-font-bolder">\
                                        '+duedate+'\
                                        </span>\
                                    </div>\
                                    <div class="ul-widget3-body">\
                                        <p>'+response.data.description+'</p>\
                                    </div>\
                                </div>');
                        CKEDITOR.instances.description.setData('');
                        $('#duedate').val('');
                        toastr.success(response.success, '', {timeOut: 5000})
                    }
                });
            }else{
                toastr.error("Please Fill the form", '', {timeOut: 5000})
            }
        });
        $("#image-file").fileinput({
            showUpload: true,
            theme: 'fa',
            dropZoneEnabled : true,
            // uploadUrl: "{{ url('admin-files') }}/{{$task->id}}", 
            uploadUrl: "{{ route('insert.admin.files', ['id' => $task->id]) }}", 
            overwriteInitial: false,
            maxFileSize:20000000,
            maxFilesNum: 20,
            uploadExtraData: function() {
                return {
                    created_at: $('.created_at').val()
                };
            }
        });
        $("#image-file").on('fileuploaded', function(event, data, previewId, index, fileId) {
            var month_names_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var response = data.response;
            // console.log(response);
            $('.files').DataTable().destroy();
            for (var i = 0; i < response.files.length; i++) {
                // Assuming response.files_url is your object with key-value pairs
                var fileUrl = response.files_url[response.files[i].id];
            
                var formattedDate = new Date(response.files[i].created_at);
                var d = formattedDate.getDate();
                var m = month_names_short[formattedDate.getMonth()];
                var y = formattedDate.getFullYear().toString().substr(-2);
                var hours = formattedDate.getHours();
                var minutes = formattedDate.getMinutes();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0' + minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;
                var newDateTime = d + " " + m + ", " + y;
            
                $('#zero_configuration_table tbody').prepend('<tr>\
                    <td>' + response.files[i].id + '</td>\
                    <td>\
                        <a href="' + fileUrl + '" target="_blank">\
                            <img src="' + fileUrl + '" alt="' + response.files[i].name + '" width="100">\
                        </a>\
                    </td>\
                    <td>' + response.files[i].name + '<button class="btn btn-dark btn-sm">' + newDateTime + '</button></td>\
                    <td><button class="btn btn-secondary btn-sm">' + response.uploaded_by + '</button></td>\
                    <td>\
                        <div class="btn-group float-md-right ml-1">\
                            <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="i-Edit"></i></button>\
                            <div class="dropdown-menu arrow">\
                                <a class="dropdown-item" href="' + fileUrl + '" target="_blank"> View</a>\
                                <a class="dropdown-item" href="' + fileUrl + '" download> Download</a>\
                                <a class="dropdown-item" href="#" onclick="deleteFile(' + response.files[i].id + ')"> Delete</a>\
                            </div>\
                        </div>\
                    </td>\
                </tr>');
            }
            $("#image-file").fileinput('refresh');
            $("#image-file").fileinput('reset');
            toastr.success('Image Updated Successfully', '', {timeOut: 5000})
            $('.files').DataTable({
                order:[[0,"desc"]],
                responsive: true,
            });
        });
    });
    $(function(){
        var dtToday = new Date();
        
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate() + 1;
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;
        $('#duedate').attr('min', maxDate);
    });
    var start = new Date;
    setInterval(function() {
        var d = new Date();
        var date = ( '0' + (d.getDate()) ).slice( -2 );
        var month = ( '0' + (d.getMonth()+1) ).slice( -2 );
        var year = d.getFullYear();
        var seconds = ( '0' + (d.getSeconds()) ).slice( -2 );
        var minutes = ( '0' + (d.getMinutes()) ).slice( -2 );
        var hour = ( '0' + (d.getHours()) ).slice( -2 );
        var dateStr = year + "-" + month + "-" + date + ' ' + hour + ':' + minutes + ':' + seconds;
        $('.created_at').val(dateStr);
    }, 1000);

    function editMessage(message_id){
        var url = "{{ route('admin.message.edit', ":message_id") }}";
        url = url.replace(':message_id', message_id);
        $.ajax({
            type:'GET',
            url: url,
            success:function(data) {
                if(data.success){
                    CKEDITOR.instances['editmessage'].setData(data.data.message);
                    console.log(data.data.message);
                    $('#exampleModalMessageEdit').find('#message_id').val(data.data.id);
                    $('#exampleModalMessageEdit').modal('toggle');
                }
            }
        });
    }
</script>
@endpush