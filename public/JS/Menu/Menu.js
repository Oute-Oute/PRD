const mobileScreen = window.matchMedia("(max-width: 990px )");
$(document).ready(function () {
    document.body.style.backgroundColor = "red";

    $(".dashboard-nav-dropdown-toggle").click(function () {
        $(this).closest(".dashboard-nav-dropdown")
            .toggleClass("show")
            .find(".dashboard-nav-dropdown")
            .removeClass("show");
        $(this).parent()
            .siblings()
            .removeClass("show");
    });
    $(".menu-toggle").click(function () {
        if (mobileScreen.matches) {
            $(".dashboard-nav").toggleClass("mobile-show");
        } else {
            $(".dashboard").toggleClass("dashboard-compact");
        }
    });
});

$(document).ready(function () {

        let test = document.getElementById('appointment');
        test.toggle("active");
});