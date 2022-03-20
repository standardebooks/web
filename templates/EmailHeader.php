<?
$preheader = $preheader ?? null;
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title></title>
	<style type="text/css">
		@font-face{
			font-family: "League Spartan";
			src: url("https://standardebooks.org/fonts/league-spartan-bold.woff2") format("woff2");
			font-weight: bold;
			font-style: normal;
		}

		body{
			border-radius: 1em;
			font-family: "Georgia", serif;
			hyphens: auto;
			font-size: 18px;
			line-height: 1.4;
			-webkit-font-smoothing: antialiased;
			-webkit-text-size-adjust: none;
			color: #222222;
			margin: auto;
			max-width: 50em;
			padding: 2em;
			background-color: #E9E7E0;
		}

		<? if($preheader){ ?>
		.preheader{
			display:none !important;
			visibility: hidden;
			mso-hide: all;
			font-size: 1px;
			color: #ffffff;
			line-height: 1px;
			height: 0;
			width: 0;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			top: -9999px;
		}
		<? } ?>

		img.logo{
			display: block;
			margin: auto;
			max-width: 100%;
			width: 300px;
		}

		p{
			margin: 1em auto;
		}

		h1,
		h2{
			font-family: "League Spartan", "Helvetica", "Arial", sans-serif;
			font-weight: bold;
			margin: 1em auto;
			text-align: center;
		}

		h1{
			border-bottom: 3px double #222;
			font-size: 2em;
			padding-bottom: 1em;
			text-transform: uppercase;
			line-height: 1;
		}

		h2{
			font-size: 1.5em;
			text-decoration: underline double;
			margin-top: 2em;
		}

		a,
		a:link,
		a:visited{
			color: #222;
			text-decoration: underline;
		}

		a:hover{
			color: #4f9d85;
		}

		.intro{
			text-align: center;
		}

		.footer{
			text-align: center;
			border-top: 3px double #222;
			padding-top: 1em;
			margin-top: 2em;
			text-transform: lowercase;
		}

		.footer img{
			margin-top: 1em;
			max-width: 110px;
		}

		a.button:link,
		a.button:visited{
			color: #fff;
		}

		a.button{
			display: inline-block;
			border: 1px solid rgba(0, 0, 0, .5);
			font-style: normal;
			box-sizing: border-box;
			background-color: #4f9d85;
			border-radius: 5px;
			padding: 1em 2em;
			color: #fff;
			text-decoration: none;
			font-family: "League Spartan", sans-serif;
			font-weight: bold;
			text-shadow: 1px 1px 0 rgba(0, 0, 0, .5);
			box-shadow: 2px 2px 0 rgba(0, 0, 0, .5), 1px 1px 0px rgba(255,255,255, .5) inset;
			position: relative;
			text-transform: lowercase;
			cursor: pointer;
			min-height: calc(1.4em + 2em + 2px);
			hyphens: none;
			text-decoration: none;
		}

		a.button:hover{
			background-color: #62bfa3;
		}

		.button-row{
			text-align: center;
		}
	</style>
</head>
<body>
	<? if($preheader){ ?><p class="preheader"><?= Formatter::ToPlainText($preheader) ?><? for($i = 0; $i < 150 - strlen($preheader); $i++){ ?>&zwnj;&nbsp;<? } ?></p><? } ?>
