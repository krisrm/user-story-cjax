<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a href="main.php" class="brand" id='app-title'>User Story Wiki</a>
				<div class="span2 pull-right">
					<button id="config-button" class="btn btn-small" onClick='window.location="configure.php";'>
						<i class="icon-cog"></i> Configure
					</button>
				</div>
				
				<div class="span3 pull-right">
					<p class="topcenter">Test Progress: 
					<?php if ($total_test == 0){
						echo "No Tests Run"; 
					} else {
						echo $total_test-$total_error."/".$total_test." Passed";
					}?></p>
				</div>
				<div class="span2 pull-right">
					<div id="overall-progress" class="progress topcenter">
						<div class="bar" style="width: <?php echo $test_pct*100;?>%;"></div>
					</div>
				</div>
				<div class="span2 pull-right">
					<button id="test-button" class="btn btn-primary" onClick='runAllTests(<?php echo count($stories)?>)'>
						<i class="icon-play-circle icon-white"></i> Run All Tests
					</button>
				</div>

			</div>
		</div>

	</div>