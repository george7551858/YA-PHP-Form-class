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

<form class="form-horizontal" method="post">
  

  {assign var="STYLE" value="default"}
  {assign var="STYLE" value="bootstrap3__h"}
  {assign var="STYLE" value="bootstrap3__v"}
  {form_text title="System Name" FI=$finputs.system_name disabled="disabled"}
  {form_text title="Contact Information" anno="When there is a warning of 'Please contact your network administrator'" FI=$finputs.admin_contact_info}

  {form_select title="HTTPS Certificate" label="Default CERT;XXX" FI=$finputs.httpsCert}

  {form_radio title="HTTPS Enable" label="Enabled;Disabled" FI=$finputs.SSL}
  
  {form_checkbox title="Internal Domain Name" label="Use the name on SSL certificate" FI=$finputs.usessl_cn}

  {form_checkbox title="Internal Domain Name2" FI=$finputs.usessl_cn2}

  {form_submit}
</form>

</body>
</html>