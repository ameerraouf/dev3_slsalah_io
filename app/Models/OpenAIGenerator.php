<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenAIGenerator extends Model
{
    use HasFactory;
    protected $table = 'openai';

    protected $guarded = [];


    public function checkFilter($filter_id, $template_id) {

        $check = OpenaiTemplateFilter::where('filter_id', $filter_id)->where('template_id' , $template_id)->first();

        if ($check) {
            return true;
        }else {
            return false;
        }

    }
    public function getFilter() {

        return $this->hasMany(OpenaiTemplateFilter::class, 'template_id');

    }
    
}
