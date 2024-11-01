<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>Inquiry CSORS</title>
    <style>
        /* Base styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h2 {
            color: #007bff;
            margin-top: 0;
        }

        .email-body p {
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .email-body a {
            color: #007bff;
            text-decoration: none;
        }

        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

        .email-footer p {
            margin: 0;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                margin: 0 auto;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Order #{{ $orderId }} has been cancelled</h1>
        </div>
        <div class="email-body">
            <h2>We’re sorry to hear that you’ve decided to cancel your order. </h2>

            <p>We understand that things can change, and
                we're here to help make the process as smooth as possible.

                Thank you for considering us, and we hope to serve you again in the future!</p>

            <p>Best regards,</p>
            <p>The CSORS Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 CSORS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
