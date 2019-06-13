<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class MailerController extends Controller {
    public function sendmail (Request $request) {
        if($request->isMethod('POST')) {
            $mail = new PHPMailer(true);
            try {
                $receiver = $request->input('receiver');
                $subject  = $request->input('subject');
                $body     = $request->input('message');

				// Server settings
	    	    $mail->SMTPDebug = 0;                                // Enable verbose debug output
				$mail->isSMTP();                                     // Set mailer to use SMTP
				$mail->Host = env('MAIL_HOST');						  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = env('MAIL_USERNAME');             // SMTP username
				$mail->Password = env('MAIL_PASSWORD');              // SMTP password
				$mail->SMTPSecure = env('MAIL_ENCRYPTION');	                           // Enable TLS encryption, `ssl` also accepted
				$mail->Port = env('MAIL_PORT');                                    // TCP port to connect to

				//Recipients
                $mail->setFrom(env('MAIL_USERNAME') , 'No-Reply');
				$mail->addAddress($receiver, '');
				$mail->addReplyTo(env('MAIL_USERNAME'), 'No-Reply');

				//Content
				$mail->isHTML(true);																// Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body    = $body;					// message

				$mail->send();
				return back()->with('success','Message has been sent!');
			} catch (Exception $e) {
                throw $e;
				return back()->with('error','Message could not be sent.');
			}
        }
        return view('email');
    }
}
