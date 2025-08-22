<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Service;
use Response;
use App\Models\User;
use App\Models\Message;
use App\Models\ClientFile;
use App\Models\Project;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Events\MessageSent;
use Illuminate\Support\Str;
use App\Notifications\MessageNotification;
use Auth;
use Notification;
use \Carbon\Carbon;
use DateTimeZone;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class ClientChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function clientHome(){
        return view('client.home');
    }

    public function clientChat(){
        return view('client.chat');
    }

    public function fetchMessages()
    {
        return Message::where('user_id', Auth()->user()->id)->orWhere('recieve_id', Auth()->user()->id)->with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);
        $carbon = Carbon::now(new DateTimeZone('America/Los_Angeles'))->toDateTimeString();
        // send Notification to customer
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->sender_id = Auth::user()->id;
        $message->message = $request->message;
        $message->role_id = 3;
        $message->created_at = $carbon;
        $message->client_id = Auth::user()->id;
        $message->save();

        if($request->hasfile('h_Item_Attachments_FileInput'))
        {
            $files_array = array();
            $i = 0;
            foreach($request->file('h_Item_Attachments_FileInput') as $file)
            {
                
                // WASABI CALL
                        $disk = Storage::disk('wasabi');
                        
                        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $name = Auth::user()->email . '/messages-files/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                        
                        // Upload the file
                        $disk->put($name, file_get_contents($file), 'public');
                        $disk->setVisibility($name, 'public');
                        
                        // Check if the file exists
                        if ($disk->exists($name)) {
                             try {
                                    // Generate a presigned URL with public-read ACL
                                    $presignedUrl = $this->generatePresignedUrl($name);
                                    
                                    $i++;
                                    $client_file = new ClientFile();
                                    $client_file->name = $file_name;
                                    $client_file->path = $name;
                                    $client_file->task_id = -1;
                                    $client_file->user_id = Auth()->user()->id;
                                    $client_file->user_check = Auth()->user()->is_employee;
                                    $client_file->production_check = 2;
                                    $client_file->message_id = $message->id;
                                    $client_file->created_at = $carbon;
                                    $client_file->save();
                                    
                             }catch (AwsException $e) {
                                    
                            }
                        }
            }
        }
        $details = [
            'title' => Auth::user()->name . ' ' . Auth::user()->last_name . ' send you a message.',
            'body' => 'Please Login into your Dashboard to view it..'
        ];
        $messageData = [
            'id' => Auth()->user()->id,
            'name' => Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'text' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' has send you a Message',
            'details' => Str::limit(filter_var($request->message, FILTER_SANITIZE_STRING), 40 ),
            'url' => '',
        ];

        $sale = User::find(Auth::user()->client->user_id);
        $sale->notify(new MessageNotification($messageData));
        \Mail::to($sale->email)->send(new \App\Mail\ClientNotifyMail($details));
        $projects = Project::select('user_id')->where('client_id', Auth::user()->id)->get();
        foreach($projects as $project){
            \Mail::to($project->added_by->email)->send(new \App\Mail\ClientNotifyMail($details));
            $project->added_by->notify(new MessageNotification($messageData));
        } 
       
        $adminusers = User::where('is_employee', 2)->get();
        foreach($adminusers as $adminuser){
            Notification::send($adminuser, new MessageNotification($messageData));
        }
        return redirect()->back()->with('success', 'Message Send Successfully.');
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
