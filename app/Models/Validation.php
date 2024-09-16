<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'results',
        'format',
        'catchall',
        'domain',
        'noblock',
        'nogeneric',
        'status',
    ];

    // Mutator for the 'results' attribute
    public function setResultsAttribute($value)
    {
        // Calculate the percentage of true boolean values
        $trueCount = ($this->attributes['format'] ?? false ? 1 : 0)
                   + ($this->attributes['catchall'] ?? false ? 1 : 0)
                   + ($this->attributes['domain'] ?? false ? 1 : 0)
                   + ($this->attributes['noblock'] ?? false ? 1 : 0)
                   + ($this->attributes['nogeneric'] ?? false ? 1 : 0);

        // Calculate the percentage
        $percentage = $trueCount * 25; // Since there are 4 boolean attributes, each true attribute contributes 25%

        // Cap the percentage at 100
        $this->attributes['results'] = min($percentage, 100);
    }
}

