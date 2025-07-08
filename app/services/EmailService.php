<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/Email_Config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;
    private $fromEmail;
    private $fromName;
    
    public function __construct()
    {
        $this->fromEmail = FROM_EMAIL;
        $this->fromName = FROM_NAME;
        $this->setupMailer();
    }
    
    /**
     * Setup PHPMailer with Gmail SMTP configuration
     */
    private function setupMailer()
    {
        $this->mailer = new PHPMailer(true);
        
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_SECURE;
            $this->mailer->Port = SMTP_PORT;
            
            $this->mailer->SMTPDebug = SMTP_DEBUG;
            
            $this->mailer->setFrom($this->fromEmail, $this->fromName);
            
        } catch (Exception $e) {
            error_log("Email setup error: " . $e->getMessage());
        }
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($userEmail, $userName, $resetToken)
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            

            // THIS IS TEMPORARY BTW, we need to change this on production
            $resetLink = "http://localhost" . BASE_URL . "/reset-password?token=" . $resetToken;
            
            $this->mailer->addAddress($userEmail, $userName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Password Reset Request - North Hill Dental';
            $this->mailer->Body = $this->getPasswordResetEmailTemplate($userName, $resetLink);
            $this->mailer->AltBody = $this->getPasswordResetEmailTextTemplate($userName, $resetLink);
            
            $result = $this->mailer->send();
            
            if ($result) {
                error_log("Password reset email sent successfully to: " . $userEmail);
                return true;
            } else {
                error_log("Failed to send password reset email to: " . $userEmail);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get HTML email template for password reset
     */
    private function getPasswordResetEmailTemplate($userName, $resetLink)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset Request</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                .logo { text-align: center; margin-bottom: 30px; }
                .logo h1 { color: #143e79; font-size: 28px; margin: 0; }
                .content { margin-bottom: 30px; }
                .button { display: inline-block; background-color: #143e79; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                .button:hover { background-color: #0f2d5c; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 20px; margin-top: 30px; }
                .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='logo'>
                    <h1>North Hill Dental</h1>
                </div>
                
                <div class='content'>
                    <h2>Password Reset Request</h2>
                    <p>Hello " . htmlspecialchars($userName) . ",</p>
                    
                    <p>We received a request to reset your password for your North Hill Dental account. If you made this request, please click the button below to reset your password:</p>
                    
                    <div style='text-align: center;'>
                        <a href='" . htmlspecialchars($resetLink) . "' class='button'>Reset Password</a>
                    </div>
                    
                    <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
                    <p style='word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace;'>" . htmlspecialchars($resetLink) . "</p>
                    
                    <div class='warning'>
                        <strong>Important:</strong> This link will expire in 1 hour for security reasons. If you didn't request this password reset, please ignore this email or contact us if you have concerns.
                    </div>
                </div>
                
                <div class='footer'>
                    <p>This email was sent by North Hill Dental<br>
                    Barangay Panubigan, Canlaon City, Philippines<br>
                    Phone: 0927 508 6540</p>
                    
                    <p>If you need assistance, please contact us at matt.cabarrubias@gmail.com</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get plain text email template for password reset
     */
    private function getPasswordResetEmailTextTemplate($userName, $resetLink)
    {
        return "
Password Reset Request - North Hill Dental

Hello " . $userName . ",

We received a request to reset your password for your North Hill Dental account. If you made this request, please visit the following link to reset your password:

" . $resetLink . "

This link will expire in 1 hour for security reasons.

If you didn't request this password reset, please ignore this email or contact us if you have concerns.

---
North Hill Dental
Barangay Panubigan, Canlaon City, Philippines
Phone: 0927 508 6540
Email: matt.cabarrubias@gmail.com
        ";
    }
    
    /**
     * Get detailed error information
     */
    public function getLastError()
    {
        return $this->mailer->ErrorInfo;
    }
} 