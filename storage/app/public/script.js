const searchLink = document.querySelectorAll(".searchLink");
const keywordsInput = document.getElementById("keywordsInput");

searchLink.forEach((link) => {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        keywordsInput.value = this.getAttribute("data-keywords");

        document.getElementById("searchForm").submit();
    });
});

function submitForm(bukuId) {
    const form = document.getElementById(`submitForm-${bukuId}`);

    if (form) {
        form.submit();
    }
}

document
    .getElementById("search-input")
    .addEventListener("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("search-form").submit();
        }
    });

$(document).ready(function () {
    $("#search-input").on("click", function () {
        $("#advanced-wraper").show();
    });

    $(".fa-times-circle").on("click", function () {
        $("#advanced-wraper").hide();
    });

    $(".letter-link").click(function () {
        var target = $(this).data("target");
        $("#" + target).toggle();

        $(".letter-group")
            .not("#" + target)
            .hide();

        $("html, body").animate({ scrollTop: $(document).height() }, "slow");

        return false;
    });
});

var botmanWidget = {
    frameEndpoint: "/botman/chat",
    chatServer: "/chatbot",
    title: "Perpustakaan Chatbot",
    mainColor: "#1db9cd",
    bubbleBackground: "#1db9cd",
    aboutText: "Perpustakaan Chatbot",
    introMessage:
        "Halo! Saya adalah chatbot perpustakaan. Ada yang bisa saya bantu?",
};
