<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function myCourse(){
        $trainer = Batch::with('course')->where('teacher_id', auth()->user()->id)->get();
        return view('trainer.my_course', compact('trainer'));
    }
}
