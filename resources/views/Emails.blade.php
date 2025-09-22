<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Registration Successful!</h1>
    <p>Hi {{ $user->name }},</p>
    <p>Welcome to our platform! Your registration is complete.</p>
    <p>Details:</p>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Registration Date: {{ $user->created_at->format('Y-m-d H:i:s') }}</li>
    </ul>
    <p>If you have any questions, reply to this email.</p>
    <p>Best,<br>The Team</p>
</body>
</html>