<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenaiTemplateFilter extends Model
{
    use HasFactory;

    protected $table = 'openai_templates_filters';

    protected $guarded = [];

    public function filter():BelongsTo 
    {
        return $this->belongsTo(OpenaiGeneratorFilter::class, 'filter_id');
    }

    public function template() {

        return $this->belongsTo(OpenAIGenerator::class, 'template_id');

    }
}
