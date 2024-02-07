// Back to top button start
$(window).scroll(function () {
   if ($(this).scrollTop() > 400) {
       $('.back-top').fadeIn('slow');
   } else {
       $('.back-top').fadeOut('slow');
   }
});

$(window).scroll(function () {
   var scroll = $(window).scrollTop();

   if (scroll >= 500) {
       $(".navbar").addClass("bg-blue p-0");
   } else {
       $(".navbar").removeClass("bg-blue p-0");
   }
});