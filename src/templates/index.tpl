<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{$data['title']}</title>
</head>
<body>

{$data['test']}

<form class="form-horizontal">
  {form_input_text label="System Name" FI=$finputs.system_name}
  {form_input_text label="Contact Information" anno=$admin_contact_anno FI=$finputs.admin_contact_info}
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
</form>

</body>
</html>