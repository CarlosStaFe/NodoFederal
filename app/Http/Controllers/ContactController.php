<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ContactController extends Controller
{
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        try {
            Mail::raw($data['message'], function ($mail) use ($data) {
                $mail->to(config('mail.contact_address'))
                    ->subject($data['subject'])
                    ->replyTo($data['email'], $data['name']);
            });
        } catch (Throwable $exception) {
            report($exception);

            return response('No se pudo enviar el mensaje. Inténtelo nuevamente más tarde.', 500);
        }

        return response('OK');
    }
}
