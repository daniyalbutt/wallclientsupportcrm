@extends('layouts.app-support')
@section('title', 'Content Writing Form')

@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Content Writing Brief INV#{{$content_form->invoice->invoice_number}}</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <form class="col-md-12 brief-form" method="post" route="{{ route('client.content.form.update', $content_form->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title mb-3">Content Writing Form</div>
                <div class="row">
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_name">Company Name</label>
                        <input class="form-control" name="company_name" id="company_name" type="text" value="{{ old('company_name', $content_form->company_name) }}" required/>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_details">What is the origin, history, timeline, chronology, achievements, and future plans of your company? (Fill out as many as you can, please).</label>
                        <textarea class="form-control" name="company_details" id="company_details" rows="5" required>{{ old('company_details', $content_form->company_details) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_industry">What is the industry that your Business caters to?</label>
                        <textarea class="form-control" name="company_industry" id="company_industry" rows="5" required>{{ old('company_industry', $content_form->company_industry) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_reason">What is the reason behind what you do; passion? Heritage? Necessity?</label>
                        <textarea class="form-control" name="company_reason" id="company_reason" rows="5" required>{{ old('company_reason', $content_form->company_reason) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_products">Please list all products and services you provide?</label>
                        <textarea class="form-control" name="company_products" id="company_products" rows="5" required>{{ old('company_products', $content_form->company_products) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="short_description">Short description of your Business in your own words?</label>
                        <textarea class="form-control" name="short_description" id="short_description" rows="5" required>{{ old('short_description', $content_form->short_description) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="mission_statement">What is your mission statement?</label>
                        <textarea class="form-control" name="mission_statement" id="mission_statement" rows="5" required>{{ old('mission_statement', $content_form->mission_statement) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="keywords">List 10 or more keywords that best describe your Business</label>
                        <textarea class="form-control" name="keywords" id="keywords" rows="5" required>{{ old('keywords', $content_form->keywords) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="competitor">List three or more of your top competitor</label>
                        <textarea class="form-control" name="competitor" id="competitor" rows="5" required>{{ old('competitor', $content_form->competitor) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_business">In your words, what are the core strengths of your Business?</label>
                        <textarea class="form-control" name="company_business" id="company_business" rows="5" required>{{ old('company_business', $content_form->company_business) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="customers_accomplish">What do you think your customers accomplish by using your product/services?</label>
                        <textarea class="form-control" name="customers_accomplish" id="customers_accomplish" rows="5" required>{{ old('customers_accomplish', $content_form->customers_accomplish) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="company_sets">What sets your company apart from your competitors?</label>
                        <textarea class="form-control" name="company_sets" id="company_sets" rows="5" required>{{ old('company_sets', $content_form->company_sets) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="existing_taglines">Do you have any existing/preferred taglines and/or slogans that you would like us to use?</label>
                        <textarea class="form-control" name="existing_taglines" id="existing_taglines" rows="5" required>{{ old('existing_taglines', $content_form->existing_taglines) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title mb-3">Attachment</div>
                <div class="row">
                    <div class="col-12">
                        <input type="file" name="attachment[]" multiple/>
                    </div>
                    @foreach($content_form->formfiles as $formfiles)
                        @foreach($img_arr as $key => $presignedUrl)
                            @if($formfiles->id == $key)
                            
                            @php
                                $parsedUrl = parse_url($presignedUrl);

                                // Extract the file name and extension from the path
                                $pathInfo = pathinfo($parsedUrl['path']);
                                $fileName = $pathInfo['filename'];
                                $extension = $pathInfo['extension'];
                            @endphp
                            
                                <div class="col-md-3">
                                    <div class="file-box">
                                        <h3>{{ $formfiles->name }}</h3>
                                        <input type="checkbox" class="dFiles" hidden checked name="files[]" value="{{ $presignedUrl }}">
                                        <a href="{{ $presignedUrl }}" target="_blank" class="btn btn-primary">
                                            Download
                                        </a>
                                        <a href="javascript:;" data-id="{{ $formfiles->id }}" class="btn btn-danger delete-file">Delete</a>
                                    </div>
                                </div>
                                
                             @endif
                        @endforeach
                    @endforeach
                </div>
                <button class="btn btn-dark btn-sm" id="downloadButton" style="float: right;" type="button">Generate ZIP</button>
                <button type="button" id="zipremove" hidden>Remove</button>
            </div>
            
        </div>
    </form>
</div>
@endsection

@push('scripts')


<!--ZIP FILE SCRIPT START-->

<script>
$('#downloadButton').click(async function(){
    var selectedFiles = [];
    
    $('#downloadButton').text('Archiving Files Please Wait...');
    $('#downloadButton').attr('disabled', 'true');
    
    $('input[class=dFiles]').each(function(){
        if($(this).prop('checked')){
            selectedFiles.push($(this).val());
        }
    });
    
    if(selectedFiles.length > 0){
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var data = JSON.stringify(selectedFiles);
        var contentFormId = {{$content_form->id}};
        
        console.log(selectedFiles);
        try {
            var response = await $.ajax({
                // url: "{{ url('/support/download') }}",
                url: '{{ route("download.support.forms") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                data:{
                 files: data, id: contentFormId, check: '4'
                }
            });
            
            console.log(response);
            if (response.url) {
                
                swal('Success!', response.file + ' <br> ZIP Generated Successfully. <br><br><span id="zipspan"></span>', 'success').then((value) => {
                  $('#downloadButton').css('display', 'block');
                });
                
                $('.swal2-confirm').css('display', 'none');
                
                var anchorTag = $('<a id="zipdownloadbtn" data-filename="'+ response.file +'">');
                
                anchorTag.attr('href', response.url);
                
                $('#zipremove').attr('data-file', response.file);
                $('#zipremove').attr('data-code', response.code);
                
                anchorTag.text('Download zip file.');
                anchorTag.addClass('btn btn-success');
                // Append the anchor tag to a container element
                $('#zipspan').append(anchorTag);
                
                $('#downloadButton').css('display','none');
                
                anchorTag.on('click', function() {
                    $(this).hide();
                    $('#downloadButton').css('display', 'block');
                    setTimeout(function() {
                        $('#zipremove').trigger('click');
                    }, 10000);
                });
                
            } else {
                console.error('Download URL not found in response');
            }
        } catch (error) {
            console.error('Error:', error);
            swal('Error!', 'An error occurred while processing your request.', 'error');
        }
    } else {
        swal('Error!', 'Please select at least one file to download.', 'error');
    }
});


$(document).on('click keydown mouseover', function() {
    if ($('.swal2-modal').hasClass('swal2-hide')) {
        
        $('#downloadButton').css('display', 'block');
        $('#downloadButton').attr('disabled', 'false');
    }
});

</script>
<script>
    $('#zipremove').on('click', function(){
        var filename = $('#zipremove').data('file');
        var code = $('#zipremove').data('code');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '{{ route("zip.remove") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            data: {
                filename: filename,
                code: code
            },
            success: function(removeResponse){
                location.reload(true);
            },
            error: function(xhr, status, error){
                console.error('Error removing file:', error);
            }
        });
    });
</script>

<!--ZIP FILE SCRIPT END-->

@endpush