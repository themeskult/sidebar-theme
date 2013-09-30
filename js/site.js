(function($) {
	
    $('nav ul li:last-child').after('<li class="search-item"><a href="#"></a></li>');  

    $('nav ul li a img').after("<span>" + $('nav ul li a img').attr('title')+"</span>");

    $('.search-item').click(function(){
        $('#searchform.search-overlay').fadeIn();
    }); 

    $('.close-item').click(function(){
        $('#searchform.search-overlay').hide();
    });

    $('code, pre').addClass('prettyprint');

    prettyPrint();

    // Create the dropdown base
    $("<select class='select-posts' />").insertBefore(".content-area");

    // Create default option "Go to..."
    $("<option />", {
       "selected": "selected",
       "value"   : "",
       "text"    : "Go to..."
    }).appendTo("#main select");

    // Populate dropdown with menu items
    $(".sidebar-posts li a").each(function() {
     var el = $(this);
     $("<option />", {
         "value"   : el.attr("href"),
         "text"    : el.children("span.title").text() + " — " + el.children("span.date").text()
     }).appendTo("#main select");
    });

    $("#main select").change(function() {
        window.location = $(this).find("option:selected").val();
    });

    $(".single .sidebar-posts").scrollTo($('li.current-post').prev());

    // Establish Variables
    var
        History = window.History, // Note: Using a capital H instead of a lower h
        State = History.getState(),
        $log = $('#log');
    // If the link goes to somewhere else within the same domain, trigger the pushstate
    $('.sidebar-posts li a').on('click', function(e) {
        e.preventDefault();
        var path = $(this).attr('href');
        var title = $(this).children('.title').text();
        History.pushState('ajax',title,path);

        $('.sidebar-posts li').removeClass('current-post');
        $(this).parent('li').addClass('current-post');

        $('.content-area').scrollTo(0);

        $('code, pre').addClass('prettyprint');

        prettyPrint();

    });

    // Bind to state change
    // When the statechange happens, load the appropriate url via ajax
    History.Adapter.bind(window,'statechange',function() { // Note: Using statechange instead of popstate
        load_site_ajax();
    });
    // Load Ajax
    function load_site_ajax() {
        State = History.getState(); // Note: Using History.getState() instead of event.state
        // History.log('statechange:', State.data, State.title, State.url);
        //console.log(event);
        // $("#primary").prepend('<div id="ajax-loader"><h4>Loading...</h4></div>');
        // $("#ajax-loader").fadeIn();
        $('#site-description').fadeTo(200,0);
        $('#content').fadeTo(200,.3);
        $("#primary").load(State.url + ' #content, #secondary', function(data) {
            /* After the content loads you can make additional callbacks*/
            // $('#site-description').text('Ajax loaded: ' + State.url);
            // $('#site-description').fadeTo(200,1);
            $('#content').fadeTo(200,1);
            // Updates the menu
            var request = $(data);
            $('code, pre').addClass('prettyprint');
            prettyPrint();
        });
    }


    function isiPad(){
        return (navigator.platform.indexOf("iPad") != -1);
    }


    if (isiPad() === false) {

        $('header.site-header nav ul li a').tipsy({title: function(){

            if ($(this).children('img').length > 0) {
                return $(this).children('img').attr('alt');
            }else{
                return $(this).html();
            }

        }, gravity: 'w'});

    };



    // live & set height();
    $("#sidebar").nanoScroller();



})( jQuery );