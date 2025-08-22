<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\LogoForm;
use App\Models\WebForm;
use App\Models\SmmForm;
use App\Models\Client;
use App\Models\ContentWritingForm;
use App\Models\SeoForm;
use App\Models\BookFormatting;
use App\Models\BookWriting;
use App\Models\AuthorWebsite;
use App\Models\Proofreading;
use App\Models\BookCover;
use App\Models\Brand;
use App\Models\ClientFile;
use App\Models\Task;
use App\Models\SubTask;
use App\Models\SubtasKDueDate;
use App\Models\ProjectSettings;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Notifications\MessageNotification;
use Illuminate\Support\Str;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Auth;
use Notification;
use Mail;
use DB;
use PDF;
use \Carbon\Carbon;
use DateTimeZone;
use ZipArchive;
use File;


// WASABI LIBRARIES
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        $project_count = Project::where('user_id', Auth()->user()->id)->orderBy('id', 'desc')->count();
        return view('support.home', compact('project_count'));
    }

    public function projects(Request $request){
        $data = new Project;
        $data = $data->where('user_id', Auth()->user()->id);
        $data = $data->orderBy('id', 'desc');
        if($request->project != ''){
            $data = $data->where('name', 'LIKE', "%$request->project%");
        }
        if($request->project_id != ''){
            $data = $data->where('id', $request->project_id);
        }
        if($request->user != ''){
            $user = $request->user;
            $data = $data->whereHas('client', function ($query) use ($user) {
                return $query->where('name', 'LIKE', "%$user%")->orWhere('email', 'LIKE', "%$user%");
            });
        }
       
        $data = $data->paginate(10);
        
        // Get the form IDs from the $data collection
        $formIds = $data->pluck('form_id')->toArray();
        
        // Retrieve the LogoForms that match the form IDs
        $allForms = collect();

        $logoForms = LogoForm::whereIn('id', $formIds)->whereNotNull('logo_name')->get();
        $webForms = WebForm::whereIn('id', $formIds)->whereNotNull('business_name')->get();
        $smmForms = SmmForm::whereIn('id', $formIds)->whereNotNull('business_name')->get();
        $contentForms = ContentWritingForm::whereIn('id', $formIds)->whereNotNull('company_name')->get();
        $seoForms = SeoForm::whereIn('id', $formIds)->whereNotNull('company_name')->get();
        $bookformattingForms = BookFormatting::whereIn('id', $formIds)->whereNotNull('book_title')->get();
        $bookwritingForms = BookWriting::whereIn('id', $formIds)->whereNotNull('book_title')->get();
        $authorwebsiteForms = AuthorWebsite::whereIn('id', $formIds)->whereNotNull('author_name')->get();
        $proofreadingForms = Proofreading::whereIn('id', $formIds)->whereNotNull('word_count')->get();
        $bookcoverForms = BookCover::whereIn('id', $formIds)->whereNotNull('title')->get();
        
        $allForms = $allForms->merge($logoForms);
        $allForms = $allForms->merge($webForms);
        $allForms = $allForms->merge($smmForms);
        $allForms = $allForms->merge($contentForms);
        $allForms = $allForms->merge($seoForms);
        $allForms = $allForms->merge($bookformattingForms);
        $allForms = $allForms->merge($bookwritingForms);
        $allForms = $allForms->merge($authorwebsiteForms);
        $allForms = $allForms->merge($proofreadingForms);
        $allForms = $allForms->merge($bookcoverForms);
        
        $allForms = $allForms->map(function ($form) {
            switch (get_class($form)) {
                case LogoForm::class:
                    $form->form_checker = 1;
                    break;
                case WebForm::class:
                    $form->form_checker = 2;
                    break;
                case SmmForm::class:
                    $form->form_checker = 3;
                    break;
                case ContentWritingForm::class:
                    $form->form_checker = 4;
                    break;
                case SeoForm::class:
                    $form->form_checker = 5;
                    break;
                case BookFormatting::class:
                    $form->form_checker = 6;
                    break;
                case BookWriting::class:
                    $form->form_checker = 7;
                    break;
                case AuthorWebsite::class:
                    $form->form_checker = 8;
                    break;
                case Proofreading::class:
                    $form->form_checker = 9;
                    break;
                case BookCover::class:
                    $form->form_checker = 10;
                    break;
            }
            return $form;
        });
        
        $settings = ProjectSettings::get();
        
        return view('support.project', compact('data', 'allForms','settings'));
    }

    public function allProjects(){
        $brand_list = Auth::user()->brand_list();
        $data = Project::whereIn('brand_id', $brand_list)->where('user_id', '!=',Auth()->user()->id)->orderBy('id', 'desc')->paginate(10);
        return view('support.all-projects', compact('data'));
    }

    public function getPdfFormByProduction($form_id, $check, $id){
        $project = Project::find($id);
        if($check == 1){
            $logo_form = LogoForm::find($form_id);
            // return view('production.form.logoform', compact('logo_form'));
        }else if($check == 2){
            $web_form = WebForm::find($form_id);
            $data = [
                'data' => $web_form,
            ];
            $pdf = PDF::loadView('production.pdf.web-form', $data);
            return $pdf->download('testing.pdf');
            // return view('production.form.webform', compact('web_form'));
        }elseif($check == 3){
            $smm_form = SmmForm::find($form_id);
            // return view('production.form.smmform', compact('smm_form'));
        }elseif($check == 4){
            $content_form = ContentWritingForm::find($form_id);
            // return view('production.form.contentform', compact('content_form'));
        }elseif($check == 5){
            $seo_form = SeoForm::find($form_id);
            // return view('production.form.seoform', compact('seo_form'));
        }
    }

    public function getFormByProduction($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
        if($check == 1){
            $logo_form = LogoForm::find($form_id);
            
            $img_arr = [];
        
            foreach($logo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.logoform', compact('logo_form','img_arr'));
        }else if($check == 2){
            $web_form = WebForm::find($form_id);
            
            $img_arr = [];
        
            foreach($web_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.webform', compact('web_form','img_arr'));
        }elseif($check == 3){
            $smm_form = SmmForm::find($form_id);
            
            $img_arr = [];
        
            foreach($smm_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.smmform', compact('smm_form','img_arr'));
        }elseif($check == 4){
            $content_form = ContentWritingForm::find($form_id);
            
            $img_arr = [];
        
            foreach($content_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.contentform', compact('content_form','img_arr'));
        }elseif($check == 5){
            $seo_form = SeoForm::find($form_id);
            
            $img_arr = [];
        
            foreach($seo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.seoform', compact('seo_form','img_arr'));
        }elseif($check == 6){
            $data = BookFormatting::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            // dd($img_arr);
            return view('production.form.bookformatting', compact('data','img_arr'));
        }elseif($check == 7){
            $data = BookWriting::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.bookwriting', compact('data','img_arr'));
        }elseif($check == 8){
            $data = AuthorWebsite::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.authorwebsite', compact('data','img_arr'));
        }elseif($check == 9){
            $data = Proofreading::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.proofreading', compact('data','img_arr'));
        }elseif($check == 10){
            $data = BookCover::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('production.form.bookcover', compact('data','img_arr'));
        }
    }

    public function getFormByMember($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
        if($check == 1){
            $logo_form = LogoForm::find($form_id);
            
            $img_arr = [];
        
            foreach($logo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.logoform', compact('logo_form', 'img_arr'));
        }else if($check == 2){
            $web_form = WebForm::find($form_id);
            
            $img_arr = [];
        
            foreach($web_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.webform', compact('web_form', 'img_arr'));
        }elseif($check == 3){
            $smm_form = SmmForm::find($form_id);
            
            $img_arr = [];
        
            foreach($smm_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.smmform', compact('smm_form', 'img_arr'));
        }elseif($check == 4){
            $content_form = ContentWritingForm::find($form_id);
            
            $img_arr = [];
        
            foreach($content_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.contentform', compact('content_form', 'img_arr'));
        }elseif($check == 5){
            $seo_form = SeoForm::find($form_id);
            
            $img_arr = [];
        
            foreach($seo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.seoform', compact('seo_form', 'img_arr'));
        }elseif($check == 6){
            $data = BookFormatting::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            
            return view('member.form.bookformatting', compact('data', 'img_arr'));
        }elseif($check == 7){
            $data = BookWriting::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('member.form.bookwriting', compact('data', 'img_arr'));
        }elseif($check == 8){
            $data = AuthorWebsite::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('member.form.authorwebsite', compact('data', 'img_arr'));
        }elseif($check == 9){
            $data = Proofreading::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('member.form.proofreading', compact('data', 'img_arr'));
        }elseif($check == 10){
            $data = BookCover::find($form_id);
            
            $img_arr = [];
        
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('member.form.bookcover', compact('data', 'img_arr'));
        }
    }

    public function getForm($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
            if($check == 1){
                $logo_form = LogoForm::find($form_id);
                
                $img_arr = [];
                
                foreach($logo_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.logoform', compact('logo_form','img_arr'));
            }else if($check == 2){
                $web_form = WebForm::find($form_id);
                
                $img_arr = [];
        
                foreach($web_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.webform', compact('web_form','img_arr'));
            }elseif($check == 3){
                $smm_form = SmmForm::find($form_id);
                
                $img_arr = [];
        
                foreach($smm_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.smmform', compact('smm_form','img_arr'));
            }elseif($check == 4){
                $content_form = ContentWritingForm::find($form_id);
                
                $img_arr = [];
        
                foreach($content_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.contentform', compact('content_form','img_arr'));
            }elseif($check == 5){
                $seo_form = SeoForm::find($form_id);
                
                $img_arr = [];
        
                foreach($seo_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.seoform', compact('seo_form','img_arr'));
            }elseif($check == 6){
                $data = BookFormatting::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.bookformatting', compact('data','img_arr'));
            }elseif($check == 7){
                $data = BookWriting::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.bookwriting', compact('data','img_arr'));
            }elseif($check == 8){
                $data = AuthorWebsite::find($form_id);
                
                $img_arr = [];
                
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.authorwesbite', compact('data','img_arr'));
            }elseif($check == 9){
                $data = Proofreading::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.proofreading', compact('data','img_arr'));
            }elseif($check == 10){
                $data = BookCover::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('support.bookcover', compact('data','img_arr'));
            }
        // }else{
        //     return redirect()->back();
        // }
    }
    
    public function getFormAdmin($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
            if($check == 1){
                $logo_form = LogoForm::find($form_id);
                
                $img_arr = [];
        
                foreach($logo_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.logoform', compact('logo_form','img_arr'));
            }else if($check == 2){
                $web_form = WebForm::find($form_id);
                
                $img_arr = [];
        
                foreach($web_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.webform', compact('web_form','img_arr'));
            }elseif($check == 3){
                $smm_form = SmmForm::find($form_id);
                
                $img_arr = [];
        
                foreach($smm_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.smmform', compact('smm_form','img_arr'));
            }elseif($check == 4){
                $content_form = ContentWritingForm::find($form_id);
                
                $img_arr = [];
        
                foreach($content_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.contentform', compact('content_form','img_arr'));
            }elseif($check == 5){
                $seo_form = SeoForm::find($form_id);
                
                $img_arr = [];
        
                foreach($seo_form->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.seoform', compact('seo_form','img_arr'));
            }elseif($check == 6){
                $data = BookFormatting::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.bookformattingform', compact('data','img_arr'));
            }elseif($check == 7){
                $data = BookWriting::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.bookwritingform', compact('data','img_arr'));
            }elseif($check == 8){
                $data = AuthorWebsite::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.authorwebsiteform', compact('data','img_arr'));
            }elseif($check == 9){
                $data = Proofreading::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.proofreadingform', compact('data','img_arr'));
            }elseif($check == 10){
                $data = BookCover::find($form_id);
                
                $img_arr = [];
        
                foreach($data->formfiles as $client_files){
                    $presignedUrl = $this->generatePresignedUrl($client_files->path);
                    $img_arr[$client_files->id] = $presignedUrl;
                }
                
                return view('admin.form.bookcoverform', compact('data','img_arr'));
            }
        // }else{
        //     return redirect()->back();
        // }
    }

    public function getFormManager($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
            if($check == 1){

            $logo_form = LogoForm::find($form_id);
            
            $img_arr = [];
                    
            foreach($logo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.logoform', compact('logo_form','img_arr'));
            }else if($check == 2){
            
            $web_form = WebForm::find($form_id);
            
            $img_arr = [];
                    
            foreach($web_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.webform', compact('web_form','img_arr'));
            }elseif($check == 3){
            
            $smm_form = SmmForm::find($form_id);
            
            $img_arr = [];
                    
            foreach($smm_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.smmform', compact('smm_form','img_arr'));
            }elseif($check == 4){
            
            $content_form = ContentWritingForm::find($form_id);
            
            $img_arr = [];
                    
            foreach($content_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.contentform', compact('content_form','img_arr'));
            }elseif($check == 5){
            
            $seo_form = SeoForm::find($form_id);
            
            $img_arr = [];
                    
            foreach($seo_form->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.seoform', compact('seo_form','img_arr'));
            }elseif($check == 6){
            
            $data = BookFormatting::find($form_id);
            
            $img_arr = [];
                    
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.bookformattingform', compact('data','img_arr'));
            }elseif($check == 7){
            
            $data = BookWriting::find($form_id);
            
            $img_arr = [];
                    
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.bookwritingform', compact('data','img_arr'));
            }elseif($check == 8){
            
            $data = AuthorWebsite::find($form_id);
            
            $img_arr = [];
                    
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.authorwebsiteform', compact('data','img_arr'));
            }elseif($check == 9){
            
            $data = Proofreading::find($form_id);
            
            $img_arr = [];
                    
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.proofreadingform', compact('data','img_arr'));
            }elseif($check == 10){
            
            $data = BookCover::find($form_id);
            
            $img_arr = [];
                    
            foreach($data->formfiles as $client_files){
                $presignedUrl = $this->generatePresignedUrl($client_files->path);
                $img_arr[$client_files->id] = $presignedUrl;
            }
            
            return view('manager.form.bookcoverform', compact('data','img_arr'));
            }
        // }else{
        //     return redirect()->back();
        // }
    }

    public function getFormSale($form_id, $check, $id){
        $project = Project::find($id);
        // if($project->user_id == Auth()->user()->id){
            
        if($check == 1){
        $logo_form = LogoForm::find($form_id);
        
        $img_arr = [];
                
        foreach($logo_form->formfiles as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
        }
        
        return view('sale.form.logoform', compact('logo_form','img_arr'));
        
        }else if($check == 2){
        $web_form = WebForm::find($form_id);
        
        $img_arr = [];
                
        foreach($web_form->formfiles as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
        }
        
        return view('sale.form.webform', compact('web_form','img_arr'));
        
        }elseif($check == 3){
        $smm_form = SmmForm::find($form_id);
        
        $img_arr = [];
                
        foreach($smm_form->formfiles as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
        }
        
        return view('sale.form.smmform', compact('smm_form','img_arr'));
        
        }elseif($check == 4){
        $content_form = ContentWritingForm::find($form_id);
        
        $img_arr = [];
                
        foreach($content_form->formfiles as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
        }
        
        return view('sale.form.contentform', compact('content_form','img_arr'));
        
        }elseif($check == 5){
        $seo_form = SeoForm::find($form_id);
        
        $img_arr = [];
                
        foreach($seo_form->formfiles as $client_files){
            $presignedUrl = $this->generatePresignedUrl($client_files->path);
            $img_arr[$client_files->id] = $presignedUrl;
        }
        
        return view('sale.form.seoform', compact('seo_form','img_arr'));
        
        }
        // }else{
        //     return redirect()->back();
        // }
    }

    public function changePassword(){
        return view('support.change-password');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Password Change Successfully.');
    }

    public function message(){
        $data = Project::where('user_id', Auth()->user()->id)->orderBy('id', 'desc')->get();
        $project = null;
        return view('support.message', compact('data', 'project'));
    }

    public function showMessage($id){
        $project = Project::find($id);
        $data = Project::where('user_id', Auth()->user()->id)->orderBy('id', 'desc')->get();
        if(Auth()->user()->id == $project->user_id){
            return view('support.message', compact('data', 'project'));
        }else{
            return redirect()->back();
        }
    }

    public function managerSendMessage(Request $request){
        $this->validate($request, [
            'message' => 'required'
        ]);
        $carbon = Carbon::now(new DateTimeZone('America/Los_Angeles'))->toDateTimeString();
        $task = Task::find($request->task_id);
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        if($task == null){
            $message->sender_id = $request->client_id;
            $message->client_id = $request->client_id;
        }else{
            $message->sender_id = $task->projects->client->id;
            $message->client_id = $task->projects->client->id;
        }
        $message->task_id = $request->task_id;
        $message->role_id = 6;
        $message->created_at = $carbon;
        $message->save();
        if($request->hasfile('images')){
            $i = 0;
            foreach($request->file('images') as $file)
            {
                
                 // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $task->projects->name . '- ChatFiles/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->task_id = $request->task_id;
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
            'title' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' has message on your task.',
            'body' => 'Please Login into your Dashboard to view it..'
        ];
        if($task != null){
            \Mail::to($task->projects->client->email)->send(new \App\Mail\ClientNotifyMail($details));
        }else{
            $client = User::find($request->client_id);
            \Mail::to($client->email)->send(new \App\Mail\ClientNotifyMail($details));
        }
        $task_id = 0;
        $project_id = 0;
        if($task != null){
            $task_id = $task->projects->id;
            $project_id = $task->projects->id;
        }

        $messageData = [
            'id' => Auth()->user()->id,
            'task_id' => $task_id,
            'project_id' => $project_id,
            'name' => Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'text' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' has send you a Message',
            'details' => Str::limit(filter_var($request->message, FILTER_SANITIZE_STRING), 40 ),
            'url' => '',
        ];
        if($task != null){
            $task->projects->client->notify(new MessageNotification($messageData));
        }else{
            $client = User::find($request->client_id);
            $client->notify(new MessageNotification($messageData));
        }
        // Message Notification sending to Admin
        $adminusers = User::where('is_employee', 2)->get();
        foreach($adminusers as $adminuser){
            Notification::send($adminuser, new MessageNotification($messageData));
        }
        return redirect()->back()->with('success', 'Message Send Successfully.')->with('data', 'message');;
    }

    public function sendMessage(Request $request){
        $this->validate($request, [
            'message' => 'required'
        ]);
        $carbon = Carbon::now(new DateTimeZone('America/Los_Angeles'))->toDateTimeString();
        $task = Task::find($request->task_id);
        // send Notification to customer
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        if($task == null){
            $message->sender_id = $request->client_id;
            $message->client_id = $request->client_id;
        }else{
            $message->sender_id = $task->projects->client->id;
            $message->client_id = $task->projects->client->id;
        }
        $message->role_id = 4;
        $message->created_at = $carbon;
        $message->save();

        if($request->hasfile('images')){
            $i = 0; 
            foreach($request->file('images') as $file)
            {
                $file_actual_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                 // WASABI CALL
                $disk = Storage::disk('wasabi');
                
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = Auth::user()->email . '/'. $task->projects->name . '- ChatFiles/' . strtolower(str_replace(' ', '-', $file_name)) . '_' . $i . '_' .time().'.'.$file->extension();
                
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
                            $client_file->name = $file_actual_name;
                            $client_file->path = $name;
                            $client_file->task_id = $request->task_id;
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
            'title' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' has message on your task.',
            'body' => 'Please Login into your Dashboard to view it..'
        ];
        if($task != null){
            \Mail::to($task->projects->client->email)->send(new \App\Mail\ClientNotifyMail($details));
        }else{
            $client = User::find($request->client_id);
            \Mail::to($client->email)->send(new \App\Mail\ClientNotifyMail($details));
        }
        $task_id = 0;
        $project_id = 0;
        if($task != null){
            $task_id = $task->projects->id;
            $project_id = $task->projects->id;
        }

        $messageData = [
            'id' => Auth()->user()->id,
            'task_id' => $task_id,
            'project_id' => $project_id,
            'name' => Auth()->user()->name . ' ' . Auth()->user()->last_name,
            'text' => Auth()->user()->name . ' ' . Auth()->user()->last_name . ' has send you a Message',
            'details' => Str::limit(filter_var($request->message, FILTER_SANITIZE_STRING), 40 ),
            'url' => '',
        ];
        if($task != null){
            $task->projects->client->notify(new MessageNotification($messageData));
        }else{
            $client = User::find($request->client_id);
            $client->notify(new MessageNotification($messageData));
        }
        // Message Notification sending to Admin
        $adminusers = User::where('is_employee', 2)->get();
        foreach($adminusers as $adminuser){
            Notification::send($adminuser, new MessageNotification($messageData));
        }
        return redirect()->back()->with('success', 'Message Send Successfully.')->with('data', 'message');;
    }

    public function sendMessageClient(Request $request){
        $this->validate($request, [
            'message' => 'required',
            'task_id' => 'required',
        ]);
        $task = Task::find($request->task_id);
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        $message->sender_id = 1;
        $message->task_id = $request->task_id;
        $message->client_id = Auth::user()->id;
        $message->save();
        $details = [
            'title' => $task->projects->client->name . ' ' . $task->projects->client->last_name . ' has message on your task.',
            'body' => 'Please Login into your Dashboard to view it..'
        ];
        \Mail::to($task->projects->added_by->email)->send(new \App\Mail\ClientNotifyMail($details));
        return response()->json(['success' => true, 'data' => $message->message, 'name' => Auth::user()->name . ' ' . Auth::user()->last_name, 'created_at' => $message->created_at->diffForHumans()]);
    }

    public function getMessageByManager(){
        
        $messages = Message::select('messages.*', DB::raw('MAX(messages.id) as max_id'))
                    ->join('users', 'users.id', '=', 'messages.client_id')
                    ->join('clients', 'users.client_id', '=', 'clients.id')
                    ->where('messages.role_id', 3)
                    ->whereIn('clients.brand_id', Auth()->user()->brand_list())
                    ->groupBy('messages.client_id')
                    ->orderBy('max_id', 'desc')
                    ->paginate(20);
        return view('manager.messageshow', compact('messages'));
    }

    public function updateSupportMessage(Request $request){
        $request->validate([
            'message_id' => 'required',
            'editmessage' => 'required',
        ]);
        $message = Message::find($request->message_id);
        if($message != null){
            $message->message = $request->editmessage;
            $message->save();
            return redirect()->back()->with('success', 'Message Updated Successfully.');
        }
        return redirect()->back()->with('success', 'Error Occured');
    }

    public function updateManagerMessage(Request $request){
        $request->validate([
            'message_id' => 'required',
            'editmessage' => 'required',
        ]);
        $message = Message::find($request->message_id);
        if($message != null){
            $message->message = $request->editmessage;
            $message->save();
            return redirect()->back()->with('success', 'Message Updated Successfully.');
        }
        return redirect()->back()->with('success', 'Error Occured');
    }

    public function updateAdminMessage(Request $request){
        $request->validate([
            'message_id' => 'required',
            'editmessage' => 'required',
        ]);
        $message = Message::find($request->message_id);
        if($message != null){
            $message->message = $request->editmessage;
            $message->save();
            return redirect()->back()->with('success', 'Message Updated Successfully.');
        }
        return redirect()->back()->with('success', 'Error Occured');
    }

    public function editMessageByManagerClientId($id){
        $message = Message::find($id);
        return response()->json(['success' => true, 'data' => $message]);
    }

    public function editMessageByAdminClientId($id){
        $message = Message::find($id);
        return response()->json(['success' => true, 'data' => $message]);
    }

    public function editMessageBySupportClientId($id){
        $message = Message::find($id);
        return response()->json(['success' => true, 'data' => $message]);
    }

    public function getMessageBySupportClientId($id, $name){
        $user = User::find($id);
        $messages = Message::where('client_id', $id)->orderBy('id', 'desc')->get();
        
        $msg_img_arr = [];
        
        foreach($messages as $message){
            if(count($message->sended_client_files) != 0){
                foreach($message->sended_client_files as $files){
                    $presignedUrl = $this->generatePresignedUrl($files->path);
                    $msg_img_arr[$files->id] = $presignedUrl;
                }
            }
        }
        
        return view('support.message.index', compact('messages', 'user','msg_img_arr'));
    }

    public function getMessageByManagerClientId($id, $name){
        $user = User::find($id);
        $messages = Message::where('client_id', $id)->orderBy('id', 'desc')->get();
        
        $msg_img_arr = [];
        
        foreach($messages as $message){
            if(count($message->sended_client_files) != 0){
                foreach($message->sended_client_files as $files){
                    $presignedUrl = $this->generatePresignedUrl($files->path);
                    $msg_img_arr[$files->id] = $presignedUrl;
                }
            }
        }
        
        return view('manager.message.index', compact('messages', 'user','msg_img_arr'));
    }

    public function getMessageBySupport(){
        $datas = Project::where('user_id', Auth()->user()->id)->orderBy('id', 'desc')->get();
        $message_array = [];
        foreach($datas as $data){
            $task_array_id = array();
            $task_id = 0;
            if(count($data->tasks) != 0){
                $task_id = $data->tasks[0]->id;
            }
            $message = Message::where('user_id', $data->client->id)->orWhere('sender_id', $data->client->id)->orderBy('id', 'desc')->first();
            if($message != null){
                $message_array[$data->client->id]['f_name'] = $data->client->name;
                $message_array[$data->client->id]['l_name'] = $data->client->last_name;
                $message_array[$data->client->id]['email'] = $data->client->email;
                $message_array[$data->client->id]['message'] = $message->message;
                $message_array[$data->client->id]['image'] = $data->client->image;
                $message_array[$data->client->id]['task_id'] = $task_id;
            }
        }
        
        return view('support.messageshow', compact('message_array'));
    }

    public function getMessageByAdmin(Request $request){
        // $filter = 0;
        // $message_array = [];
        // $datas = Project::orderBy('id', 'desc')->get();
        // if($request->message != ''){
        //     $task_id = 0;
        //     $messages = Message::where('message', 'like', '%' . $request->message . '%')->orderBy('id', 'desc')->get();
        //     foreach($messages as $message){
        //         if($message->user_name != null){
        //             $message_array[$message->user_name->id]['f_name'] = $message->user_name->name;
        //             $message_array[$message->user_name->id]['l_name'] = $message->user_name->last_name;
        //             $message_array[$message->user_name->id]['email'] = $message->user_name->email;
        //             $message_array[$message->user_name->id]['message'] = $message->message;
        //             $message_array[$message->user_name->id]['image'] = $message->user_name->image;
        //             $projects = Project::where('client_id', $message->user_name->id)->get();
        //             foreach($projects as $project){
        //                 foreach($project->tasks as $key => $tasks){
        //                     $message_array[$message->user_name->id]['task_id'][$key] = $tasks->id;
        //                 }
        //             }
        //         }
        //     }

        // }else{
        //     $filter = 1;
        //     foreach($datas as $data){
        //         $task_array_id = array();
        //         $task_id = 0;
        //         if(count($data->tasks) != 0){
        //             $task_id = $data->tasks[0]->id;
        //         }
        //         $message = Message::where('user_id', $data->client->id)->orWhere('sender_id', $data->client->id)->orderBy('id', 'desc')->first();
        //         if($message != null){
        //             $message_array[$data->client->id]['f_name'] = $data->client->name;
        //             $message_array[$data->client->id]['l_name'] = $data->client->last_name;
        //             $message_array[$data->client->id]['email'] = $data->client->email;
        //             $message_array[$data->client->id]['message'] = $message->message;
        //             $message_array[$data->client->id]['image'] = $data->client->image;
        //             $projects = Project::where('client_id', $data->client->id)->get();
        //             foreach($projects as $project){
        //                 foreach($project->tasks as $key => $tasks){
        //                     $message_array[$data->client->id]['task_id'][$key] = $tasks->id;
        //                 }
        //             }
        //         }
        //     }
        // }
        $filter = 0;
        $brand_array = [];
        $brands = DB::table('brands')->select('id', 'name')->get();
        foreach($brands as $key => $brand){
            array_push($brand_array, $brand->id);
        }
        $message_array = [];
        $data = User::where('is_employee', 3)->where('client_id', '!=', 0);
        if($request->brand != null){
            $get_brand = $request->brand;
            $data = $data->whereHas('client', function ($query) use ($get_brand) {
                return $query->where('brand_id', $get_brand);
            });
        }else{
            $data = $data->whereHas('client', function ($query) use ($brand_array) {
                return $query->whereIn('brand_id', $brand_array);
            });
        }
        if($request->message != null){
            $message = $request->message;
            $data = $data->whereHas('messages', function ($query) use ($message) {
                return $query->where('message', 'like', '%' . $message . '%');
            });
        }
        $data = $data->orderBy('id', 'desc')->paginate(20);
        return view('admin.messageshow', compact('message_array', 'brands', 'filter', 'data'));
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
