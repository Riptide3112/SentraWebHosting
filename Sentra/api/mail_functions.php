<?php
# includes/mail_functions.php
function sendPasswordResetEmail($to, $firstName, $resetLink) {
    $subject = "Resetare parolă - Sentra Hosting";
    
    $message = "
    <!DOCTYPE html>
    <html lang='ro'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Resetare Parolă</title>
        <style>
            body { 
                font-family: 'Inter', Arial, sans-serif; 
                background-color: #f8fafc; 
                margin: 0; 
                padding: 0; 
                color: #334155;
            }
            .container { 
                max-width: 600px; 
                margin: 0 auto; 
                background: white; 
                border-radius: 12px; 
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            .header { 
                background: linear-gradient(135deg, #00BFB2 0%, #059669 100%); 
                padding: 30px 20px; 
                color: white; 
                text-align: center; 
            }
            .logo { 
                font-size: 28px; 
                font-weight: bold; 
                margin-bottom: 10px;
            }
            .content { 
                padding: 30px; 
                line-height: 1.6; 
            }
            .button { 
                display: inline-block; 
                background: linear-gradient(135deg, #00BFB2 0%, #059669 100%); 
                color: white; 
                padding: 14px 32px; 
                text-decoration: none; 
                border-radius: 8px; 
                font-weight: 600; 
                margin: 20px 0; 
                text-align: center;
                box-shadow: 0 4px 15px rgba(0, 191, 178, 0.3);
            }
            .footer { 
                background: #f1f5f9; 
                padding: 20px; 
                text-align: center; 
                color: #64748b; 
                font-size: 14px;
                border-top: 1px solid #e2e8f0;
            }
            .code-box {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                padding: 15px;
                margin: 15px 0;
                word-break: break-all;
                font-family: monospace;
                color: #475569;
            }
            .warning {
                background: #fef3c7;
                border: 1px solid #f59e0b;
                border-radius: 6px;
                padding: 12px;
                margin: 15px 0;
                color: #92400e;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>🔐 Sentra</div>
                <h2>Resetare Parolă</h2>
            </div>
            
            <div class='content'>
                <p>Salut <strong>{$firstName}</strong>,</p>
                
                <p>Ai solicitat resetarea parolei pentru contul tău de hosting <strong>Sentra</strong>.</p>
                
                <p style='text-align: center;'>
                    <a href='{$resetLink}' class='button'>Resetează Parola</a>
                </p>
                
                <p>Dacă butonul nu funcționează, copiază și lipește următorul link în browser:</p>
                
                <div class='code-box'>{$resetLink}</div>
                
                <div class='warning'>
                    <strong>⚠️ Important:</strong> 
                    <ul>
                        <li>Acest link va expira în <strong>15 minute</strong></li>
                        <li>Link-ul poate fi folosit o singură dată</li>
                        <li>Dacă nu ai solicitat resetarea parolei, poți ignora acest email</li>
                    </ul>
                </div>
                
                <p>Pentru orice problemă, nu ezita să ne contactezi la <a href='mailto:support@sentra.ro'>support@sentra.ro</a>.</p>
                
                <p>Cu respect,<br>Echipa <strong>Sentra Hosting</strong></p>
            </div>
            
            <div class='footer'>
                <p>© " . date('Y') . " Sentra Hosting. Toate drepturile rezervate.</p>
                <p>Acest email a fost trimis automat. Te rugăm să nu răspunzi.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Headers pentru email HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Sentra Hosting <noreply@sentra.ro>" . "\r\n";
    $headers .= "Reply-To: support@sentra.ro" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Încearcă să trimiți email-ul
    try {
        $result = mail($to, $subject, $message, $headers);
        return $result;
    } catch (Exception $e) {
        error_log("Eroare la trimiterea email-ului: " . $e->getMessage());
        return false;
    }
}
?>