<?
/**
 * @var string $subject
 * @var string $bodyHtml
 */

$bodyHtml = $bodyHtml ?? '';
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= Formatter::EscapeHtml($subject) ?></title>
	<style type="text/css">
		@font-face{
			font-family: "League Spartan";
			src: url("https://standardebooks.org/fonts/league-spartan-bold.woff2") format("woff2");
			font-weight: bold;
			font-style: normal;
		}

		.highlight{
			background: transparent;
			background-image: linear-gradient(to right, rgba(255, 225, 0, 0.1), rgba(255, 225, 0, 0.7) 4%, rgba(255, 225, 0, 0.3));
			border-radius: 0.8em 0.3em;
			box-decoration-break: clone;
			-webkit-box-decoration-break: clone;
			margin: 0 -0.4em;
			padding: 0.1em 0.4em;
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
			max-width: 35em;
			padding: 2em;
			background-color: #E9E7E0;
		}

		.preheader {
			display: none !important;
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
			text-decoration-skip-ink: none;
			hyphens: none;
		}

		h1{
			border-bottom: 3px double #222;
			font-size: 2em;
			padding-bottom: 1em;
			text-transform: uppercase;
			line-height: 1.2;
			text-align: center;
			margin: 1em auto;
			margin-top: 2em;
		}

		h2{
			font-size: 1.5em;
			margin-top: 2em;
			margin-bottom: 0;
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

		.signature{
			font-style: italic;
			margin-top: 2em;
			text-align: right;
		}

		.signature p{
			margin: 0;
		}

		address{
			font-size: .8em;
			font-style: normal;
			text-transform: none;
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
			margin: 2em 0;
			text-align: center;
		}

		table.ebooks{
			margin: 1em 0;
		}

		table.ebooks td{
			padding: 1em;
		}

		table.ebooks img{
			max-width: 100%;
			border: 1px solid #222;
			border-radius: 5px;
		}

		.editors-note{
			border: 3px double #222222;
			padding: 2em;
			font-style: italic;
		}

		.editors-note h2{
			margin-top: 0;
			font-style: normal;
			text-align: center;
		}

		.editors-note i,
		.editors-note em{
			font-style: normal;
		}

		.featured-ebooks{
			list-style: none;
			padding: 0;
			margin: 0;
			text-align: center;
			margin-left: 1em;
		}

		.featured-ebooks a{
			display: block;
			font-size: 0;
			border-radius: 5px;
		}

		.featured-ebooks img{
			border: 1px solid #222;
			border-radius: 5px;
			width: 182px;
		}

		.featured-ebooks li{
			display: inline-block;
			margin-top: 1em;
		}

		.featured-ebooks li{
			margin-right: 1em;
		}

		.byline{
			text-align: right;
		}

		.highlight{
			background: transparent;
			background-image: linear-gradient(to right, rgba(255, 225, 0, 0.1), rgba(255, 225, 0, 0.7) 4%, rgba(255, 225, 0, 0.3));
			border-radius: 0.8em 0.3em;
			box-decoration-break: clone;
			-webkit-box-decoration-break: clone;
			margin: 0 -0.4em;
			padding: 0.1em 0.4em;
		}

		.footer ul.social-media{
			padding: 0;
			margin: 0;
			line-height: 0;
			margin-bottom: 1em;
			margin-top: -1em;
			margin-left: 1em;
		}

		.footer ul.social-media li{
			display: inline-block;
			margin-right: 1em;
			margin-top: 1em;
		}

		.footer ul.social-media li p{
			margin: 0;
		}

		.footer ul.social-media a{
			display: block;
		}

		.footer ul.social-media a img{
			height: 2em;
			width: 2em;
			margin: 0;
		}

		.footer ul.social-media a:hover img{
			/* This filter changes a black background to #62bfa3, which is var(--button-highlight) */
			filter: invert(69%) sepia(35%) saturate(459%) hue-rotate(111deg) brightness(92%) contrast(87%);
		}

		blockquote{
			background: rgba(0, 0, 0, .05);
			padding: 1em;
			margin: 1em;
			border-radius: 5px;
		}

		blockquote p{
			margin: 0;
		}

		@media(max-width: 500px){
			table.ebooks td{
				padding: .5em;
			}
		}

		@media(max-width: 300px){
			table.ebooks td{
				padding: .25em;
			}
		}
	</style>
</head>
<body>
	<a href="https://standardebooks.org">
		<img alt="The Standard Ebooks logo" class="logo" src="https://standardebooks.org/images/logo-full.png"/>
	</a>
	<?= $bodyHtml ?>
</body>
</html>
