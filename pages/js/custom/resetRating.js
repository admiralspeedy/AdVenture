function resetRating(rating) //Resets the user's visible rating
{
    //Reset the visible rating to the specified value
    if(rating == 0)
    {
        $('#star1').css("color", "lightgrey");
        $('#star2').css("color", "lightgrey");
        $('#star3').css("color", "lightgrey");
        $('#star4').css("color", "lightgrey");
        $('#star5').css("color", "lightgrey");
    }
    if(rating == 1)
    {
        $('#star1').css("color", "#26bbad");
        $('#star2').css("color", "lightgrey");
        $('#star3').css("color", "lightgrey");
        $('#star4').css("color", "lightgrey");
        $('#star5').css("color", "lightgrey");
    }
    else if(rating == 2)
    {
        $('#star1').css("color", "#26bbad");
        $('#star2').css("color", "#26bbad");
        $('#star3').css("color", "lightgrey");
        $('#star4').css("color", "lightgrey");
        $('#star5').css("color", "lightgrey");
    }
    else if(rating == 3)
    {
        $('#star1').css("color", "#26bbad");
        $('#star2').css("color", "#26bbad");
        $('#star3').css("color", "#26bbad");
        $('#star4').css("color", "lightgrey");
        $('#star5').css("color", "lightgrey");
    }
    else if(rating == 4)
    {
        $('#star1').css("color", "#26bbad");
        $('#star2').css("color", "#26bbad");
        $('#star3').css("color", "#26bbad");
        $('#star4').css("color", "#26bbad");
        $('#star5').css("color", "lightgrey");
    }
    else if(rating == 5)
    {
        $('#star1').css("color", "#26bbad");
        $('#star2').css("color", "#26bbad");
        $('#star3').css("color", "#26bbad");
        $('#star4').css("color", "#26bbad");
        $('#star5').css("color", "#26bbad");
    }
}

var rating = 0; //Global rating value

window.addEventListener('load', function()
{
    //Get the user's rating from the hidden element, and update the visible rating
    rating = Number($('#rating').html());
    $('#rating').remove();
    resetRating(rating);
}, false);