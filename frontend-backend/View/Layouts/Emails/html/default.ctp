<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
<head>
	<title>E-mail do Site</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css" media="all">
	<!--
	* {
		margin: 0;
		padding: 0;
	}
	a {
		font-weight: bold;
		color: #C00;
		text-decoration: none;
	}
	a:hover {
		text-decoration: underline;
	}
	-->
	</style>
</head>

<body style="padding: 0; margin: 0; background-color: #F7F7F7;">
	<table cellpading="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr style="width: 100%; height: 100%;">
			<td valign="top" style="font: 12px Arial, Verdana, sans-serif; width: 100%; height: 100%; padding: 24px 21px; background-color: #F7F7F7; vertical-align: top;">
				<table bgcolor="#FFFFFF" cellpading="0" cellspacing="1" border="0" style="width: 740px; margin: 0 auto; background: #FFFFFF; border: 1px solid #999999; -webkit-box-shadow: 1px 1px 5px 1px rgba(0,0,0,0.2); -moz-box-shadow: 1px 1px 5px 1px rgba(0,0,0,0.2); box-shadow: 1px 1px 5px 1px rgba(0,0,0,0.2);" width="680" align="center">
					<thead>
						<tr>
							<th style="padding: 20px 16px;"><a href="http://www.4oito.com.br"><img style="border: none; display: block;" src="<?php echo Router::url("/img/email/header.png", true); ?>" alt="Quatro Oito" /></a></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding: 16px; background: #FFFFFF;"><?php echo $this->fetch("content"); ?></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td style="font-size: 14px; font-weight: bold; text-align: center; padding: 9px; background-color: #333333;"><a href="http://www.4oito.com.br" style="color: #FFF;">http://www.4oito.com.br</a></td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
