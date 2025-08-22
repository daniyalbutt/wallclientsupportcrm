@extends('layouts.app-production')
@section('title', 'Book Formatting & Publishing Form')

@section('content')
<div class="breadcrumb">
    <h1 class="mr-2">Book Formatting & Publishing Brief INV#{{$data->invoice->invoice_number}}</h1>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <form class="col-md-12 brief-form" method="post" route="{{ route('client.logo.form.update', $data->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title mb-3 text-center"><h2>Book Formatting & Publishing Brief Form</h2></div>
                                </div>
                                <div class="card-body">
                                    <div class="card-title mb-3">Author’s Details</div>
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="book_title">What is the title of the book? <span>*</span></label>
                                            <input class="form-control" name="book_title" id="book_title" type="text" value="{{ old('book_title', $data->book_title) }}" required/>
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="book_subtitle">What is the subtitle of the book?</label>
                                            <input class="form-control" name="book_subtitle" id="book_subtitle" type="text" value="{{ old('book_subtitle', $data->book_subtitle) }}"/>
                                        </div>
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="author_name">What is the name of the author? <span>*</span></label>
                                            <input class="form-control" name="author_name" id="author_name" type="text"  value="{{ old('author_name', $data->author_name) }}" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title mb-3">Book Details</div>
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="contributors">Book Description <span>*</span></label>
                                            <textarea class="form-control" name="contributors" id="contributors" rows="5" required>{{ old('contributors', $data->contributors) }}</textarea>
                                        </div>
                                         <div class="col-md-6 form-group mb-3">
                                            <label for="keywords">Keywords to target: (1-7 Keywords) <span>*</span></label>
                                            <input class="form-control" name="keywords" id="keywords" type="text"  value="{{ old('keywords', $data->keywords) }}" required/>
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="book_category">Book Category(s)<span>*</span></label>
                                            <input class="form-control" name="book_category" id="book_category" type="text"  value="{{ old('book_category', $data->book_category) }}" required/>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>Book Color Type <span>*</span></p>
                                        <div class="row">
                                            <div class="col-lg-2 form-group mb-3">
                                                <label for="black_white" class="w-100"> 
                                                    <div class="formCheck purpose-box font-box">
                                                        <div class="form-check ml-0 pl-0">
                                                            <input type="radio" class="form-check-input" id="black_white" name="book_color_type" value="1" data-value="Book Color Type" data-name="required" {{ $data->book_color_type == '1' ? 'checked' : '' }} >Black & White 
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="col-lg-2 form-group mb-3">
                                                <label for="standard_color" class="w-100"> 
                                                    <div class="formCheck purpose-box font-box">
                                                        <div class="form-check ml-0 pl-0">
                                                            <input type="radio" class="form-check-input" id="standard_color" name="book_color_type" value="2" data-value="Book Color Type" data-name="required" {{ $data->book_color_type == '2' ? 'checked' : '' }} >Standard Color 
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="col-lg-2 form-group mb-3">
                                                <label for="premium_color" class="w-100"> 
                                                    <div class="formCheck purpose-box font-box">
                                                        <div class="form-check ml-0 pl-0">
                                                            <input type="radio" class="form-check-input" id="premium_color" name="book_color_type" value="3" data-value="Book Color Type" data-name="required" {{ $data->book_color_type == '3' ? 'checked' : '' }} >Premium Color
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title mb-3">Formatting Requirements</div>
                                    <p>Which trim size do you want your book to be formatted on? <span>*</span></p>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_1" name="trim_size" value="5.5_8.5_landscape" {{ $data->trim_size == '5.5_8.5_landscape' ? 'checked' : '' }}>
                                                    <label for="trim_size_1" class="comic">5.5″ X 8.5″ (Landscape)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_2" name="trim_size" value="5.5_8.5_portrait" {{ $data->trim_size == '5.5_8.5_portrait' ? 'checked' : '' }}>
                                                    <label for="trim_size_2" class="comic">5.5″ X 8.5″ (Portrait)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_3" name="trim_size" value="6_9_landscape" {{ $data->trim_size == '6_9_landscape' ? 'checked' : '' }}>
                                                    <label for="trim_size_3" class="comic">6″ X 9″ (Landscape)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_4" name="trim_size" value="6_9_portrait" {{ $data->trim_size == '6_9_portrait' ? 'checked' : '' }}>
                                                    <label for="trim_size_4" class="comic">6″ X 9″ (Portrait)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_5" name="trim_size" value="8.5_11_landscape" {{ $data->trim_size == '8.5_11_landscape' ? 'checked' : '' }}>
                                                    <label for="trim_size_5" class="comic">8.5″ X 11″ (Landscape)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_6" name="trim_size" value="8.5_11_portrait" {{ $data->trim_size == '8.5_11_portrait' ? 'checked' : '' }}>
                                                    <label for="trim_size_6" class="comic">8.5″ X 11″ (Portrait)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="formCheck font-box">
                                                <div class="form-check pl-0">
                                                    <input type="radio" class="form-check-input" id="trim_size_7" name="trim_size" value="Other" {{ $data->trim_size == 'Other' ? 'checked' : '' }}>
                                                    <label for="trim_size_7" class="comic">Other (Please specify)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 form-group mb-3">
                                            <label for="trim_size_8">If you have selected other, please specify the trim size and orientation (landscape/portrait) you want your book to be formatted on.</label>
                                            <input class="form-control" name="other_trim_size" id="trim_size_8" type="text"  value="{{ old('other_trim_size', $data->other_trim_size) }}"/>
                                        </div>
                                        <div class="col-md-12 form-group mb-3">
                                            <label for="additional_instructions">Any Additional Instructions that you would like us to follow?</label>
                                            <textarea class="form-control" name="additional_instructions" id="additional_instructions" rows="5">{{ old('additional_instructions', $data->additional_instructions) }}</textarea>
                                        </div>
                                    </div>
                                    <p>Which formats do you want your book to be formatted on? <span>*</span></p>
                                    @php
                                    $book_formatted = json_decode($data->book_formatted);
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label for="ebook" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="ebook" name="book_formatted[]" value="eBook" @if($book_formatted != null) {{ in_array('eBook', $book_formatted) ? ' checked' : '' }} @endif data-value="Which formats do you want your book to be formatted on?" data-name="required">eBook
                                                    </div>
                                                </div>
                                            </label>
                                            <div id="ebookPrice" style="display: none;">
                                                <label for="ebookPriceInput">Specify the Price you would like to set on Amazon for your e-Book</label>
                                                <input type="text" class="form-control" id="ebookPriceInput" name="ebook_price" value="{{ $data->ebook_price !=  null ? $data->ebook_price : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="paperback" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="paperback" name="book_formatted[]" value="Paperback" @if($book_formatted != null) {{ in_array('Paperback', $book_formatted) ? ' checked' : '' }} @endif>Paperback
                                                    </div>
                                                </div>
                                            </label>
                                            <div id="paperbackPrice" style="display: none;">
                                                <label for="paperbackPriceInput">Specify the Price you would like to set on Amazon for your Paperback</label>
                                                <input type="text" class="form-control" id="paperbackPriceInput" name="paperback_price" value="{{ $data->paperback_price !=  null ? $data->paperback_price : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="hardcover" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="hardcover" name="book_formatted[]" value="Hardcover" @if($book_formatted != null) {{ in_array('Hardcover', $book_formatted) ? ' checked' : '' }} @endif>Hardcover
                                                    </div>
                                                </div>
                                            </label>
                                            <div id="hardcoverPrice" style="display: none;">
                                                <label for="hardcoverPriceInput">Specify the Price you would like to set on Amazon for your Hardcover</label>
                                                <input type="text" class="form-control" id="hardcoverPriceInput" name="hardcover_price" value="{{ $data->hardcover_price !=  null ? $data->hardcover_price : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pt-3">Where do you want to publish? <span>*</span></p>
                                    @php
                                    $publish_your_book = json_decode($data->publish_your_book);
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <label for="amazon_kdp" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="amazon_kdp" name="publish_your_book[]" value="Amazon KDP" @if($publish_your_book != null) {{ in_array('Amazon KDP', $publish_your_book) ? ' checked' : '' }} @endif data-value="Where do you want to?" data-name="required">Amazon KDP
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="barnes_noble" class="w-100">
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="barnes_noble" name="publish_your_book[]" value="Barnes & Noble" @if($publish_your_book != null) {{ in_array('Barnes & Noble', $publish_your_book) ? ' checked' : '' }} @endif>Barnes & Noble
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="google_books" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="google_books" name="publish_your_book[]" value="Google Books" @if($publish_your_book != null) {{ in_array('Google Books', $publish_your_book) ? ' checked' : '' }} @endif>Google Books
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="kobo" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="kobo" name="publish_your_book[]" value="Kobo" @if($publish_your_book != null) {{ in_array('Kobo', $publish_your_book) ? ' checked' : '' }} @endif>Kobo
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="ingram_spark" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="ingram_spark" name="publish_your_book[]" value="Ingram Spark" @if($publish_your_book != null) {{ in_array('Ingram Spark', $publish_your_book) ? ' checked' : '' }} @endif>Ingram Spark
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="other_publish" class="w-100"> 
                                                <div class="formCheck purpose-box font-box">
                                                    <div class="form-check ml-0 pl-0">
                                                        <input type="checkbox" class="form-check-input" id="other_publish" name="publish_your_book[]" value="Other" @if($publish_your_book != null) {{ in_array('Other', $publish_your_book) ? ' checked' : '' }} @endif>Other
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-12 form-group mb-3">
                                            <label for="other_publish_your_book">If you have selected other, please specify where you want to publish your book.</label>
                                            <input class="form-control" name="other_publish_your_book" id="other_publish_your_book" type="text"   value="{{ $data->other_publish_your_book !=  null ? $data->other_publish_your_book : '' }}"/>
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
                    @foreach($data->formfiles as $formfiles)
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
                                        <h3><input type="checkbox" class="dFiles" name="files[]" value="{{ $presignedUrl }}"> {{ $formfiles->name }}</h3>
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
        var bookformatingFormId = {{$data->id}};
        
        // console.log(selectedFiles);
        try {
            var response = await $.ajax({
                // url: "{{ url('/support/download') }}",
                url: '{{ route("download.production.forms") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                data:{
                 files: data, id: bookformatingFormId, check: '6'
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
                    }, 5000);
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

<script>
    $(document).ready(function() {
        if ($('#ebook').is(':checked')) {
            $('#ebookPrice').show();
            $('#ebookPriceInput').prop('required', true);
        }
        
        if ($('#paperback').is(':checked')) {
            $('#paperbackPrice').show();
            $('#paperbackPriceInput').prop('required', true);
        }
        
        if ($('#hardcover').is(':checked')) {
            $('#hardcoverPrice').show();
            $('#hardcoverPriceInput').prop('required', true);
        }
        
        // Toggle price input fields based on checkbox changes
        $('#ebook').change(function() {
            $('#ebookPrice').toggle($(this).is(':checked'));
            $('#ebookPriceInput').prop('required', $(this).is(':checked'));
        });
        
        $('#paperback').change(function() {
            $('#paperbackPrice').toggle($(this).is(':checked'));
            $('#paperbackPriceInput').prop('required', $(this).is(':checked'));
        });
        
        $('#hardcover').change(function() {
            $('#hardcoverPrice').toggle($(this).is(':checked'));
            $('#hardcoverPriceInput').prop('required', $(this).is(':checked'));
        });
    });
</script>
<script>
    $(document).ready(function() {
        
        if($('#other_publish').is(':checked')){
            $('#other_publish_your_book').prop('required', true);
        }else{
            $('#other_publish_your_book').prop('required', false);
        }
        
        if($('#trim_size_7').is(':checked')){
            $('#trim_size_8').prop('required', true); // Set the input field as required
        } else {
            $('#trim_size_8').prop('required', false); // Remove the required attribute
        }
        
        $('#trim_size_7').change(function() {
            if ($(this).is(':checked')) {
                $('#trim_size_8').prop('required', true); // Set the input field as required
            } else {
                $('#trim_size_8').prop('required', false); // Remove the required attribute
            }
        });
        $('#trim_size_1 , #trim_size_2, #trim_size_3, #trim_size_4, #trim_size_5, #trim_size_6').change(function() {
            if ($(this).is(':checked')) {
                $('#trim_size_8').prop('required', false);
            }
        });
        
        $('#other_publish').change(function(){
           if($(this).is(':checked')) {
               $('#other_publish_your_book').prop('required', true);
           }else{
               $('#other_publish_your_book').prop('required', false);
           }
        });
        
        
    });
</script>
@endpush