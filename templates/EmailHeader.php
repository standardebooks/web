<?
$preheader = $preheader ?? null;
$letterhead = $letterhead ?? false;
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
			font-style: normal;
			font-weight: bold;
			src: url("https://standardebooks.org/fonts/league-spartan-bold.woff2") format("woff2");
		}

		body{
			text-align: center;
		}

		div.body{
			text-align: left;
			background-color: #E9E7E0;
			border: 1px solid #AAA8A3;
			border-radius: 1em;
			font-family: "Georgia", serif;
			font-size: 18px;
			hyphens: auto;
			line-height: 1.4;
			color: #222222;
			margin: auto;
			max-width: 60ch;
			padding: 2em;
			-webkit-font-smoothing: antialiased;
			-webkit-text-size-adjust: none;
		}

		<? if($letterhead){ ?>
		div.body.letterhead{
			background-image: url("https://standardebooks.org/images/logo-email.png");
			background-position: top 2em right 2em;
			background-repeat: no-repeat;
			background-size: 210px 49px;
			padding-top: 5em;
		}
		<? } ?>

		<? if($preheader){ ?>
		.preheader{
			display: none !important;
			color: #ffffff;
			font-size: 1px;
			height: 0;
			line-height: 1px;
			mso-hide: all;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			top: -9999px;
			visibility: hidden;
			width: 0;
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
			hyphens: none;
			margin: 1em auto;
			text-align: center;
		}

		h1{
			border-bottom: 3px double #222;
			font-size: 2em;
			line-height: 1.3;
			margin-top: .25em;
			padding-bottom: .75em;
			text-transform: uppercase;
		}

		h2{
			font-size: 1.5em;
			margin-top: 2em;
			text-decoration: underline double;
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

		address{
			text-transform: none;
		}

		address p{
			margin: 0;
		}

		.intro{
			text-align: center;
		}

		.footer{
			border-top: 1px solid #ccc;
			font-size: .75em;
			margin-top: 2em;
			padding-top: 2em;
			text-align: center;
			text-transform: lowercase;
		}

		.footer p{
			margin: 0;
		}

		.footer img{
			margin-top: 2em;
			max-width: 55px;
		}

		footer{
			margin-right: 4em;
			margin-top: 2em;
			text-align: right;
		}

		footer p{
			margin: 0;
		}

		a.button:link,
		a.button:visited{
			color: #fff;
		}

		a.button{
			background-color: #4f9d85;
			border: 1px solid rgba(0, 0, 0, .5);
			border-radius: 5px;
			box-shadow: 2px 2px 0 rgba(0, 0, 0, .5), 1px 1px 0px rgba(255,255,255, .5) inset;
			box-sizing: border-box;
			color: #fff;
			cursor: pointer;
			display: inline-block;
			font-family: "League Spartan", sans-serif;
			font-style: normal;
			font-weight: bold;
			hyphens: none;
			min-height: calc(1.4em + 2em + 2px);
			padding: 1em 2em;
			text-decoration: none;
			text-shadow: 1px 1px 0 rgba(0, 0, 0, .5);
			text-transform: lowercase;
		}

		a.button:hover{
			background-color: #62bfa3;
		}

		.button-row{
			margin: 2em auto;
			text-align: center;
		}

		.letterhead{
			text-align: right;
		}
	</style>
</head>
<body>
	<div class="body<? if($letterhead){ ?> letterhead<? } ?>">
	<? if($preheader){ ?><p class="preheader"><?= Formatter::ToPlainText($preheader) ?><? for($i = 0; $i < 150 - strlen($preheader); $i++){ ?>&zwnj;&nbsp;<? } ?></p><? } ?>
