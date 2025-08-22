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
use net\authorize\api\contract\v1 as Anet;

use Carbon\Carbon;
use App\Events\NotificationEvent;
// PDF LIBRARY
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoiceMail;

class ChargeCustomerController extends Controller
{
    public function index(){
        
        $transactions = Invoice::with('merchant')->where('transaction_id', 'NOT LIKE', "%ch_%")->where('transaction_id', '!=', null)->where('payment_status',2)->get();
        
        return view('manager.authorize-payment.index', compact('transactions'));
    }
    
    public function charge(Request $request){
        $transactionId = $request->input('transaction_id');
        $amount = $request->input('amount');
        $description = $request->input('description');
        $cardNumber = $request->input('card_number'); // Retrieved from previous transaction
        $expirationDate = $request->input('expiration_date'); // Retrieved from previous transaction
    
    
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('74uMeZN5KgYc');
        $merchantAuthentication->setTransactionKey('5aNXe9qLy26C374x');
    
        $refTransId = $transactionId;
    
        // Create payment type using card details
        $transactionRequest = new Anet\TransactionRequestType();
        $transactionRequest->setTransactionType("priorAuthCaptureTransaction"); // Using Prior Authorization Capture
        $transactionRequest->setAmount($amount);
        $transactionRequest->setRefTransId($refTransId);
    
        // Create request
        $request_tr = new Anet\CreateTransactionRequest();
        $request_tr->setMerchantAuthentication($merchantAuthentication);
        $request_tr->setTransactionRequest($transactionRequest);
    
        // Execute transaction
        $controller = new AnetController\CreateTransactionController($request_tr);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        dd($response);
        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            return response()->json([
                'success' => true,
                'transaction_id' => $response->getTransactionResponse()->getTransId(),
                'message' => 'Charge successful!'
            ]);
        } else {
            $errorMessages = $response->getTransactionResponse()->getErrors();
            return response()->json([
                'success' => false,
                'error' => $errorMessages[0]->getErrorText() ?? 'Transaction failed.'
            ]);
        }
    }
        
    
    public function getTransactionDetails(Request $request)
    {
        // $getMerchantKeys = Merchant::where('is_authorized', 2)->where('status', 1)->first();
        
        $transactionId = $request->input('transaction_id');

        // Set API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        // $merchantAuthentication->setName($getMerchantKeys->login_id);
        // $merchantAuthentication->setTransactionKey($getMerchantKeys->secret_key);
        $merchantAuthentication->setName('74uMeZN5KgYc');
        $merchantAuthentication->setTransactionKey('5aNXe9qLy26C374x');

        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transactionId);
    
        $controller = new AnetController\GetTransactionDetailsController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    
        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            $transaction = $response->getTransaction();
            
            return response()->json([
                'success' => true,
                'email' => $transaction->getCustomer()->getEmail(),
                'card_number' => $transaction->getPayment()->getCreditCard()->getCardNumber(),
                'expiration' => $transaction->getPayment()->getCreditCard()->getExpirationDate(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Transaction details not found!',
                'error' => $response->getMessages()->getMessage()[0]->getText(),
            ]);
        }
    }
}