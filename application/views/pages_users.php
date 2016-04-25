<div class="row">
	<div class="col-sm-12">
		<h1 class="pull-left">List users</h1>
		<a class="btn btn-default pull-right h1" href="<?php echo site_url('UserController/register'); ?>" role="button">Register new</a>
	</div>
</div>
<hr>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Username</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Credits</th>
			<th>Subscription</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php $attributes = array('class' => 'navbar-form navbar-left'); ?>
		<?php foreach($users as $user) { ?>
		<tr>
			<td><?php echo $user->id; ?></td>
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->email; ?></td>
			<td><?php echo $user->msisdn; ?></td>
			<td><?php echo $user->credits; ?></td>
			<td><?php echo ($user->active) ? 'Active' : 'Disabled'; ?></td>
			<td>
				<?php echo form_open('usercontroller/listusers', $attributes); ?>
			        <div class="form-group">
			          <input type="text" class="form-control" placeholder="Type text" name="sms">
			        </div>
			        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
			        <input type="hidden" name="msisdn" value="<?php echo $user->msisdn; ?>">
			        <button type="submit" class="btn btn-default">Send SMS</button>
			    <?php echo form_close(); ?>
			</td>
			<td class="text-right">
				<a class="btn btn-default" href="<?php echo site_url('UserController/update/' . $user->id); ?>" role="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
				<a class="btn btn-default" href="<?php echo site_url('UserController/updateSubscription/' . $user->id); ?>" role="button">Change subscription</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php
if (isset($transaction)) { ?>
<!-- Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="resultModalLabel">SMS transaction response</h4>
      </div>
      <div class="modal-body">
        <div class="list-group">
		  <div class="list-group-item active">
		    <h4 class="list-group-item-heading">Sending SMS</h4>
		    <p class="list-group-item-text"><?php echo $transaction['sendSMS']->statusCode . ': ' . $transaction['sendSMS']->statusMessage; ?></p>
		  </div>
		  <div class="list-group-item">
		    <h4 class="list-group-item-heading">Getting Token</h4>
		    <p class="list-group-item-text"><?php echo (isset($transaction['getToken']->statusCode)) ? $transaction['getToken']->statusCode . ': ' . $transaction['getToken']->statusMessage : 'No response'; ?></p>
		  </div>
		  <div class="list-group-item">
		    <h4 class="list-group-item-heading">Getting bill</h4>
		    <p class="list-group-item-text"><?php echo (isset($transaction['getBill']->statusCode)) ? $transaction['getBill']->statusCode . ': ' . $transaction['getBill']->statusMessage : 'No response'; ?></p>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$('#resultModal').modal('show');
</script>

<?php } ?>