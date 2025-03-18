<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redefinição de Senha</title>
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
        .button {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
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
            <h1>Redefinição de Senha</h1>
        </div>

        <div class="content">
            <p>Olá,</p>

            <p>Recebemos uma solicitação para redefinir a senha da sua conta. Para prosseguir com a redefinição, clique no botão abaixo:</p>

            <div style="text-align: center;">
                <a href="{{ env('APP_MOBILE_URL') }}/reset-password?token={{ $token }}&email={{ $email }}" class="button">Redefinir Senha</a>
            </div>

            <p>Ou copie e cole o seguinte link no seu navegador:</p>

            <p style="word-break: break-all;">
                {{ env('APP_MOBILE_URL') }}/reset-password?token={{ $token }}&email={{ $email }}
            </p>

            <div class="note">
                <p><strong>Nota:</strong> Este link é válido por 24 horas. Após esse período, você precisará solicitar uma nova redefinição de senha.</p>
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