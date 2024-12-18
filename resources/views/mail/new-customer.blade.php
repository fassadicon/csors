<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>CSORS - New Customer Joined!</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333;">
    <table align="center"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="600"
        style="border-collapse: collapse; background-color: #ffffff; margin: 20px auto; border: 1px solid #dddddd;">
        <tr>
            <td align="center"
                bgcolor="#007bff"
                style="padding: 20px 0; color: #ffffff;">
                <h1 style="margin: 0; font-size: 24px;">CSORS - New Customer Joined!</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p style="margin: 0; font-size: 16px; line-height: 1.5;">
                    Good news!,
                </p>
                <p style="margin: 20px 0; font-size: 16px; line-height: 1.5;">
                    We are excited to inform you that a new customer has successfully joined the CSORS platform.
                </p>
                <table border="0"
                    cellpadding="0"
                    cellspacing="0"
                    width="100%"
                    style="margin: 20px 0; font-size: 16px;">
                    <tr>
                        <td width="30%"
                            style="font-weight: bold;">Customer Name:</td>
                        <td width="30%">{{ $first_name . ' ' . $last_name }}</td>
                    </tr>
                    <tr>
                        <td width="30%"
                            style="font-weight: bold;">Email:</td>
                        <td width="30%">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td width="30%"
                            style="font-weight: bold;">Registration Date:</td>
                        <td>{{ now() }}</td>
                    </tr>
                </table>
                <p style="margin: 20px 0; font-size: 16px; line-height: 1.5;">
                    Please log in to the <a href="https://csors.online/admin">admin portal</a> for more details.
                </p>
            </td>
        </tr>
        <tr>
            <td align="center"
                bgcolor="#f4f4f4"
                style="padding: 10px; color: #555; font-size: 12px;">
                &copy; 2024 CSORS. All Rights Reserved.
            </td>
        </tr>
    </table>
</body>

</html>
