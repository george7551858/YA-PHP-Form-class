<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{$data['title']}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="//code.jquery.com/jquery-2.1.3.min.js"></script>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body class="container">

{$data['test']}
{$error}

<form class="form-horizontal" method="post">
  {assign var="STYLE" value="default"}
  {assign var="STYLE" value="bootstrap3__v"}
  {assign var="STYLE" value="bootstrap3__h"}

  {form_text     title="System Name" FI=$finputs.system_name}
  {form_text     title="Contact Information" anno='When there is a warning of "Please contact your network administrator"' FI=$finputs.admin_contact_info}

  {form_select   title="HTTPS Certificate" label="Default CERT" FI=$finputs.httpsCert}

  {form_radio    title="HTTPS Enable" label="Enable;Disable;Enable(Secure)" FI=$finputs.SSL}

  {form_checkbox title="Internal Domain Name" label="Use the name on SSL certificate" FI=$finputs.useSSLCN}
  {form_text     FI=$finputs.device_name disabled="disabled"}

  {form_radio    title="Portal URL" FI=$finputs.HOMEPAGE_en label="Specific;Original;None"}
  {form_text     anno='(e.g. http://www.example.com)'  FI=$finputs.succeed_page}
  {form_text     anno='(e.g. IEMobile/7.0,XBLWP7, separate by comma)'  FI=$finputs.Skip_portal_popup placeholder='Exceptions (User Agent)'}

  {form_text     title="User Log Access" FI=$finputs.billlog_ip placeholder='Enter IP Address Here'}

  {form_radio    title="SNMP" label="Enable;Disable" FI=$finputs.SNMP_en}

  {form_text     title="Suspend Warning Message " FI=$finputs.suspend_message}

  {form_submit}
</form>

</body>
</html>