

 <html>
<head>
    <title></title>
</head>
<body>
<p>Hi {{ ucfirst(@$content['name']) }}</p>

<p>We're happy to say that your campaign is now live!</p>

<p><strong>Campaign Name: {{ ucfirst(@$content['camp_name']) }}<br />
Total Email: {{ @$content['total_email'] }}<br />
Starting Time : {{ @$content['start_time'] }}<br />
Stage: {{ @$content['stage']  }}</strong></p>

<p>This is an automated notification. Please do not reply directly to this message.</p>

<p><strong>Thanks &amp; Regards,</strong></p>

<p><strong>Team Reachomation</strong><br />
&nbsp;</p>
</body>
</html>
