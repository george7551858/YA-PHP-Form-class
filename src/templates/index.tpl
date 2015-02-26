<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{$data['title']}</title>
</head>
<body>

{$data['test']}

<form class="form-horizontal">
  {form_text title="System Name" FI=$finputs.system_name}
  {form_text title="Contact Information" anno="When there is a warning of 'Please contact your network administrator'" FI=$finputs.admin_contact_info}

  {form_select title="HTTPS Certificate" label="Default CERT" FI=$finputs.httpsCert}

  {form_radio title="HTTPS Enable" label="Enabled;Disabled" FI=$finputs.SSL}
  
  {form_checkbox title="Internal Domain Name" label="Use the name on SSL certificate" FI=$finputs.usessl_cn}

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>

</body>
</html>