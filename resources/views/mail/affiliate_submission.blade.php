<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Form Submission</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="container">
    <!-- See the settings for some head CSS styles -->
    <table class="email" width="100%" border=0; cellspacing=0; cellpadding="20" style="border-bottom-width: 10px;
border-bottom-style: solid;
border-bottom-color: #ff665e">

        <tr>
            <td class="header" style="background-color: #ff665e;">

                <table border="0" style="color: #fff; 
      width: 900px; 
      margin: 0 auto; 
      font-family: Arial, Helvetica, sans-serif;" cellspacing="0" width="900">
                    <tr>
                        <td style="text-align: center !important" colspan="2">
                            <h1>Affiliate Form Submission</h1>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td class="content" style="background-color: #eee;">

                <table border="0" style="color: #444; 
    width: 600px; 
    margin: 0 auto; 
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #ddd;
    font-family: Arial, Helvetica, sans-serif;
                      line-height: 1.4;" cellpadding="15" cellspacing="1" width="600">

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Brand Name:</strong></td>
                        <td>{{ $data['brand_name'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Name:</strong></td>
                        <td>{{ $data['name'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Email:</strong></td>
                        <td>{{ $data['email'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Phone:</strong></td>
                        <td>{{ $data['phone'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Audience:</strong></td>
                        <td>{{ $data['audience'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Web Link:</strong></td>
                        <td>{{ $data['web_link'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Referring:</strong></td>
                        <td>{{ $data['referring'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Other Referring Source:</strong></td>
                        <td>{{ $data['referring_other'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Audience Benefit:</strong></td>
                        <td>{{ $data['audience_benefit'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Audience Promote:</strong></td>
                        <td>{{ $data['audience_promote'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Need Help:</strong></td>
                        <td>{{ $data['need_help'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>URL:</strong></td>
                        <td>{{ $data['url'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>IP Address:</strong></td>
                        <td>{{ $data['ip_address'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>City:</strong></td>
                        <td>{{ $data['city'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Country:</strong></td>
                        <td>{{ $data['country'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Internet Connection:</strong></td>
                        <td>{{ $data['internet_connection'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Zipcode:</strong></td>
                        <td>{{ $data['zipcode'] }}</td>
                    </tr>

                    <tr style="background-color:#fff">
                        <td width="30%"><strong>Region:</strong></td>
                        <td>{{ $data['region'] }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>