<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="color-scheme" content="light">

    <title>Password Reset Verification Code</title>

    <style>
        @media only screen and (max-width: 620px) {
            .ayaka-email-outer {
                padding: 18px 10px !important;
            }

            .ayaka-email-header {
                padding: 26px 21px !important;
            }

            .ayaka-email-content {
                padding: 28px 21px !important;
            }

            .ayaka-email-footer {
                padding: 18px 21px !important;
            }

            .ayaka-email-title {
                font-size: 21px !important;
            }

            .ayaka-email-otp {
                padding: 18px 8px !important;
                font-size: 30px !important;
                letter-spacing: 7px !important;
            }
        }

        @media only screen and (max-width: 360px) {
            .ayaka-email-content {
                padding-right: 17px !important;
                padding-left: 17px !important;
            }

            .ayaka-email-otp {
                font-size: 26px !important;
                letter-spacing: 5px !important;
            }
        }
    </style>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #eef4fa;
        color: #172033;
        font-family: Arial, Helvetica, sans-serif;
        -webkit-text-size-adjust: 100%;
    ">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="
            width: 100%;
            background-color: #eef4fa;
        ">
        <tr>
            <td class="ayaka-email-outer" align="center" style="padding: 40px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                    style="
                        width: 100%;
                        max-width: 580px;
                        overflow: hidden;
                        border: 1px solid #dbeaf0;
                        border-radius: 22px;
                        background-color: #ffffff;
                        box-shadow: 0 18px 45px rgba(7, 89, 133, 0.12);
                    ">
                    {{-- =====================================================
                         EMAIL HEADER
                    ====================================================== --}}

                    <tr>
                        <td class="ayaka-email-header"
                            style="
                                padding: 31px 32px;
                                background-color: #075985;
                                background-image: linear-gradient(
                                    135deg,
                                    #071827 0%,
                                    #075985 52%,
                                    #0ea5b7 100%
                                );
                            ">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td width="48" valign="middle" style="width: 48px;">
                                        <div
                                            style="
                                                width: 44px;
                                                height: 44px;
                                                border: 1px solid rgba(255, 255, 255, 0.22);
                                                border-radius: 14px;
                                                background-color: rgba(255, 255, 255, 0.13);
                                                color: #ffffff;
                                                font-size: 22px;
                                                line-height: 44px;
                                                text-align: center;
                                            ">
                                            &#9749;
                                        </div>
                                    </td>

                                    <td valign="middle" style="padding-left: 11px;">
                                        <h1
                                            style="
                                                margin: 0;
                                                color: #ffffff;
                                                font-size: 18px;
                                                font-weight: 700;
                                                line-height: 1.3;
                                            ">
                                            Kayu Manis Restaurant
                                        </h1>

                                        <p
                                            style="
                                                margin: 4px 0 0;
                                                color: #a5f3fc;
                                                font-size: 10px;
                                                font-weight: 600;
                                                letter-spacing: 1px;
                                                line-height: 1.4;
                                                text-transform: uppercase;
                                            ">
                                            Ayaka Suites Jakarta
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <div
                                style="
                                    margin-top: 25px;
                                    padding-top: 21px;
                                    border-top: 1px solid rgba(255, 255, 255, 0.15);
                                ">
                                <span
                                    style="
                                        display: inline-block;
                                        margin-bottom: 8px;
                                        color: #a5f3fc;
                                        font-size: 9px;
                                        font-weight: 700;
                                        letter-spacing: 1.2px;
                                        text-transform: uppercase;
                                    ">
                                    Secure account verification
                                </span>

                                <h2 class="ayaka-email-title"
                                    style="
                                        margin: 0;
                                        color: #ffffff;
                                        font-size: 25px;
                                        font-weight: 700;
                                        line-height: 1.3;
                                    ">
                                    Your verification code
                                </h2>

                                <p
                                    style="
                                        margin: 8px 0 0;
                                        color: rgba(255, 255, 255, 0.72);
                                        font-size: 12px;
                                        line-height: 1.6;
                                    ">
                                    Use this code to continue resetting
                                    your account password.
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- =====================================================
                         EMAIL CONTENT
                    ====================================================== --}}

                    <tr>
                        <td class="ayaka-email-content" style="padding: 35px 32px;">
                            <p
                                style="
                                    margin: 0 0 13px;
                                    color: #172033;
                                    font-size: 14px;
                                    font-weight: 700;
                                    line-height: 1.6;
                                ">
                                Hello {{ $user->name }},
                            </p>

                            <p
                                style="
                                    margin: 0 0 23px;
                                    color: #68788d;
                                    font-size: 13px;
                                    line-height: 1.7;
                                ">
                                We received a request to reset the password
                                for your Kayu Manis Restaurant account.
                                Enter the verification code below to continue.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td
                                        style="
                                            padding: 5px;
                                            border-radius: 18px;
                                            background-color: #e8f7fb;
                                        ">
                                        <div class="ayaka-email-otp"
                                            style="
                                                padding: 21px 12px;
                                                border: 1px solid #bce6ee;
                                                border-radius: 14px;
                                                background-color: #f6fcfd;
                                                color: #075985;
                                                font-size: 34px;
                                                font-weight: 700;
                                                letter-spacing: 10px;
                                                line-height: 1.3;
                                                text-align: center;
                                            ">
                                            {{ $otp }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="margin-top: 22px;">
                                <tr>
                                    <td width="34" valign="top" style="width: 34px;">
                                        <div
                                            style="
                                                width: 28px;
                                                height: 28px;
                                                border-radius: 9px;
                                                background-color: #e8f7fb;
                                                color: #087ea4;
                                                font-size: 14px;
                                                line-height: 28px;
                                                text-align: center;
                                            ">
                                            &#9201;
                                        </div>
                                    </td>

                                    <td valign="top"
                                        style="
                                            padding-left: 7px;
                                            color: #68788d;
                                            font-size: 12px;
                                            line-height: 1.65;
                                        ">
                                        This verification code expires in

                                        <strong style="color: #075985;">
                                            {{ $expirationMinutes }} minutes
                                        </strong>.

                                        Never share this code with anyone.
                                    </td>
                                </tr>
                            </table>

                            <div
                                style="
                                    margin-top: 23px;
                                    padding: 13px 14px;
                                    border: 1px solid #dbeaf0;
                                    border-radius: 13px;
                                    background-color: #f7fafc;
                                    color: #7a899c;
                                    font-size: 11px;
                                    line-height: 1.65;
                                ">
                                <strong
                                    style="
                                        display: block;
                                        margin-bottom: 3px;
                                        color: #536477;
                                    ">
                                    Did not request this?
                                </strong>

                                You can safely ignore this email.
                                Your password will remain unchanged.
                            </div>
                        </td>
                    </tr>

                    {{-- =====================================================
                         EMAIL FOOTER
                    ====================================================== --}}

                    <tr>
                        <td class="ayaka-email-footer"
                            style="
                                padding: 20px 32px;
                                border-top: 1px solid #e4edf2;
                                background-color: #f8fbfc;
                                color: #8a99aa;
                                font-size: 10px;
                                line-height: 1.6;
                                text-align: center;
                            ">
                            <strong
                                style="
                                    display: block;
                                    margin-bottom: 3px;
                                    color: #536477;
                                ">
                                Kayu Manis Restaurant · Ayaka Suites
                            </strong>

                            &copy; {{ date('Y') }} Ayaka Suites.
                            All rights reserved.
                        </td>
                    </tr>
                </table>

                <p
                    style="
                        max-width: 520px;
                        margin: 17px auto 0;
                        color: #97a5b5;
                        font-size: 9px;
                        line-height: 1.6;
                        text-align: center;
                    ">
                    This is an automated security email.
                    Please do not reply to this message.
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
