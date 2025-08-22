@extends('layouts.app-support')
@section('title', 'SEO Form')

@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">SEO Brief INV#{{$seo_form->invoice->invoice_number}}</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <form class="col-md-12 brief-form" method="post" route="{{ route('client.logo.form.update', $seo_form->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title mb-3">SEO Brief Form</div>
                <div class="row">
                    <!--BUSINESS DETAILS START-->
                    <div class="col-md-3 form-group mb-3">
                        <label for="company_name">Company/Business Name</label>
                        <input class="form-control" name="company_name" id="company_name" type="text" value="{{ old('company_name', $seo_form->company_name) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="business_established">Business Address (To Display) </label>
                        <input class="form-control" name="business_established" id="business_established" type="text" value="{{ old('business_established', $seo_form->business_established) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="original_owner">Mailing Address (for verification purposes) </label>
                        <input class="form-control" name="original_owner" id="original_owner" type="email"  value="{{ old('original_owner', $seo_form->original_owner) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="age_current_site">Business City </label>
                        <input class="form-control" name="age_current_site" id="age_current_site" type="text"  value="{{ old('age_current_site', $seo_form->age_current_site) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="top_goals">Business State</label>
                        <input class="form-control" name="top_goals" id="top_goals" type="text"  value="{{ old('top_goals', $seo_form->top_goals) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="core_offer">Business Zip Code</label>
                        <input class="form-control" name="core_offer" id="core_offer" type="text"  value="{{ old('core_offer', $seo_form->core_offer) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="average_order_value">Business Phone Number</label>
                        <input class="form-control" name="average_order_value" id="average_order_value" type="text"  value="{{ old('average_order_value', $seo_form->average_order_value) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="selling_per_month">Business Contact Person Name</label>
                        <input class="form-control" name="selling_per_month" id="selling_per_month" type="text"  value="{{ old('selling_per_month', $seo_form->selling_per_month) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="client_lifetime_value">Business Contact Person Email Address</label>
                        <input class="form-control" name="client_lifetime_value" id="client_lifetime_value" type="text"  value="{{ old('client_lifetime_value', $seo_form->client_lifetime_value) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="supplementary_offers">Business Website Address (URL)</label>
                        <input class="form-control" name="supplementary_offers" id="supplementary_offers" type="text"  value="{{ old('supplementary_offers', $seo_form->supplementary_offers) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="getting_clients">Business Working Hours</label>
                        <input class="form-control" name="getting_clients" id="getting_clients" type="text"  value="{{ old('getting_clients', $seo_form->getting_clients) }}" required/>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="currently_spending">Business Category </label>
                        <input class="form-control" name="currently_spending" id="currently_spending" type="text"  value="{{ old('currently_spending', $seo_form->currently_spending) }}" required/>
                    </div>
                    <!--BUSINESS DETAILS END-->
                    
                    <div class="col-md-12 form-group mb-3">
                        <label for="monthly_visitors">Please share your URLs (If any) I don’t have any.</label>
                        <textarea class="form-control" name="monthly_visitors" id="monthly_visitors" rows="3" required>{{ old('monthly_visitors', $seo_form->monthly_visitors) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="people_adding">Do you have a physical location for your business? (Yes/No)</label>
                        <input class="form-control" name="people_adding" id="people_adding" type="text"  value="{{ old('people_adding', $seo_form->people_adding) }}" required/>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="monthly_financial">Which location(s) do you want to target?</label>
                        <textarea class="form-control" name="monthly_financial" id="monthly_financial" rows="3" required>{{ old('monthly_financial', $seo_form->monthly_financial) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="that_much">Do you use only one website for this business? If not, list out the others</label>
                        <textarea class="form-control" name="that_much" id="that_much" rows="3" required>{{ old('that_much', $seo_form->that_much) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="specific_target">Do you have the ability to make changes by yourself to your website? (Yes/No)</label>
                        <textarea class="form-control" name="specific_target" id="specific_target" rows="3" required>{{ old('specific_target', $seo_form->specific_target) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="competitors">Do you currently have access to your Google My Business page? (Yes/No)</label>
                        <textarea class="form-control" name="competitors" id="competitors" rows="3" required>{{ old('competitors', $seo_form->competitors) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="third_party_marketing">What specific cities or geographic area are most important for your business to be visible in?</label>
                        <textarea class="form-control" name="third_party_marketing" id="third_party_marketing" rows="3" required>{{ old('third_party_marketing', $seo_form->third_party_marketing) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="current_monthly_sales">Please mention the terms or services that are most important for your business to get found for on the internet (Use Comma To Separate)</label>
                        <textarea class="form-control" name="current_monthly_sales" id="current_monthly_sales" rows="3" required>{{ old('current_monthly_sales', $seo_form->current_monthly_sales) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="current_monthly_revenue">Which review websites does your business have most reviews on?</label>
                        <textarea class="form-control" name="current_monthly_revenue" id="current_monthly_revenue" rows="3" required>{{ old('current_monthly_revenue', $seo_form->current_monthly_revenue) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="target_region">Are there any keyword suggestions? Please list below!</label>
                        <textarea class="form-control" name="target_region" id="target_region" rows="3" required>{{ old('target_region', $seo_form->target_region) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="looking_to_execute">First off, tell us a little about your business. What do you offer, what sets you apart, etc.? </label>
                        <textarea class="form-control" name="looking_to_execute" id="looking_to_execute" rows="3" required>{{ old('looking_to_execute', $seo_form->looking_to_execute) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <h4>Target Audience/Vision:</h4>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="time_zone"> Who are you hoping to target with your social profiles? What about a target demographic/age range? </label>
                        <textarea class="form-control" name="time_zone" id="time_zone" rows="3" required>{{ old('time_zone', $seo_form->time_zone) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <h4>Content:</h4>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="time_zone">We like to include a balance of information about your business with interesting facts within the industry, and trending articles related to your industry and your customers. Do you have any topics that would be useful in starting a dialogue with your clients about?</label>
                        <textarea class="form-control" name="seo_content" id="seo_content" rows="3" required>{{ old('seo_content', $seo_form->seo_content) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <h4>Websites:</h4>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="time_zone">Are there any websites that you tend to use to find industry information or that you think we would find useful in gathering content from?</label>
                        <textarea class="form-control" name="inform_websites" id="inform_websites" rows="3" required>{{ old('inform_websites', $seo_form->inform_websites) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <h4>Things to Avoid:</h4>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="time_zone">Are there any topics, websites, or information that you DO NOT want us to use?</label>
                        <textarea class="form-control" name="things_to_avoid" id="things_to_avoid" rows="3" required>{{ old('things_to_avoid', $seo_form->things_to_avoid) }}</textarea>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="time_zone">We also encourage you to send us any new information as we go. This allows us to keep our content up to date.</label>
                        <textarea class="form-control" name="new_information" id="new_information" rows="3" required>{{ old('new_information', $seo_form->new_information) }}</textarea>
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
                    @foreach($seo_form->formfiles as $formfiles)
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
$('#downloadButton').click(function(){
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
        var smmFormId = {{$seo_form->id}};
        
        console.log(selectedFiles);
        $.ajax({
            // url: "{{ url('/support/download') }}",
            url: '{{ route("download.support.forms") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            data:{
             files: data, id: smmFormId, check: '5'
            },
            success: function(response){
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
            }
        });
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