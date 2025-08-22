<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Generation | Projectwall</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body class="container">
    <table class="email" width="100%" border="0" cellspacing="0" cellpadding="20" style="border-bottom-width: 10px; border-bottom-style: solid; border-bottom-color: #0C99BA">
        <tr>
            <td class="header" style="background-color: #0C99BA;">
                <table border="0" style="color: #fff; width: 900px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif;" cellspacing="0" width="900">
                    <tr>
                        <td style="text-align: center !important" colspan="2">
                            <h1>Lead Assigned</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="content" style="background-color: #eee;">
                <table border="0" style="color: #444; width: 600px; margin: 0 auto; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #ddd; font-family: Arial, Helvetica, sans-serif; line-height: 1.4;" cellpadding="15" cellspacing="1" width="600">
                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Brand:</strong></td>
                        <td>{{ $data['brand_name'] }}</td>
                    </tr>
                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Assigned By:</strong></td>
                        <td>{{ $data['assigned_by'] }}</td>
                    </tr>
                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Assigned To:</strong></td>
                        <td>{{ $data['assigned_to'] }}</td>
                    </tr>
                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Assigned On:</strong></td>
                        <td>{{ $data['assigned_on'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>