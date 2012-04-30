<form id='story' action="main.php" method="post" class="form-horizontal well">
					<input id='doAction' type="hidden" name="update" value='true'>
					<input type="hidden" name="uid" value='<?php echo $story->id?>'>
					<div class="uc-id">#<?php echo $story->id?></div>
					<fieldset>
						<legend>
							Title<input name= "title" id="title" type="text" value='<?php echo $story->title?>'>
						</legend>
						<div class="control-group">
							<label class="control-label" for="actor">Primary Actor</label>
							<div class="controls">
								<input name="actor" type="text" class="input-xlarge" id="actor" placeholder='"As a..."'
								value='<?php echo $story->actor?>'>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="goal">Goal</label>
							<div class="controls">
								<input name="goal" type="text" class="input-xlarge" id="goal" placeholder='"I want to..."'
								value='<?php echo $story->goal?>'>
							</div>
							
						</div>
						<div class="control-group">
							<label class="control-label" for="benefit">Benefit</label>
							<div class="controls">
								<input name="benefit" type="text" class="input-xlarge" id="benefit" placeholder='"So that I can..."'
								value='<?php echo $story->benefit?>'>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="comment">Comment</label>
							<div class="controls">
								<textarea name="comment" class="input-xlarge" id="comment"><?php echo $story->comment?></textarea>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="actor">Test Script</label>
						<textarea name="script" id="script" rows="7" ><?php echo $story->script?></textarea>
						</div>
						<?php if(count($story->errors)>0):?>
						<div class="control-group">
							<h3>Errors</h3>
							<table class="table error-table">
							<thead><tr><th>Assertion ID</th><th>Error Message</th></tr></thead><tbody>
							<?php foreach ($story->errors as $error){
							echo '<tr><td class="err-id">'.$error[0].'</td><td>'.$error[1]."</td></tr>";
							}?>					
							</tbody>
							</table>
						</div>
						<?php endif;?>
						<div class="control-group">
							<div class="controls">
							<button type="submit" class="btn btn-primary">Save</button>
							<button type="reset" class="btn" onClick="window.location='main.php?uid=<?php echo $uid?>'">Cancel</button>
							<button class="btn btn-danger" onClick='doDelete(<?php echo $uid;?>)'><i class="icon-trash icon-white"></i> Delete</button>
						</div>
						</div>
					</fieldset>
				</form>