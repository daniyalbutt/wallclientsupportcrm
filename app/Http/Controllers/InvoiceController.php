<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\User;
use App\Models\Brand;
use App\Models\NoForm;
use App\Models\Merchant;
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
use App\Models\Currency;
use App\Models\Service;
use Illuminate\Http\Request;
use Auth;
use Stripe;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\PaymentNotification;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Carbon\Carbon;
use App\Events\NotificationEvent;
// PDF LIBRARY
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoiceMail;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = Client::find($id);
        $brand = Brand::all();
        $services = Service::all();
        $currencies =  Currency::all();
        return view('admin.invoice.create', compact('user', 'brand', 'currencies', 'services'));
    }

    public function invoiceAll(Request $request){
        $data = new Invoice();
        $brands = Brand::all();
        $data = $data->orderBy('id', 'desc');
        if($request->package != ''){
            $data = $data->where('custom_package', 'LIKE', "%$request->package%");
        }
        if($request->invoice != ''){
            $data = $data->where('invoice_number', 'LIKE', "%$request->invoice%");
        }
        if($request->customer != ''){
            $customer = $request->customer;
            $data = $data->whereHas(
                'client', function($q) use($customer){
                    $q->where('name', 'LIKE', "%$customer%");
                    $q->orWhere('last_name', 'LIKE', "%$customer%");
                }
            );
        }
        if($request->customer_email != ''){
            $customer_email = $request->customer_email;
            $data = $data->whereHas(
                'client', function($q) use($customer_email){
                    $q->where('email', 'LIKE', "%$customer_email%");
                }
            );
        }
        if($request->agent != ''){
            $agent = $request->agent;
            $data = $data->whereHas(
                'sale', function($q) use($agent){
                    $q->where('name', 'LIKE', "%$agent%");
                    $q->orWhere('last_name', 'LIKE', "%$agent%");
                }
            );
        }
        if($request->status != 0){
            $data = $data->where('payment_status', $request->status);
        }
        if($request->brand != 0){
            $brand = $request->brand;
            $data = $data->whereHas('brands', function($q) use($brand){
                        $q->where('id', $brand);
                    });
        }
        $data = $data->paginate(10);
        $display = '';
        if ($request->ajax()) {
            foreach ($data as $rander) {
                $form = '';
                if($rander->payment_status == 1){
                    $form = '<form method="post" action="'.route('admin.invoice.paid', $rander->id).'">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="submit" class="mark-paid btn btn-glow btn-danger p-0 ml-1" style="font-size: 12px;padding: 0 5px 0 5px !important;">Mark Payed</button>
                    </form>';
                }
                $display .= 
                '<tr>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="btn btn-sm btn-dark">#'.$rander->invoice_number.'</span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                              '. $rander->client->name . '<br>' . $rander->client->last_name .'
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                              '. $rander->client->email .'
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                '. ($rander->sales_agent_id != 0 ? $rander->sale->name . ' ' . $rander->sale->last_name : 'From Website') .'
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                            <button class="btn btn-sm btn-secondary">'. $rander->brands->name .'</button>
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                '. $rander->currency_show->sign .''. $rander->amount.'
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                '. $rander->merchant->name .'
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                <span class="btn btn-'.\App\Models\Invoice::STATUS_COLOR[$rander->payment_status].' btn-sm" style="display: flex;justify-content: center;font-size: 0.813rem;">
                                    '.\App\Models\Invoice::PAYMENT_STATUS[$rander->payment_status].
                                    $form.'
                                </span>
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex  py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                <button class="btn btn-sm btn-secondary mb-1">' . date('g:i a', strtotime($rander->created_at)) . '</button>
                                <button class="btn btn-sm btn-secondary">' . date('d M, Y', strtotime($rander->created_at)) . '</button>
                          </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex py-1" style="justify-content: center;text-align: center;">
                          <div class="d-flex flex-column justify-content-center">
                                <div>
                                    <a href="javascript:;" class="mb-2 btn btn-glow btn-primary btn-icon btn-sm mr-1 more-detail" data-id="'.$rander->id.'">
                                        <span class="ul-btn__icon"><i class="i-Memory-Card-2"></i></span>
                                        <span class="ul-btn__text">Details</span>    
                                        <span class="badge badge-danger" id="flashing">New</span>
                                    </a>
                                    <a href="'.route('admin.link', $rander->id).'" class="btn btn-glow btn-info btn-icon btn-sm">
                                        <span class="ul-btn__icon"><i class="i-Eye-Visible"></i></span>
                                        <span class="ul-btn__text">View</span>
                                    </a>
                                </div>
                          </div>
                        </div>
                    </td>
                </tr>';
            }
            return $display;
        }
        return view('admin.invoice.index', compact('data', 'brands'));
    }

    public function getInvoice(){

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
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'brand' => 'required',
            'service' => 'required',
            'package' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'payment_type' => 'required'
        ]);


        $latest = Invoice::latest()->first();
        $currentDate = date('d-m-Y');

        $formattedDate = str_replace('-', '', $currentDate);
        
        if (! $latest) {
            $nextInvoiceNumber = $formattedDate . '-1';
        } else {
            $expNum = explode('-', $latest->invoice_number);
            $expIncrement = (int)end($expNum) + 1;
            $nextInvoiceNumber = $formattedDate . '-' . $expIncrement;
        }
        
		$invoice = new Invoice;
        $invoice->name = $request->name;
        $invoice->email = $request->email;
        $contact = $request->contact;
        if($contact == null){
            $contact = '#';
        }
        $invoice->contact = $contact;
        $invoice->brand = $request->brand;
        $invoice->package = $request->package;
        $invoice->currency = $request->currency;
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $nextInvoiceNumber;
        $invoice->sales_agent_id = Auth()->user()->id;
        $invoice->discription = $request->discription;
        $invoice->amount = $request->amount;
        $invoice->payment_status = '1';
        $invoice->custom_package = $request->custom_package;
        $invoice->payment_type = $request->payment_type;
		$service = implode(",",$request->service);
		$invoice->service = $service;
        $invoice->save();					
		$id = $invoice->id;
		return redirect()->route('admin.link',($invoice->id));
    }

    public function linkPage($id){
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        return view('admin.invoice.link-page', compact('_getInvoiceData', 'id', '_getInvoiceData', '_getBrand'));
    }

    public function linkPageSale($id){
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        return view('sale.invoice.link-page', compact('_getInvoiceData', 'id', '_getInvoiceData', '_getBrand'));
    }

    public function linkPageManager($id){
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        return view('manager.invoice.link-page', compact('_getInvoiceData', 'id', '_getInvoiceData', '_getBrand'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function payNow($id){
		$id = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($id);
        $_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        $_getMerchant = Merchant::where('id',$_getInvoiceData->merchant_id)->first();
        return view('invoice.paynow', compact('_getInvoiceData','_getBrand','_getMerchant'));
    }

    public function paymentProcess(Request $request)
    {
        $invoiceId = $request->invoice_id;
	    $invoiceData = Invoice::findOrFail($invoiceId);
        $merchant_name;
        if($invoiceData->merchant == null){
            $merchant = DB::table('merchants')->where('secret_key', env('STRIPE_SECRET'))->first();
            $merchant_name = $merchant->name;
        }else{
            $merchant_name = $invoiceData->merchant->name;
        }
        $temp = explode(' ', $invoiceData->brands->name);
        $result = '';
        foreach($temp as $t){
            $result .= $t[0];
        }
        
	    $customerName = $request->user_name;
        $customerEmail = $request->user_email;
        $customerPhone = $request->user_phone;
        $customerAddress = $request->address;
        $ServiceAmount = $invoiceData->amount;
        $token = $request->stripeToken;
        $packageName = $invoiceData->package;
        if($invoiceData->package == 0){
            $packageName = $invoiceData->custom_package;
        }
        $merchant = 1;
        if($invoiceData->merchant_id == null){
            $merchant = 1;
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        }else{
            if($invoiceData->merchant->is_authorized == 2){
                $merchant = 2;
            }else if($invoiceData->merchant->is_authorized == 3){
                $merchant = 3;
            }else if($invoiceData->merchant->is_authorized == 4){
                $merchant = 4;
            }else{
                $merchant = 1;
                Stripe\Stripe::setApiKey($invoiceData->merchant->secret_key);
            }
        }

        if($merchant == 1){
        
            try{
                $cust_id = \Stripe\Customer::create(array(
                    "name" => $customerName,
                    "email" => $customerEmail,
                    "phone" => $customerPhone,
                    "source" =>$token,
                    "address" => [
                        "line1" => $request->address,
                        "postal_code" => $request->zip,
                        "city" => $request->city,
                        "state" => $request->set_state,
                        "country" => $request->country,
                    ],
                ));

                $customer = $cust_id->id;
                DB::table('clients')->where('id', $invoiceData->client->id)->update(['stripe_token'=>$customer]);

                $service_name = '';
                $service_array = explode(',', $invoiceData->service);
                for($i = 0; $i < count($service_array); $i++){
                    $service = Service::find($service_array[$i]);
                    $service_name .= $service->name;
                    if(($i + 1) == count($service_array)){
                        
                    }else{
                        $service_name .=  ', ';
                    }
                }
                $transaction_id = '';

                /* Creating Customer In Stripe */
                if($invoiceData->payment_type == 0 ){
                    $mainAmount =  $ServiceAmount * 100 ;
                    $charge =  \Stripe\Charge::create(array(
                        "amount" => $mainAmount,
                        "currency" => $invoiceData->currency_show->short_name,
                        "customer" => $customer,
                        "receipt_email" => $request->user_email,
                        "description" => $result . ' - ' . 'Invoice ID: #' . $invoiceData->invoice_number,
                        "statement_descriptor" => $result,
                        "shipping" => [
                            "name" => $customerName,
                            "address" => [
                                "line1" => $request->address,
                                "postal_code" => $request->zip,
                                "city" => $request->city,
                                "state" => $request->set_state,
                                "country" => $request->country,
                            ],
                        ]
                        // "description" => $result . ' ' . $merchant_name . ' - ' . $service_name . ' ( ' . $invoiceData->discription . ' )',
                    ));
                    $transaction_id = $charge->id;
                }else{
                    $mainAmount =  $ServiceAmount - 5 ;
                    $paymnetOne = $mainAmount * 100;
                        $charge =  \Stripe\Charge::create(array(
                            "amount" => $paymnetOne,
                            "currency" => $invoiceData->currency_show->short_name,
                            "customer" => $customer,
                            "receipt_email" => $request->user_email,
                            "description" => $result . ' - ' . 'Invoice ID: #' . $invoiceData->invoice_number,
                            "statement_descriptor" => $result,
                            "shipping" => [
                                "name" => $customerName,
                                "address" => [
                                    "line1" => $request->address,
                                    "postal_code" => $request->zip,
                                    "city" => $request->city,
                                    "state" => $request->set_state,
                                    "country" => $request->country,
                                ],
                            ]
                            // "description" => $result . ' ' . $merchant_name . ' - ' . $service_name . ' ( ' . $invoiceData->discription . ' )',
                        ));
                    
                        $devCharge =  \Stripe\Charge::create(array(
                            "amount" => '250',
                            "currency" => $invoiceData->currency_show->short_name,
                            "customer" => $customer,
                            "receipt_email" => $request->user_email,
                            "description" => $result . ' - ' . 'Invoice ID: #' . $invoiceData->invoice_number,
                            "statement_descriptor" => $result,
                            "shipping" => [
                                "name" => $customerName,
                                "address" => [
                                    "line1" => $request->address,
                                    "postal_code" => $request->zip,
                                    "city" => $request->city,
                                    "state" => $request->set_state,
                                    "country" => $request->country,
                                ],
                            ]
                            // "description" => $result . ' ' . $merchant_name . ' - ' . $service_name . ' ( ' . $invoiceData->discription . ' )',
                        ));
                    
                        $devCharge =  \Stripe\Charge::create(array(
                            "amount" => '250',
                            "currency" => $invoiceData->currency_show->short_name,
                            "customer" => $customer,
                            "receipt_email" => $request->user_email,
                            "description" => $result . ' - ' . 'Invoice ID: #' . $invoiceData->invoice_number,
                            "statement_descriptor" => $result,
                            "shipping" => [
                                "name" => $customerName,
                                "address" => [
                                    "line1" => $request->address,
                                    "postal_code" => $request->zip,
                                    "city" => $request->city,
                                    "state" => $request->set_state,
                                    "country" => $request->country,
                                ],
                            ]
                            // "description" => $result . ' ' . $merchant_name . ' - ' . $service_name . ' ( ' . $invoiceData->discription . ' )',
                        ));
                    $transaction_id = $charge->id;
                }
                 $is_error = 0;
            }catch(Stripe\Exception\CardException $e){
                $error = $e->getError();
                $error_message = $e->getError()->message;
                $is_error = 1;
            }catch (\Stripe\Exception\RateLimitException $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Too many requests made to the API too quickly
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Exception\AuthenticationException $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Network communication with Stripe failed
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                $error = $e->getError();
                $error_message = $e->getMessage();
                $is_error = 1;
                // Something else happened, completely unrelated to Stripe
            }

            if($is_error == 1){
                
                $declineCode = $error->code;
            
                if ($declineCode === 'card_declined') {
                    $insufficientFundsMessage = "Payment declined. Reason: $error_message";
                } elseif ($declineCode === 'insufficient_funds') {
                    $insufficientFundsMessage = "Insufficient funds. Reason: $error_message";
                }
                
                $invoice = array();
                $invoice['request'] = $request;
                $get_invoice_err = Invoice::findOrFail($request->invoice_id);
                
                $messageData = [
                    'id' => $get_invoice_err->id,
                    'name' => $customerName ,
                    'email' => $customerEmail,
                    'text' => 'Invoice Number #' . $get_invoice_err->id . isset($insufficientFundsMessage) ? $insufficientFundsMessage : 'Payment Declined' . $customerName . ' - ' . $customerEmail,
                    'details' => '',
                    'url' => '',
                ];
                if(($get_invoice_err->sale->is_employee != 2) && ($get_invoice_err->sale->is_employee != 6)){
                    $get_invoice_err->sale->notify(new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                // Message Notification sending to Admin & Managers
                $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice_err) {
                                return $query->where('brand_id', $get_invoice_err->brand);
                            })->get();

                foreach($managers as $manager){
                    Notification::send($manager, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }

                $adminusers = User::where('is_employee', 2)->get();
                foreach($adminusers as $adminuser){
                    Notification::send($adminuser, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                
                return redirect()->back()->with('stripe_error', $error_message);
            }

            if($charge->status == "succeeded"){			
                $invoice = array();
                $invoice['request'] = $request;
                $get_invoice = Invoice::findOrFail($request->invoice_id);
                if($get_invoice){
                    $get_invoice->transaction_id = $transaction_id;
                    $get_invoice->payment_status = '2';
                    $get_invoice->invoice_date = Carbon::today()->toDateTimeString();
                    $get_invoice->save();
                }
                $user = Client::where('email', $get_invoice->client->email)->first();
                $user_client = User::where('email', $get_invoice->client->email)->first();
                if($user_client != null){
                    $service_array = explode(',', $get_invoice->service);
                    for($i = 0; $i < count($service_array); $i++){
                        $service = Service::find($service_array[$i]);
                        if($service->form == 0){
                            //No Form
                            if($get_invoice->createform == 1){
                                $no_form = new NoForm();
                                $no_form->name = $get_invoice->custom_package;
                                $no_form->invoice_id = $get_invoice->id;

                                if($user_client != null){
                                    $no_form->user_id = $user_client->id;
                                }
                                $no_form->client_id = $user->id;
                                $no_form->agent_id = $get_invoice->sales_agent_id;
                                $no_form->save();
                            }
                        }elseif($service->form == 1){
                            // Logo Form
                            if($get_invoice->createform == 1){
                                $logo_form = new LogoForm();
                                $logo_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $logo_form->user_id = $user_client->id;
                                }
                                $logo_form->client_id = $user->id;
                                $logo_form->agent_id = $get_invoice->sales_agent_id;
                                $logo_form->save();
                            }
                        }elseif($service->form == 2){
                            // Website Form
                            if($get_invoice->createform == 1){
                                $web_form = new WebForm();
                                $web_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $web_form->user_id = $user_client->id;
                                }
                                $web_form->client_id = $user->id;
                                $web_form->agent_id = $get_invoice->sales_agent_id;
                                $web_form->save();
                            }
                        }elseif($service->form == 3){
                            // Smm Form
                            if($get_invoice->createform == 1){
                                $smm_form = new SmmForm();
                                $smm_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $smm_form->user_id = $user_client->id;
                                }
                                $smm_form->client_id = $user->id;
                                $smm_form->agent_id = $get_invoice->sales_agent_id;
                                $smm_form->save();
                            }
                        }elseif($service->form == 4){
                            // Content Writing Form
                            if($get_invoice->createform == 1){
                                $content_writing_form = new ContentWritingForm();
                                $content_writing_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $content_writing_form->user_id = $user_client->id;
                                }
                                $content_writing_form->client_id = $user->id;
                                $content_writing_form->agent_id = $get_invoice->sales_agent_id;
                                $content_writing_form->save();
                            }
                        }elseif($service->form == 5){
                            // Search Engine Optimization Form
                            if($get_invoice->createform == 1){
                                $seo_form = new SeoForm();
                                $seo_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $seo_form->user_id = $user_client->id;
                                }
                                $seo_form->client_id = $user->id;
                                $seo_form->agent_id = $get_invoice->sales_agent_id;
                                $seo_form->save();
                            }
                        }
                    }
                }
                $details = [
                    'title' =>  'Invoice Number #' . $get_invoice->id . ' has been paid by '. $customerName . ' - ' . $customerEmail,
                    'body' => 'Please Login into your Dashboard to view it..'
                ];
                // \Mail::to($get_invoice->sale->email)->send(new \App\Mail\ClientNotifyMail($details));

                $messageData = [
                    'id' => $get_invoice->id,
                    'name' => $customerName ,
                    'email' => $customerEmail,
                    'text' => 'Invoice Number #' . $get_invoice->id . ' has been paid by '. $customerName . ' - ' . $customerEmail,
                    'details' => '',
                    'url' => '',
                ];
                if(($get_invoice->sale->is_employee != 2) && ($get_invoice->sale->is_employee != 6)){
                    $get_invoice->sale->notify(new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                // Message Notification sending to Admin & Managers
                $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice) {
                                return $query->where('brand_id', $get_invoice->brand);
                            })->get();

                foreach($managers as $manager){
                    Notification::send($manager, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }

                $adminusers = User::where('is_employee', 2)->get();
                foreach($adminusers as $adminuser){
                    Notification::send($adminuser, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                
                return redirect()->route('thankYou',($get_invoice->id));
            }else{
                return redirect()->route('failed',($get_invoice->id));
            }
        }else if($merchant == 2){
            // authorized.net
            $input = $request->input();
            $get_expiration = explode('/', $input['expiration']);
            $expirationMonth = $get_expiration[0];
            if (count($get_expiration) == 1) {
                $message_text = "Please enter a valid Card Expiration Date";
                $msg_type = "error_msg";         
                return back()->with($msg_type, $message_text);
            }
            $expirationYear = $get_expiration[1];
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($invoiceData->merchant->login_id);
            $merchantAuthentication->setTransactionKey($invoiceData->merchant->secret_key);
            $refId = 'ref' . time();
            $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate('20'.$expirationYear . "-" .$expirationMonth);
            $creditCard->setCardCode($input['cvv']);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            $order = new AnetAPI\OrderType();
            $order->setInvoiceNumber($invoiceData->invoice_number);
            $order->setDescription($result . ' - ' . 'Invoice ID: #' . $invoiceData->invoice_number);

            // Set the customer's Bill To address
            $customerAddress = new AnetAPI\CustomerAddressType();
            // $customerAddress->setFirstName($invoiceData->name);
            $customerAddress->setFirstName($request->owner);
            $customerAddress->setAddress($request->address);
            $customerAddress->setCity($request->city);
            $customerAddress->setState($request->set_state);
            $customerAddress->setZip($request->zip);
            $customerAddress->setEmail($request->user_email);
            $customerAddress->setCountry($request->country);
            $customerAddress->setPhoneNumber($request->user_phone);

            $customerData = new AnetAPI\CustomerDataType();
            $customerData->setType("individual");
            $customerData->setEmail($request->user_email);

            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($invoiceData->amount);
            $transactionRequestType->setOrder($order);
            $transactionRequestType->setPayment($paymentOne);
            $transactionRequestType->setBillTo($customerAddress);
            $transactionRequestType->setCustomer($customerData);
            $transactionRequestType->setCurrencyCode($request->currency);
            $transactionRequestType->setCustomerIP($request->ip());
            $requests = new AnetAPI\CreateTransactionRequest();
            $requests->setMerchantAuthentication($merchantAuthentication);
            $requests->setRefId($refId);
            $requests->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($requests);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            // $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            if ($response != null) {
                if ($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        $message_text = $tresponse->getMessages()[0]->getDescription().", Transaction ID: " . $tresponse->getTransId();
                        $msg_type = "success_msg";

                        // Payment Done By Authorized
                        $get_invoice = Invoice::findOrFail($request->invoice_id);
                        if($get_invoice){
                            $get_invoice->transaction_id = $tresponse->getTransId();
                            $get_invoice->payment_status = '2';
                            $get_invoice->invoice_date = Carbon::today()->toDateTimeString();
                            // GET AUTH CODE $tresponse->getAuthCode();
                            $get_invoice->save();
                        }
                        $user = Client::where('email', $get_invoice->client->email)->first();
                        $user_client = User::where('email', $get_invoice->client->email)->first();
                        if($user_client != null){
                            $service_array = explode(',', $get_invoice->service);
                            for($i = 0; $i < count($service_array); $i++){
                                $service = Service::find($service_array[$i]);
                                if($service->form == 0){
                                    //No Form
                                    if($get_invoice->createform == 1){
                                        $no_form = new NoForm();
                                        $no_form->name = $get_invoice->custom_package;
                                        $no_form->invoice_id = $get_invoice->id;

                                        if($user_client != null){
                                            $no_form->user_id = $user_client->id;
                                        }
                                        $no_form->client_id = $user->id;
                                        $no_form->agent_id = $get_invoice->sales_agent_id;
                                        $no_form->save();
                                    }
                                }elseif($service->form == 1){
                                    // Logo Form
                                    if($get_invoice->createform == 1){
                                        $logo_form = new LogoForm();
                                        $logo_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $logo_form->user_id = $user_client->id;
                                        }
                                        $logo_form->client_id = $user->id;
                                        $logo_form->agent_id = $get_invoice->sales_agent_id;
                                        $logo_form->save();
                                    }
                                }elseif($service->form == 2){
                                    // Website Form
                                    if($get_invoice->createform == 1){
                                        $web_form = new WebForm();
                                        $web_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $web_form->user_id = $user_client->id;
                                        }
                                        $web_form->client_id = $user->id;
                                        $web_form->agent_id = $get_invoice->sales_agent_id;
                                        $web_form->save();
                                    }
                                }elseif($service->form == 3){
                                    // Smm Form
                                    if($get_invoice->createform == 1){
                                        $smm_form = new SmmForm();
                                        $smm_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $smm_form->user_id = $user_client->id;
                                        }
                                        $smm_form->client_id = $user->id;
                                        $smm_form->agent_id = $get_invoice->sales_agent_id;
                                        $smm_form->save();
                                    }
                                }elseif($service->form == 4){
                                    // Content Writing Form
                                    if($get_invoice->createform == 1){
                                        $content_writing_form = new ContentWritingForm();
                                        $content_writing_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $content_writing_form->user_id = $user_client->id;
                                        }
                                        $content_writing_form->client_id = $user->id;
                                        $content_writing_form->agent_id = $get_invoice->sales_agent_id;
                                        $content_writing_form->save();
                                    }
                                }elseif($service->form == 5){
                                    // Search Engine Optimization Form
                                    if($get_invoice->createform == 1){
                                        $seo_form = new SeoForm();
                                        $seo_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $seo_form->user_id = $user_client->id;
                                        }
                                        $seo_form->client_id = $user->id;
                                        $seo_form->agent_id = $get_invoice->sales_agent_id;
                                        $seo_form->save();
                                    }
                                }
                            }
                        }
                        $details = [
                            'title' =>  'Invoice Number #' . $get_invoice->id . ' has been paid by '. $customerName . ' - ' . $customerEmail,
                            'body' => 'Please Login into your Dashboard to view it..'
                        ];
                        // \Mail::to($get_invoice->sale->email)->send(new \App\Mail\ClientNotifyMail($details));

                        $messageData = [
                            'id' => $get_invoice->id,
                            'name' => $customerName . ' - ' . $customerEmail ,
                            'email' => $customerEmail,
                            'text' => 'Invoice Number #' . $get_invoice->invoice_number . ' Paid.',
                            'details' => '',
                            'url' => '',
                        ];
                        if(($get_invoice->sale->is_employee != 2) && ($get_invoice->sale->is_employee != 6)){
                            $get_invoice->sale->notify(new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }
                        // Message Notification sending to Admin & Managers
                        $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice) {
                                        return $query->where('brand_id', $get_invoice->brand);
                                    })->get();

                        foreach($managers as $manager){
                            Notification::send($manager, new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }

                        $adminusers = User::where('is_employee', 2)->get();
                        foreach($adminusers as $adminuser){
                            Notification::send($adminuser, new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }
                        return redirect()->route('thankYou',($get_invoice->id));
                    } else {
                        $message_text = 'There were some issue with the payment. Please try again later.';
                        $msg_type = "error_msg";                                    

                        if ($tresponse->getErrors() != null) {
                            $message_text = $tresponse->getErrors()[0]->getErrorText();
                            $msg_type = "error_msg";                                    
                        }
                    }
                    // Or, print errors if the API request wasn't successful
                } else {
                    $message_text = 'There were some issue with the payment. Please try again later.';
                    $msg_type = "error_msg";                                    
                    $message_text_auth = "";

                    $tresponse = $response->getTransactionResponse();
                    
                    $error_code = "";
                    
                    $errorMessages = $response->getMessages()->getMessage();
                    foreach ($errorMessages as $message) {
                        // Log error message
                        $error_code = $message->getCode();
                    }

                    if ($tresponse != null && $tresponse->getErrors() != null) {
                        // $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $message_text = "Payment Declined";
                        $msg_type = "error_msg";                    
                    }else if($error_code === "E00007"){
                        $message_text = "Authorize Transaction Key Is Invalid. Please Update New Keys.";
                        $msg_type = "error_msg";  
                    }else if ($error_code === 'E00027') {
                        $message_text = "Payment Declined - Insufficient Funds.";
                        $msg_type = "error_msg";  
                    }else if ($error_code === 'E00003') {
                        $message_text = "Payment Declined - Invalid Address.";
                        $msg_type = "error_msg";  
                    } else {
                        // $message_text = $response->getMessages()->getMessage()[0]->getText();
                        $message_text = "Payment Declined";
                        $msg_type = "error_msg";
                    }
                    
                    // API RESPONSE FAILED NOTIFY
                    
                    $get_invoice_auth = Invoice::findOrFail($request->invoice_id);
                
                    $messageData = [
                        'id' => $get_invoice_auth->id,
                        'name' => $customerName ,
                        'email' => $customerEmail,
                        'text' => 'Invoice Number #' . $get_invoice_auth->id . ' ' . $message_text . ' ' . $customerName . ' - ' . $customerEmail,
                        'details' => '',
                        'url' => '',
                    ];
                    if(($get_invoice_auth->sale->is_employee != 2) && ($get_invoice_auth->sale->is_employee != 6)){
                        $get_invoice_auth->sale->notify(new PaymentNotification($messageData));
                        event(new NotificationEvent($messageData['text'],$messageData['id']));
                    }
                    // Message Notification sending to Admin & Managers
                    $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice_auth) {
                                    return $query->where('brand_id', $get_invoice_auth->brand);
                                })->get();
    
                    foreach($managers as $manager){
                        Notification::send($manager, new PaymentNotification($messageData));
                        event(new NotificationEvent($messageData['text'],$messageData['id']));
                    }
    
                    $adminusers = User::where('is_employee', 2)->get();
                    foreach($adminusers as $adminuser){
                        Notification::send($adminuser, new PaymentNotification($messageData));
                        event(new NotificationEvent($messageData['text'],$messageData['id']));
                    }    
                }
            } else {

                $message_text = "No response returned";
                $msg_type = "error_msg";
            }
            return back()->with($msg_type, $message_text);
        }else if($merchant == 3){
            $ccnumber = str_replace(' ', '', $request->cardNumber);
            $en_ccnumber = Crypt::encryptString($ccnumber);
        
            $ccexp = str_replace('/', '', $request->expiration);
            $en_ccexp = Crypt::encryptString($ccexp);
        
            $cvv = $request->cvv;
            $en_cvv = Crypt::encryptString($cvv);
        
            $name = $request->user_name;
            $full_name = explode(' ', $name);
            $first_name = $full_name[0];
            $last_name = '';
            if(count($full_name) > 1){
                $last_name = $full_name[1];
            }
            $amount = $invoiceData->amount;
            $address = $request->address;
            $city = $request->city;
            $state = $request->set_state;
            $email = $request->user_email;
            $zip = $request->zip;
            // dd($invoiceData->merchant->secret_key);
            $url = 'https://secure.nmi.com/api/transact.php';
            $vars = "security_key=".$invoiceData->merchant->secret_key
            . "&type=sale"
            . "&amount=". $amount
            . "&first_name=". $first_name
            . "&last_name=". $last_name
            . "&email=". $email
            . "&city=" . $city
            . "&state=" . $state
            . "&address1=" . $address
            . "&zip=" . $zip
            . "&ccnumber=". $ccnumber
            . "&ccexp=" . $ccexp
            . "&cvv=" . $cvv;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $server_output = curl_exec($ch);
            curl_close($ch);
            $output = $server_output;
            $output_data = explode('&', $output);
            // dd($output_data);
            $array = [];
            foreach($output_data as $key => $value){
                $data_output = explode('=', $value);
                $array[$data_output[0]] = $data_output[1];
            }
            $get_invoice = Invoice::find($request->invoice_id);
            if($array['response'] == 1){
                $get_invoice->payment_status = 2;
            }else{
                $get_invoice->payment_status = 1;
            }
            $get_invoice->invoice_date = Carbon::today()->toDateTimeString();
            
            $get_invoice->transaction_id = $array['transactionid'];
            $get_invoice->save();
        
            if($array['response'] == 1){
                // dd($array['responsetext'] , json_encode($array));
                $user = Client::where('email', $get_invoice->client->email)->first();
                $user_client = User::where('email', $get_invoice->client->email)->first();
                if($user_client != null){
                    $service_array = explode(',', $get_invoice->service);
                    for($i = 0; $i < count($service_array); $i++){
                        $service = Service::find($service_array[$i]);
                        if($service->form == 0){
                            //No Form
                            if($get_invoice->createform == 1){
                                $no_form = new NoForm();
                                $no_form->name = $get_invoice->custom_package;
                                $no_form->invoice_id = $get_invoice->id;

                                if($user_client != null){
                                    $no_form->user_id = $user_client->id;
                                }
                                $no_form->client_id = $user->id;
                                $no_form->agent_id = $get_invoice->sales_agent_id;
                                $no_form->save();
                            }
                        }elseif($service->form == 1){
                            // Logo Form
                            if($get_invoice->createform == 1){
                                $logo_form = new LogoForm();
                                $logo_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $logo_form->user_id = $user_client->id;
                                }
                                $logo_form->client_id = $user->id;
                                $logo_form->agent_id = $get_invoice->sales_agent_id;
                                $logo_form->save();
                            }
                        }elseif($service->form == 2){
                            // Website Form
                            if($get_invoice->createform == 1){
                                $web_form = new WebForm();
                                $web_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $web_form->user_id = $user_client->id;
                                }
                                $web_form->client_id = $user->id;
                                $web_form->agent_id = $get_invoice->sales_agent_id;
                                $web_form->save();
                            }
                        }elseif($service->form == 3){
                            // Smm Form
                            if($get_invoice->createform == 1){
                                $smm_form = new SmmForm();
                                $smm_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $smm_form->user_id = $user_client->id;
                                }
                                $smm_form->client_id = $user->id;
                                $smm_form->agent_id = $get_invoice->sales_agent_id;
                                $smm_form->save();
                            }
                        }elseif($service->form == 4){
                            // Content Writing Form
                            if($get_invoice->createform == 1){
                                $content_writing_form = new ContentWritingForm();
                                $content_writing_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $content_writing_form->user_id = $user_client->id;
                                }
                                $content_writing_form->client_id = $user->id;
                                $content_writing_form->agent_id = $get_invoice->sales_agent_id;
                                $content_writing_form->save();
                            }
                        }elseif($service->form == 5){
                            // Search Engine Optimization Form
                            if($get_invoice->createform == 1){
                                $seo_form = new SeoForm();
                                $seo_form->invoice_id = $get_invoice->id;
                                if($user_client != null){
                                    $seo_form->user_id = $user_client->id;
                                }
                                $seo_form->client_id = $user->id;
                                $seo_form->agent_id = $get_invoice->sales_agent_id;
                                $seo_form->save();
                            }
                        }
                    }
                }
                
                $messageData = [
                    'id' => $get_invoice->id,
                    'name' => $request->user_name ,
                    'email' => $request->user_email,
                    'text' => 'Invoice Number #' . $get_invoice->id . ' has been paid by '. $request->user_name . ' - ' . $request->user_email,
                    'details' => '',
                    'url' => '',
                ];
                if(($get_invoice->sale->is_employee != 2) && ($get_invoice->sale->is_employee != 6)){
                    $get_invoice->sale->notify(new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                // Message Notification sending to Admin & Managers
                $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice) {
                                return $query->where('brand_id', $get_invoice->brand);
                            })->get();

                foreach($managers as $manager){
                    Notification::send($manager, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }

                $adminusers = User::where('is_employee', 2)->get();
                foreach($adminusers as $adminuser){
                    Notification::send($adminuser, new PaymentNotification($messageData));
                    event(new NotificationEvent($messageData['text'],$messageData['id']));
                }
                
                return redirect()->route('thankYou',($get_invoice->id));
            }else{
                $message_text = $array['responsetext'];
                $msg_type = "error_msg";
                return back()->with($msg_type, $message_text);
            }
        
        }else if($merchant == 4){
            // Capture the incoming JSON payload
            $token = $request->square_token ?? '';
            // dd($token);
    
            // Validate token
            if (empty($token)) {
                return response()->json(['success' => false, 'message' => 'Payment token is missing'], 400);
            }
    
            // Square API credentials (sandbox)
            $accessToken = $invoiceData->merchant->secret_key;
            $locationId = $invoiceData->merchant->login_id; // Replace with your actual Location ID
            
            // Payment details
            $amount = $invoiceData->amount * 100; // Amount in cents (100 cents = 1 USD)
            $currency = $invoiceData->currency_show->short_name;
    
            // Generate a unique idempotency key for each request
            $idempotencyKey = uniqid();
    
            // Prepare payment request body
            $paymentRequestBody = [
                'source_id' => $token,
                'idempotency_key' => $idempotencyKey,
                'amount_money' => [
                    'amount' => $amount,
                    'currency' => $currency,
                ],
                'location_id' => $locationId // Include the Location ID in the request
            ];
    
            // dd($paymentRequestBody);
            try {
                // Initialize cURL
                $ch = curl_init();
                
                // Set the URL for the cURL request (sandbox environment)
                curl_setopt($ch, CURLOPT_URL, 'https://connect.squareup.com/v2/payments');
                
                // Set the HTTP headers
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accessToken,
                    'Square-Version: 2022-01-05',
                ]);
                
                // Set cURL options to make a POST request with the JSON body
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentRequestBody));
                
                // Return the response instead of printing
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                // Execute the cURL request and get the response
                $response = curl_exec($ch);
                
                // Check for cURL errors
                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    echo json_encode(['success' => false, 'message' => "cURL error: $error_msg"]);
                    curl_close($ch);
                    exit;
                }
                
                // Get HTTP status code
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                // Close the cURL session
                curl_close($ch);
                
                // Decode the response
                $responseData = json_decode($response, true);
                // dd($responseData);
                // Check if the request was successful
                if (isset($responseData['payment'])) {
                $paymentId = $responseData['payment']['id'];
                    $invoice = array();
                    $invoice['request'] = $request;
                    $get_invoice = Invoice::findOrFail($request->invoice_id);
                        if($get_invoice){
                            $get_invoice->transaction_id = $paymentId;
                            $get_invoice->payment_status = '2';
                            $get_invoice->invoice_date = Carbon::today()->toDateTimeString();
                            $get_invoice->save();
                        }
                        $user = Client::where('email', $get_invoice->client->email)->first();
                        $user_client = User::where('email', $get_invoice->client->email)->first();
                        if($user_client != null){
                            $service_array = explode(',', $get_invoice->service);
                            for($i = 0; $i < count($service_array); $i++){
                                $service = Service::find($service_array[$i]);
                                if($service->form == 0){
                                    //No Form
                                    if($get_invoice->createform == 1){
                                        $no_form = new NoForm();
                                        $no_form->name = $get_invoice->custom_package;
                                        $no_form->invoice_id = $get_invoice->id;
        
                                        if($user_client != null){
                                            $no_form->user_id = $user_client->id;
                                        }
                                        $no_form->client_id = $user->id;
                                        $no_form->agent_id = $get_invoice->sales_agent_id;
                                        $no_form->save();
                                    }
                                }elseif($service->form == 1){
                                    // Logo Form
                                    if($get_invoice->createform == 1){
                                        $logo_form = new LogoForm();
                                        $logo_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $logo_form->user_id = $user_client->id;
                                        }
                                        $logo_form->client_id = $user->id;
                                        $logo_form->agent_id = $get_invoice->sales_agent_id;
                                        $logo_form->save();
                                    }
                                }elseif($service->form == 2){
                                    // Website Form
                                    if($get_invoice->createform == 1){
                                        $web_form = new WebForm();
                                        $web_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $web_form->user_id = $user_client->id;
                                        }
                                        $web_form->client_id = $user->id;
                                        $web_form->agent_id = $get_invoice->sales_agent_id;
                                        $web_form->save();
                                    }
                                }elseif($service->form == 3){
                                    // Smm Form
                                    if($get_invoice->createform == 1){
                                        $smm_form = new SmmForm();
                                        $smm_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $smm_form->user_id = $user_client->id;
                                        }
                                        $smm_form->client_id = $user->id;
                                        $smm_form->agent_id = $get_invoice->sales_agent_id;
                                        $smm_form->save();
                                    }
                                }elseif($service->form == 4){
                                    // Content Writing Form
                                    if($get_invoice->createform == 1){
                                        $content_writing_form = new ContentWritingForm();
                                        $content_writing_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $content_writing_form->user_id = $user_client->id;
                                        }
                                        $content_writing_form->client_id = $user->id;
                                        $content_writing_form->agent_id = $get_invoice->sales_agent_id;
                                        $content_writing_form->save();
                                    }
                                }elseif($service->form == 5){
                                    // Search Engine Optimization Form
                                    if($get_invoice->createform == 1){
                                        $seo_form = new SeoForm();
                                        $seo_form->invoice_id = $get_invoice->id;
                                        if($user_client != null){
                                            $seo_form->user_id = $user_client->id;
                                        }
                                        $seo_form->client_id = $user->id;
                                        $seo_form->agent_id = $get_invoice->sales_agent_id;
                                        $seo_form->save();
                                    }
                                }
                            }
                        }
                        $details = [
                            'title' =>  'Invoice Number #' . $get_invoice->id . ' has been paid by '. $customerName . ' - ' . $customerEmail,
                            'body' => 'Please Login into your Dashboard to view it..'
                        ];
                        // \Mail::to($get_invoice->sale->email)->send(new \App\Mail\ClientNotifyMail($details));
        
                        $messageData = [
                            'id' => $get_invoice->id,
                            'name' => $customerName ,
                            'email' => $customerEmail,
                            'text' => 'Invoice Number #' . $get_invoice->id . ' has been paid by '. $customerName . ' - ' . $customerEmail,
                            'details' => '',
                            'url' => '',
                        ];
                        if(($get_invoice->sale->is_employee != 2) && ($get_invoice->sale->is_employee != 6)){
                            $get_invoice->sale->notify(new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }
                        // Message Notification sending to Admin & Managers
                        $managers = User::where('is_employee', 6)->whereHas('brands', function ($query) use ($get_invoice) {
                                        return $query->where('brand_id', $get_invoice->brand);
                                    })->get();
        
                        foreach($managers as $manager){
                            Notification::send($manager, new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }
        
                        $adminusers = User::where('is_employee', 2)->get();
                        foreach($adminusers as $adminuser){
                            Notification::send($adminuser, new PaymentNotification($messageData));
                            event(new NotificationEvent($messageData['text'],$messageData['id']));
                        }
                        
                        return redirect()->route('thankYou',($get_invoice->id));
                        // return response()->json(['success' => true, 'payment' => $responseData['payment']]);
                    } else {
                        if (isset($responseData['errors'])) {
                            $errors = $responseData['errors'];
                            $errorMessage = '';
                
                            // Check for specific error codes and handle them
                            foreach ($errors as $error) {
                                switch ($error['code']) {
                                    case 'VERIFY_CVV_FAILURE':
                                        $errorMessage = 'CVV verification failed. Please check the CVV code.';
                                        break;
                                    case 'VERIFY_EXPIRATION_FAILURE':
                                        $errorMessage = 'Expiration verification failed. Please check the expiration date.';
                                        break;
                                    case 'CARD_DECLINED':
                                        if ($error['detail'] === 'GENERIC_DECLINE') {
                                            $errorMessage = 'Card declined. Please check if your card details are correct.';
                                        } elseif ($error['detail'] === 'LOST_CARD') {
                                            $errorMessage = 'Card declined. The card has been reported as lost.';
                                        } elseif ($error['detail'] === 'STOLEN_CARD') {
                                            $errorMessage = 'Card declined. The card has been reported as stolen.';
                                        } else {
                                            $errorMessage = 'Card declined. Please check if your card has sufficient balance.';
                                        }
                                        break;
                                    case 'CARD_EXPIRED':
                                        $errorMessage = 'Card expired. Please use a valid card.';
                                        break;
                                    case 'INVALID_CARD':
                                        $errorMessage = 'Invalid card number. Please use a valid card.';
                                        break;
                                    default:
                                        $errorMessage = 'Payment failed. Please try again later.';
                                        break;
                                }
                            }
                         
                        $message_text = $errorMessage;
                        $msg_type = "error_msg";
                        return back()->with($msg_type, $message_text);   
                        }
                    }
                } catch (\Exception $e) {
                    $errors = json_decode($e->getMessage(), true);
                    $errorMessage = 'Payment failed. Please try again later.';
                    
                    if (is_array($errors)) {
                        foreach ($errors as $error) {
                            switch ($error['code']) {
                                case 'VERIFY_CVV_FAILURE':
                                    $errorMessage = 'CVV verification failed. Please check the CVV code.';
                                    break;
                                case 'VERIFY_EXPIRATION_FAILURE':
                                    $errorMessage = 'Expiration verification failed. Please check the expiration date.';
                                    break;
                                case 'CARD_DECLINED':
                                    if ($error['detail'] === 'GENERIC_DECLINE') {
                                        $errorMessage = 'Card declined. Please check if your card details are correct.';
                                    } elseif ($error['detail'] === 'LOST_CARD') {
                                        $errorMessage = 'Card declined. The card has been reported as lost.';
                                    } elseif ($error['detail'] === 'STOLEN_CARD') {
                                        $errorMessage = 'Card declined. The card has been reported as stolen.';
                                    } else {
                                        $errorMessage = 'Card declined. Please check if your card has sufficient balance.';
                                    }
                                    break;
                                case 'CARD_EXPIRED':
                                    $errorMessage = 'Card expired. Please use a valid card.';
                                    break;
                                case 'INVALID_CARD':
                                    $errorMessage = 'Invalid card number. Please use a valid card.';
                                    break;
                                case 'GENERIC_DECLINE':
                                    $errorMessage = 'Card declined. Please check if your card details are correct.';
                                    break;
                                case 'LOST_CARD':
                                    $errorMessage = 'Card declined. The card has been reported as lost.';
                                    break;
                                case 'STOLEN_CARD':
                                    $errorMessage = 'Card declined. The card has been reported as stolen.';
                                    break;
                                default:
                                    $errorMessage = 'Payment failed. Please try again later.';
                                    break;
                            }
                        }
                        
                        $message_text = $errorMessage;
                        $msg_type = "error_msg";
                        return back()->with($msg_type, $message_text);
                    }
                    // return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
                }
        }
    }

    public function thankYou($id)
    {
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        return view('invoice.thank-you')->with(compact('_getInvoiceData'))->with(compact('id','_getInvoiceData','_getBrand'));
    }

    public function failed($id)
    {
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('brand_name',$_getInvoiceData->brand)->first();
        return view('invoice.failed')->with(compact('_getInvoiceData'))->with(compact('id','_getInvoiceData','_getBrand'));
    }

    public function managerStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'brand' => 'required',
            'service' => 'required',
            'package' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'merchant' => 'required'
        ]);
        $latest = Invoice::latest()->first();
        
        $currentDate = date('d-m-Y');

        $formattedDate = str_replace('-', '', $currentDate);
        
        if (! $latest) {
            $nextInvoiceNumber = $formattedDate.'-1';
        }else{
            $expNum = explode('-', $latest->invoice_number);
            $expIncrement = (int)$expNum[1] + 1;
            $nextInvoiceNumber = $formattedDate.'-'.$expIncrement;
        }
        
        $contact = $request->contact;
        if($contact == null){
            $contact = '#';
        }
		$invoice = new Invoice;
        if($request->createform != null){
            $invoice->createform = $request->createform;
        }else{
            $invoice->createform = 1;
        }
        $invoice->name = $request->name;
        $invoice->email = $request->email;
        $invoice->contact = $contact;
        $invoice->brand = $request->brand;
        $invoice->package = $request->package;
        $invoice->currency = $request->currency;
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $nextInvoiceNumber;
        $invoice->sales_agent_id = Auth()->user()->id;
        $invoice->discription = $request->discription;
        $invoice->amount = $request->amount;
        $invoice->payment_status = '1';
        $invoice->custom_package = $request->custom_package;
        $invoice->payment_type = $request->payment_type;
		$service = implode(",",$request->service);
		$invoice->service = $service;
        $invoice->merchant_id = $request->merchant;
        $invoice->save();				
		$id = $invoice->id;
        
        $id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        $package_name = '';
        if($_getInvoiceData->package == 0){
            $package_name = strip_tags($_getInvoiceData->custom_package);
        }
        // $sendemail = $request->sendemail;
        $sendemail = 0;
        if($sendemail == 1){
            // Send Invoice Link To Email
            $details = [
                'brand_name' => $_getBrand->name,
                'brand_logo' => $_getBrand->logo,
                'brand_phone' => $_getBrand->phone,
                'brand_email' => $_getBrand->email,
                'brand_address' => $_getBrand->address,
                'invoice_number' => $_getInvoiceData->invoice_number,
                'currency_sign' => $_getInvoiceData->currency_show->sign,
                'amount' => $_getInvoiceData->amount,
                'name' => $_getInvoiceData->name,
                'email' => $_getInvoiceData->email,
                'contact' => $_getInvoiceData->contact,
                'date' => $_getInvoiceData->created_at->format('jS M, Y'),
                'link' => route('client.paynow', $id),
                'package_name' => $package_name,
                'discription' => $_getInvoiceData->discription
            ];
            // \Mail::to($_getInvoiceData->email)->send(new \App\Mail\InoviceMail($details));
        }
		return redirect()->route('manager.link',($invoice->id));
    }

    public function saleStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'brand' => 'required',
            'service' => 'required',
            'package' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'merchant' => 'required'
        ]);
        $latest = Invoice::latest()->first();
        
        $currentDate = date('d-m-Y');

        $formattedDate = str_replace('-', '', $currentDate);
        
        if (! $latest) {
            $nextInvoiceNumber = $formattedDate.'-1';
        }else{
            $expNum = explode('-', $latest->invoice_number);
            $expIncrement = (int)$expNum[1] + 1;
            $nextInvoiceNumber = $formattedDate.'-'.$expIncrement;
        }
        $contact = $request->contact;
        if($contact == null){
            $contact = '#';
        }
		$invoice = new Invoice;
        if($request->createform != null){
            $invoice->createform = $request->createform;
        }else{
            $invoice->createform = 1;
        }
        $invoice->name = $request->name;
        $invoice->email = $request->email;
        $invoice->contact = $contact;
        $invoice->brand = $request->brand;
        $invoice->package = $request->package;
        $invoice->currency = $request->currency;
        $invoice->client_id = $request->client_id;
        $invoice->invoice_number = $nextInvoiceNumber;
        $invoice->sales_agent_id = Auth()->user()->id;
        $invoice->discription = $request->discription;
        $invoice->amount = $request->amount;
        $invoice->payment_status = '1';
        $invoice->custom_package = $request->custom_package;
        $invoice->payment_type = $request->payment_type;
		$service = implode(",",$request->service);
		$invoice->service = $service;
		$invoice->merchant_id = $request->merchant;

        $invoice->save();				
		$id = $invoice->id;
        
        $id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        $package_name = '';
        if($_getInvoiceData->package == 0){
            $package_name = strip_tags($_getInvoiceData->custom_package);
        }
        // $sendemail = $request->sendemail;
        $sendemail = 0;
        if($sendemail == 1){
            // Send Invoice Link To Email
            $details = [
                'brand_name' => $_getBrand->name,
                'brand_logo' => $_getBrand->logo,
                'brand_phone' => $_getBrand->phone,
                'brand_email' => $_getBrand->email,
                'brand_address' => $_getBrand->address,
                'invoice_number' => $_getInvoiceData->invoice_number,
                'currency_sign' => $_getInvoiceData->currency_show->sign,
                'amount' => $_getInvoiceData->amount,
                'name' => $_getInvoiceData->name,
                'email' => $_getInvoiceData->email,
                'contact' => $_getInvoiceData->contact,
                'date' => $_getInvoiceData->created_at->format('jS M, Y'),
                'link' => route('client.paynow', $id),
                'package_name' => $package_name,
                'discription' => $_getInvoiceData->discription
            ];
            // \Mail::to($_getInvoiceData->email)->send(new \App\Mail\InoviceMail($details));
        }
		return redirect()->route('sale.link',($invoice->id));
    }

    public function getInvoiceBySaleManager(Request $request){
        $data = new Invoice;
        $data = $data->whereIn('brand', Auth()->user()->brand_list());
        $data = $data->orderBy('id', 'desc');
        $perPage = 10;
        if($request->package != ''){
            $data = $data->where('custom_package', 'LIKE', "%$request->package%");
        }
        if($request->invoice != ''){
            $data = $data->where('invoice_number', 'LIKE', "%$request->invoice%");
        }
        if($request->user != ''){
            $data = $data->where('name', 'LIKE', "%$request->user%")->orWhere('email', 'LIKE', "%$request->user%");
        }
        if($request->status != 0){
            $data = $data->where('payment_status', $request->status);
        }
        $data = $data->paginate(10);
        return view('manager.invoice.index', compact('data'));
    }    

    public function getInvoiceByUserId (Request $request){
        $data = new Invoice;
        $data = $data->where('sales_agent_id', Auth()->user()->id);
        $data = $data->orderBy('id', 'desc');
        $perPage = 10;
        if($request->package != ''){
            $data = $data->where('custom_package', 'LIKE', "%$request->package%");
        }
        if($request->invoice != ''){
            $data = $data->where('invoice_number', 'LIKE', "%$request->invoice%");
        }
        if($request->user != 0){
            $data = $data->where('name', 'LIKE', "%$request->user%");
            $data = $data->orWhere('email', 'LIKE', "%$request->user%");
        }
        if($request->status != 0){
            $data = $data->where('payment_status', $request->status);
        }
        $data = $data->paginate(10);
        return view('sale.invoice.index', compact('data'));
    }

    public function getSingleInvoice($id){
        $data = Invoice::where('id', $id)->where('sales_agent_id', Auth::user()->id)->first();
        if($data == null){
            return redirect()->back();
        }else{
            return view('sale.invoice.show', compact('data'));
        }
    }

    public function invoicePaidByIdManager($id){
        $invoice = Invoice::find($id);
        $user = Client::where('email', $invoice->client->email)->first();
        $user_client = User::where('email', $invoice->client->email)->first();
        if($user_client != null){
            $service_array = explode(',', $invoice->service);
            for($i = 0; $i < count($service_array); $i++){
                $service = Service::find($service_array[$i]);
                if($service->form == 0){
                    //No Form
                    if($invoice->createform == 1){
                        $no_form = new NoForm();
                        $no_form->name = $invoice->custom_package;
                        $no_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $no_form->user_id = $user_client->id;
                        }
                        $no_form->client_id = $user->id;
                        $no_form->agent_id = $invoice->sales_agent_id;
                        $no_form->save();
                    }
                }elseif($service->form == 1){
                    // Logo Form
                    if($invoice->createform == 1){
                        $logo_form = new LogoForm();
                        $logo_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $logo_form->user_id = $user_client->id;
                        }
                        $logo_form->client_id = $user->id;
                        $logo_form->agent_id = $invoice->sales_agent_id;
                        $logo_form->save();
                    }
                }elseif($service->form == 2){
                    // Website Form
                    if($invoice->createform == 1){
                        $web_form = new WebForm();
                        $web_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $web_form->user_id = $user_client->id;
                        }
                        $web_form->client_id = $user->id;
                        $web_form->agent_id = $invoice->sales_agent_id;
                        $web_form->save();
                    }
                }elseif($service->form == 3){
                    // Smm Form
                    if($invoice->createform == 1){
                        $smm_form = new SmmForm();
                        $smm_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $smm_form->user_id = $user_client->id;
                        }
                        $smm_form->client_id = $user->id;
                        $smm_form->agent_id = $invoice->sales_agent_id;
                        $smm_form->save();
                    }
                }elseif($service->form == 4){
                    // Content Writing Form
                    if($invoice->createform == 1){
                        $content_writing_form = new ContentWritingForm();
                        $content_writing_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $content_writing_form->user_id = $user_client->id;
                        }
                        $content_writing_form->client_id = $user->id;
                        $content_writing_form->agent_id = $invoice->sales_agent_id;
                        $content_writing_form->save();
                    }
                }elseif($service->form == 5){
                    // Search Engine Optimization Form
                    if($invoice->createform == 1){
                        $seo_form = new SeoForm();
                        $seo_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $seo_form->user_id = $user_client->id;
                        }
                        $seo_form->client_id = $user->id;
                        $seo_form->agent_id = $invoice->sales_agent_id;
                        $seo_form->save();
                    }
                }elseif($service->form == 6){
                    // Book Formatting & Publishing
                    if($invoice->createform == 1){
                        $book_formatting_form = new BookFormatting();
                        $book_formatting_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $book_formatting_form->user_id = $user_client->id;
                        }
                        $book_formatting_form->client_id = $user->id;
                        $book_formatting_form->agent_id = $invoice->sales_agent_id;
                        $book_formatting_form->save();
                    }
                }elseif($service->form == 7){
                    // Book Formatting & Publishing
                    if($invoice->createform == 1){
                        $book_writing_form = new BookWriting();
                        $book_writing_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $book_writing_form->user_id = $user_client->id;
                        }
                        $book_writing_form->client_id = $user->id;
                        $book_writing_form->agent_id = $invoice->sales_agent_id;
                        $book_writing_form->save();
                    }
                }elseif($service->form == 8){
                    // Author Website
                    if($invoice->createform == 1){
                        $author_website_form = new AuthorWebsite();
                        $author_website_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $author_website_form->user_id = $user_client->id;
                        }
                        $author_website_form->client_id = $user->id;
                        $author_website_form->agent_id = $invoice->sales_agent_id;
                        $author_website_form->save();
                    }
                }elseif($service->form == 9){
                    // Author Website
                    if($invoice->createform == 1){
                        $proofreading_form = new Proofreading();
                        $proofreading_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $proofreading_form->user_id = $user_client->id;
                        }
                        $proofreading_form->client_id = $user->id;
                        $proofreading_form->agent_id = $invoice->sales_agent_id;
                        $proofreading_form->save();
                    }
                }elseif($service->form == 10){
                    // Author Website
                    if($invoice->createform == 1){
                        $bookcover_form = new BookCover();
                        $bookcover_form->invoice_id = $invoice->id;
                        if($user_client != null){
                            $bookcover_form->user_id = $user_client->id;
                        }
                        $bookcover_form->client_id = $user->id;
                        $bookcover_form->agent_id = $invoice->sales_agent_id;
                        $bookcover_form->save();
                    }
                }
            }
        }
        $invoice->payment_status = 2;
        $invoice->invoice_date = Carbon::today()->toDateTimeString();
        $invoice->save();
        return redirect()->back()->with('success','Invoice# ' . $invoice->invoice_number . ' Mark as Paid.');
    }

    public function invoicePaidById($id){
        $invoice = Invoice::find($id);
        $invoice->payment_status = 2;
        $invoice->save();
        return redirect()->back()->with('success','Invoice# ' . $invoice->invoice_number . ' Mark as Paid.');
    }

    public function editInvoice($id){
        $invoice = Invoice::find($id);
        $brand = Brand::whereIn('id', Auth()->user()->brand_list())->get();;
        $services = Service::all();
        $currencies =  Currency::all();
        $merchant =  Merchant::all();
        return view('sale.invoice.edit', compact('invoice', 'brand', 'services', 'currencies', 'merchant'));
    }

    public function editInvoiceManager($id){
        $invoice = Invoice::find($id);
        $brand = Brand::whereIn('id', Auth()->user()->brand_list())->get();;
        $services = Service::all();
        $currencies =  Currency::all();
        $merchant =  Merchant::all();
        return view('manager.invoice.edit', compact('invoice', 'brand', 'services', 'currencies', 'merchant'));
    }

    public function saleUpdateManager(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'brand' => 'required',
            'service' => 'required',
            'package' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'merchant' => 'required'
        ]);
        $contact = $request->contact;
        if($contact == null){
            $contact = '#';
        }
        $invoice = Invoice::find($request->invoice_id);
        $invoice->name = $request->name;
        $invoice->email = $request->email;
        $invoice->contact = $contact;
        $invoice->brand = $request->brand;
        $invoice->package = $request->package;
        $invoice->currency = $request->currency;
        $invoice->client_id = $request->client_id;
        $invoice->sales_agent_id = Auth()->user()->id;
        $invoice->discription = $request->discription;
        $invoice->amount = $request->amount;
        $invoice->custom_package = $request->custom_package;
        $invoice->payment_type = $request->payment_type;
		$service = implode(",",$request->service);
		$invoice->service = $service;
		$invoice->merchant_id = $request->merchant;
        $invoice->save();
        return redirect()->route('manager.link',($invoice->id));
    }

    public function saleUpdate(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'brand' => 'required',
            'service' => 'required',
            'package' => 'required',
            'currency' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'merchant' => 'required',
        ]);
        $contact = $request->contact;
        if($contact == null){
            $contact = '#';
        }
        $invoice = Invoice::find($request->invoice_id);
        $invoice->name = $request->name;
        $invoice->email = $request->email;
        $invoice->contact = $contact;
        $invoice->brand = $request->brand;
        $invoice->package = $request->package;
        $invoice->currency = $request->currency;
        $invoice->client_id = $request->client_id;
        $invoice->sales_agent_id = Auth()->user()->id;
        $invoice->discription = $request->discription;
        $invoice->amount = $request->amount;
        $invoice->custom_package = $request->custom_package;
        $invoice->payment_type = $request->payment_type;
		$service = implode(",",$request->service);
		$invoice->service = $service;
		$invoice->merchant_id = $request->merchant;
        $invoice->save();
        return redirect()->route('sale.link',($invoice->id));
    }
    
    public function covertToPdf($id)
    {
		$id = base64_encode($id);
		$invoiceId = base64_decode($id);
		$_getInvoiceData = Invoice::findOrFail($invoiceId);
		$_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
		
		
// 		return view('admin.invoice.invoice-pdf', compact('_getInvoiceData', 'id', '_getInvoiceData', '_getBrand'));
        $pdf = PDF::loadView('admin.invoice.invoice-pdf', compact(['_getInvoiceData', 'id', '_getInvoiceData', '_getBrand']));
        return $pdf->download('INV'.$_getInvoiceData->invoice_number.'.pdf');
    }
    
    public function sendMailInvoice(Request $request){
        $id = $request->id;
        $_getInvoiceData = Invoice::findOrFail($id);
        $_getBrand = Brand::where('id',$_getInvoiceData->brand)->first();
        
        $service_name = '';
        $service_array = explode(',', $_getInvoiceData->service);
        for($i = 0; $i < count($service_array); $i++){
            $service = Service::find($service_array[$i]);
            $service_name .= $service->name;
            if(($i + 1) == count($service_array)){
                
            }else{
                $service_name .=  ', ';
            }
        }        

        $details = [
            'brand_name' => $_getBrand->name,
            'brand_logo' => $_getBrand->logo,
            'brand_phone' => $_getBrand->phone,
            'brand_email' => $_getBrand->email,
            'brand_address' => $_getBrand->address,
            'invoice_number' => $_getInvoiceData->invoice_number,
            'currency_sign' => $_getInvoiceData->currency_show->sign,
            'amount' => $_getInvoiceData->amount,
            'name' => $_getInvoiceData->name,
            'email' => $_getInvoiceData->email,
            'contact' => $_getInvoiceData->contact,
            'date' => $_getInvoiceData->created_at->format('jS M, Y'),
            'link' => route('client.paynow', base64_encode($id)),
            'payment_status' => $_getInvoiceData->payment_status,
            'services' => $service_name,
            'merchant' => $_getInvoiceData->merchant_id,
            'description' => $_getInvoiceData->discription
        ];        
        
        // dd($details);
        // \Mail::to('muzammil.coredigitals@gmail.com')->send(new \App\Mail\SendInvoiceMail($details));
        \Mail::to($_getInvoiceData->email)->send(new \App\Mail\SendInvoiceMail($details));
        
        return response()->json(['success' => true, 'message' => 'Invoice Mail Sent Successfully!'], 200);
    }
    
    public function invoiceDetail(Request $request){
        // dd($request->all());
        
        $invoice_id = $request->id;
        
        $get_Invoice = Invoice::with([
            'sale',
            'currency_show',
            'brands',
            'merchant',
            'client.user',
        ])->where('id', $invoice_id)->first();
        
        $serviceList = explode(',', $get_Invoice->service); 

        $services = Service::whereIn('id', $serviceList)->pluck('name')->toArray();
    
        $get_Invoice->service_names = $services;
        
        return response()->json($get_Invoice);
    }
    
    public function myInvoiceManager(Request $request){
        $data = new Invoice;
        $data = $data->where('sales_agent_id', Auth::user()->id);
        $data = $data->orderBy('id', 'desc');
        $perPage = 10;
        
        if($request->package != ''){
            $data = $data->where('custom_package', 'LIKE', "%$request->package%");
        }
        if($request->invoice != ''){
            $data = $data->where('invoice_number', 'LIKE', "%$request->invoice%");
        }
        if($request->user != ''){
            $data = $data->where('name', 'LIKE', "%$request->user%")->orWhere('email', 'LIKE', "%$request->user%");
        }
        if($request->status != 0){
            $data = $data->where('payment_status', $request->status);
        }
        $data = $data->paginate(10);
            
        return view('manager.invoice.my_invoice', compact('data'));
    }
}
