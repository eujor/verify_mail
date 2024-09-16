<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use TJVB\MailCatchall\Facades\MailCatchall;
class ValidationController extends Controller
{
    //
    //Todo create a method called validateEmail, it uses Request and accepts email address only
    // create a results view
    //create a route

    /**
     * Validate an email address and store/update based on the findings.
     *
     * @param Request $request
     * @return View
     */
    public function validateEmail(Request $request)
    {
        // Add logic to validate that the input provided is an email
        // and check for the other params e.g format, domain etc
        // Ultimately store/update based on your findings
        // and return to a results view

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Extract the validated email from the request
        $email = $request->input('email');

        // Save the email to the database
        $validation = new Validation;
        $validation->email = $email;
        $validation->save();

        // Perform additional checks, e.g., domain validation
        // Get the domain from the email address
        $domain = substr(strrchr($validation->email, "@"), 1);

        // Check if the domain has valid DNS records
        $domainStatus =  checkdnsrr($domain);

        // Save domain status
        $validation->domain = $domainStatus;

        // Continue with other checks and finally save the validation instance
        // $validation->save();

        // Return to the home view with the validation instance
        return view('home', compact('validation'));
    }


    // Method To validate email via api


    public function validateEmailViaApi(Request $request)
    {
        try {
            // Validate that the input provided is an email
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            // Extract the validated email from the request
            $email = $request->input('email');

            // Get the domain from the email address
            $domain = substr(strrchr($email, "@"), 1);

            // Check if the domain has valid DNS records
            $domainStatus = checkdnsrr($domain, "MX");

            // Check if the email is from a generic domain
            $generic = $this->isGenericEmail($domain);

            // Check if the email is blocked
            $isBlocked = !$this->isEmailBlocked($email);

            // Calculate the results based on the current attribute values
            $trueCount = (1) // Assuming format validation is always true
                       + ($domainStatus ? 1 : 0)
                       + ($generic ? 1 : 0)
                       + ($isBlocked ? 1 : 0);

            // Calculate the percentage
            $percentage = $trueCount * 25; // Each true attribute contributes 25%

            // Determine the validation status based on conditions
            $validationStatus = $domainStatus && $generic && $isBlocked;

            // Prepare response data
            $responseData = [
                'email' => $email,
                'format' => true, // Assuming format validation is true
                'domain' => $domainStatus,
                'generic' => $generic,
                'noblock' => $isBlocked,
                'results' => $percentage,
                'status' => $validationStatus ? 'valid' : 'invalid',
            ];

            // Return a JSON response with the validation data
            return response()->json(['validation' => $responseData]);

        } catch (\Exception $e) {
            // Log the exception and return a JSON error response
            \Log::error('Validation error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    // Method to check for generic emails
    private function isGenericEmail($domain): bool
    {
        $genericDomains = [
            'gmail.com',
            'yahoo.com',
            'hotmail.com',
        ];

        return in_array($domain, $genericDomains);
    }

    // Method to check for blocked or spam emails
    private function isEmailBlocked($email)
    {
        $blockedEmails = ['blocked@example.com', 'spam@example.com'];

        return in_array($email, $blockedEmails);
    }
    public function validateBatchEmails ()
    {
        return view('batchvalidation');
    }

}
