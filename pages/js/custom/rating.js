window.addEventListener('load', function()
{
    //Get the user's ID from the hidden element
    var id = Number($('#userid').html());

    //Update the visible rating when the user hovers over the stars and reset back to the correct value when they stop hovering
    $('#star1').hover(
        function(){
            $('#star1').css("color", "#26bbad");
            $('#star2').css("color", "lightgrey");
            $('#star3').css("color", "lightgrey");
            $('#star4').css("color", "lightgrey");
            $('#star5').css("color", "lightgrey");
        },
        function(){resetRating(rating)}
    );

    $('#star2').hover(
        function(){
            $('#star1').css("color", "#26bbad");
            $('#star2').css("color", "#26bbad");
            $('#star3').css("color", "lightgrey");
            $('#star4').css("color", "lightgrey");
            $('#star5').css("color", "lightgrey");
        },
        function(){resetRating(rating)}
    );

    $('#star3').hover(
        function(){

            $('#star1').css("color", "#26bbad");
            $('#star2').css("color", "#26bbad");
            $('#star3').css("color", "#26bbad");
            $('#star4').css("color", "lightgrey");
            $('#star5').css("color", "lightgrey");
        },
        function(){resetRating(rating)}
    );

    $('#star4').hover(
        function(){

            $('#star1').css("color", "#26bbad");
            $('#star2').css("color", "#26bbad");
            $('#star3').css("color", "#26bbad");
            $('#star4').css("color", "#26bbad");
            $('#star5').css("color", "lightgrey");
        },
        function(){resetRating(rating)}
    );

    $('#star5').hover(
        function(){

            $('#star1').css("color", "#26bbad");
            $('#star2').css("color", "#26bbad");
            $('#star3').css("color", "#26bbad");
            $('#star4').css("color", "#26bbad");
            $('#star5').css("color", "#26bbad");
        },
        function(){resetRating(rating)}
    );

    //Update the user's rating when a star is clicked
    $('#star1').click(function(){
        $.post("/system/rate", {userid: id, rating: 1},
            function(data)
            {
                rating = Number(data.newrating);
                resetRating(rating);
            }, "json"
        );
    });

    $('#star2').click(function(){
        $.post("/system/rate", {userid: id, rating: 2},
            function(data)
            {
                rating = Number(data.newrating);
                resetRating(rating);
            }, "json"
        );
    });

    $('#star3').click(function(){
        $.post("/system/rate", {userid: id, rating: 3},
            function(data)
            {
                rating = Number(data.newrating);
                resetRating(rating);
            }, "json"
        );
    });

    $('#star4').click(function(){
        $.post("/system/rate", {userid: id, rating: 4},
            function(data)
            {
                rating = Number(data.newrating);
                resetRating(rating);
            }, "json"
        );
    });

    $('#star5').click(function(){
        $.post("/system/rate", {userid: id, rating: 5},
            function(data)
            {
                rating = Number(data.newrating);
                resetRating(rating);
            }, "json"
        );
    });
}, false);