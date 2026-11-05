<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function lines()
    {
        return $this->hasMany(BudgetLine::class, 'budget_category_id');
    }
}