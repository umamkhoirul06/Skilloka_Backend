<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    use HasUuids;

    protected $fillable = [

        'course_id',

        'start_date',

        'end_date',

        'days_of_week',

        'daily_start',

        'daily_end',

        'max_capacity'

    ];


    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',

        'daily_start' => 'datetime:H:i',

        'daily_end' => 'datetime:H:i',

    ];


    /*
    relasi ke course
    */
    public function course()
    {

        return $this->belongsTo(Course::class);

    }


    /*
    relasi ke booking
    */
    public function bookings()
    {

        return $this->hasMany(Booking::class);

    }

}