<?php

namespace App\Http\Controllers;

use App\Models\LogoForm;
use App\Models\WebForm;
use App\Models\SmmForm;
use App\Models\ContentWritingForm;
use App\Models\SeoForm;
use App\Models\BookFormatting;
use App\Models\NoForm;
use App\Models\BookWriting;
use App\Models\AuthorWebsite;
use App\Models\Proofreading;
use App\Models\BookCover;
use Illuminate\Http\Request;
use App\Models\FormFiles;
use Auth;
use File;
use ZipArchive;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class LogoFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $logo_form = LogoForm::find($id);
        if($logo_form->user_id == Auth::user()->id){
            
            $img_arr = [];
        
            foreach($logo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('client.logo', compact('logo_form','img_arr'));
        }else{
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogoForm  $logoForm
     * @return \Illuminate\Http\Response
     */
    public function show(LogoForm $logoForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogoForm  $logoForm
     * @return \Illuminate\Http\Response
     */
    public function edit(LogoForm $logoForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogoForm  $logoForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $logo_form = LogoForm::find($id);
        if($logo_form->user_id == Auth::user()->id){
            $logo_form->logo_name = $request->logo_name;
            $logo_form->slogan = $request->slogan;
            $logo_form->business = $request->business;
            $logo_form->logo_categories = json_encode($request->logo_categories);
            $logo_form->icon_based_logo = json_encode($request->icon_based_logo);
            $logo_form->font_style = $request->font_style;
            $logo_form->additional_information = $request->additional_information;
            $logo_form->save();
            if($request->hasfile('attachment'))
            {
                $i = 0;
                foreach($request->file('attachment') as $file)
                {
                    // WASABI CALL
                    $disk = Storage::disk('wasabi');
                    
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $name = Auth::user()->email . '/'. $request->logo_name . '/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                    
                    // Upload the file
                    $disk->put($name, file_get_contents($file), 'public');
                    $disk->setVisibility($name, 'public');
                    
                    // Check if the file exists
                    if ($disk->exists($name)) {
                     try {
                            // Generate a presigned URL with public-read ACL
                            $presignedUrl = $this->generatePresignedUrl($name);
                            
                            $i++;
                            $form_files = new FormFiles();
                            $form_files->name = $file_name;
                            $form_files->path = $name;
                            $form_files->logo_form_id = $logo_form->id;
                            $form_files->form_code = 1;
                            $form_files->save();
                            
                        } catch (AwsException $e) {
                            
                        }
                    }
                    
                }
            }
            
            // dd($logo_form);
            return redirect()->back()->with('success', 'Logo Form Created');
        }else{
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogoForm  $logoForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        if($request->code == 1){
            $logo_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            
            if(Auth::user()->id == $logo_form->logo_form->user_id){
                $logo_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 2){
            $web_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = WebForm::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $web_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
            
        }else if($request->code == 3){
            $smm_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = SmmForm::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $smm_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 4){
            $content_writing_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = ContentWritingForm::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $content_writing_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 5){
            $seo_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = SeoForm::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $seo_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 6){
            $book_format_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = BookFormatting::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $book_format_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 7){
            $book_writing_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = BookWriting::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $book_writing_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 8){
            $author_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = AuthorWebsite::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $author_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 9){
            $proof_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = Proofreading::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $proof_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }else if($request->code == 10){
            $cover_form = FormFiles::where('form_code', $request->code)->where('id', $request->id)->first();
            $formCheck = BookCover::where('user_id', Auth::user()->id)->get();
            
            if($formCheck != null){
                $cover_form->delete();
                return response()->json(['success' => true, 'message' => 'File Deleted Successfully']);
            }else{
                return response()->json(['success' => false, 'message' => 'You Dont have Access']);
            }
        }
        
    }

    public function getBriefPending(){
        $logo_form = LogoForm::where('logo_name', '')->get();
        $web_form = WebForm::where('business_name', null)->get();
        $smm_form = SmmForm::where('business_name', null)->get();
        $content_writing_form = ContentWritingForm::where('company_name', null)->get();
        $seo_form = SeoForm::where('company_name', null)->get();
        $book_formatting_form = BookFormatting::where('book_title', null)->get();
        $author_website_form = AuthorWebsite::where('author_name', null)->get();
        $book_writing_form = BookWriting::where('book_title', null)->get();
        $no_form = NoForm::where('name', null)->get();
        $proofreading_form = Proofreading::where('description', null)->get();
        $bookcover_form = BookCover::where('title', null)->get();
        return view('admin.brief.pending', compact('logo_form', 'web_form', 'smm_form', 'content_writing_form', 'seo_form', 'book_formatting_form', 'book_writing_form', 'author_website_form', 'proofreading_form', 'bookcover_form', 'no_form'));
    }

    public function getBriefPendingById(){
        $logo_form = LogoForm::where('logo_name', '')->where('agent_id', Auth()->user()->id)->get();
        $web_form = WebForm::where('business_name', null)->where('agent_id', Auth()->user()->id)->get();
        $smm_form = SmmForm::where('business_name', null)->where('agent_id', Auth()->user()->id)->get();
        $content_writing_form = ContentWritingForm::where('company_name', null)->where('agent_id', Auth()->user()->id)->get();
        $seo_form = SeoForm::where('company_name', null)->where('agent_id', Auth()->user()->id)->get();
        return view('sale.brief.pending', compact('logo_form', 'web_form', 'smm_form', 'content_writing_form', 'seo_form'));
    }
    
    public function getBriefPendingByIdManager(){
        $logo_form = LogoForm::where('logo_name', '')->whereHas('invoice', function ($query) {
                        return $query->where('brand', Auth::user()->brand_list());
                    })->get();                                
        $web_form = WebForm::where('business_name', null)->whereHas('invoice', function ($query) {
                        return $query->where('brand', Auth::user()->brand_list());
                    })->get();
        $smm_form = SmmForm::where('business_name', null)->whereHas('invoice', function ($query) {
                        return $query->where('brand', Auth::user()->brand_list());
                    })->get();

        $content_writing_form = ContentWritingForm::where('company_name', null)->whereHas('invoice', function ($query) {
                                    return $query->where('brand', Auth::user()->brand_list());
                                })->get();

        $seo_form = SeoForm::where('company_name', null)->whereHas('invoice', function ($query) {
                        return $query->where('brand', Auth::user()->brand_list());
                    })->get();
        
        $book_formatting_form = BookFormatting::where('book_title', null)->whereHas('invoice', function ($query) {
                        return $query->where('brand', Auth::user()->brand_list());
                    })->get();

        $author_website_form = AuthorWebsite::where('author_name', null)->whereHas('invoice', function ($query) {
                                    return $query->where('brand', Auth::user()->brand_list());
                                })->get();
        
        $book_writing_form = BookWriting::where('book_title', null)->whereHas('invoice', function ($query) {
            return $query->where('brand', Auth::user()->brand_list());
        })->get();
        
        $no_form = NoForm::whereHas('invoice', function ($query) {
                    return $query->where('brand', Auth::user()->brand_list());
                })->get();

        $proofreading_form = Proofreading::where('description', null)->whereHas('invoice', function ($query) {
            return $query->where('brand', Auth::user()->brand_list());
        })->get();

        $bookcover_form = BookCover::where('title', null)->whereHas('invoice', function ($query) {
            return $query->where('brand', Auth::user()->brand_list());
        })->get();

        return view('manager.brief.pending', compact('logo_form', 'web_form', 'smm_form', 'content_writing_form', 'seo_form', 'book_formatting_form', 'book_writing_form', 'author_website_form', 'no_form', 'proofreading_form', 'bookcover_form'));
    }

    public function getPendingProject(){
        $logo_form = LogoForm::where('logo_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $web_form = WebForm::where('business_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $smm_form = SmmForm::where('business_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $content_writing_form = ContentWritingForm::where('company_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $seo_form = SeoForm::where('company_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $book_formatting_form = BookFormatting::where('book_title', '!=' , '')->with('project')->doesntHave('project')->get();
        $book_writing_form = BookWriting::where('book_title', '!=' , '')->with('project')->doesntHave('project')->get();
        $author_website_form = AuthorWebsite::where('author_name', '!=' , '')->with('project')->doesntHave('project')->get();
        $proofreading_form = Proofreading::where('description', '!=' , '')->with('project')->doesntHave('project')->get();
        $bookcover_form = BookCover::where('title', '!=' , '')->with('project')->doesntHave('project')->get();
        $no_form = NoForm::where('name', '!=' , '')->with('project')->doesntHave('project')->get();
        return view('admin.brief.fill', compact('logo_form', 'web_form', 'smm_form', 'content_writing_form', 'seo_form', 'book_formatting_form', 'book_writing_form', 'author_website_form', 'proofreading_form', 'bookcover_form', 'no_form'));
    }

    public function getPendingProjectManager(){
        $logo_form = LogoForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                        return $query->whereIn('brand', Auth::user()->brand_list());
                    })->orderBy('id', 'desc')->get();
        $web_form = WebForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                        return $query->whereIn('brand', Auth::user()->brand_list());
                    })->orderBy('id', 'desc')->get();
        $smm_form = SmmForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                        return $query->whereIn('brand', Auth::user()->brand_list());
                    })->orderBy('id', 'desc')->get();
        $content_writing_form = ContentWritingForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                                    return $query->whereIn('brand', Auth::user()->brand_list());
                                })->orderBy('id', 'desc')->get();
        $seo_form = SeoForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                        return $query->whereIn('brand', Auth::user()->brand_list());
                    })->orderBy('id', 'desc')->get();
        $book_formatting_form = BookFormatting::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                                    return $query->whereIn('brand', Auth::user()->brand_list());
                                })->orderBy('id', 'desc')->get();
        $book_writing_form = BookWriting::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
            return $query->whereIn('brand', Auth::user()->brand_list());
        })->orderBy('id', 'desc')->get();

        $author_website_form = AuthorWebsite::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
            return $query->whereIn('brand', Auth::user()->brand_list());
        })->orderBy('id', 'desc')->get();

        $proofreading_form = Proofreading::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
            return $query->whereIn('brand', Auth::user()->brand_list());
        })->orderBy('id', 'desc')->get();

        $bookcover_form = BookCover::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
            return $query->whereIn('brand', Auth::user()->brand_list());
        })->orderBy('id', 'desc')->get();
        
        $no_form = NoForm::with('project')->doesntHave('project')->whereHas('invoice', function ($query) {
                        return $query->whereIn('brand', Auth::user()->brand_list());
                    })->orderBy('id', 'desc')->get();
        
        return view('manager.brief.fill', compact('logo_form', 'web_form', 'smm_form', 'content_writing_form', 'seo_form', 'book_formatting_form', 'book_writing_form', 'author_website_form', 'no_form', 'proofreading_form', 'bookcover_form'));
    }

    public function getPendingProjectbyIdManager($id, $form){
        if($form == 1){
            $logo_form = LogoForm::find($id);
            return view('manager.brief.logoform', compact('logo_form'));
        }elseif($form == 2){
            $web_form = WebForm::find($id);
            return view('manager.brief.webform', compact('web_form'));
        }elseif($form == 3){
            $smm_form = SmmForm::find($id);
            return view('manager.brief.smmform', compact('smm_form'));
        }elseif($form == 4){
            $content_form = ContentWritingForm::find($id);
            return view('manager.brief.contentform', compact('content_form'));
        }elseif($form == 5){
            $seo_form = SeoForm::find($id);
            return view('manager.brief.seoform', compact('seo_form'));
        }elseif($form == 6){
            $book_formatting_form = BookFormatting::find($id);
            return view('manager.brief.bookformattingform', compact('book_formatting_form'));
        }elseif($form == 7){
            $data = BookWriting::find($id);
            return view('manager.brief.bookwritingform', compact('data'));
        }elseif($form == 8){
            $data = AuthorWebsite::find($id);
            return view('manager.brief.authorwebsiteform', compact('data'));
        }elseif($form == 9){
            $data = Proofreading::find($id);
            return view('manager.brief.proofreadingform', compact('data'));
        }elseif($form == 10){
            $data = BookCover::find($id);
            return view('manager.brief.bookcoverform', compact('data'));
        }
    }

    public function getPendingProjectbyId($id, $form){
        if($form == 1){
            $logo_form = LogoForm::find($id);
            return view('admin.brief.logoform', compact('logo_form'));
        }elseif($form == 2){
            $web_form = WebForm::find($id);
            return view('admin.brief.webform', compact('web_form'));
        }elseif($form == 3){
            $smm_form = SmmForm::find($id);
            return view('admin.brief.smmform', compact('smm_form'));
        }elseif($form == 4){
            $content_form = ContentWritingForm::find($id);
            return view('admin.brief.contentform', compact('content_form'));
        }elseif($form == 5){
            $seo_form = SeoForm::find($id);
            return view('admin.brief.seoform', compact('seo_form'));
        }elseif($form == 6){
            $data = BookFormatting::find($id);
            return view('admin.brief.bookformattingform', compact('data'));
        }elseif($form == 7){
            $data = BookWriting::find($id);
            return view('admin.brief.bookwritingform', compact('data'));
        }elseif($form == 8){
            $data = AuthorWebsite::find($id);
            return view('admin.brief.authorwebsiteform', compact('data'));
        }elseif($form == 9){
            $data = Proofreading::find($id);
            return view('admin.brief.proofreadingform', compact('data'));
        }elseif($form == 10){
            $data = BookCover::find($id);
            return view('admin.brief.bookcoverform', compact('data'));
        }
    }
    
    protected function generatePresignedUrl($filePath)
    {
       $s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => config('filesystems.disks.wasabi.region'),
            'credentials' => [
                'key'    => config('filesystems.disks.wasabi.key'),
                'secret' => config('filesystems.disks.wasabi.secret'),
            ],
            'endpoint' => config('filesystems.disks.wasabi.endpoint'),
        ]);

        $command = $s3Client->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.wasabi.bucket'),
            'Key'    => $filePath,
            'ACL'    => 'public-read', // Set ACL for public-read access
        ]);

        $request = $s3Client->createPresignedRequest($command, '+6 days 23 hours');

        return (string) $request->getUri();
    }
}
