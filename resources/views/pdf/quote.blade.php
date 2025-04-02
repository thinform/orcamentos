<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento #{{ $quote->quote_number }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000000;
            max-width: 100%;
            margin: 0;
            padding: 10px;
            line-height: 1.2;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            @if($background_image)
            background-image: url('{{ str_replace('\\', '/', $background_image) }}');
            background-position: top left;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.1;
            @endif
            z-index: -1;
        }
        .content {
            position: relative;
            z-index: 1;
            background-color: transparent;
        }
        .header {
            position: relative;
            margin-bottom: 15px;
            width: 100%;
            display: table;
        }
        .company-info {
            float: left;
            width: 65%;
        }
        .company-info h1 {
            font-size: 12pt;
            margin: 0 0 8px 0;
        }
        .company-info p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .quote-number-box {
            float: right;
            width: 180px;
            border: 1px solid #000;
            background-color: #f5f5f5;
            padding: 6px;
            text-align: center;
            margin-top: 5px;
        }
        .quote-number-box h2 {
            margin: 0;
            font-size: 10pt;
        }
        .clear {
            clear: both;
        }
        .client-info {
            border: 1px solid #d3d3d3;
            padding: 8px;
            margin: 15px 0;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .client-info p {
            margin: 2px 0;
            font-weight: bold;
            font-size: 8pt;
        }
        h3 {
            font-size: 10pt;
            margin: 10px 0;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .products-table th, 
        .products-table td {
            border: 1px solid #d3d3d3;
            padding: 4px;
            height: 22px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
        }
        .products-table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        .products-table .col-cod { width: 10%; }
        .products-table .col-desc { width: 50%; }
        .products-table .col-qtd { width: 8%; }
        .products-table .col-und { width: 8%; }
        .products-table .col-total { width: 14%; }
        
        .totals {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .totals tr td {
            padding: 4px 8px;
            text-align: right;
            font-size: 8pt;
        }
        .totals .label {
            text-align: left;
            width: 75%;
        }
        .totals .payment-card {
            background-color: #ffffff;
        }
        .totals .discount {
            color: #ff0000;
        }
        .totals .subtotal {
            background-color: #ffe4e1;
            font-style: italic;
            color: #444;
        }
        .totals .shipping {
            background-color: #e6f3ff;
        }
        .totals .total-cash {
            background-color: #0066cc;
            color: #ffffff;
            font-weight: bold;
            font-size: 9pt;
        }
        .observations {
            border: 1px solid #0066cc;
            padding: 8px;
            margin: 15px 0;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .observations ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .observations li {
            margin: 4px 0;
            font-size: 8pt;
        }
        .observations li:before {
            content: "* ";
            color: #0066cc;
        }
        .footer {
            margin-top: 20px;
            font-size: 8pt;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 30px 0 3px 0;
        }
        .signature-name {
            font-weight: bold;
            margin: 2px 0;
        }
        .signature-email {
            font-size: 8pt;
            color: #666;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <div class="company-info">
                <h1>THINFORMA - CNPJ: 54.178.539/0001-64</h1>
                <p>Rua Tenerife, Nº 407 - JARDIM ATLÂNTICO</p>
                <p>31.550-220, BELO HORIZONTE, MG - WHATSAPP: (31) 99243-1019 - SITE: http://www.thinforma.com.br</p>
            </div>
            <div class="quote-number-box">
                <h2>ORÇAMENTO Nº.<br>{{ $quote->quote_number }}</h2>
            </div>
            <div class="clear"></div>
        </div>

        <div class="client-info">
            <p>{{ $quote->client->name }} - {{ $quote->client->phone }} - {{ $quote->client->email }}</p>
            <p>{{ $quote->client->address }}, {{ $quote->client->number }} - {{ $quote->client->district }}</p>
            <p>{{ $quote->client->zip_code }} - {{ $quote->client->city }}, {{ $quote->client->state }}</p>
            <p>CPF/CNPJ: {{ $quote->client->cpf_cnpj }}</p>
        </div>

        <h3>DETALHAMENTO DO PEDIDO</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th class="col-cod">COD</th>
                    <th class="col-desc">DESCRIÇÃO DO PRODUTO</th>
                    <th class="col-qtd">QTD</th>
                    <th class="col-und">UND</th>
                    <th class="col-total">VL. TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>PÇ</td>
                    <td>R$ {{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                @for ($i = count($quote->products); $i < 25; $i++)
                <tr>
                    <td>COD</td>
                    <td>DESCRIÇÃO DO PRODUTO</td>
                    <td>QTD</td>
                    <td>UM</td>
                    <td>DEIXAR EM BRANCO</td>
                </tr>
                @endfor
            </tbody>
        </table>

        <table class="totals">
            <tr class="payment-card">
                <td class="label">PAGAMENTO CARTÃO:</td>
                <td>10x de R$ {{ number_format($quote->monthly_installment, 2, ',', '.') }}</td>
            </tr>
            <tr class="discount">
                <td class="label">DESCONTO:</td>
                <td>R$ {{ number_format($quote->total * 0.18, 2, ',', '.') }}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">TOTAL PARCIAL (SEM FRETE):</td>
                <td>R$ {{ number_format($quote->total, 2, ',', '.') }}</td>
            </tr>
            <tr class="shipping">
                <td class="label">CUSTOS COM TRANSPORTE (FRETE) - FAVOR SOLICITAR COTAÇÃO:</td>
                <td>R$ {{ number_format($quote->shipping_cost, 2, ',', '.') }}</td>
            </tr>
            <tr class="total-cash">
                <td class="label">PAGAMENTO C/ DESCONTO À VISTA:</td>
                <td>R$ {{ number_format($quote->total - ($quote->total * 0.18), 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="observations">
            <ul>
                <li>O Orçamento tem validade 48 horas;</li>
                <li>Máquinas Personalizadas não têm direito a Arrependimento;</li>
                <li>Somos Empresa com CNPJ devidamente regularizada;</li>
                <li>Todas as peças tem Garantia Oficial e Procedência;</li>
            </ul>
        </div>

        <div class="footer">
            <p>Belo Horizonte, {{ now()->translatedFormat('d \d\e F \d\e Y') }}.</p>
            <div class="signature-line"></div>
            <p class="signature-name">Tonny Heringer</p>
            <p class="signature-email">thinform@gmail.com</p>
        </div>
    </div>
</body>
</html> 