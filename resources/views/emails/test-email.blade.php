<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - E-Masjid System</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 2px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon span {
            display: inline-block;
            width: 60px;
            height: 60px;
            background-color: #10b981;
            color: white;
            border-radius: 50%;
            line-height: 60px;
            font-size: 24px;
        }
        .message {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 2px;
            border-left: 4px solid #10b981;
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table th,
        .info-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table th {
            background-color: #f3f4f6;
            font-weight: 600;
            width: 30%;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Test Email Berjaya</h1>
            <p>Konfigurasi SMTP E-Masjid System</p>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <span>✓</span>
            </div>
            
            <h2 style="color: #1f2937; margin-bottom: 15px;">Tahniah!</h2>
            
            <div class="message">
                <p style="margin: 0;">{{ $testMessage }}</p>
            </div>
            
            <table class="info-table">
                <tr>
                    <th>Dari</th>
                    <td>{{ $fromName }}</td>
                </tr>
                <tr>
                    <th>Masa Dihantar</th>
                    <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span style="color: #10b981; font-weight: 600;">✓ Berjaya</span></td>
                </tr>
            </table>
            
            <p style="margin-top: 30px; color: #6b7280; font-size: 11px;">
                <strong>Nota:</strong> Email ini dihantar secara automatik untuk menguji konfigurasi SMTP. 
                Jika anda menerima email ini, bermakna sistem email berfungsi dengan baik.
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} E-Masjid System. Semua hak terpelihara.</p>
            <p>Sistem Pengurusan Masjid Digital</p>
        </div>
    </div>
</body>
</html>
