<?php include("head.php");?>
<!DOCTYPE html>
<html>
<?php include("htmlhead.php");?>
<body>
<?php include("header.php");?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div id="sidebar" class="span4">
				<div class="well sidebar-nav">
					<ul id="use-case-list" class="nav nav-list">
						<li id="use-case-list-title" class="nav-header">All User Stories</li>
						<?php foreach ($stories as $s){
							$active = isset($uid) && $uid == $s->id ? "class= 'active'" : "";
							echo '<li '.$active.'><a class="uc" href="main.php?uid='.$s->id.'">';
							echo $s->title.'</a>';
							if ($s->test_total > 0){
								$pct = ($s->test_total-$s->error_total)/$s->test_total;
								$badgeclass = "";
								if ($pct == 1){
									$badgeclass="badge-success";
								} else if ($pct < 0.5){
									$badgeclass = "badge-error";
								} else {
									$badgeclass = "badge-warning";
								}
								
								echo '<span class="uc-badge badge '.$badgeclass.'">'.($s->test_total-$s->error_total).'/'.$s->test_total.'</span>';
							} else {
								echo '<span class="uc-badge badge">0</span>';
							}
							echo '</li>';
						}?>
					</ul>
					<form>
						<input type="hidden" name="create" value="true">
						<button class="btn btn-success">
							<i class="icon-plus-sign icon-white"></i> New User Story
						</button>
					</form>
				</div>
			</div>
			<div id="main" class="span8">
			<?php if (isset($uid) && $story->id != NULL){
				include('userstory.php');
			} else {
				include('emptymain.php');
			}?>
				<div class="modal fade" id='runTests'>
					<div class="modal-header">
						<h2>Test Progress</h2>
					</div>
					<div class="modal-body">
						<p id='progress-message'>Running tests on <?php echo $config['app'];?>...</p>
						<div id="overall-progress" class="progress progress-striped active" style="margin:0px 20px 20px 20px;">
							<div class="bar" id='modal-bar'></div>
						</div>
						<p id='progress-total'></p>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn" onClick='cancelTests();' id='button-modal'>Cancel</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</body>
</html>
<?php $db->close();?>