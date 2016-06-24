jQuery(document).ready(function() {

	//Default Action

	jQuery(".tab_content").hide(); //Hide all content

	jQuery("ul.tabs li:first").addClass("active").fadeIn(500); //Activate first tab

	jQuery(".tab_content:first").fadeIn(500); //Show first tab content

	

	//On Click Event

	jQuery("ul.tabs li").click(function() {

		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class

		jQuery(this).addClass("active"); //Add "active" class to selected tab

		jQuery(".tab_content").hide(); //Hide all tab content

		var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content

		jQuery(activeTab).fadeIn(500); //Fade in the active content

		return false;

	});



});



jQuery(document).ready(function() {

	jQuery('#evrplus-donate-box').fadeIn();

	

	jQuery('img.evrplus-close').click(function() {

		jQuery('#evrplus-donate-box').fadeOut();

	});

});