<?php include("header.php");?>

<div id="home">
	<div id="contentbg">
		<div id="content">
			<div>
				<div class="left">
					<div class="page-header">
						<h1>Physical & Occupational Therapy</h1>
					</div>
					<div class="page-header-triangle"></div>
				</div><!-- left -->
				

				<a href="appointments.php" class="apt-btn">Make an Appointment Today!</a>
				<div class="clear"></div>
				<div class="doctor">
					<div id="ptslideshow">
						<div>
							<img src="images/pt4.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt5.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt7.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt8.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt9.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt10.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt11.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt12.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt13.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt14.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt15.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt16.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt17.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<!--<div>
							<img src="images/pt18.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt19.jpg" class="mySlides" alt="Physical Therapy" />
						</div>
						<div>
							<img src="images/pt20.jpg" class="mySlides" alt="Physical Therapy" />
						</div>-->
					</div>

					<p>At Fayetteville Orthopaedics, we offer physical therapy for the treatment of a comprehensive range of orthopedic injuries and conditions.</p>
					<p>With our Fayetteville physical therapists, you’ll benefit from world-class physical therapy services from a highly-specialized team. Our skilled, licensed therapists work with our orthopedic surgeons to offer personalized, thoughtful care for each patient designed to get them back to a healthy and pain-free lifestyle. Our therapists have expertise in total joint rehab and work closely with physicians to ensure continuity of care and comprehensive care for meeting patient goals.</p>
					<p>Our physical therapists in Fayetteville are dedicated to giving our patients the very best in advanced therapy techniques for exceptional rehabilitation services. After a thorough evaluation, we’ll work with you to determine an ideal, individualized treatment to help achieve your therapy goals. You’ll see a team of therapists who work with you toward a full life of work, sports and recreation.</p>
					<p>Sports rehab returns athletes to full potential, including advanced training for agility, balance, and jumping mechanics for all high school and college athletes.</p>
					<p>We use a range of therapies and techniques to restore joint strength and mobility. Your prescribed treatment may include:</p>

					<ul>
						<li>Post surgical rehabilitation</li>
						<li>Functional capacity evaluations featuring BTE</li>
						<li>Work hardening</li>
						<li>And more</li>
					</ul>

					<p>Contact us today to learn more about physical therapy at Fayetteville Orthopaedics & Sports Medicine, or to schedule an appointment in Fayetteville. We look forward to helping you recover from injury or surgery using exceptional physical therapy techniques and treatments.</p>
				</div><!-- doctor -->
			</div><!-- content filler -->
			<script type="text/javascript">
				$("#ptslideshow > div:gt(0)").hide();

				setInterval(function() { 
					$('#ptslideshow > div:first')
					.fadeOut(1000)
					.next()
					.fadeIn(1000)
					.end()
					.appendTo('#ptslideshow');
				},  3000);
			</script>
			<?php include('footer.php'); ?>