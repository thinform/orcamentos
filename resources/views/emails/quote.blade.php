<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento #{{ $quote->quote_number }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Olá {{ $quote->client->name }},</h2>
        
        <p>Segue em anexo o orçamento #{{ $quote->quote_number }}.</p>
        
        <div style="background-color: #f7fafc; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Detalhes do Orçamento:</strong></p>
            <ul style="list-style: none; padding: 0;">
                <li>Data: {{ $quote->created_at->format('d/m/Y') }}</li>
                <li>Validade: {{ $quote->validity }} dias</li>
                <li>Total: R$ {{ number_format($quote->total, 2, ',', '.') }}</li>
            </ul>
        </div>

        <p>Você pode visualizar o orçamento completo no PDF em anexo.</p>
        
        <p>Se precisar de mais informações ou tiver alguma dúvida, entre em contato conosco.</p>
        
        <p>Atenciosamente,<br>Equipe de Orçamentos</p>
    </div>
</body>
</html> 