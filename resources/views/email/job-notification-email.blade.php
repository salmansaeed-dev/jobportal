<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Notification Email</title>
</head>
<body> 
    <h1>Hello {{ $emailData['employer']->name }}</h1>
    <p>Job title : {{ $emailData['job']->title }}</p>

    <p>Details</p>

    <p>{{ Name: $emailData['user']->name }}</p>
    <p>{{ Email:$emailData['user']->email }}</p>
    <p>{{ Mobile : $emailData['user']->mobile }}</p>
</body>
</html>