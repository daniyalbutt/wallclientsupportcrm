<?php

namespace App\Http\Controllers;

use App\Models\Proofreading;
use Illuminate\Http\Request;
use App\Models\FormFiles;
use Auth;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class ProofreadingController extends Controller
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
     * @param  \App\Models\Proofreading  $proofreading
     * @return \Illuminate\Http\Response
     */
    public function show(Proofreading $proofreading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proofreading  $proofreading
     * @return \Illuminate\Http\Response
     */
    public function edit(Proofreading $proofreading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proofreading  $proofreading
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $proofreading_form = Proofreading::find($id);
        if($proofreading_form->user_id == Auth::user()->id){
            $proofreading_form->description = $request->description;
            $proofreading_form->word_count = $request->word_count;
            $proofreading_form->services = $request->services;
            $proofreading_form->completion = $request->completion;
            $proofreading_form->previously = $request->previously;
            $proofreading_form->specific_areas = $request->specific_areas;
            $proofreading_form->suggestions = $request->suggestions;
            $proofreading_form->mention = $request->mention;
            $proofreading_form->major = $request->major; 
            $proofreading_form->trigger = $request->trigger;
            $proofreading_form->character = $request->character;
            $proofreading_form->guide = $request->guide;
            $proofreading_form->areas = $request->areas;
            $proofreading_form->save();

            if($request->hasfile('attachment'))
            {
                $i = 0;
                foreach($request->file('attachment') as $file)
                {
                    // WASABI CALL
                        $disk = Storage::disk('wasabi');
                        
                        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $name = Auth::user()->email . '/'. Auth::user()->email . '- Proofreading' . '/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                        
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
                                    $form_files->logo_form_id = $proofreading_form->id;
                                    $form_files->form_code = 9;
                                    $form_files->save();
                             }catch (AwsException $e) {
                                    
                            }
                        }
                }
            }
            return redirect()->back()->with('success', 'Proofreading Website Form Created');
        }else{
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proofreading  $proofreading
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proofreading $proofreading)
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
