$(function(){
    // Update the count down every 1 second
    var distance = 120;
    var x = setInterval(function() {
        // Time calculations for days, hours, minutes and seconds
        var minutes = Math.floor(distance / 60);
        var seconds = Math.floor(distance % 60);

        if (seconds < 9) {
            seconds = "0" + seconds;
        }
        if (minutes < 9) {
            minutes = "0" + minutes;
        }

        $('#timer').text(minutes + "m : " + seconds + "s left");

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            $('#timer').text("EXPIRED");
            $(':input[type="submit"]').prop('disabled', true);
            $('input[type="text"]').keyup(function() {
                if($(this).val() != '') {
                    $(':input[type="submit"]').prop('disabled', false);
                }
            });
        }

        distance--;
    }, 1000);
});


