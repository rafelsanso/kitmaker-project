<div class="row">
  <div class="col-sm-12">
    <h1 class="pull-left">New registration</h1>
    <a class="btn btn-default pull-right h1" href="<?php echo site_url('usercontroller/listusers'); ?>" role="button">Return to user list</a>
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
    <?php echo form_open('usercontroller/register'); ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo (isset($user['username']) && !isset($message['success'])) ? $user['username'] : ''; ?>">
      </div>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo (isset($user['email']) && !isset($message['success'])) ? $user['email'] : ''; ?>">
      </div>
      <div class="form-group">
        <label for="email">Mobile</label>
        <input type="text" class="form-control" id="msisdn" placeholder="Mobile" name="msisdn" value="<?php echo (isset($user['msisdn']) && !isset($message['success'])) ? $user['msisdn'] : ''; ?>">
        <p class="help-block">After registration, will send an automatic sms for complete registration.</p>
      </div>
      <input type="hidden" name="sms" value="Registration">
      <input type="hidden" name="credits" value="0">
      <button type="submit" class="btn btn-default">Submit</button>
    <?php echo form_close(); ?>
  </div><!--<div class="signin_form">-->
</div><!--<div class="signup_wrap">-->