$(document).ready(function () {


    $('.deleteBtn').on('click', function () {
        let idUser = $(this).attr('dataIdUser');
        $('.removeUser').attr('dataIdUser', idUser);
    });


    $(".removeUser").on('click', function () {
        let removeUrl = $(this).attr('dataIdUser');
        $.ajax({
            url: removeUrl,
            success: function (response) {
                window.location.href = "events";
            },
        });
    });
});