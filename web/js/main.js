
$('document').ready(function(){

    //infinite scroll
    $('.messages').jscroll({
        loadingHtml: '<img src="/img/loading.gif" alt="Loading" /> Loading...',
        padding: 20,
        nextSelector: '.paginator > a.next',
        contentSelector: '.messages',
        debug: true
    });

});