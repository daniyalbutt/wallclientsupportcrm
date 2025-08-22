<?php

namespace App\Http\Controllers;

use App\Models\ContentWritingForm;
use Illuminate\Http\Request;
use Auth;
use App\Models\FormFiles;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class ContentWritingFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $content_form = ContentWritingForm::find($id);
        if($content_form->user_id == Auth::user()->id){
            
            $img_arr = [];
        
            foreach($content_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('client.content', compact('content_form','img_arr'));
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
     * @param  \App\Models\ContentWritingForm  $contentWritingForm
     * @return \Illuminate\Http\Response
     */
    public function show(ContentWritingForm $contentWritingForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContentWritingForm  $contentWritingForm
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentWritingForm $contentWritingForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContentWritingForm  $contentWritingForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $content_form = ContentWritingForm::find($id);
        if($content_form->user_id == Auth::user()->id){
            $content_form->company_name = $request->company_name;
            $content_form->company_details = $request->company_details;
            $content_form->company_industry = $request->company_industry;
            $content_form->company_reason = $request->company_reason;
            $content_form->company_products = $request->company_products;
            $content_form->short_description = $request->short_description;
            $content_form->mission_statement = $request->mission_statement;
            $content_form->keywords = $request->keywords;
            $content_form->competitor = $request->competitor;
            $content_form->company_business = $request->company_business;
            $content_form->customers_accomplish = $request->customers_accomplish;
            $content_form->company_sets = $request->company_sets;
            $content_form->existing_taglines = $request->existing_taglines;
            $content_form->save();
            if($request->hasfile('attachment'))
            {
                $i = 0;
                foreach($request->file('attachment') as $file)
                {
                    
                    // WASABI CALL
                    $disk = Storage::disk('wasabi');
                    
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $name = Auth::user()->email . '/'. $request->company_name . '/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                    
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
                            $form_files->logo_form_id = $content_form->id;
                            $form_files->form_code = 4;
                            $form_files->save();
                         }catch (AwsException $e) {
                                
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'Content Writing Form Created');
        }else{
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContentWritingForm  $contentWritingForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentWritingForm $contentWritingForm)
    {
        //
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
