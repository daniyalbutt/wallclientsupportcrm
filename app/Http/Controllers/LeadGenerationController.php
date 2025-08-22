<?php

namespace App\Http\Controllers;

use App\Models\LeadGeneration;
use App\Models\Brand;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\LeadAssignMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Auth;
use App\Events\LeadAssigned;
use App\Events\LeadAssignManager;
use App\Events\LeadStatusAgent;
use App\Events\LeadStatusAdmin;
use App\Models\LeadGenerationLog;

use App\Models\Invoice;

class LeadGenerationController extends Controller
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
    
    public function index()
    {
        if(Auth::user()->is_employee == 0){
            $leads = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('sale_agent', Auth::user()->id)->orderBy('created_at','DESC')->get();
            
            $pending = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Pending')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $contacted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Contacted')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $follow_up = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Follow-Up')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $re_engagged = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Re-Engaged')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $interested = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Interested')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $proposal_sent = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Proposal Sent')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $lost = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Lost')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $converted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Converted')->where('sale_agent', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            
            return view('leads.index', compact('leads', 'pending', 'contacted', 'follow_up', 're_engagged', 'interested', 'proposal_sent', 'lost','converted'));
        }
        if(Auth::user()->is_employee == 6){
            $leads = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->get();
            
            $pending = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Pending')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $contacted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Contacted')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $follow_up = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Follow-Up')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $re_engagged = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Re-Engaged')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $interested = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Interested')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $proposal_sent = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Proposal Sent')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $lost = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Lost')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            $converted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Converted')->where('user_id', Auth::user()->id)->orderBy('updated_at','DESC')->get();
            
            return view('leads.index', compact('leads', 'pending', 'contacted', 'follow_up', 're_engagged', 'interested', 'proposal_sent', 'lost','converted'));   
        }else{
            $leads = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->orderBy('created_at','DESC')->get();
            $pending = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Pending')->orderBy('updated_at','DESC')->get();
            $contacted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Contacted')->orderBy('updated_at','DESC')->get();
            $follow_up = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Follow-Up')->orderBy('updated_at','DESC')->get();
            $re_engagged = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Re-Engaged')->orderBy('updated_at','DESC')->get();
            $interested = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Interested')->orderBy('updated_at','DESC')->get();
            $proposal_sent = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Proposal Sent')->orderBy('updated_at','DESC')->get();
            $lost = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Lost')->orderBy('updated_at','DESC')->get();
            $converted = LeadGeneration::with('user', 'brand', 'sale', 'assignedBy')->where('status','=','Converted')->orderBy('updated_at','DESC')->get();
            
            return view('leads.index', compact('leads', 'pending', 'contacted', 'follow_up', 're_engagged', 'interested', 'proposal_sent', 'lost','converted'));   
        }
    }

    public function create()
    {
        $brands = Brand::all();
        $services = Service::all();
        $users = User::where('is_employee',6)->orWhere('is_employee',0)->get();
        return view('leads.create',compact('brands','services','users'));
    }

    public function store(Request $request)
    {
        $get_user = User::findOrFail($request->user_id);
        
        if ($get_user->is_employee == 0) {
            $request->merge(['sale_agent' => $request->user_id]);
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:lead_generations,email',
                'phone' => 'required|string|max:20',
                'medium' => 'required|string',
                'service' => 'required|array',
                'brand_id' => 'required|exists:brands,id',
                'sale_agent' => 'required|exists:users,id',
            ];
        } elseif ($get_user->is_employee == 6) {
            $request->merge(['user_id' => $request->user_id]);
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:lead_generations,email',
                'phone' => 'required|string|max:20',
                'medium' => 'required|string',
                'service' => 'required|array',
                'brand_id' => 'required|exists:brands,id',
                'user_id' => 'required|exists:users,id',
            ];
        }
        $validated = $request->validate($rules);

        $validated['service'] = json_encode($validated['service']);

        $notes = [];
        
        $notes[Auth::user()->id] = [
            'note' => $request->notes,
            'date' => now()->toDateTimeString(),
        ];

        $leadGen = LeadGeneration::create($validated);
        
        $leadGen->status = "Pending";
        $leadGen->notes = json_encode($notes);
        $leadGen->assigned_by = Auth::user()->id;
        $leadGen->assigned_on = Carbon::now();
        $leadGen->save();
        
        $log = new LeadGenerationLog();
        $log->user_id = Auth::user()->id;
        $log->lead_data_new = json_encode($leadGen);
        $log->action_applied = "Created New Lead";
        $log->save();
        
        if ($get_user->is_employee == 6) {
            event(new LeadAssignManager($leadGen));
        }
        if ($get_user->is_employee == 0) {
            event(new LeadAssigned($leadGen));
        }

        return redirect('/admin/leads')->with('success', 'Lead Assigned Successfully.');
    }
    
    public function getUserBrands(Request $request)
    {
        $brand_id = $request->brand_id;
        $users = User::select('id', 'name', 'last_name','is_employee')->whereHas('brands', function ($query) use ($brand_id) {
                    return $query->where('brand_id', $brand_id);
                })->get();
        
        return response()->json(['success' => true , 'data' => $users ]);
    }

    public function edit($id)
    {
        $lead = LeadGeneration::findOrFail($id);
        $brands = Brand::all();
        $services = Service::all();
        $brand_id = $lead->brand_id;
        $users = User::select('id', 'name', 'last_name','is_employee')->whereHas('brands', function ($query) use ($brand_id) {
                    return $query->where('brand_id', $brand_id);
                })->get();
        return view('leads.edit', compact('lead', 'brands','users','services'));
    }

    public function update(Request $request, $id)
    {
        $get_user = User::findOrFail($request->user_id);
        
        if ($get_user->is_employee == 0) {
            $request->merge(['sale_agent' => $request->user_id]);
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:lead_generations,email,' . $id,
                'phone' => 'required|string|max:20',
                'medium' => 'required|string',
                'service' => 'required|array',
                'brand_id' => 'required|exists:brands,id',
                'sale_agent' => 'required|exists:users,id',
            ];
        } elseif ($get_user->is_employee == 6) {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:lead_generations,email,' . $id,
                'phone' => 'required|string|max:20',
                'medium' => 'required|string',
                'service' => 'required|array',
                'brand_id' => 'required|exists:brands,id',
                'user_id' => 'required|exists:users,id',
            ];
        } else {
            return redirect()->back()->withErrors(['user_id' => 'Invalid user type.']);
        }
    
        $validated = $request->validate($rules);
    
        $lead = LeadGeneration::findOrFail($id);
        
        // Lead Log Old
        $log = new LeadGenerationLog();
        $log->user_id = Auth::user()->id;
        $log->lead_data_old = json_encode($lead);
        $log->action_applied = "Edit/Update Lead";
        $log->save();
        
        $lead->name = $request->name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->service = json_encode($request->service);
        $lead->brand_id = $request->brand_id;
    
        if ($get_user->is_employee == 0) {
            $lead->sale_agent = $request->sale_agent;
        } elseif ($get_user->is_employee == 6) {
            $lead->user_id = $request->user_id;
        }
        
        // Lead Log New
        $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
        $updateLogNewData->lead_data_new = json_encode($lead);
        $updateLogNewData->save();
    
        $lead->save();
    
        return redirect('/admin/leads')->with('success', 'Lead updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        // dd($request->all());
        
        $lead = LeadGeneration::findOrFail($id);
        
        // Lead Log Old
        $log = new LeadGenerationLog();
        $log->user_id = Auth::user()->id;
        $log->lead_data_old = json_encode($lead);
        $log->action_applied = "Lead Status Update";
        $log->save();
        
        $lead->status = $request->status;
        $lead->save();
        
        // Lead Log New
        $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
        $updateLogNewData->lead_data_new = json_encode($lead);
        $updateLogNewData->save();
        
        if(Auth::user()->is_employee == 0){
            event(new LeadStatusAgent($lead));
        }
        if(Auth::user()->is_employee == 6){
            event(new LeadStatusAdmin($lead));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Lead Status Updated!'
        ]);
    }
    
    public function getBrandUsers($brand_id)
    {
        $users = User::where('brand_id', $brand_id)->get(['id', 'name']);
        return response()->json($users);
    }


    public function destroy($id)
    {
        
        $lead = LeadGeneration::findOrFail($id);
        // Lead Log Old
        $log = new LeadGenerationLog();
        $log->user_id = Auth::user()->id;
        $log->lead_data_old = json_encode($lead);
        $log->action_applied = "Lead Deleted";
        $log->save();
        
        $lead->delete();

        return redirect('/admin/leads')->with('success', 'Lead deleted successfully.');
    }
    
    public function get_agent(Request $request){
        $brand_id = $request->brand_id;
        $users = User::select('id', 'name', 'last_name')->where('is_employee', 0)->whereHas('brands', function ($query) use ($brand_id) {
                    return $query->where('brand_id', $brand_id);
                })->get();
                
        // dd($users);
        
        return response()->json(['success' => true , 'data' => $users ]);
        
    }
    
    public function get_assign_user(Request $request){
        $brand_id = $request->brand_id;
        $users = User::select('id', 'name', 'last_name','is_employee')->whereHas('brands', function ($query) use ($brand_id) {
                    return $query->where('brand_id', $brand_id);
                })->get();
        
        return response()->json(['success' => true , 'data' => $users ]);
        
    }
    
    
    public function assign_lead(Request $request){
        if(Auth::user()->is_employee == 6){
            $lead_id = $request->lead_id;
            $sale_agent = $request->sale_agent;
            
            $agent = User::findOrFail($sale_agent);
            
            $lead = LeadGeneration::findOrFail($lead_id);
            
            // Lead Log Old
            $log = new LeadGenerationLog();
            $log->user_id = Auth::user()->id;
            $log->lead_data_old = json_encode($lead);
            $log->action_applied = "Lead Assign Manager to Agent";
            $log->save();
        
            $lead->sale_agent = $sale_agent;
            $lead->assigned_on = Carbon::now();
            $lead->save();
            
            // Lead Log New
            $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
            $updateLogNewData->lead_data_new = json_encode($lead);
            $updateLogNewData->save();
            
            $brand = Brand::findOrFail($lead->brand_id);
            $manager = User::findOrFail($lead->user_id);
            
            $message = 'Lead Assigned To '. $agent->name . ' ' . $agent->last_name .' successfully.';
            
            $data = [
                'brand_name' => $brand->name,
                'assigned_by' => $manager->name . ' ' . $manager->last_name,
                'assigned_to' => $agent->name . ' ' . $agent->last_name,
                'assigned_on' => $lead->updated_at->format('Y-m-d H:i:s')
            ];
            
            // Mail::to('muzammil.coredigitals@gmail.com')->send(new LeadAssignMail($data));
            
            event(new LeadAssigned($lead));
            
            return redirect('/leads')->with('success', $message);
        }
        
        if(Auth::user()->is_employee == 2){
            $get_user = User::findOrFail($request->sale_agent);
            
            if($get_user->is_employee == 0){
                $lead_id = $request->lead_id;
                $sale_agent = $request->sale_agent;
                
                $assigned_to = User::findOrFail($sale_agent);
                
                $lead = LeadGeneration::findOrFail($lead_id);
                
                // Lead Log Old
                $log = new LeadGenerationLog();
                $log->user_id = Auth::user()->id;
                $log->lead_data_old = json_encode($lead);
                $log->action_applied = "Lead Assign";
                $log->save();
                
                $lead->sale_agent = $sale_agent;
                $lead->assigned_on = Carbon::now();
                $lead->save();
                
                // Lead Log New
                $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
                $updateLogNewData->lead_data_new = json_encode($lead);
                $updateLogNewData->save();
                
                $brand = Brand::findOrFail($lead->brand_id);
                $assigned_by = User::findOrFail($lead->assigned_by);
                
                $message = 'Lead Assigned To '. $assigned_to->name . ' ' . $assigned_to->last_name .' successfully.';
                
                $data = [
                    'brand_name' => $brand->name,
                    'assigned_by' => $assigned_by->name . ' ' . $assigned_by->last_name,
                    'assigned_to' => $assigned_to->name . ' ' . $assigned_to->last_name,
                    'assigned_on' => $lead->updated_at->format('Y-m-d H:i:s')
                ];
                
                // Mail::to('muzammil.coredigitals@gmail.com')->send(new LeadAssignMail($data));
                
                event(new LeadAssigned($lead)); // Sale Agent Notification Event
                
                return redirect('/admin/leads')->with('success', $message);
                
            }elseif($get_user->is_employee == 6){
                $userId = $request->sale_agent; // set id to user_id (manager)
                
                $lead_id = $request->lead_id;
                $user_id = $userId;
                
                $assigned_to = User::findOrFail($user_id);
                
                $lead = LeadGeneration::findOrFail($lead_id);
               
                // Lead Log Old
                $log = new LeadGenerationLog();
                $log->user_id = Auth::user()->id;
                $log->lead_data_old = json_encode($lead);
                $log->action_applied = "Lead Assign";
                $log->save();
                
                $lead->user_id = $user_id;
                $lead->assigned_on = Carbon::now();
                $lead->save();
                
                // Lead Log New
                $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
                $updateLogNewData->lead_data_new = json_encode($lead);
                $updateLogNewData->save();
        
                $brand = Brand::findOrFail($lead->brand_id);
                $assigned_by = User::findOrFail($lead->assigned_by);
                
                $message = 'Lead Assigned To '. $assigned_to->name . ' ' . $assigned_to->last_name .' successfully.';
                
                $data = [
                    'brand_name' => $brand->name,
                    'assigned_by' => $assigned_by->name . ' ' . $assigned_by->last_name,
                    'assigned_to' => $assigned_to->name . ' ' . $assigned_to->last_name,
                    'assigned_on' => $lead->updated_at->format('Y-m-d H:i:s')
                ];
                
                // Mail::to('muzammil.coredigitals@gmail.com')->send(new LeadAssignMail($data));
                
                event(new LeadAssignManager($lead)); // Sale Manager Notification Event
                
                return redirect('/admin/leads')->with('success', $message);
            }
            
        }
    }
    
    public function removeAgentManager(Request $request){
        $lead = LeadGeneration::findOrFail($request->lead_id);
        $user = User::findOrFail($request->user_id);
        
        if($user->is_employee == 6){
            
            // Lead Log Old
            $log = new LeadGenerationLog();
            $log->user_id = Auth::user()->id;
            $log->lead_data_old = json_encode($lead);
            $log->action_applied = "Lead Manager Removal";
            $log->save();
            
            $lead->user_id = null;
            $lead->save();
            
            // Lead Log New
            $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
            $updateLogNewData->lead_data_new = json_encode($lead);
            $updateLogNewData->save();
            
            $message = 'Lead Sale Manager: '. $user->name . ' ' . $user->last_name .' access removed successfully.';
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        if($user->is_employee == 0){
            
            // Lead Log Old
            $log = new LeadGenerationLog();
            
            $log->user_id = Auth::user()->id;
            $log->lead_data_old = json_encode($lead);
            $log->action_applied = "Lead Agent Removal";
            $log->save();
            
            $lead->sale_agent = null;
            $lead->save();
            
            // Lead Log New
            $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
            $updateLogNewData->lead_data_new = json_encode($lead);
            $updateLogNewData->save();
        
            $message = 'Lead Sale Agent: '. $user->name . ' ' . $user->last_name .' access removed successfully.';
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
    }
    
    public function enableManagerAssign(Request $request){
        $lead = LeadGeneration::findOrFail($request->lead_id);
        $user = User::findOrFail($request->user_id);
        
        if($lead->manager_assign == 0){
            // Lead Log Old
            $log = new LeadGenerationLog();
            $log->user_id = Auth::user()->id;
            $log->lead_data_old = json_encode($lead);
            $log->action_applied = "Lead Manager Assign Access Enabled";
            $log->save();
        
            $lead->manager_assign = 1;
            $lead->save();
            
            // Lead Log New
            $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
            $updateLogNewData->lead_data_new = json_encode($lead);
            $updateLogNewData->save();
            
        }else{
            // Lead Log Old
            $log = new LeadGenerationLog();
            $log->user_id = Auth::user()->id;
            $log->lead_data_old = json_encode($lead);
            $log->action_applied = "Lead Manager Assign Access Removed";
            $log->save();
        
            $lead->manager_assign = 0;
            $lead->save();
            
            // Lead Log New
            $updateLogNewData = LeadGenerationLog::findOrFail($log->id);
            $updateLogNewData->lead_data_new = json_encode($lead);
            $updateLogNewData->save();
        }
        
        $message = 'Lead Assign Enabled For Manager: '. $user->name . ' ' . $user->last_name;
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    public function updateNotes(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'notes' => 'required|string',
        ]);
    
        $lead = LeadGeneration::findOrFail($id);
    
        $notes = json_decode($lead->notes, true);
    
        if (is_null($notes)) {
            $notes = [];
        }
    
        $notes[Auth::user()->id] = [
            'note' => $request->notes,
            'date' => now()->toDateTimeString(),
        ];
    
        $lead->notes = json_encode($notes);
    
        $lead->save();
    
        return redirect()->back()->with('success', 'Note updated successfully.');
    }
    
    public function dataViewHistory(Request $request){
        $lead = LeadGeneration::findOrFail($request->id);
        
        // Lead Log Old
        $log = new LeadGenerationLog();
        $log->user_id = Auth::user()->id;
        $log->lead_data_old = json_encode($lead);
        $log->action_applied = "Lead Viewed";
        $log->save();
    }
    
    public function LeadLogs(){
        if(Auth::user()->is_employee == 2){
            $logs = LeadGenerationLog::with('user')->get();
            $users = User::where('is_employee', 0)->orWhere('is_employee', 6)->get();
            
            return view('leads.logs', compact('logs','users'));
        }else{
            abort(403, 'Unauthorized access.');
        }
    }
}
