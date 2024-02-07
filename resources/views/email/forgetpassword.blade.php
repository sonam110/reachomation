<html>
<head>
	<title></title>
</head>
<body>
<p>Hi {{ ucfirst($content['name']) }},</p>

<p>Forgot your password?</p>

<p>To reset the password, please click the link below</p>

<p><a href="{{URL('/')}}/password-reset/{{ $content['token'] }}" target="_blank"
style="box-sizing: border-box;display: inline-block;font-family:'Cabin',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #0f172a; border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word;">
<span style="display:block;padding:14px 44px 13px;line-height:120%;"><span
	style="font-size: 16px; line-height: 19.2px;"><strong><span
			style="line-height: 19.2px; font-size: 16px;">RESET PASSWORD</span></strong>
</span>
</span>
</a></p>

<p>If you're not sure why you're receiving this message, you can report it to us by emailing to&nbsp;<br />
<a href="mailto:contact@reachomation.com">contact@reachomation.com</a></p>

<p>If you suspect someone has unauthorized access to your account,we suggest you change your password by logging into your account.</p>

<p><strong>Thanks&nbsp;</strong></p>

<p><strong>Team Reachomation</strong><br />
&nbsp;</p>
</body>
</html>



<!-- <!DOCTYPE html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="x-apple-disable-message-reformatting">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>

	<style type="text/css">
		table,
		td {
			color: #000000;
		}

		a {
			color: #0000ee;
			text-decoration: underline;
		}

		@media only screen and (min-width: 620px) {
			.u-row {
				width: 600px !important;
			}

			.u-row .u-col {
				vertical-align: top;
			}

			.u-row .u-col-100 {
				width: 600px !important;
			}
		}

		@media (max-width: 620px) {
			.u-row-container {
				max-width: 100% !important;
				padding-left: 0px !important;
				padding-right: 0px !important;
			}

			.u-row .u-col {
				min-width: 320px !important;
				max-width: 100% !important;
				display: block !important;
			}

			.u-row {
				width: calc(100% - 40px) !important;
			}

			.u-col {
				width: 100% !important;
			}

			.u-col>div {
				margin: 0 auto;
			}
		}

		body {
			margin: 0;
			padding: 0;
		}

		table,
		tr,
		td {
			vertical-align: top;
			border-collapse: collapse;
		}

		p {
			margin: 0;
		}

		.ie-container table,
		.mso-container table {
			table-layout: fixed;
		}

		* {
			line-height: inherit;
		}

		a[x-apple-data-detectors='true'] {
			color: inherit !important;
			text-decoration: none !important;
		}
	</style>
	<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet" type="text/css">
</head>

<body class="clean-body u_body"
	style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #f9f9f9;color: #000000">
	<table
		style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #f9f9f9;width:100%"
		cellpadding="0" cellspacing="0">
		<tbody>
			<tr style="vertical-align: top">
				<td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
					<div class="u-row-container" style="padding: 0px;background-color: transparent">
						<div class="u-row"
							style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
								<div class="u-col u-col-100"
									style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
									<div style="width: 100% !important;">
										<div
											style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'Cabin',sans-serif;"
															align="left">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="u-row-container" style="padding: 0px;background-color: transparent">
						<div class="u-row"
							style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
								<div class="u-col u-col-100"
									style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
									<div style="width: 100% !important;">
										<div
											style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:20px;font-family:'Cabin',sans-serif;"
															align="left">

															<table width="100%" cellpadding="0" cellspacing="0" border="0">
																<tr>
																	<td style="padding-right: 0px;padding-left: 0px;" align="center">

																		<img align="center" border="0" src="https://reachomation.com/images/reachomation-black.png"
																			alt="Image" title="Image"
																			style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 32%;max-width: 179.2px;"
																			width="179.2" />
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="u-row-container" style="padding: 0px;background-color: transparent">
						<div class="u-row"
							style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #60A396!important;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
								<div class="u-col u-col-100"
									style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
									<div style="width: 100% !important;">
										<div
											style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:40px 10px 10px;font-family:'Cabin',sans-serif;"
															align="left">

															<table width="100%" cellpadding="0" cellspacing="0" border="0">
																<tr>
																	<td style="padding-right: 0px;padding-left: 0px;" align="center">
																		<img align="center" border="0"
																			src="https://cdn.templates.unlayer.com/assets/1597218650916-xxxxc.png" alt="Image"
																			title="Image"
																			style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 26%;max-width: 150.8px;"
																			width="150.8" />
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</tbody>
											</table>

											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'Cabin',sans-serif;"
															align="left">
															<div
																style="color: #fffff; line-height: 140%; text-align: center; word-wrap: break-word;">
																<p style="font-size: 14px; line-height: 140%;"><strong>T H A N K S&nbsp; &nbsp;F O
																		R&nbsp; &nbsp;S I G N I N G&nbsp; &nbsp;U P !</strong></p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>

											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:0px 10px 31px;font-family:'Cabin',sans-serif;"
															align="left">
															<div
																style="color: #e5eaf5; line-height: 140%; text-align: center; word-wrap: break-word;">
																<p style="font-size: 14px; line-height: 140%;"><span
																		style="font-size: 28px; line-height: 39.2px;"><strong><span
																				style="line-height: 39.2px; font-size: 28px;">Forgot Your Reachomation Account Password
																			</span></strong>
																	</span>
																</p>
															</div>

														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="u-row-container" style="padding: 0px;background-color: transparent">
						<div class="u-row"
							style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
								<div class="u-col u-col-100"
									style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
									<div style="width: 100% !important;">
										<div
											style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:33px 55px;font-family:'Cabin',sans-serif;"
															align="left">

															<div style="line-height: 160%; text-align: center; word-wrap: break-word;">
																<p style="font-size: 14px; line-height: 160%;"><span
																		style="font-size: 22px; line-height: 35.2px;">Hi {{ ucfirst($content['name']) }},
																	</span></p>
																<p style="font-size: 14px; line-height: 160%;"><span
																		style="font-size: 18px; line-height: 28.8px;">We're To reset the password, please click the link below.</span></p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>

											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'Cabin',sans-serif;"
															align="left">
															<div align="center">
																<a href="{{URL('/')}}/password-reset/{{ $content['token'] }}" target="_blank"
																	style="box-sizing: border-box;display: inline-block;font-family:'Cabin',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #0f172a; border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word;">
																	<span style="display:block;padding:14px 44px 13px;line-height:120%;"><span
																			style="font-size: 16px; line-height: 19.2px;"><strong><span
																					style="line-height: 19.2px; font-size: 16px;">RESET PASSWORD</span></strong>
																		</span>
																	</span>
																</a>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:33px 55px;font-family:'Cabin',sans-serif;"
															align="left">

															<div style="line-height: 160%; text-align: center; word-wrap: break-word;">
																<p style="font-size: 14px; line-height: 160%;"><span
																		style="font-size: 22px; line-height: 35.2px;">If you’re not sure why you’re receiving this message, you can report it to us by emailing to contact@reachomation.com

																	</span></p>
																<p style="font-size: 14px; line-height: 160%;"><span
																		style="font-size: 18px; line-height: 28.8px;">If you suspect someone has unauthorized access to your account,we suggest you change your password by logging into your account.</span></p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:33px 55px 60px;font-family:'Cabin',sans-serif;"
															align="left">

															<div style="line-height: 160%; text-align: center; word-wrap: break-word;">
																<p style="line-height: 160%; font-size: 14px;"><span
																		style="font-size: 18px; line-height: 28.8px;">Thanks,</span></p>
																<p style="line-height: 160%; font-size: 14px;"><span
																		style="font-size: 18px; line-height: 28.8px;">Reachomation Team</span></p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="u-row-container" style="padding: 0px;background-color: transparent">
						<div class="u-row"
							style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #60A396!important;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
								<div class="u-col u-col-100"
									style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
									<div style="width: 100% !important;">
										<div
											style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
											<table style="font-family:'Cabin',sans-serif;" role="presentation" cellpadding="0" cellspacing="0"
												width="100%" border="0">
												<tbody>
													<tr>
														<td
															style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'Cabin',sans-serif;"
															align="left">
															<div
																style="color: #fafafa; line-height: 180%; text-align: center; word-wrap: break-word;">
																<p style="font-size: 14px; line-height: 180%;"><span
																		style="font-size: 16px; line-height: 28.8px;">Copyrights &copy; Reachomation
																		All Rights
																		Reserved</span></p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html> -->