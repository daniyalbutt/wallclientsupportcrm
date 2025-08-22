<?php

namespace App\Http\Controllers;

use \App\Models\BrandLeads;
use \App\Models\ApplyAffiliate;
use \App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Mail\AffiliateSubmissionMail;
use Illuminate\Support\Facades\Mail;

class BrandLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            // Redirect the user to the login page
            return redirect('/login')->with('message', 'Please login to access this page.');
        }
        
        if(auth()->user()->is_employee == 2){
            $leads = BrandLeads::orderBy('created_at', 'desc')->get();
            // $leads_brand = BrandLeads::distinct()->get(['brand_name']);
            
            foreach ($leads as $lead) {
                $brand = Brand::where('name', $lead->brand_name)->select('logo')->first();
                $lead->brand_logo = $brand ? $brand->logo : null;  // Add the brand_logo to each lead
            }
            // dd($leads);
            return view('admin.brand.leads', compact('leads'));
        }else {
            abort(403, 'Unauthorized User. Access Denied!');
        }
        
    }
    
    public function indexAffiliate()
    {
        if (!Auth::check()) {
            // Redirect the user to the login page
            return redirect('/login')->with('message', 'Please login to access this page.');
        }
        
        if(auth()->user()->is_employee == 2){
            $leads = ApplyAffiliate::orderBy('created_at', 'desc')->get();
            
            foreach ($leads as $lead) {
                $brand = Brand::where('name', $lead->brand_name)->select('logo')->first();
                $lead->brand_logo = $brand ? $brand->logo : null;  // Add the brand_logo to each lead
            }
            // dd($leads);
            return view('admin.brand.affiliate', compact('leads'));
        }else {
            abort(403, 'Unauthorized User. Access Denied!');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the API token is valid
        $apiToken = $request->header('X-API-TOKEN');
        if (!$apiToken || !hash_equals($apiToken, env('API_TOKEN'))) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'brand_name' => 'string',
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'service' => 'string',
            'message' => 'string',
            'url' => 'string',
            'ip_address' => 'string',
            'city' => 'string',
            'country' => 'string',
            'internet_connection' => 'string',
            'zipcode' => 'string',
            'region' => 'string'
        ]);

        // Sanitize the input data
        $validatedData = $this->sanitizeInputData($validatedData);

        $currentTime = Carbon::now()->timezone('GMT+5');

        // Format the date and time if needed
        $formattedDateTime = $currentTime->format('Y-m-d H:i:s');
        
        // Insert the data into your Laravel database
        $myModel = new \App\Models\BrandLeads;
        $myModel->fill($validatedData);
        $myModel->created_at = $formattedDateTime;
        $myModel->updated_at = $formattedDateTime;
        $myModel->save();
        
        return response()->json(['success' => true]);
    }

    public function storeAffiliateLeads(Request $request){
        $apiToken = $request->header('X-API-TOKEN');
        if (!$apiToken || !hash_equals($apiToken, env('API_TOKEN'))) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'brand_name' => 'string',
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'audience' => 'string',
            'web_link' => 'string',
            'referring' => 'string',
            'referring_other' => 'string',
            'audience_benefit' => 'string',
            'audience_promote' => 'string',
            'need_help' => 'string',
            'url' => 'string',
            'ip_address' => 'string',
            'city' => 'string',
            'country' => 'string',
            'internet_connection' => 'string',
            'zipcode' => 'string',
            'region' => 'string'
        ]);

        // Sanitize the input data
        $validatedData = $this->sanitizeInputData($validatedData);

        $currentTime = Carbon::now()->timezone('GMT+5');

        // Format the date and time if needed
        $formattedDateTime = $currentTime->format('Y-m-d H:i:s');
        
        // Insert the data into your Laravel database
        $myModel = new \App\Models\ApplyAffiliate;
        $myModel->fill($validatedData);
        $myModel->created_at = $formattedDateTime;
        $myModel->updated_at = $formattedDateTime;
        $myModel->save();
        
        Mail::to('kashan.azam@coredigitals.biz')->send(new AffiliateSubmissionMail($validatedData));
        
        return response()->json(['success' => true]);
    }

    private function sanitizeInputData($data)
    {
        // Remove any HTML or PHP tags from the input data
        foreach ($data as $key => $value) {
            $data[$key] = strip_tags($value);
        }

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = BrandLeads::find($id);
        // $decodeId = base64_decode($id);
        // $data = BrandLeads::find($decodeId);
        
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

