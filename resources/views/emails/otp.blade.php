<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Código de Verificação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h1 {
            color: #007BFF;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .otp-container {
            background-color: #f0f0f0;
            border-radius: 5px;
            margin: 20px 0;
            padding: 20px;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #007BFF;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .note {
            font-size: 14px;
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 3px solid #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
            <h1>Código de Verificação</h1>
        </div>

        <div class="content">
            <p>Olá,</p>

            <p>Recebemos uma solicitação para redefinir a senha da sua conta. Para verificar sua identidade, use o código abaixo:</p>

            <div class="otp-container">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="note">
                <p><strong>Atenção:</strong> Este código é válido por 10 minutos. Após esse período, você precisará solicitar um novo código.</p>
            </div>

            <p>Se você não solicitou esta redefinição de senha, ignore este email. Sua conta permanecerá segura.</p>

            <p>Atenciosamente,<br>Equipe de Suporte</p>
        </div>

        <div class="footer">
            <p>Este é um email automático. Por favor, não responda diretamente a esta mensagem.</p>
            <p>&copy; {{ date('Y') }} Sua Empresa. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>