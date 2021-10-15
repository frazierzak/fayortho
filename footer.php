<div class="clear"></div>
</article>
<footer>
	<div>
		<ul>
			<li>
				<a href="index.php">Home</a>
			</li>
			<li>
				<a href="patients.php">First Visit</a>
			</li>
			<li>
				<a href="patient-exp.php">Patient Experience</a>
			</li>
			<li>
				<a href="patient-edu.php">Patient Education</a>
			</li>
			<li>
				<a href="https://www.exscribepatientportal.com/Fayetteville" target="_blank">Patient Portal</a>
			</li>
			<li>
				<a href="doctors.php">Physician Team</a>
			</li>
			<li>
				<a href="leadership.php">Leadership Team</a>
			</li>
			<li>
				<a href="treatments.php">Treatments</a>
			</li>
			<li>
				<a href="pt_ot.php">PT/OT</a>
			</li>
			<li>
				<a href="urgentcare.php">Urgent Care</a>
			</li>
			<li>
				<a href="location.php">Location</a>
			</li>
			<li>
				<a href="careers.php">Careers</a>
			</li>
		</ul>
		<ul>
			<li><strong>Physicians Office</strong></li>
			<li>Office: (910) 484-3114</li>
			<li>Fax: (910) 484-8824</li>
			<li>Suite 100</li>
			<li>1991 Fordham Dr. Fayetteville, NC 28304</li>
			<li><strong>Physical Therapy</strong></li>
			<li>Office: (910) 484-4653</li>
			<li>Suite 102</li>
		</ul>
		<ul>
			<li>
				<a href="https://www.facebook.com/Fayetteville-Orthopaedics-Sports-Medicine-145947582122159/">
					Facebook <img src="images/facebook.png" width="50">
				</a>
			</li>
			<li>
				<a href="https://www.linkedin.com/company/35548778">
					LinkedIn <img src="images/linkedin.png" width="50">
				</a>
			</li>
			<li>
				<a href="https://fayettevilleasc.com/">
					Fayetteville Ambulatory Surgery Center
				</a>
			</li>
			<li>
				<a  href="http://www.capefearvalley.com/home/index.aspx">
					Cape Fear Valley Health
				</a>
			</li>
			<li>
				<a  href="https://www.aaos.org/Default.aspx?ssopc=1">
					American Academy of Orthopaedic Surgeons
				</a>
			</li>
			<li>
				<a href="http://www.rossitservices.com">
					Web Design by Ross IT Services
				</a>
			</li>
		</ul>
	</div>
</footer>
<script type="text/javascript">
	  // When the user scrolls the page, execute stickyHeader 
	  window.onscroll = function() {stickyHeader()};

	  // Get the header
	  var header = document.getElementById("header");
	  var article = document.getElementById("article");

	  // Get the offset position of the navbar
	  var sticky = header.offsetTop;

	  // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
	  function stickyHeader() {
	  	if (window.pageYOffset > sticky) {
	  		header.classList.add("sticky");
	  		article.classList.add("sticky");
	  	} else {
	  		header.classList.remove("sticky");
	  		article.classList.remove("sticky");
	  	}
	  }

	  var navButton = document.getElementById("nav-button");
	  var phoneButton = document.getElementById("phone-button");
	  var mapButton = document.getElementById("map-button");
	  var navFlex= document.getElementById("flex-mobile-nav");
	  var phonesFlex= document.getElementById("flex-phones");
	  var mapFlex= document.getElementById("flex-map");

	  $(navButton).click(function() {
	  	if ( $( navFlex ).is( ":hidden" ) ) {
	  		$( navFlex ).slideDown( "slow" );
	  		navButton.classList.add("mobile-active");

	  	} else {
	  		$( navFlex ).slideUp( "slow" );
	  		navButton.classList.remove("mobile-active");
	  	}
	  	if ( $( phonesFlex ).is( ":visible" ) ) {
	  		$( phonesFlex ).slideUp( "slow" );
	  		phoneButton.classList.remove("mobile-active");
	  	}
	  	if ( $( mapFlex ).is( ":visible" ) ) {
	  		$( mapFlex ).slideUp( "slow" );
	  		mapButton.classList.remove("mobile-active");
	  	}
	  });

	  $(phoneButton).click(function() {
	  	if ( $( phonesFlex ).is( ":hidden" ) ) {
	  		$( phonesFlex ).slideDown( "slow" );
	  		phoneButton.classList.add("mobile-active");
	  	} else {
	  		$( phonesFlex ).slideUp( "slow" );
	  		phoneButton.classList.remove("mobile-active");
	  	}
	  	if ( $( navFlex ).is( ":visible" ) ) {
	  		$( navFlex ).slideUp( "slow" );
	  		navButton.classList.remove("mobile-active");
	  	}
	  	if ( $( mapFlex ).is( ":visible" ) ) {
	  		$( mapFlex ).slideUp( "slow" );
	  		mapButton.classList.remove("mobile-active");
	  	}
	  });

	  $(mapButton).click(function() {
	  	if ( $( mapFlex ).is( ":hidden" ) ) {
	  		$( mapFlex ).slideDown( "slow" );
	  		mapButton.classList.add("mobile-active");
	  	} else {
	  		$( mapFlex ).slideUp( "slow" );
	  		mapButton.classList.remove("mobile-active");
	  	}
	  	if ( $( navFlex ).is( ":visible" ) ) {
	  		$( navFlex ).slideUp( "slow" );
	  		navButton.classList.remove("mobile-active");
	  	}
	  	if ( $( phonesFlex ).is( ":visible" ) ) {
	  		$( phonesFlex ).slideUp( "slow" );
	  		phoneButton.classList.remove("mobile-active");
	  	}
	  });

	  function passWord() {
		var testV = 1;
		var pass1 = prompt('Please Enter Your Password',' ');
		while (testV < 3) {
		if (!pass1)
		history.go(-1);
		if (pass1.toLowerCase() == "fayortho#123") {
		alert('Password Successful');
		window.open('jasd8DKnasdajsmk3.php');
		break;
		}
		testV+=1;
		var pass1 =
		prompt('Access Denied - Password Incorrect, Please Try Again.','Password');
		}
		if (pass1.toLowerCase()!="password" & testV ==3)
		history.go(-1);
		return " ";
		}

		// Covid Modal Code
		var modal = document.getElementById("modal");

		// Get the button that opens the modal
		var btn = document.getElementById("modalbtn");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		btn.onclick = function() {
		  modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		}
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
</body>

	</html>