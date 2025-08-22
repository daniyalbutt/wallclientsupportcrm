@extends('layouts.app-admin')
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Settings</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <section id="basic-form-layouts">
        <div class="row match-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form">Project Tasks Settings</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                                <table class="display table table-striped" style="width:100%">
                                <tr>
                                    <th>Allow/Disallow Empty Forms For All Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="empty_task" name="empty_task" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->empty_task == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                
                                
                            </table>
                            <div class="row">
                                    <div class="col-md-6">
                                        <table class="display table table-striped table-bordered" style="width:100%">
                                            <tr>
                                    <th>Logo Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="logoForm" name="logoForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->logoForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Web Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="webForm" name="webForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->webForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>SMM Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="smmForm" name="smmForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->smmForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Content Writing Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="contentForm" name="contentForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->contentForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>SEO Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="seoForm" name="seoForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->seoForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="display table table-striped table-bordered" style="width:100%">
                                            <tr>
                                    <th>Book Formatting Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="formattingForm" name="formattingForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->formattingForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Book Writing Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="writingForm" name="writingForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->writingForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Author Website Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="authorForm" name="authorForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->authorForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Editing/Proofreading Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="proofreadingForm" name="proofreadingForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->proofreadingForm == 1 ? 'checked' : '' }} >
                                        </div>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Book Cover Forms.</th>
                                    <td>
                                        
                                        <div class="switch_box box_1">
                                            <input type="checkbox" class="switch_1" id="coverForm" name="coverForm" data-toggle="toggle" data-on="Yes" data-off="No" {{ $settings->coverForm == 1 ? 'checked' : '' }} >
                                        </div>
                                    </td>
                                </tr>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
     $('.switch_1').off('change').on('change', function() {
            var column = $(this).attr('name');
            var value = $(this).prop('checked') ? 1 : 0;

            $.ajax({
                type: 'POST',
                url: '{{ route("admin.project.settings.update") }}',
                data: {
                    column: column,
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Task Settings Updated!', '', { timeOut: 5000 });
                }
            });
        });
</script>
@endpush