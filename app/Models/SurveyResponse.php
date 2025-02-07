<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'q1', 'q2', 'q3', 'q4', 'q5',
        'q6', 'q7', 'q8', 'q9', 'q10',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

?>
