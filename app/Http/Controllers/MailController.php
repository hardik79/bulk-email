<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

class MailController extends Controller
{
    public function showForm()
    {
        return view('emails.sendMailForm');
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $emailsJson = $request->input('email');
        $emailsArray = json_decode($emailsJson, true);

        $emails = [];
        foreach ($emailsArray as $emailObject) {
            if (isset($emailObject['value']) && filter_var($emailObject['value'], FILTER_VALIDATE_EMAIL)) {
                $emails[] = $emailObject['value'];
            }
        }

        $details = [
            'title' => $request->subject,
            'body' => $request->body
        ];

        foreach ($emails as $email) {
            Mail::to($email)->send(new SendMail($details));
        }

        return back()->with('success', 'Emails have been sent.');
    }
}
