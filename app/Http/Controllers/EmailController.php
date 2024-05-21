<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(int $id)
    {
        $user = User::find($id);

        Mail::to('your_email@gmail.com')->send(new VerificationMail($user));
    }
}
