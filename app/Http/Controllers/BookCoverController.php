<?php

namespace App\Http\Controllers;

use App\Models\BookCover;
use Illuminate\Http\Request;
use App\Models\FormFiles;
use Auth;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class BookCoverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\BookCover  $bookCover
     * @return \Illuminate\Http\Response
     */
    public function show(BookCover $bookCover)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookCover  $bookCover
     * @return \Illuminate\Http\Response
     */
    public function edit(BookCover $bookCover)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookCover  $bookCover
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bookcover_form = BookCover::find($id);
        if($bookcover_form->user_id == Auth::user()->id){
            $bookcover_form->title = $request->title;
            $bookcover_form->subtitle = $request->subtitle;
            $bookcover_form->author = $request->author;
            $bookcover_form->contributors = $request->contributors;
            $bookcover_form->genre = $request->genre;
            $bookcover_form->isbn = $request->isbn;
            $bookcover_form->trim_size = $request->trim_size;
            $bookcover_form->explain = $request->explain;
            $bookcover_form->information = $request->information;
            $bookcover_form->about = $request->about;
            $bookcover_form->keywords = $request->keywords;
            $bookcover_form->images_provide = $request->images_provide;
            $bookcover_form->category = $request->category;
            $bookcover_form->save();
            if($request->hasfile('attachment'))
            {
                $i = 0;
                foreach($request->file('attachment') as $file)
                {
                    // WASABI CALL
                        $disk = Storage::disk('wasabi');
                        
                        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $name = Auth::user()->email . '/'. $request->title . '/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                        
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
                                    $form_files->logo_form_id = $bookcover_form->id;
                                    $form_files->form_code = 6;
                                    $form_files->save();
                             }catch (AwsException $e) {
                                    
                            }
                        }
                }
            }
            return redirect()->back()->with('success', 'Book Cover Design Form Created');
        }else{
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookCover  $bookCover
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookCover $bookCover)
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
