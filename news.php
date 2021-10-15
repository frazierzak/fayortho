<?php include("header.php");?>

<div id="home">
	<div id="contentbg">
		<div id="content">
			<div>
				<div class="left">
					<div class="page-header">
						<h1>Patients</h1>
					</div>
					<div class="page-header-triangle"></div>
				</div><!-- left -->

			
				<a href="appointments.php" class="apt-btn">Make an Appointment Today!</a>

				<div class="clear"></div>			
				<h1>News</h1>
				<p>Click a month below to expand.</p>
				<div class="clear"></div>
				<h2 class="accordion active">August, 2019</h2>
				<div class="panel" style="max-height: initial;">
					<div class="news-header">
						<h3 class="news-title">Saturday Sports Clinic</h3>
						<h3 class="month">Posted on 8/1/19</h3>
						<div class="clear"></div>
					</div>
					<p>Fayetteville Orthopaedics & Sports Medicine is proud to announce that for the 16th consecutive year we will be staffing our Saturday Sports Clinic to help meet the needs of our local athletes. The Clinic will be held on Saturday mornings from 8:30 to 10:30 am, beginning August 17 through November 9th, 2019.</p>

 					<p>No appointment is necessary for this clinic, but athletes under 18 years of age should be accompanied by a parent or legal guardian.  Patients should also bring a copy of their insurance card to the visit.</p>
				</div><!-- panel -->
				<div class="clear"></div>
				<h2 class="accordion">May, 2019</h2>
				<div class="panel">
					<div class="news-header">
						<h3 class="news-title">Memorial Day Closure</h3>
						<h3 class="month">Posted on 5/23/19</h3>
						<div class="clear"></div>
					</div>
					<p>This Memorial Day, May 27th, we will be closed. We will reopen the following day, May 28th, with normal business hours.</p>
				</div><!-- panel -->
				<div class="clear"></div>
				<h2 class="accordion">April, 2019</h2>
				<div class="panel">
					<div class="news-header">
						<h3 class="news-title">Sports Physical Day</h3>
						<h3 class="month">Posted on 4/23/19</h3>
						<div class="clear"></div>
					</div>
					<img src="images/sports_physical_2019.jpg" style="width: 100%; box-sizing: border-box;" />
				</div><!-- panel -->
				<div class="clear"></div>
		</div>
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
		</script>
		<?php include('footer.php'); ?>