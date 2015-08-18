var locationHost = "//" + location.host;
var appendPath = "/ajay/rttheme";
(function ($) {
    $(document).ready(function () {


        /* Home Page Top Slider */
        var owl = $("#owl-demo");
        owl.owlCarousel({
// navigation: true, // Show next and prev buttons
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true
        });
        // Custom Navigation Events
        $(".next").click(function () {
            owl.trigger('owl.next');
        });
        $(".prev").click(function () {
            owl.trigger('owl.prev');
        });
        /* Home Page Top Slider End */

        /* Home Page Thumbnail Slider */
        $("#owl-video").owlCarousel({
            autoPlay: 3000, //Set AutoPlay to 3 seconds
            items: 4,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 3],
            navigation: true,
            navigationText: [
                "<i class='icon-chevron-left icon-white'><</i>",
                "<i class='icon-chevron-right icon-white'>></i>"
            ],
        });
        /* Home Page Thumbnail Slider End */



        /* Home Page Partner Slider */
        $("#owl-partner").owlCarousel({
            autoPlay: 3000, //Set AutoPlay to 3 seconds
            items: 4,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 3],
            navigation: true,
            navigationText: [
                "<i class='icon-chevron-left icon-white'><</i>",
                "<i class='icon-chevron-right icon-white'>></i>"
            ],
        });
        /* Home Page Partner Slider End */

      
    });



})(jQuery);