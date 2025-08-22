
@extends('layouts.app-support')
@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Projects</h1>
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-body">
                <form action="{{ route('support.project') }}" method="GET" id="search-form">
                    <div class="row">
                        <div class="col-md-3 form-group mb-3">
                            <label for="package">Search Project</label>
                            <input type="text" class="form-control" id="project" name="project" value="{{ Request::get('project') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="invoice">Search Project#</label>
                            <input type="text" class="form-control" id="project_id" name="project_id" value="{{ Request::get('project_id') }}">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="user">Search Name or Email</label>
                            <input type="text" class="form-control" id="user" name="user" value="{{ Request::get('user') }}">
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-primary">Search Result</button>
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
            <div class="card-body">
                <h4 class="card-title mb-3">Project Details</h4>
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $datas)
                             @php 
                                $formExists = $allForms->where('id', $datas->form_id)->where('form_checker', $datas->form_checker)->where('form_checker')->isNotEmpty();
                            @endphp
                            <tr>
                                <td>{{$datas->id}}</td>
                                <td>
                                    @if(count($datas->tasks) == 0)
                                    {{$datas->name}}
                                    @else
                                    <a href="#">{{$datas->name}}</a>
                                    @endif
                                </td>
                                <td>
                                    {{$datas->client->name}} {{$datas->client->last_name}}<br>
                                    {{$datas->client->email}}  <br>
                                    {{$datas->client->contact}}
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm">{{$datas->brand->name}}</button>
                                </td>
                                <td>
                                    @if($datas->status == 1)
                                        <button class="btn btn-success btn-sm">Active</button>
                                    @else
                                        <button class="btn btn-danger btn-sm">Deactive</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('support.message.show.id', ['id' => $datas->client->id ,'name' => $datas->client->name]) }}" class="btn btn-secondary btn-sm">
                                        Message
                                    </a>
                                    @if($datas->form_checker != 0)
                                    <a href="{{ route('support.form', [ 'form_id' => $datas->form_id , 'check' => $datas->form_checker, 'id' => $datas->id]) }}" class="btn btn-primary btn-icon btn-sm">
                                        View Form
                                    </a>
                                    @endif
                                    @if($settings[0]->empty_task == 1)
                                        @if($formExists)
                                            <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                                Create Task
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-icon btn-sm btn-noform">
                                                Create Task
                                            </button>
                                        @endif
                                    @else
                                        @if($formExists || $settings[0]->logoForm == 0 && $datas->form_checker == 1)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->webForm == 0 && $datas->form_checker == 2)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->smmForm == 0 && $datas->form_checker == 3)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->contentForm == 0 && $datas->form_checker == 4)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->seoForm == 0 && $datas->form_checker == 5)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->formattingForm == 0 && $datas->form_checker == 6)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->writingForm == 0 && $datas->form_checker == 7)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->authorForm == 0 && $datas->form_checker == 8)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->proofreadingForm == 0 && $datas->form_checker == 9)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @elseif($formExists || $settings[0]->coverForm == 0 && $datas->form_checker == 10)
                                        <a href="{{ route('create.task.by.project.id', ['id' => $datas->id, 'name' => preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $datas->name))) ]) }}" class="btn btn-dark btn-icon btn-sm">
                                            Create Task
                                        </a>
                                        @else
                                        <button type="button" class="btn btn-danger btn-icon btn-sm btn-noform">
                                            Create Task
                                        </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $data->links("pagination::bootstrap-4") }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('.btn-noform').on('click', function(){
            toastr.error('Form is empty! Please contact your client to fillup the form.', '', { timeOut: 8000 });
        });
    })
</script>
@endpush