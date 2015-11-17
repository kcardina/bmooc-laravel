<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bMOOC | Aanmelden</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="/bMoocLaravel/public/dropzone.css"> -->
    <style>
		body { padding-top:50px; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		.vcenter {
			display: inline-block;
			vertical-align: middle;
			float: none;
		}
    </style>
</head>
<body>
<center>
Gelieve u via Twitter aan te melden om deze tool te gebruiken.<br />

<a href="{{ URL::to('login') }}/twitter">{!! HTML::image('twitter_login.png', null, array('style'=>"width: 150px")) !!}</a>
</center>
</body>
</html>