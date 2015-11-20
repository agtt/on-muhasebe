<?php include_once('../../header.php'); ?>

<div class="button-bar">
  <ul class="button-group">
    <li><a href="<?php url('page'); ?>/user/message.php?message_type=receiver" class="button secondary"><?php lang('Inbox'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/message.php?message_type=sender" class="button secondary"><?php lang('Outbox'); ?></a></li>
    <li><a href="<?php url('page'); ?>/user/message_new.php" class="button secondary"><?php lang('New Message'); ?></a></li>
  </ul>
</div>

<?php if(isset($_GET['user_id'])) { ?>
	
<?php if(get_the_user('id') < 1) { alert_box('alert', get_lang('No User ID')); exit;	} ?>

<div class="row">
	<div class="six columns">
    
    <?php
	if(isset($_POST['btn_message']))
	{
		$receiver_id = safety_filter($_POST['receiver_id']);
		$title		 = safety_filter($_POST['title']);	
		$description = safety_filter($_POST['description']);
		$datetime	 = safety_filter($_POST['datetime']);
		
		if(!get_log($datetime, get_the_current_user('id'), 'New Message', true))
		{
			if(add_message('', $datetime, get_the_current_user('id'), $receiver_id, $title, $description))
			{
				add_log($datetime, '', '', '', 'New Message', get_lang('Generated new posts').' ['.get_the_user('user_name').']');
				alert_box('success', get_lang('Successful'));	
			}
		}
	}
	?>
    
    <form name="form_message" id="form_message" action="" method="POST">
    	<fieldset>
        	<legend><?php lang('New Message'); ?></legend>
            
            <label for="receiver"><?php lang('Receiver'); ?></label>
            <input type="text" name="receiver" id="receiver" value="<?php the_user('display_name'); ?>" readonly />
            <input type="hidden" name="receiver_id" id="receiver_id" value="<?php the_user('id'); ?>" />
            
            <label for="title"><?php lang('Title'); ?></label>
            <input type="text" name="title" id="title" class="required" maxlength="255" minlength="3" />
            
            <label for="description"><?php lang('Description'); ?></label>
            <textarea name="description" id="description" rows="5" class="required" minlength="3"></textarea>
            
            <input type="hidden" name="datetime" id="datetime" value="<?php config('datetime'); ?>" />
            
            <input type="submit" name="btn_message" id="btn_message" class="button" value="<?php lang('Submit'); ?>" />
            
            <p></p>
            
        </fieldset>
    </form>	
    </div>
    <div class="six columns">
    	
    </div>
</div>
    
<?php } else { ?>
<table class="dataTable" width="100%">
    <thead>
        <tr>
            <th width="1"></th>
            <th><?php lang('User Name'); ?></th>
            <th><?php lang('Level'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query_users = mysql_query("SELECT * FROM $database->users WHERE status='publish' ORDER BY id ASC");
    while($list_users = mysql_fetch_assoc($query_users))
    {
		
		if(get_meta('', $list_users['id'], 'user', 'display_name') != '') { $list_users['display_name'] = get_meta('', $list_users['id'], 'user', 'display_name'); }
		else{ $list_users['display_name'] = $list_users['user_name']; }
		
        echo '
        <tr>
            <td></td>
            <td><a href="?user_id='.$list_users['id'].'">'.$list_users['display_name'].'</a></td>
            <td>'.$list_users['level'].'</td>
        </tr>
        ';
    }
    ?>
    </tbody>
</table>
<?php } ?>

<?php include_once('../../footer.php'); ?>