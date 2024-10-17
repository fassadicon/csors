<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>

    <style>
        .button {
            padding: 10px 10px;
            background-color: rgb(39, 127, 113);
            border: none;
            color: white;
            text-decoration: none;
            /* Ensures the link looks like a button */
            border-radius: 5px;
            /* Makes it look nicer */
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; margin: 20px;">
    <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid rgba(0, 0, 0, .5); border-radius: 10px;">
        <img src="https://csors.online/images/LOGO.jpg" alt="" width="100" style="max-width: 100px;">
        <h3><strong>{{ $name }}</strong></h3>
        <h3><strong>Here is your email and password</strong></h3>
        <p>Email: {{$email}}</p>
        <p>Password: {{$password}}</p>
    </div>
</body>

</html>