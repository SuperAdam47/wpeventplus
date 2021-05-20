function confirmDelete() {
  if (confirm("Are you sure want to delete?")) {
    return true;
  }
}

var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	  sURLVariables = sPageURL.split("&"),
	  sParameterName,
	  i;
	for (i = 0; i < sURLVariables.length; i++) {
	  sParameterName = sURLVariables[i].split("=");
	  if (sParameterName[0] === sParam) {
	    return sParameterName[1] === undefined ? true : sParameterName[1];
	  }
	}
};


(function ($) {
	$(document).ready(function () {
		  
  		var page = getUrlParameter("paging");

		  $(".event_sort").change(function () {
		    var sort = $(".event_sort_field").val();
		    var sortDirection = $(".sort_direction").val();

		    if (page > 1) {
		      var current_url =
		        location.protocol +
		        "//" +
		        location.host +
		        location.pathname +
		        "?page=eventplus_admin_events&sort=" +
		        sort +
		        "&paging=" +
		        page;
		    } else {
		      var current_url =
		        location.protocol +
		        "//" +
		        location.host +
		        location.pathname +
		        "?page=eventplus_admin_events&sort=" +
		        sort;
		    }

		    current_url += "&sort_direction=" + sortDirection;
		    window.location.href = current_url;
		  });

		  $(".event_id_filter").on('change',function () {
		    var oField = $(this);
		    var event_id = oField.val();

		    var current_url =
		      location.protocol +
		      "//" +
		      location.host +
		      location.pathname +
		      "?page=" +
		      oField.attr("data-current-uri") +
		      "&event_id=" +
		      event_id;
		    window.location.href = current_url;
		  });

		  $(".event_atten_sort").change(function () {
		    var sort = $(".event_atten_sort").val();
		    if (!sort == "") {
		      var current_url =
		        location.protocol +
		        "//" +
		        location.host +
		        location.pathname +
		        "?page=attendee&sort=" +
		        sort;
		      window.location.href = current_url;
		    }
		  });

		  $('a.poplight[href^="#"]').on("click", function () {
		    var popID = $(this).attr("rel");

		    var popURL = $(this).attr("href");

		    /*Pull Query & Variables from href URL*/

		    var query = popURL.split("?");

		    var dim = query[1].split("&");

		    var popWidth = dim[0].split("=")[1];

		    $("#" + popID)
		      .fadeIn()
		      .css({ width: Number(popWidth) })
		      .prepend(
		        '<a href="#" class="close"><img src="/wp-content/plugins/eventsplus/images/btn-close.png" class="btn_close" title="Close Window" alt="Close" /></a>'
		      );

		    var popMargTop = ($("#" + popID).height() + 80) / 2;

		    var popMargLeft = ($("#" + popID).width() + 80) / 2;

		    $("#" + popID).css({
		      "margin-top": -popMargTop,
		      "margin-left": -popMargLeft,
		    });

		    $("body").append('<div id="fade"></div>');
		    $("#fade").css({ filter: "alpha(opacity=80)" }).fadeIn();

		    return false;
		  });

		  /*Close Popups and Fade Layer*/

		  $("body").on("click", "a.close, #fade", function () {
		    $("#fade , .popup_block").fadeOut(function () {
		      $("#fade, a.close").remove();
		    });

		    return false;
		  });

		  $("a.ev_reg-fancylink").fancybox({});

		  $("a.ev_widget-fancylink").fancybox({});
		
		  /*Default Action*/

		  $(".tab_content").hide(); //Hide all content

		  $("ul.tabs li:first").addClass("active").show(); //Activate first tab

		  $(".tab_content:first").show(); //Show first tab content

		  $(".dropdown-toggle").dropdown();

		  /*On Click Event*/

		  $("ul.tabs li").on("click", function () {
		    $("ul.tabs li").removeClass("active");

		    $(this).addClass("active");

		    $(".tab_content").hide();

		    var activeTab = $(this).find("a").attr("href");

		    $(activeTab).fadeIn();

		    return false;
		  });
		  var recurring_choice = $("#recurring_choice").val();
		  var infinate_event = $("input[name=infinate_event]:checked").val();

		  $("ul.tabs li a").on("click", function () {
		    if (infinate_event == "no") {
		      $(".p2").css("display", "block");
		    }
		  });

		  if ($(".infinate_event").val() == "yes") $(".p2").fadeOut();
		  else $(".p2").fadeIn();
		  $(".infinate_event").change(function () {
		    if ($(this).val() == "yes") $(".p2").fadeOut();
		    else $(".p2").fadeIn();
		  });
		  if (recurring_choice == "yes") {
		    $(".recurrence_options").css("display", "block");
		    $(".infinate_event").prop("disabled", false);
		  } else {
		    $(".recurrence_options").css("display", "none");
		    $(".infinate_event")
		      .filter('[value="no"]')
		      .prop("checked", true)
		      .trigger("change")
		      .prop("disabled", true);
		  }
		  $("select#recurring_choice").on('change',function () {
		    var recurring_choice = $("select#recurring_choice").val();

		    if (recurring_choice == "yes") {
		      $(".recurrence_options").css("display", "block");
		      $(".infinate_event").prop("disabled", false);
		      $(".p2").css("display", "none");
		    } else {
		      $(".infinate_event").prop("disabled", true);
		      $(".recurrence_options").css("display", "none");
		      $(".p2").css("display", "block");
		      $("#infinate_event_2").prop("checked", true);
		      $(".infinate_event").trigger("change");
		      $(".infinate_event")
		        .filter('[value="no"]')
		        .prop("checked", true)
		        .trigger("change");
		    }
		  });

		  $("#term_c_y").on("click", function () {
		    $("#term_div").show();
		  });

		  $("#term_c_n").on("click", function () {
		    $("#term_div").hide();
		  });
	});
}(jQuery));