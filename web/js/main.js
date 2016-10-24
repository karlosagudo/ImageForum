
$('document').ready(function(){

    //infinite scroll
    $('.messages').jscroll({
        loadingHtml: '<img src="/img/loading.gif" alt="Loading" /> Loading...',
        padding: 20,
        nextSelector: '.paginator > a.next',
        contentSelector: '.messages'
    });

    //update counters
    var ajaxCounters =  function() {
        $.get("/update-counters", function(message) {
            $("#numberOfPosts").text(message.posts);
            $("#numberOfViews").text(message.views);
        });
    }

    setInterval(ajaxCounters, 15000);
});