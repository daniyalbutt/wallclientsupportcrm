<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $details['invoice_number'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f9f9f9;">
    <div style="max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <table style="width: 100%; background: #fff; padding: 20px 0 20px 0; border-radius: 8px;">
            <!-- Header Section -->
            <tr>
                <td style="text-align: left;">
                    <img src="https://projectwall.net/{{ $details['brand_logo'] }}" alt="{{$details['brand_name']}}" width="200">
                </td>
                <td style="text-align: right;">
                    <span style="font-size: 14px; color: #333;">INVOICE</span><br>
                    <span style="font-size: 12px; color: #555;">#{{ $details['invoice_number'] }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 20px; text-align: left; font-size: 12px; color: #555;">
                    {{ $details['brand_email'] }}<br>
                    {{ $details['brand_phone'] }}
                </td>
            </tr>
        </table>
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px; align-items: flex-start;">
            <div style="flex: 1;">
                <strong style="display: block; margin-bottom: 10px;">Bill To</strong>
                <p style="margin: 2px 0;font-size: 12px;">{{ $details['name'] }}</p>
                <p style="margin: 2px 0;font-size: 12px;">{{ $details['email'] }}</p>
                <p style="margin: 2px 0;font-size: 12px;">{{ $details['contact'] }}</p>
            </div>
            <div style="flex: 1; text-align: center;">
                
            </div>
            <div style="flex: 1; text-align: right;margin: 0 0 0 425px;">
                <table style="width: 100%; border-spacing: 0;">
                    <tr>
                        <td style="font-size: 12px;color: gray; text-align: right; padding: 5px;">Invoice Date:</td>
                        <td style="font-size: 12px;text-align: right; padding: 5px;">{{ $details['date'] }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color: gray; text-align: right; padding: 5px;">Payment Terms:</td>
                        <td style="font-size: 12px;text-align: right; padding: 5px;">Online</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color: gray; text-align: right; padding: 5px;">Due Date:</td>
                        <td style="font-size: 12px;text-align: right; padding: 5px;">{{ $details['date'] }}</td>
                    </tr>
                    @if($details['payment_status'] == 1)
                    <tr>
                        <td style="font-size: 12px;color: gray; text-align: right; padding: 5px;">Payment Status:</td>
                        <td style="font-size: 12px;text-align: right; padding: 5px;color: red;">UNPAID</td>
                    </tr>
                    @else
                    <tr>
                        <td style="font-size: 12px;color: gray; text-align: right; padding: 5px;">Payment Status:</td>
                        <td style="font-size: 12px;text-align: right; padding: 5px;color: green;">PAID</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #333; color: white;">
                    <th style="padding: 10px;font-size: 12px; text-align: left;">Item</th>
                    <th style="padding: 10px;font-size: 12px; text-align: center;">Quantity</th>
                    <th style="padding: 10px;font-size: 12px; text-align: center;">Rate</th>
                    <th style="padding: 10px;font-size: 12px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px;font-size: 12px;width: 50%;">{{ $details['description'] }}</td>
                    <td style="padding: 10px;font-size: 12px; text-align: center;">1</td>
                    <td style="padding: 10px;font-size: 12px; text-align: center;">{{ $details['currency_sign'] }}{{ $details['amount'] }}</td>
                    <td style="padding: 10px;font-size: 12px; text-align: right;">{{ $details['currency_sign'] }}{{ $details['amount'] }}</td>
                </tr>
            </tbody>
        </table>
        
        <div style="display: flex;justify-content: end;">
            <div style="margin: 0 0 20px 450px;width: 50%;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 95px;">
                    <tbody>
                        <tr>
                            <td style="padding: 10px;font-size: 12px; text-align: left; font-weight: bold;">Subtotal</td>
                            <td style="padding: 10px;font-size: 12px; text-align: right; font-weight: bold;">{{ $details['currency_sign'] }}{{ $details['amount'] }}</td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #f0f0f0;">
                            <td style="padding: 10px;font-size: 12px; text-align: left;">Total</td>
                            <td style="padding: 10px;font-size: 12px; text-align: right;">{{ $details['currency_sign'] }}{{ $details['amount'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0 0 10px;font-size: 12px; text-align: left;">Amount Paid</td>
                            @if($details['payment_status'] == 2)
                            <td style="padding: 10px 0 10px 10px;font-size: 12px; text-align: right;">
                                <a href="javascript:;" style="font-size: 12px;text-decoration: none;background: green;color: #fff;border-radius: 5px;padding: 8px;">{{ $details['currency_sign'] }}{{ $details['amount'] }}</a>
                            </td>
                            @else
                            <td style="padding: 10px 0 0 10px;font-size: 12px; text-align: right;">
                                <a href="{{ $details['link'] }}" style="font-size: 12px;text-decoration: none;background: green;color: #fff;border-radius: 5px;padding: 8px;">Pay Now</a>
                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div style="margin: 50px 0 0 0;width: 100%;padding-right: 130px;">
                <strong style="display: block; margin-bottom: 10px;font-size: 12px;">Notes</strong>
                <p style="margin: 10px 0; font-size: 12px; line-height: 1.6;">Please note that your payment will be processed through our authorized merchant, 
                    @if($details['merchant'] == 12)
                    <b>"Marketing Notch"</b>. 
                    @elseif($details['merchant'] == 15)
                    <b>"The Native Publisher"</b>. 
                    @endif
                    . This name will appear on your bank statement as the payee.</p>
                <div style="margin: 20px 0;">
                    <strong style="display: block; margin-bottom: 10px;font-size: 12px;">Terms</strong>
                    <p style="margin: 10px 0; font-size: 12px; line-height: 1.6;">If you require any assistance with payment options or understanding our terms, please don't hesitate to reach out.</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

