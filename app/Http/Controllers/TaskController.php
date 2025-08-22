<?php

namespace App\Http\Controllers;
use App\Models\LogoForm;
use App\Models\WebForm;
use App\Models\SmmForm;
use App\Models\ContentWritingForm;
use App\Models\SeoForm;
use App\Models\BookFormatting;
use App\Models\BookWriting;
use App\Models\AuthorWebsite;
use App\Models\Proofreading;
use App\Models\BookCover;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\ClientFile;
use App\Models\Message;
use App\Models\Brand;
use App\Models\TaskMemberList;
use App\Models\ProductionMemberAssign;
use Illuminate\Http\Request;
use App\Notifications\TaskNotification;
use Illuminate\Support\Str;
use Notification;
use Carbon\Carbon;
use DB;
use Auth;
use App\Models\SubTask;
use File;
use DateTime;
use ZipArchive;
use App\Events\NewTaskEvent;

// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Task::where('brand_id', Auth()->user()->brand_list())->get();
        return view('sale.task.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project = Project::where('status', 1)->where('brand_id', Auth()->user()->brand_list())->get();
        return view('sale.task.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project' => 'required',
            'category' => 'required',
            'description' => 'required',
            'duedate' => 'required',
        ]);
        $validate_task = Task::where('project_id', $request->project)->where('category_id', $request->category)->first();
        if($validate_task != null){
            return redirect()->back()->with('error', 'Project and Task Already Taken.');
        }
        $get_product = Project::where('id', $request->project)->first();
        $request->request->add(['brand_id' => $get_product->brand->id]);
        $request->request->add(['project_id' => $request->input('project')]);
        $request->request->add(['category_id' => $request->input('category')]);
        $request->request->add(['user_id' => auth()->user()->id]);
        $task = Task::create($request->all());
        if($request->hasfile('filenames'))
        {
            $i = 0;
            foreach($request->file('filenames') as $file)
            {
                 // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $validate_task->task->projects->name . '- SUBTASK/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->user_id = Auth()->user()->id;
                            $client_file->user_check = Auth()->user()->is_employee;
                            $client_file->task_id = $task->id;
                            $client_file->created_at = $request->created_at;
                            $client_file->save();
                            
                     }catch (AwsException $e) {
                            
                        }
                    }    
            }
        }
        // Sale For 0
        // Production For 1
        // Admin For 2
        $this->sendTaskNotification($task->id, 1);
        return redirect()->back()->with('success', 'Task created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        return view('sale.task.show', compact('task'));
    }

    public function productionSubtaskShow($id, $notify = null){
        if($notify != null){
            $Notification = Auth::user()->Notifications->find($notify);
            if($Notification){
                $Notification->markAsRead();
            }   
        }

        $subtask = ProductionMemberAssign::find($id);
        
        $img_arr = [];
        
        foreach($subtask->task->client_files as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
            // array_push($img_arr, $presignedUrl);
        }
        
        return view('production.subtask.show', compact('subtask', 'img_arr'));
        // if($subtask->assigned_by == Auth::user()->id){
            
        // }else{
        //     return redirect()->back();
        // }
    }

    public function productionShow($id, $notify = null){
        if($notify != null){
            $Notification = Auth::user()->Notifications->find($notify);
            if($Notification){
                $Notification->markAsRead();
            }   
        }
        $task = Task::find($id);
        
        $img_arr = [];
        
        foreach($task->client_files as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
            // array_push($img_arr, $presignedUrl);
        }
        
        
        $members = User::where('is_employee', 5)->whereHas('category', function ($query) use ($task){
            return $query->where('category_id', '=', $task->category_id);
        })->get();
        return view('production.task.show', compact('task', 'members', 'img_arr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function insertFilesMember(Request $request, $id, $subtask_id){
        
        $get_task = SubTask::where('id', $subtask_id)->first();
        // dd($get_task);
        if($request->hasfile('images'))
        {
            $files_array = array();
            $files_url = array();
            $i = 0;
            foreach($request->file('images') as $file)
            {
                 // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $get_task->task->projects->name . '- SUBTASK/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->task_id = $id;
                            $client_file->subtask_id = $subtask_id;
                            $client_file->user_id = Auth()->user()->id;
                            $client_file->user_check = Auth()->user()->is_employee;
                            $client_file->production_check = 0;
                            $client_file->save();
                            array_push($files_array, $client_file->id);
                            $files_url[$client_file->id] = $presignedUrl;
                     }catch (AwsException $e) {
                            
                        }
                    }
            }
        }
        $data = ClientFile::whereIn('id', $files_array)->get();
        $role = '';
        $uploaded_by = Auth()->user()->name . ' ('. $role .')';
        return response()->json(['uploaded' => 'success', 'files' => $data, 'uploaded_by' => $uploaded_by, 'files_url' => $files_url]);
    }

    public function managerInsertFiles(Request $request, $id){
        if($request->hasfile('images'))
        {
            $files_array = array();
            $files_url = array();
            $i = 0;
            $get_task = Task::where('id', $id)->first();
            foreach($request->file('images') as $file)
            {
                // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $get_task->projects->name . '- TASK/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->task_id = $id;
                            $client_file->user_id = Auth()->user()->id;
                            $client_file->user_check = Auth()->user()->is_employee;
                            $client_file->production_check = 1;
                            $client_file->save();
                            array_push($files_array, $client_file->id);
                            $files_url[$client_file->id] = $presignedUrl;
                     }catch (AwsException $e) {
                            
                        }
                    }
            }
        }
        $data = ClientFile::whereIn('id', $files_array)->get();
        $role = '';
        $uploaded_by = Auth()->user()->name . ' ' . Auth()->user()->last_name;
        return response()->json(['uploaded' => 'success', 'files' => $data, 'uploaded_by' => $uploaded_by, 'files_url' => $files_url]);
    }

    public function deleteFiles(Request $request){
        $file = ClientFile::find($request->id);
        if($file->user_id == Auth::user()->id){
            // File::delete(public_path().'/files/'.$file->path);
            $file->delete();
            return response()->json(['success' => true, 'data' => 'File Deleted Succesfully']);
        }else{
            return response()->json(['success' => false, 'data' => 'This file is uploaded by another Person.']);
        }
    }

    public function insertFiles(Request $request, $id){
        
        
        $get_task = Task::where('id', $id)->first();
        
        if($request->hasfile('images'))
        {
            $files_array = array();
            $files_url = array();
            $i = 0;
            foreach($request->file('images') as $file)
            {
                
                // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $get_task->projects->name . '- TASK/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->task_id = $id;
                            $client_file->user_id = Auth()->user()->id;
                            $client_file->user_check = Auth()->user()->is_employee;
                            $client_file->production_check = 1;
                            $client_file->save();
                            array_push($files_array, $client_file->id);
                            $files_url[$client_file->id] = $presignedUrl;
                            
                        }catch (AwsException $e) {
                            
                        }
                    }
            }
        }

        $data = ClientFile::whereIn('id', $files_array)->get();
        $role = '';
        $uploaded_by = Auth()->user()->name . ' ' . Auth()->user()->last_name;
        return response()->json(['uploaded' => 'success', 'files' => $data, 'uploaded_by' => $uploaded_by, 'files_url' => $files_url]);
    }


    public function sendTaskNotification($task_id, $role) {
        if($role == 1){
            $task = Task::where('id', $task_id)->first();
            $category_users = DB::table('category_users')->select('user_id')->where('category_id', $task->category_id)->pluck('user_id');
            $users = User::whereIn('id', $category_users)->where('is_employee', 1)->get();
            $taskData = [
                'id' => Auth()->user()->id,
                'task_id' => $task_id,
                'name' => Auth()->user()->name . ' ' . Auth()->user()->last_name,
                'email' => Auth()->user()->email,
                'text' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' Created a Task ',
                'message' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' Created a Task ',
                'details' => Str::limit(filter_var($task->description, FILTER_SANITIZE_STRING), 40 ),
            ];
            foreach($users as $user){
                $user->notify(new TaskNotification($taskData));
            }
        }
        return true;
    }

    public function updateTask(Request $request, $id){
        $value = $request->value;
        $task = Task::find($id);
        $user = $task->user;
        $task->status = $value;
        $task->save();
        $status = '';
        if($value == 0){
            $status = 'Open';
        }else if($value == 1){
            $status = 'Re Open';
        }else if($value == 2){
            $status = 'Hold';
        }else if($value == 3){
            $status = 'Completed';
        }else if($value == 4){
            $status = 'In Progress';
        }else if($value == 5){
            $status = 'Sent for Approval';
        }else if($value == 6){
            $status = 'Incomplete Brief';
        }

        $description = $task->projects->name . " Marked as " . $status;
        $assignData = [
            'id' => Auth()->user()->id,
            'task_id' => $task->id,
            'name' => Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'text' => $description,
            'details' => '',
        ];
        $user->notify(new TaskNotification($assignData));
        return response()->json(['status' => true, 'message' => 'Status Updated Successfully']);
    }

    public function createTaskByProjectId($id, $name, $notify = null){
        if($notify != null){
            $Notification = Auth::user()->Notifications->find($notify);
            if($Notification){
                $Notification->markAsRead();
            }   
        }
        // $project = Project::where('status', 1)->where('user_id', Auth()->user()->id)->where('brand_id', Auth()->user()->brand_list())->where('id', $id)->first();
        $cat_array = array();
        $project = Project::where('status', 1)->whereIn('brand_id', Auth()->user()->brand_list())->where('id', $id)->first();
        DB::table('projects')
            ->where('id', $id)
            ->where('user_id', Auth()->user()->id)
            ->limit(1)
            ->update(array('seen' => 1));
        foreach($project->tasks as $tasks){
            array_push($cat_array, $tasks->category_id);
        }
        $categorys = Category::where('status', 1)->get();
        return view('support.task.create', compact('project', 'categorys'));
    }

    public function storeTaskBySupport(Request $request){
        $request->validate([
            'project' => 'required',
            'category' => 'required',
            'description' => 'required',
            'duedate' => 'required',
        ]);
        $validate_task = Task::where('project_id', $request->project)->where('category_id', $request->category)->first();
        if($validate_task != null){
           
        }
        $get_product = Project::where('status', 1)->whereIn('brand_id', Auth()->user()->brand_list())->where('id', $request->project)->first();
        $category = $request->category;
        for($i = 0; $i < count($category); $i++){
            $request->request->add(['brand_id' => $get_product->brand->id]);
            $request->request->add(['project_id' => $request->input('project')]);
            $request->request->add(['category_id' => $category[$i]]);
            $request->request->add(['user_id' => auth()->user()->id]);
            $task = Task::create($request->all());
            if($request->hasfile('filenames'))
            {
                $i = 0;
                foreach($request->file('filenames') as $file)
                {
                    // WASABI CALL
                    $disk = Storage::disk('wasabi');
                    
                    
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $name = Auth::user()->email . '/'. $get_product->name . '- TASK/' . $file_name . '_' . $i . '_]' .time().'.'.$file->extension();
                    
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
                            $client_file->user_id = Auth()->user()->id;
                            $client_file->user_check = Auth()->user()->is_employee;
                            $client_file->production_check = 1;
                            $client_file->task_id = $task->id;
                            $client_file->save();
                            
                        } catch (AwsException $e) {
                            
                        }
                    }
                }
            }
            $subtask = new SubTask();
            $subtask->task_id = $task->id;
            $subtask->description = $task->description;
            $subtask->user_id = $task->user_id;
            $subtask->duedate = $task->duedate;
            $subtask->save();
        }
        // Sale For 0
        // Production For 1
        // Admin For 2
        $this->sendTaskNotification($task->id, 1);
        
        event(new NewTaskEvent('You have a new task assigned by: ' . Auth()->user()->name, $task->id));
        
        return redirect()->back()->with('success', 'Task created Successfully.');
    }

    public function supportTaskList(Request $request){
        $data = new Task();
        $categorys_array = [];
        $categorys = Category::all();
        foreach($categorys as $category){
            array_push($categorys_array, $category->id);
        }
        $task_array = [];
        $notification_task = Auth()->user()->unreadnotifications->where('type', 'App\Notifications\TaskNotification')->all();
        foreach($notification_task as $notification_tasks){
            array_push($task_array, $notification_tasks['data']['task_id']);
        
        }
        $brand_id = Auth()->user()->brand_list();

        if($request->id != ''){
            $data = $data->where('id', $request->id);
        }


        if($request->brand != ''){
            $brand_id = [$request->brand];
        }
        $data = $data->whereIn('brand_id', $brand_id);

        if($request->category != ''){
            $data = $data->where('category_id', $request->category);
        }

        if($request->status != ''){
            $data = $data->where('status', $request->status);
        }
        if($request->name != ''){
            $name = $request->name;
            $data = $data->whereHas('projects.client', function ($query) use ($name) {
                return $query->where('name', 'LIKE', "%{$name}%")->orWhere('last_name', 'LIKE', "%{$name}%")->orWhere('email', 'LIKE', "%{$name}%");
            });
        }

        $data = $data->whereHas('projects', function ($query) {
            return $query->where('user_id', '=', Auth()->user()->id);
        });

        $data = $data->whereNotIn('id', $task_array)->orderBy('id', 'desc')->paginate(20);
        
        $notify_data = Task::whereIn('brand_id', Auth()->user()->brand_list())->whereHas('projects', function ($query) {
                            return $query->where('user_id', '=', Auth()->user()->id);
                        })->whereIn('id', $task_array)->orderBy('id', 'desc')->get();
        $brands =  Brand::whereIn('id', Auth()->user()->brand_list())->get();
        
        $date_now = new DateTime();
        


        $expected_delivery_today = Task::whereIn('brand_id', Auth()->user()->brand_list())
            ->whereHas('projects', function ($query) {
                $query->where('user_id', '=', Auth()->user()->id);
            })->whereHas('todaySubtask')->whereIn('status', [0, 1, 4])->with('sub_tasks')->get();

        //Modified

        
        $mainquery = SubTask::select(DB::raw('*, max(duedate)'))->whereNotNull('duedate')->orderBy('duedate','desc')
                            ->groupBy('task_id')->whereHas('task',function($query){
                                    $query->where('user_id','=',Auth()->user()->id)->whereIn('brand_id', Auth()->user()->brand_list())->whereIn('status', [0, 1, 4])->whereHas('projects',  function($project_query){
                                        $project_query->where('user_id', '=', Auth()->user()->id);
                                    });
                            })->with('task');


        // $example_today = SubTask::where('duedate',date('Y-m-d'))
        //     ->whereHas('task',function($query){
        //         $query->where('user_id','=',Auth()->user()->id)->whereIn('brand_id', Auth()->user()->brand_list())->whereIn('status', [0, 1, 4])
        //         ->whereHas('projects',  function($project_query){
        //         $project_query->where('user_id', '=', Auth()->user()->id);
        //     });
        // })->with('task')->get();
        $example_today = $mainquery->whereDate('duedate',date('Y-m-d'))->get();

        
        //End Modified
            
        $expected_delivery_duedate = Task::whereIn('brand_id', Auth()->user()->brand_list())
            ->whereHas('projects', function ($q) {
                $q->where('user_id', '=', Auth()->user()->id);
            })->whereHas('sub_tasks', function($q) use ($date_now){
                $q->whereDate('duedate', '<', $date_now->format('Y-m-d'));
            })->whereIn('status', [0, 1, 4])->orderBy('id', 'desc')->get();
        //Modified
        // $example_delivery_duedate = SubTask::orderBy('duedate','desc')->where('duedate','<',date('Y-m-d'))->whereHas('task',function($query){
        //     $query->where('user_id','=',Auth()->user()->id)->whereIn('brand_id', Auth()->user()->brand_list())->whereIn('status', [0, 1, 4])->whereHas('projects',  function($project_query){
        //         $project_query->where('user_id', '=', Auth()->user()->id);
        //     });
        // })->with('task')->get();
        $example_delivery_duedate = $mainquery->whereDate('duedate','>',date('Y-m-d'))->get();
        

        $yesterday_date = $date_now->modify('+1 day');

        $expected_delivery_yesterday = Task::whereIn('brand_id', Auth()->user()->brand_list())->whereHas('projects', function ($query) {
            return $query->where('user_id', '=', Auth()->user()->id);
        })->whereHas('sub_tasks', function($query) use ($yesterday_date){
            return $query->whereNotNull('duedate')->whereDate('duedate', $yesterday_date)->orderBy('id', 'desc')->whereIn('status', [0, 1, 4]);
        })->whereIn('status', [0, 1, 4])->orderBy('id', 'desc')->get();
        //Modified
        // $example_deliverly_yesterday = SubTask::whereDate('duedate',date("Y-m-d", strtotime( '-1 days' ) ))->whereHas('task',function($query){
        //     $query->where('user_id','=',Auth()->user()->id)->whereIn('brand_id', Auth()->user()->brand_list())->whereIn('status', [0, 1, 4])->whereHas('projects',  function($project_query){
        //         $project_query->where('user_id', '=', Auth()->user()->id);
        //     });
        // })->with('task')->get(); 
        $example_deliverly_yesterday = $mainquery->whereDate('duedate',date("Y-m-d", strtotime( '-1 days' ) ))->get();

        //EndModified

        // MyData
        $myData = [
            'today_date' => $example_today,
            'yesterday_date' => $example_deliverly_yesterday,
            'due_date_sub_task' => $example_delivery_duedate
        ];
        // MyDataEndModified

        return view('support.task.index', compact('data', 'notify_data', 'brands', 'categorys', 'expected_delivery_today', 'expected_delivery_duedate', 'expected_delivery_yesterday','myData'));
    }

    public function saleTaskShow($id){
        $task = Task::where('id', $id)->whereIn('brand_id', Auth()->user()->brand_list())->first();
        // dd($task);
        
        $img_arr = [];
        
        foreach($task->client_files_support as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
            // array_push($img_arr, $presignedUrl);
        }
        
        $messages = Message::where('sender_id', $task->projects->client->id)->orWhere('user_id', $task->projects->client->id)->get();
        return view('sale.task.show', compact('task', 'messages', 'img_arr'));
    }

    public function supportTaskShow($id, $notify = null){
        if($notify != null){
            $Notification = Auth::user()->Notifications->find($notify);
            if($Notification){
                $Notification->markAsRead();
            }   
        }
        $task = Task::where('id', $id)->whereIn('brand_id', Auth()->user()->brand_list())->whereHas('projects', function ($query) {
                    return $query->where('user_id', '=', Auth()->user()->id);
                })->first();
        
        $img_arr = [];
        
        foreach($task->client_files_support as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
            // array_push($img_arr, $presignedUrl);
        }
        
        $messages = Message::where('sender_id', $task->projects->client->id)->orWhere('user_id', $task->projects->client->id)->orderBy('id', 'desc')->get();
        
        $msg_img_arr = [];
        
        foreach($messages as $message){
            if(count($message->sended_client_files) != 0){
                foreach($message->sended_client_files as $files){
                    $presignedUrl = $this->generatePresignedUrl($files->path);
                    $msg_img_arr[$files->id] = $presignedUrl;
                }
            }
        }

        $notification_task = Auth()->user()->notifications->where('type', 'App\Notifications\TaskNotification')->all();
        foreach($notification_task as $notification_tasks){
            if($id == $notification_tasks['data']['task_id']){
                $Notification = Auth::user()->Notifications->find($notification_tasks->id);
                if($Notification){
                    $Notification->markAsRead();
                }
            }
        }
        return view('support.task.show', compact('task', 'messages', 'img_arr','msg_img_arr'));
    }

    public function managerTaskShow($id){
        $task = Task::where('id', $id)->whereIn('brand_id', Auth()->user()->brand_list())->first();
        if(in_array($task->brand_id, Auth()->user()->brand_list())){
            $messages = Message::where('sender_id', $task->projects->client->id)->orWhere('user_id', $task->projects->client->id)->orderBy('id', 'desc')->get();
            
            
            $msg_img_arr = [];
        
            foreach($messages as $message){
                if(count($message->sended_client_files) != 0){
                    foreach($message->sended_client_files as $files){
                        $presignedUrl = $this->generatePresignedUrl($files->path);
                        $msg_img_arr[$files->id] = $presignedUrl;
                    }
                }
            }
            
            
             $img_arr = [];
        
            foreach($task->client_files as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
                // array_push($img_arr, $presignedUrl);
            }
            
            return view('manager.task.show', compact('task', 'messages', 'img_arr','msg_img_arr'));
        }else{
            return redirect()->back();
        }
    }

    public function managerTaskProduction(Request $request){
        $request->validate([
            'description' => 'required',
        ]);
        $request->request->add(['user_id' => auth()->user()->id]);
        $sub_task = SubTask::create($request->all());
        $cat_id = $sub_task->task->category_id;
        Task::where('id', $request->input('task_id'))->update(['status' => 1]);
        $data = SubTask::find($sub_task->id);
        $duedate = null;
        if($data->duedate != null){
            $duedate = date('d M, y', strtotime($data->duedate));
        }
        $leads = User::where('is_employee', 1)->where('status', 1)->whereHas('category', function($q) use ($cat_id){
            $q->where('category_id', $cat_id);
        })->get();
        $taskData = [
            'id' => Auth()->user()->id,
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($leads as $lead){
            $lead->notify(new TaskNotification($taskData));
        }
        $admins = User::where('is_employee', 2)->get();
        $adminTaskData = [
            'id' => Auth()->user()->id,
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($admins as $admin){
            $admin->notify(new TaskNotification($adminTaskData));
        }
        return response()->json(['success' => 'Sub Task created Successfully.', 'data' => $data, 'user_name' => auth()->user()->name, 'duedate' => $duedate, 'created_at' => $data->created_at->diffForHumans()]);
    }

    public function supportTaskStore(Request $request){
        $request->validate([
            'description' => 'required',
        ]);
        $request->request->add(['user_id' => auth()->user()->id]);
        $sub_task = SubTask::create($request->all());
        $cat_id = $sub_task->task->category_id;
        Task::where('id', $request->input('task_id'))->update(['status' => 1]);
        $data = SubTask::find($sub_task->id);
        $duedate = null;
        if($data->duedate != null){
            $duedate = date('d M, y', strtotime($data->duedate));
        }
        $leads = User::where('is_employee', 1)->where('status', 1)->whereHas('category', function($q) use ($cat_id){
            $q->where('category_id', $cat_id);
        })->get();
        $taskData = [
            'id' => Auth()->user()->id,
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($leads as $lead){
            $lead->notify(new TaskNotification($taskData));
        }
        $admins = User::where('is_employee', 2)->get();
        $adminTaskData = [
            'id' => Auth()->user()->id,
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($admins as $admin){
            $admin->notify(new TaskNotification($adminTaskData));
        }
        return response()->json(['success' => 'Sub Task created Successfully.', 'data' => $data, 'user_name' => auth()->user()->name, 'duedate' => $duedate, 'created_at' => $data->created_at->diffForHumans()]);
    }


    public function managerTaskStore(Request $request){
        $request->validate([
            'description' => 'required',
        ]);
        $request->request->add(['user_id' => auth()->user()->id]);
        $sub_task = SubTask::create($request->all());
        $cat_id = $sub_task->task->category_id;
        Task::where('id', $request->input('task_id'))->update(['status' => 1]);
        $data = SubTask::find($sub_task->id);
        $duedate = null;
        if($data->duedate != null){
            $duedate = date('d M, y', strtotime($data->duedate));
        }
        $leads = User::where('is_employee', 1)->where('status', 1)->whereHas('category', function($q) use ($cat_id){
            $q->where('category_id', $cat_id);
        })->get();
        $taskData = [
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($leads as $lead){
            $lead->notify(new TaskNotification($taskData));
        }
        $admins = User::where('is_employee', 2)->get();
        $adminTaskData = [
            'name' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'task_id' => $sub_task->task->id,
            'text' => 'Task Assigned by ' . Auth()->user()->name . ' ' . Auth()->user()->last_name . ' (' . $sub_task->task->category->name . ')',
            'details' => Str::limit(filter_var($request->description, FILTER_SANITIZE_STRING), 40 ),
        ];
        foreach($admins as $admin){
            $admin->notify(new TaskNotification($adminTaskData));
        }
        return response()->json(['success' => 'Sub Task created Successfully.', 'data' => $data, 'user_name' => auth()->user()->name . ' ' . auth()->user()->last_name , 'duedate' => $duedate, 'created_at' => $data->created_at->diffForHumans()]);
    }

    public function showFilesToClient(Request $request){
        $client_file = ClientFile::find($request->id);
        $details = [
            'title' => $client_file->task->projects->added_by->name . ' ' . $client_file->task->projects->added_by->last_name . ' has updated a file.',
            'body' => 'Please Login into your Dashboard to view it..'
        ];
        $client_file->show_to_client = $client_file->task->projects->client_id;
        $client_file->save();
        \Mail::to($client_file->task->projects->client->email)->send(new \App\Mail\ClientNotifyMail($details));
        return response()->json(['success' => true]);
    }

    public function memberTask(Request $request){

        $subtasks = ProductionMemberAssign::where('assigned_to', Auth::user()->id);

        if($request->status != null){
            $subtasks = $subtasks->whereIn('status', $request->status);
        }else{
            $subtasks = $subtasks->where('status', 0);
        }
        $subtasks = $subtasks->get();
        return view('member.task.index', compact('subtasks'));
    }

    public function showFilesToAgent(Request $request){
        $client_file = ClientFile::find($request->id);
        $subtask_id = $client_file->subtask_id;
        if($subtask_id != null){
            $data = ProductionMemberAssign::where('subtask_id', $subtask_id)->where('assigned_to', $client_file->user_id)->first();
            $data->status = 3;
            $data->save();
        }
        $client_file->production_check = 1;
        $client_file->save();
        return response()->json(['success' => true]);
    }

    public function storeoNotesBySupport(Request $request){
        $task = Task::find($request->task_id);
        $task->notes = $request->notes;
        $task->save();
        return response()->json(['success' => true, 'data' => 'Notes Updated Successfully']);
    }

    public function storeoNotesByManager(Request $request){
        $task = Task::find($request->task_id);
        $task->notes = $request->notes;
        $task->save();
        return response()->json(['success' => true, 'data' => 'Notes Updated Successfully']);
    }

    public function categoryMemberList(Request $request){
        $cat_id = $request->category_id;
        $members = User::select('id', 'name', 'email', 'last_name')->whereIn('is_employee', [5, 1])->whereHas('category', function ($query) use ($cat_id){
            return $query->where('category_id', '=', $cat_id);
        })->get();
        return response()->json(['success' => true, 'data' => $members]);
    }

    public function categoryMemberListAdd(Request $request){
        $data = TaskMemberList::where('user_id', $request->user_id)->where('task_id', $request->task_id)->first();
        if($data){
            return response()->json(['success' => false, 'data' => 'User Already Added']);
        }else{
            $task_member = new TaskMemberList();
            $task_member->user_id = $request->user_id;
            $task_member->task_id = $request->task_id;
            $task_member->save();
            $user = DB::table('users')->where('id', $request->user_id)->first();
            return response()->json(['success' => true, 'data' => $user]);
        }
    }

    public function categoryMemberListRemove(Request $request){
        TaskMemberList::where('user_id', $request->user_id)->where('task_id', $request->task_id)->delete();
        return response()->json(['success' => true]);
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
    
    public function download(Request $request)
    {
        // dd(asset('Development SEO Form - SEO.zip'));
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        $url_code = $randomString;
        
        $validate_task = Task::where('id', $request->id)->first();
        $projectName = preg_replace('/\s+/', '', $validate_task->projects->name);
        
        // Get array of selected file URLs
        $fileUrls = json_decode($request->input('files'), true);
        
        // dd($fileUrls);
        // Path to store the downloaded files
        $tempDir = public_path(Auth::user()->email . $url_code);
        $zipFilePath = public_path($projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip');
        
        // Create the temp directory if it doesn't exist
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0777, false, false);
        }
        
        
        // Download each file to the temp directory
        foreach ($fileUrls as $fileUrl) {
            // Extract filename from URL
            $fileName = pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_BASENAME);
            $filePath = $tempDir . '/' . $fileName;
            
            // Download the file
            $fileContent = file_get_contents($fileUrl);
            file_put_contents($filePath, $fileContent);
        }
        
        // Create a new zip archive
        $zip = new ZipArchive;
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            
            foreach (glob($tempDir . '/*') as $file) {
                $zip->addFile($file, basename($file));
            }
            
            // dd($zip);
            // dd(glob($tempDir . '/*'));
            
            $zip->close();
        
            // Return the public URL of the zip file
            return response()->json(['url' => asset($projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip'),'file' => $projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip', 'code' => $url_code]);
        } else {
            return response()->json(['error' => "Failed to create zip file."], 500);
        }
    }
    
    public function RemoveZipFile(Request $request){
        
        // dd($request->all());
        
        // Clean up: delete the temp directory and its contents
        File::deleteDirectory(public_path(Auth::user()->email. $request->code));
        File::delete(public_path($request->filename));
        
        
        return response()->json(['success' => "Temporary File Removed."]);
        
    }
    
    public function formDownload(Request $request)
    {
        // dd($request->all());
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        $url_code = $randomString;
        $name = "";
        
        if($request->check == 1){
            $validate_task = LogoForm::find($request->id);
            $name = $validate_task->logo_name;
        }else if($request->check == 2){
            $validate_task = WebForm::find($request->id);
            $name = $validate_task->business_name;
        }elseif($request->check == 3){
            $validate_task = SmmForm::find($request->id);
            $name = $validate_task->business_name;
        }elseif($request->check == 4){
            $validate_task = ContentWritingForm::find($request->id);
            $name = $validate_task->company_name;
        }elseif($request->check == 5){
            $validate_task = SeoForm::find($request->id);
            $name = $validate_task->company_name;
        }elseif($request->check == 6){
            $validate_task = BookFormatting::find($request->id);
            $name = $validate_task->book_title;
        }elseif($request->check == 7){
            $validate_task = BookWriting::find($request->id);
            $name = $validate_task->book_title;
        }elseif($request->check == 8){
            $validate_task = AuthorWebsite::find($request->id);
            $name = $validate_task->author_name;
        }elseif($request->check == 9){
            $validate_task = Proofreading::find($request->id);
            $name = $validate_task->clients->name;
        }elseif($request->check == 10){
            $validate_task = BookCover::find($request->id);
            $name = $validate_task->title;
        }
        
        
        $projectName = preg_replace('/\s+/', '', $name);
        
        // Get array of selected file URLs
        $fileUrls = json_decode($request->input('files'), true);
        
        
        // Path to store the downloaded files
        $tempDir = public_path(Auth::user()->email . $url_code);
        $zipFilePath = public_path($projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip');
        
        // Create the temp directory if it doesn't exist
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0777, true, true);
        }
        
        
        // Download each file to the temp directory
        foreach ($fileUrls as $fileUrl) {
            // Extract filename from URL
            $fileName = pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_BASENAME);
            $filePath = $tempDir . '/' . $fileName;
            
            // Download the file
            $fileContent = file_get_contents($fileUrl);
            // dd($fileContent);
            
            file_put_contents($filePath, $fileContent);
        }
        
        // dd(glob($tempDir . '/*'));
        // Create a new zip archive
        $zip = new ZipArchive;
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            
            foreach (glob($tempDir . '/*') as $file) {
                $zip->addFile($file, basename($file));
            }
            
            // dd($zip);
            // dd(glob($tempDir . '/*'));
            
            $zip->close();
        
            // Return the public URL of the zip file
            return response()->json(['url' => asset($projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip'),'file' => $projectName . '(' . Auth::user()->email . ')-'. $url_code . '.zip', 'code' => $url_code]);
        } else {
            return response()->json(['error' => "Failed to create zip file."], 500);
        }
    }

}
