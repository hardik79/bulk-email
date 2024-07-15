<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function saveCredentials(Request $request)
    {
        $request->validate([
            'smtpServer' => 'required|string',
            'smtpPort' => 'required|integer',
            'smtpUser' => 'required|string',
            'smtpPassword' => 'required|string',
        ]);

        session([
            'smtpServer' => $request->smtpServer,
            'smtpPort' => $request->smtpPort,
            'smtpUser' => $request->smtpUser,
            'smtpPassword' => $request->smtpPassword,
        ]);

        return response()->json(['success' => true]);
    }
}
