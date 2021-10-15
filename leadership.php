<?php include("header.php");?>

<div id="home">
	<div id="contentbg">
		<div id="content">
			<div>
				<div class="left">
					<div class="page-header">
						<h1>Leadership Team</h1>
					</div>
					<div class="page-header-triangle"></div>
				</div><!-- left -->
				<a href="appointments.php" class="apt-btn">Make an Appointment Today!</a>
				<div class="clear"></div>
				<div id="staff-section">
					<div class="staff">
						<div class="staff-thumb">
							<img src="images/lee.jpg" />
						</div><!-- staff-thumb -->
						<div class="staff-bio">
							<h1>Martha Callahan</h1>
							<h2>Operations Manager</h2>
							
						</div><!-- staff-bio -->
					</div><!-- staff -->
					<!-- <div class="staff">
						<div class="staff-thumb">
							<img src="images/carlos.jpg" />
						</div>
						<div class="staff-bio">
							<h1>Carlos Sernas</h1>
							<h2>Operations Manager</h2>
							
						</div>
					</div> -->
					<div class="staff">
						<div class="staff-thumb">
							<img src="images/cathy.jpg" />
						</div><!-- staff-thumb -->
						<div class="staff-bio">
							<h1>Cathy M. Bragg, CPC</h1>
							<h2>Insurance/Billing Manager</h2>
							
						</div><!-- staff-bio -->
					</div><!-- staff -->
				</div><!-- staff-section -->
			</div>
		</div><!-- content -->
	</div><!-- contentbg -->

</div><!-- home -->
<script type="text/javascript">
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight){
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			}
		});
	}
	$(function() {
		$(".rslides").responsiveSlides();
	});
</script>
<?php include("footer.php");?>