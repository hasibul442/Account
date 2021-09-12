<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = 'positions';
    protected $fillable = ['company_id','salary','name'];
    public function companyname()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}