<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <style>
        /* Make sure the body takes up the full width */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

         /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Table cell styling */
        td {
            text-align: center;
            padding: 10px;
            width: 25%; /* 4 columns, each taking up 25% of the width */
            vertical-align: top;
        }

        /* QR code image styling */
        td img {
            max-width: 100%;
            height: auto;
        }

        /* Optional: Add some padding around the QR codes */
        td p {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Asset Management</h1>
    <table>
        <tr>
            @foreach ($qrCodes as $index => $data)
                <td>
                    <div class="qr-code-item">
                        <img src="data:image/png;base64,{{ base64_encode($data['qrCode']) }}" alt="QR Code"><br>
                        <b><u style="font-size: 10px;">{{ $data['product']->id_asset }}</u></b><br>
                        <b style="font-size: 10px;">{{ $data['product']->type_device }}</b>
                    </div>
                </td>

                <!-- After every 4th QR code, break the row and start a new one -->
                @if (($index + 1) % 4 == 0 && $index + 1 < count($qrCodes))
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>
