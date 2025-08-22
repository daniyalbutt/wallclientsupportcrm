<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSettings extends Model
{
    use HasFactory;

    protected $fillable = ['empty_task','logoForm','webForm','smmForm','contentForm','seoForm','formattingForm','writingForm','authorForm','proofreadingForm','coverForm'];
}