<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenaiGeneratorFilter extends Model
{
    use HasFactory;
    protected $table = 'openai_filters';
    public $timestamps = false;

    public function templates():HasMany 
    {

        return $this->hasMany(OpenAIGenerator::class, 'filters', 'name');

    }
    public function checkFilter($filter_id) {

        $check = OpenaiTemplateFilter::where('filter_id', $filter_id)->first();

        if ($check) {
            return true;
        }else {
            return false;
        }

    }
}
