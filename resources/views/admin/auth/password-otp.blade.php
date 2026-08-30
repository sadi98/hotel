<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Password Reset Code</title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background: #eef5f8;
        font-family: Arial, sans-serif;
    ">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 35px 15px;">
                <table width="100%" cellspacing="0" cellpadding="0" border="0"
                    style="
                        max-width: 570px;
                        overflow: hidden;
                        border-radius: 20px;
                        background: #ffffff;
                    ">
                    <tr>
                        <td
                            style="
                                padding: 30px;
                                background: #075985;
                                color: #ffffff;
                            ">
                            <h2 style="margin: 0;">
                                Kayu Manis Restaurant
                            </h2>

                            <p
                                style="
                                    margin: 7px 0 0;
                                    color: #a5f3fc;
                                    font-size: 11px;
                                ">
                                Ayaka Suites Management
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px;">
                            <p>
                                Hello {{ $user->name }},
                            </p>

                            <p
                                style="
                                    color: #68788d;
                                    line-height: 1.7;
                                ">
                                Use the code below to reset
                                your management password.
                            </p>

                            <div
                                style="
                                    margin: 25px 0;
                                    padding: 20px;
                                    border-radius: 14px;
                                    background: #eef9fb;
                                    color: #075985;
                                    font-size: 32px;
                                    font-weight: bold;
                                    letter-spacing: 10px;
                                    text-align: center;
                                ">
                                {{ $otp }}
                            </div>

                            <p
                                style="
                                    color: #68788d;
                                    font-size: 13px;
                                    line-height: 1.7;
                                ">
                                This code expires in
                                {{ $expirationMinutes }} minutes.
                                Never share this code with anyone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
