$('body').on('tap', '.removeCard', function () {
    var $this = $('#cardAvisos');
    $($this).removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).queue(function() {
        $($this).remove();
    });
});