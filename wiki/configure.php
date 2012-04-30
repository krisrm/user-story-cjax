<?php include("head.php");?>
<!DOCTYPE html>
<html>
<?php include("htmlhead.php");?>
<body>
<?php include("header.php");?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<form action="main.php" class="form-horizontal well">
					<input type="hidden" name="configure" value="true">
					<fieldset>
						<legend>Configure Application Tester <button id='back' onClick="history.back();" class="btn">Back</button></legend>
						<div class="control-group">
							<label class="control-label" for="server">Crawling Server</label>
							<div class="controls">
								<input name="server" type="text" class="input" id="server"
									value='<?php echo $config['server'];?>'>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="app">Application Address</label>
							<div class="controls">
								<input name="app" type="text" class="input" id="app"
									value='<?php echo $config['app'];?>'>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="actor">Setup Script</label>
						<textarea name="script" id="script" rows="7" ><?php echo $config['script'];?></textarea>
						
						</div>
						<div class="control-group">
							<div class="controls">
							<button type="submit" class="btn btn-primary">OK</button>
							<button type="reset" class="btn">Cancel</button>
							
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<?php $db->close();?>