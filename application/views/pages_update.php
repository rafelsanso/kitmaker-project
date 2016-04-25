<div class="row">
  <div class="col-sm-12">
    <h1 class="pull-left">Update subscription</h1>
    <a class="btn btn-default pull-right h1" href="<?php echo site_url('UserController/listUsers'); ?>" role="button">Return to user list</a>
  </div>
</div>
<hr>
<div class="signup_wrap">
  <?php if (isset($message['success'])) { ?>
    <div class="alert alert-success" role="alert">
      <?php echo $message['success']; ?>
    </div>
  <?php } ?>
  <?php if (isset($message['error'])) { ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $message['error']; ?>
    </div>
  <?php } ?>
  <div class="signin_form">
    <?php echo form_open('UserController/update/' . $user[0]->id); ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo $user[0]->username; ?>">
      </div>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $user[0]->email; ?>">
      </div>
      <div class="form-group">
        <label for="email">Mobile</label>
        <input type="text" class="form-control" id="mobile" placeholder="Mobile" name="mobile" value="<?php echo $user[0]->mobile; ?>">
      </div>
      <div class="form-group">
        <label for="credits">Credits</label>
        <input type="text" class="form-control" id="credits" placeholder="Credits" name="credits" value="<?php echo $user[0]->credits; ?>">
        <p class="help-block">Set first credits for this subscription.</p>
      </div>
      <div class="form-group">
        <label>
        <input type="checkbox" name="active" id="active" value="1" <?php echo ($user[0]->active) ? 'checked="checked"' : ''; ?>> Active subscription
      </label>
      </div>
      <input type="hidden" name="id" id="id" value="<?php echo $user[0]->id; ?>">
      <button type="submit" class="btn btn-default">Submit</button>
    <?php echo form_close(); ?>
  </div><!--<div class="signin_form">-->
</div><!--<div class="signup_wrap">-->