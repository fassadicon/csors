<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
            <h1>{{$title}}</h1>
        </div>
        <div class="email-body">
            <h2>{{$heading}}</h2>


            <p>Our team has reviewed the documents and found that some information or details may be incorrect or
                incomplete. To proceed with your application, we kindly request that you re-upload the updated and
                corrected versions of the following documents:</p>

            <p>Please ensure that the re-uploaded documents are clear, accurate, and meet the following criteria:</p>

            <ul>
                <li>All information is up to date and verified.</li>
                <li>The document is readable and in the required format (e.g., PDF).</li>
                <li>Relevant signatures or stamps are included, if applicable.</li>
            </ul>

            <p>If you need assistance or have questions regarding the re-upload process, feel free to 
                contact our support team or reach out to the superadmin
                directly for more information.</p>

            <p>We appreciate your prompt attention to this matter, as it will help us move forward with your request.
                Kindly submit the updated documents as soon as possible to avoid any delays in processing your
                application.</p>

            <p>Thank you for your cooperation.</p>

            <p>Best regards,</p>
            <p>The CSORS Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 CSORS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>