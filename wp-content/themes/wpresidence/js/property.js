/**
 * Property Page JS
 *
 * This file contains JavaScript functions used on the single property pages of the WpResidence theme.
 * It manages user interactions, AJAX calls, mortgage calculations, reviews, and several UI components 
 * like star ratings, floor plans, and print functionality.
 *
 * @package    WpResidence
 * @subpackage JavaScript 
 * @version    1.0.0
 *
 * Dependencies: 
 * - jQuery
 * - Chart.js (for mortgage pie chart and statistics)
 * - AJAX (WordPress AJAX for printing pages, submitting reviews, etc.)
 * - Venobox (for image and video lightboxes)
 *
 * Usage:
 * - Included in single property pages (single-estate-property.php)
 * - Dynamically interacts with various page elements like stars, forms, and charts.
 * - Ensures scripts are only executed when the relevant elements are present on the page.
 */

var morgageChart;

jQuery(document).ready(function ($) {
    "use strict";

 
    // Initialize all necessary functionality when the document is ready
    wpestate_enable_star_action();   // Star rating actions (hover and click events)
    wpestate_print_property_page();  // Print property page feature
    wpestate_edit_review();          // Edit property reviews
    wpestate_submit_review();        // Submit new reviews
    wpestate_enable_floor_plans();   // Enable floor plan functionality (expand/collapse)

    // Initialize Venobox (lightbox functionality for images/videos) if the element is present
    if ($(".venobox").length > 0) {
        $('.venobox').venobox(); 
    }
});

/**
 * Star Rating Interactions
 *
 * Handles user interactions with star ratings, including hover effects and click events.
 */
function wpestate_enable_star_action() {
    const stars = jQuery('.empty_star');  // Select all star elements

    // Handle hover effect for selecting stars
    stars.on('mouseenter', function () {
        const index = stars.index(this);  // Get the index of the hovered star
        stars.each(function (loopIndex) { // Loop through all stars and select up to the hovered index
            if (loopIndex <= index) {
                jQuery(this).addClass('starselected');  // Highlight the star on hover
            } else {
                jQuery(this).removeClass('starselected');
            }
        });
    });

    // Clear the hover effect when leaving the rating area
    jQuery('.rating').mouseleave(function () {
        stars.removeClass('starselected');  // Remove the hover effect when mouse leaves
    });

    // Handle click event for selecting stars
    stars.on('click', function () {
        stars.removeClass('starselected_click');  // Remove previous click selections
        const index = stars.index(this);          // Get the index of the clicked star
        stars.each(function (loopIndex) {         // Loop through and mark selected stars
            if (loopIndex <= index) {
                jQuery(this).addClass('starselected_click');  // Keep the stars selected on click
            }
        });
    });
}

/**
 * Mortgage Pie Chart
 *
 * Displays a pie chart for the mortgage breakdown using Chart.js. It calculates the percentage 
 * for principal, property tax, and HOA fees, and updates dynamically when the user changes values.
 */
function wpestate_show_morg_pie() {
    // Ensure the chart element exists before proceeding
    if (!document.getElementById('morgage_chart')) {
        return;
    }

    // Set up data for the pie chart
    const ctx_pie = jQuery("#morgage_chart").get(0).getContext("2d");
    const data_morg = {
        datasets: [{
            data: [
                jQuery('#morg_principal').attr('data-per'),  // Principal percentage
                jQuery('#monthly_property_tax').attr('data-per'),  // Property tax percentage
                jQuery('#hoo_fees').attr('data-per')  // HOA fees percentage
            ],
            backgroundColor: ["#0073e1", "#0dc3f8", "#FF5E5B"]  // Chart colors for different sections
        }],
        labels: [
            wpestate_property_vars.label_principal,  // Label for principal
            wpestate_property_vars.label_property_tax,  // Label for property tax
            wpestate_property_vars.label_hoo  // Label for HOA fees
        ]
    };

    // Chart options for customizing appearance and behavior
    const options_morg = {
        responsive: true,
        cutoutPercentage: 70,  // Doughnut chart cutout size
        layout: {
            padding: {
                left: 50
            }
        },
        animation: {
            animateScale: true,  // Enable scale animation
            animateRotate: true  // Enable rotation animation
        },
        tooltips: {
            enabled: false  // Disable tooltips for cleaner display
        },
        legend: {
            display: false  // Hide the chart legend
        }
    };

    // Create the mortgage chart
    morgageChart = new Chart(ctx_pie, {
        type: 'doughnut',  // Doughnut chart type
        data: data_morg,
        options: options_morg
    });

    // Update chart and calculations when input values change
    jQuery('#morgage_down_payment, #morgage_down_payment_percent, #monthly_property_tax, #hoo_fees, #morgage_home_price, #morgage_term, #morgage_interest').on('change', function () {
        wpestate_compute_morg();  // Recompute the mortgage breakdown
    });
}

/**
 * Mortgage Calculation
 *
 * Calculates the monthly mortgage payment based on user inputs (home price, interest rate, term, etc.).
 * Updates both the mortgage chart and displayed values in real time.
 */
function wpestate_compute_morg() {
    // Get and parse input values for mortgage calculation
    const homePrice = parseFloat(jQuery('#morgage_home_price').val()) || 0;
    const downPayment = parseFloat(jQuery('#morgage_down_payment').val()) || 0;
    const term = parseFloat(jQuery('#morgage_term').val()) || 1;
    const interest = parseFloat(jQuery('#morgage_interest').val()) || 0;
    const hooFees = parseFloat(jQuery('#hoo_fees').val()) || 0;
    const propertyTax = parseFloat(jQuery('#monthly_property_tax').val()) || 0;

    // Calculate the principal and monthly payment
    const principal = homePrice - downPayment;
    const monthlyInterest = interest / 100 / 12;
    const operatorA = monthlyInterest * principal;
    const operatorB = 1 + monthlyInterest;

    let monthlyPmt = operatorA / (1 - Math.pow(operatorB, (-1 * term * 12)));
    if (interest === 0) {
        monthlyPmt = principal / (term * 12);  // Handle zero interest case
    }

    const totalMonthly = monthlyPmt + hooFees + propertyTax;

    // Update the DOM with calculated mortgage values
    jQuery('#morg_principal').text(monthlyPmt.toFixed(2));
    jQuery('#morg_month_total').text(totalMonthly.toFixed(2));

    // Update the mortgage pie chart
    const percentPrincipal = (monthlyPmt * 100) / totalMonthly;
    const percentHoa = (hooFees * 100) / totalMonthly;
    const percentTax = (propertyTax * 100) / totalMonthly;

    morgageChart.data.datasets[0].data = [percentPrincipal, percentTax, percentHoa];
    morgageChart.update();  // Redraw the chart with new data
}

/**
 * Print Property Page
 *
 * Enables the print functionality on the property page by making an AJAX request 
 * to generate a printable version of the page.
 */
function wpestate_print_property_page() {
    jQuery('#print_page').on('click', function (event) {
        event.preventDefault();

        const propId = jQuery(this).attr('data-propid');  // Get the property ID
        const ajaxurl = control_vars.admin_url + 'admin-ajax.php';
        const myWindow = window.open('', 'Print Me', 'width=700, height=842');
        const nonce = jQuery('#wpestate_ajax_filtering').val();  // Security nonce

        // AJAX request to generate the printable content
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'wpestate_ajax_create_print',
                propid: propId,
                security: nonce
            },
            success: function (data) {
                // Write the response to the new window and trigger print
                myWindow.document.write(data);
                myWindow.document.close();
                myWindow.focus();
                setTimeout(() => myWindow.print(), 3000);
            },
            error: function (errorThrown) {
                console.error('Print page error:', errorThrown);
            }
        });
    });
}

/**
 * Edit Review
 *
 * Handles the functionality for editing existing reviews via an AJAX request.
 */
function wpestate_edit_review() {
    jQuery('#edit_review').on('click', function () {
        const listingId = jQuery(this).attr('data-listing_id');  // Get the listing ID
        const title = jQuery(this).parent().find('#wpestate_review_title').val();  // Review title
        const content = jQuery(this).parent().find('#wpestare_review_content').val();  // Review content
        const stars = jQuery(this).parent().find('.starselected_click').length;  // Star rating count
        const commentId = jQuery(this).attr('data-coment_id');  // Get the comment ID
        const nonce = jQuery('#wpestate_review_nonce').val();  // Security nonce
        const ajaxurl = control_vars.admin_url + 'admin-ajax.php';

        // Check if the user selected stars and entered content
        if (stars > 0 && content !== '') {
            jQuery('.rating').text(control_vars.posting);  // Update the UI to show "posting" status

            // AJAX request to update the review
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'wpestate_edit_review',
                    listing_id: listingId,
                    title: title,
                    stars: stars,
                    content: content,
                    coment: commentId,
                    security: nonce
                },
                success: function () {
                    jQuery('.rating').text(control_vars.review_edited);  // Update the UI to show success
                },
                error: function (errorThrown) {
                    console.error('Edit review error:', errorThrown);
                }
            });
        }
    });
}

/**
 * Submit New Review
 *
 * Handles the functionality for submitting new property reviews via an AJAX request.
 */
function wpestate_submit_review() {
    jQuery('#submit_review').on('click', function () {
        const listingId = jQuery(this).attr('data-listing_id');  // Get the listing ID
        const title = jQuery(this).parent().find('#wpestate_review_title').val();  // Review title
        const content = jQuery(this).parent().find('#wpestare_review_content').val();  // Review content
        const stars = jQuery(this).parent().find('.starselected_click').length;  // Star rating count
        const nonce = jQuery('#wpestate_review_nonce').val();  // Security nonce
        const ajaxurl = control_vars.admin_url + 'admin-ajax.php';

        // Check if the user selected stars and entered content
        if (stars > 0 && content !== '') {
            jQuery('.rating').text(control_vars.posting);  // Update the UI to show "posting" status

            // AJAX request to submit the new review
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'wpestate_post_review',
                    listing_id: listingId,
                    title: title,
                    stars: stars,
                    content: content,
                    security: nonce
                },
                success: function () {
                    jQuery('.rating').text(control_vars.review_posted);  // Update the UI to show success
                    jQuery('#wpestate_review_title, #wpestare_review_content').val('');  // Clear input fields
                },
                error: function (errorThrown) {
                    console.error('Submit review error:', errorThrown);
                }
            });
        }
    });
}

/**
 * Enable Floor Plan Functionality
 *
 * Enables floor plan interaction, allowing users to expand/collapse floor plan images 
 * and delete individual floor plans.
 */
function wpestate_enable_floor_plans() {
    // Toggle floor plan details on click
    jQuery('.front_plan_row').on('click', function (event) {
        event.preventDefault();
        jQuery(this).parent().find('.front_plan_row_image').slideUp();
        jQuery(this).next().slideDown();  // Show the selected floor plan image
    });

    // Remove a floor plan when clicking the delete button
    jQuery('.deleter_floor').on('click', function () {
        jQuery(this).parent().remove();  // Remove the floor plan from the DOM
    });
}

/**
 * Floor Plans Lightbox Initialization
 *
 * This function initializes the lightbox functionality for floor plans in the WpResidence theme.
 * It sets up event listeners for opening and closing the lightbox, initializes the Owl Carousel
 * for floor plan slides, and handles the navigation between slides.
 *
 * @package WpResidence
 * @subpackage PropertyDetails
 * @since 3.0.3
 *
 * Dependencies:
 * - jQuery
 * - Owl Carousel 2
 *
 * Usage: This function should be called after the DOM is fully loaded and Owl Carousel is available.
 */


function estate_start_lightbox_floorplans() {
    "use strict";
    // Variable to store the slide index to jump to
    var jump_slide;

    // Close lightbox when the close button is clicked
    jQuery('.lighbox-image-close-floor').on('click', function(event) {
        event.preventDefault();
        // Reset z-index values for header elements
        jQuery('.master_header').css('z-index', '100');
        jQuery('.container').css('z-index', '2');
        jQuery('.header_media').css('z-index', 3);
        // Hide the lightbox
        jQuery('.lightbox_property_wrapper_floorplans').hide();
    });
    let is_rtl=false;
    if(control_vars.is_rtl==='1'){
        is_rtl=true; 
   }
    // Initialize Owl Carousel for floor plans
    jQuery("#owl-demo-floor").owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        items: 1,
        dots: false,
        startPosition: 1,
        rtl:is_rtl,
        navText: [
            '<button class="carousel-control-prev wpresidence-carousel-control" type="button" data-bs-target="#carousel-property-page-header" data-bs-slide="prev"><i class="demo-icon icon-left-open-big"></i><span class="visually-hidden">Previous</span></button>',
            '<button class="carousel-control-next wpresidence-carousel-control" type="button" data-bs-target="#carousel-property-page-header" data-bs-slide="next"><i class="demo-icon icon-right-open-big"></i><span class="visually-hidden">Next</span></button>'
        ]
    });

    // Open lightbox when a floor plan trigger is clicked
    jQuery('.lightbox_trigger_floor').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        // Show the lightbox
        jQuery('.lightbox_property_wrapper_floorplans').show();
        // Adjust z-index values for header elements
        jQuery('.master_header').css('z-index', '0');
        jQuery('.container').css('z-index', '1');
        jQuery('.header_media').css('z-index', 1);
        // Calculate the slide index to jump to
        jump_slide = parseInt(jQuery(this).attr('data-slider-no')) - 1;
        // Get the carousel instance and jump to the specific slide
        var carousel = jQuery("#owl-demo-floor");
        carousel.owlCarousel();
        carousel.trigger("to.owl.carousel", [jump_slide, 1, true]);
    });
}

/**
 * Show Statistics Accordion
 *
 * This function displays a bar chart with property views statistics on the page.
 * It checks if the chart container is present, and if so, initializes the Chart.js bar chart.
 */
function wpestate_show_stat_accordion() {
    // Check if the chart element exists; if not, exit the function early
    const chartElement = document.getElementById('myChart');
    if (!chartElement) {
        return;
    }

    // Get the context for Chart.js
    const ctx = jQuery(chartElement).get(0).getContext("2d");

    // Parse chart labels and data from the global variables
    const labels = JSON.parse(wpestate_property_vars.singular_label);
    const trafficData = JSON.parse(wpestate_property_vars.singular_values);

    // Define chart data and dataset configuration
    const data = {
        labels: labels,
        datasets: [{
            label: wpestate_property_vars.property_views,
            backgroundColor: "rgba(220,220,220,0.5)",  // Bar fill color
            borderColor: "rgba(220,220,220,0.8)",      // Bar stroke color
            hoverBackgroundColor: "rgba(220,220,220,0.75)",  // Highlight fill color
            hoverBorderColor: "rgba(220,220,220,1)",   // Highlight stroke color
            data: trafficData
        }]
    };

    // Chart options for configuring the display
    const options = {
        title: {
            display: false,
            text: 'Page Views'  // Chart title
        },
        scales: {
            y: {
                beginAtZero: true,  // Start y-axis from zero
                grid: {
                    color: "rgba(0,0,0,.05)",  // Grid line color
                    lineWidth: 1  // Grid line width
                }
            },
            x: {
                grid: {
                    display: true  // Show grid lines on x-axis
                }
            }
        },
        barPercentage: 0.9,  // Adjust bar width
        categoryPercentage: 0.8,  // Adjust space between bars
        responsive: true,  // Make chart responsive
        plugins: {
            legend: {
                display: false  // Disable legend
            }
        }
    };

    // Create the bar chart using Chart.js
    new Chart(ctx, {
        type: 'bar',  // Specify chart type as bar chart
        data: data,
        options: options
    });
}



/**
 * Initialize the Lightbox Slick Slider
 *
 * This function sets up and initializes a slick slider for property images inside the lightbox.
 * It handles slide navigation, autoplay, and closing the lightbox. It is triggered when
 * the lightbox is opened and the slider element is present.
 *
 * Dependencies:
 * - jQuery
 * - Slick Slider plugin
 *
 * @package WpResidence
 * @since 3.0.3
 */

function estate_start_lightbox_slickslider() {
    
    // Loop through each #owl-demo element and initialize the slick slider
    jQuery('#owl-demo').each(function() {

        var slick;
        let is_rtl=false;
       
        if(control_vars.is_rtl==='1'){
             is_rtl=true; 
        }
        // Initialize slick slider only if it is not already initialized
        slick = jQuery(this).not('.slick-initialized').slick({
            infinite: true, // Enables infinite scrolling of slides
            slidesToShow: 1, // Only show one slide at a time
            slidesToScroll: 1, // Scroll through one slide at a time
            dots: false, // Do not show dots for slide navigation
            autoplay: false, // Disable autoplay for the slider
            rtl:is_rtl,
            nextArrow: '<button class="carousel-control-prev wpresidence-carousel-control" type="button" data-bs-target="#wpresidence-blog-post-carousel-bootstrap" data-bs-slide="prev"><i class="demo-icon icon-left-open-big"></i><span class="visually-hidden">Previous</span></button>', // Custom "Next" button
            prevArrow: '<button class="carousel-control-next wpresidence-carousel-control" type="button" data-bs-target="#wpresidence-blog-post-carousel-bootstrap" data-bs-slide="next"><i class="demo-icon icon-right-open-big"></i><span class="visually-hidden">Next</span></button>' // Custom "Previous" button
        });
        

        // Event handler: Opens the lightbox and navigates to the clicked slide
        jQuery('.lightbox_trigger').on('click', function(event) {
            event.preventDefault(); // Prevent default action of the link

            // Get the slide number from the data-slider-no attribute and adjust it for zero-based index
            var jump_slide = parseInt(jQuery(this).attr('data-slider-no')) - 1;

            // Navigate to the selected slide
            jQuery('#owl-demo').slick('slickGoTo', jump_slide);

            // Display the lightbox wrapper
            jQuery('.lightbox_property_wrapper').show();
        });

        // Event handler: Closes the lightbox when the close button is clicked
        jQuery('.lighbox-image-close').on('click', function(event) {
            event.preventDefault(); // Prevent default action of the close button

            // Hide the lightbox wrapper
            jQuery('.lightbox_property_wrapper').hide();
        });
    });
}

 
/**
 * Initialize Lightbox with Owl Carousel or Slick Slider
 * 
 * This function sets up the lightbox slider for the property images, either using
 * the Owl Carousel or Slick Slider based on the theme settings. It handles navigation,
 * opening, and closing of the lightbox.
 *
 * Dependencies:
 * - jQuery
 * - Owl Carousel
 * - Slick Slider (conditionally loaded based on theme settings)
 *
 * @package WpResidence
 * @since 3.0.3
 */

function estate_start_lightbox() {
    "use strict"; // Enforce strict mode for better error checking

    // If the theme is set to use Slick Slider, initialize it and return
    if (control_vars.wp_estate_lightbox_slider === 'slick') {
        estate_start_lightbox_slickslider();
        return; // Exit the function if Slick Slider is initialized
    }

    var jump_slide;
    let is_rtl=false;
    if(control_vars.is_rtl==='1'){
         is_rtl=true;
    }

    // Initialize Owl Carousel with the required options
    var owl = jQuery("#owl-demo").owlCarousel({
        loop: true,           // Enable infinite loop of slides
        margin: 0,            // Set margin between slides to 0
        nav: true,            // Show navigation arrows
        items: 1,             // Display one item at a time
        dots: false,          // Disable dots for slide navigation
        startPosition: 1,     // Start from the second item (1-indexed)
        rtl:is_rtl,
        navText: [            // Custom HTML for navigation buttons
            '<button class="carousel-control-prev wpresidence-carousel-control" type="button" data-bs-target="#carousel-property-page-header" data-bs-slide="prev"><i class="demo-icon icon-left-open-big"></i><span class="visually-hidden">Previous</span></button>',
            '<button class="carousel-control-next wpresidence-carousel-control" type="button" data-bs-target="#carousel-property-page-header" data-bs-slide="next"><i class="demo-icon icon-right-open-big"></i><span class="visually-hidden">Next</span></button>'
        ]
    });

    // Event handler: Opens the lightbox and navigates to the selected slide
    jQuery('.lightbox_trigger').on('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Get the slide number from the data-slider-no attribute and adjust for zero-based index
        jump_slide = parseInt(jQuery(this).attr('data-slider-no')) - 1;

        // Navigate to the selected slide within the Owl Carousel
        var carousel = jQuery("#owl-demo");
        carousel.owlCarousel();
        carousel.trigger("to.owl.carousel", [jump_slide, 1, true]);

        // Display the lightbox wrapper
        jQuery('.lightbox_property_wrapper').show();
    });

    // Event handler: Closes the lightbox when the close button is clicked
    jQuery('.lighbox-image-close').on('click', function(event) {
        event.preventDefault(); // Prevent the default close action

        // Hide the lightbox wrapper
        jQuery('.lightbox_property_wrapper').hide();
    });
}




/**
 * Schedule Tour Slider Initialization
 *
 * This function initializes the schedule tour slider for property tour scheduling options.
 * It recalculates the slider every time the "Schedule a Tour" tab is clicked to ensure proper rendering.
 * It also manages interactions such as selecting tour times and specific dates.
 *
 * Features:
 * - Initializes Slick sliders based on the number of visible items set via a data attribute.
 * - Supports right-to-left (RTL) layout when specified in control_vars.
 * - Highlights selected tour options and dates.
 *
 * Dependencies:
 * - jQuery
 * - Slick Slider
 * - Bootstrap tabs (for tab switching)
 */

function wpestate_schedule_tour_slider() {
    // Initialize or reinitialize schedule tour slider
    function initScheduleSlider() {
        jQuery('.wpestate_property_schedule_dates_wrapper').each(function () {
            var items = parseInt(jQuery(this).attr('data-visible-items')); // Get the number of visible items for the slider
            var isRTL = control_vars.is_rtl === '1';
            // If Slick is already initialized, destroy it first to ensure recalculation
            if (jQuery(this).hasClass('slick-initialized')) {
                jQuery(this).slick('unslick');
            }

            // Initialize Slick slider
            jQuery(this).slick({
                infinite: true,                // Enable infinite scrolling
                slidesToShow: items,           // Show the number of items specified in the data attribute
                slidesToScroll: 1,             // Scroll one slide at a time
                dots: false,                   // Disable navigation dots
                rtl: isRTL,
                responsive: [
                    {
                        breakpoint: 480,       // For screens smaller than 480px
                        settings: {
                            slidesToShow: 3,   // Show 3 slides
                            slidesToScroll: 1  // Scroll 1 slide at a time
                        }
                    }
                ]
            });

           
        });
    }

    // Initialize the slider on page load (for visible sliders)
    initScheduleSlider();

    // Reinitialize the slider when the specific "Schedule a Tour" tab is clicked
    jQuery('#tab_property_schedule-tab').on('click', function () {
        initScheduleSlider(); // Recalculate the slider when this tab is clicked
    });
    
    // Reinitialize the slider when the specific "Schedule a Tour" tab is clicked
    jQuery('#sidebar-schedule-tab').on('click', function () {
        initScheduleSlider(); // Recalculate the slider when this tab is clicked
    });


    // Handle clicking on schedule tour options (e.g., Morning, Afternoon, Evening)
    jQuery('.wpestate_display_schedule_tour_option').on('click', function () {
        var parent = jQuery(this).parent();
        parent.find('.wpestate_display_schedule_tour_option').removeClass('shedule_option_selected'); // Remove selected class from other options
        jQuery(this).addClass('shedule_option_selected'); // Add selected class to the clicked option
    });

    // Handle clicking on specific dates for scheduling (e.g., selecting a particular day)
    jQuery('.wpestate_property_schedule_singledate_wrapper').on('click', function () {
        var parent = jQuery(this).parent();
        parent.find('.wpestate_property_schedule_singledate_wrapper').removeClass('shedule_day_option_selected'); // Remove selected class from other dates
        jQuery(this).addClass('shedule_day_option_selected'); // Add selected class to the clicked date
    });
}



