<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'repeatCount' => 'nullable|integer|min:1'
        ]);

        $smtpServer = session('smtpServer');
        $smtpPort = session('smtpPort');
        $smtpUser = session('smtpUser');
        $smtpPassword = session('smtpPassword');

        if (!$smtpServer || !$smtpPort || !$smtpUser || !$smtpPassword) {
            return redirect()->back()->withErrors(['error' => 'SMTP credentials are not set.']);
        }

        $config = [
            'transport' => 'smtp',
            'host' => $smtpServer,
            'port' => $smtpPort,
            'encryption' => 'tls',
            'username' => $smtpUser,
            'password' => $smtpPassword,
            'timeout' => null,
            'auth_mode' => null,
        ];

        config(['mail.mailers.smtp' => $config]);
        config(['mail.default' => 'smtp']);

        $emailsJson = $request->input('email');
        $emailsArray = json_decode($emailsJson, true);

        $emails = [];
        foreach ($emailsArray as $emailObject) {
            if (isset($emailObject['value']) && filter_var($emailObject['value'], FILTER_VALIDATE_EMAIL)) {
                $emails[] = $emailObject['value'];
            }
        }

        if (empty($emails)) {
            return redirect()->back()->withErrors(['error' => 'No valid email addresses provided.']);
        }

        $details = [
            'title' => $request->subject,
            'body' => $request->body
        ];

        $repeatCount = $request->input('repeatCount') ? (int) $request->input('repeatCount') : 1;

        try {
            foreach ($emails as $email) {
                for ($i = 0; $i < $repeatCount; $i++) {
                    Mail::to($email)->send(new SendMail($details));
                }
            }
            return back()->with('success', 'Emails have been sent.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to send emails: ' . $e->getMessage()]);
        }
    }
}
