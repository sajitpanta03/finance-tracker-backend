<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
   protected $fillable=[
    'amount',
    'date_spend',
    'description',
    'user_id',
    'category_id',
    'budget_id'
   ];

   public function users(){
    return $this->belongsTo(User::class, 'user_id');
   }

   public function expense_categories(){
    return $this->belongsTo(Expense_category::class, 'category_id');
   }

   public function budgets(){
    return $this->belongsTo( Budget::class, 'budget_id');
   }
}
