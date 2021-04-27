<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplatePageSlug extends Model
{
    use HasFactory;
    
    public function template_page()
    {
        return $this->belongsTo(TemplatePage::class,'template_page_id');
    }
}
