var lastResults; //Stores the last set of search results
var curPage = 1; //Stores the current page
var totalPages = 1; //Stores the number of pages
var itemsPerPage = 10; //Stores the number of items to show per page

function showResults(page)
{
    //Update the totalNumber of pages and the current page
    totalPages = Math.ceil(lastResults.ads.length / itemsPerPage);
    curPage = page;

    //Calculate the results to show based on the current page
    var low = itemsPerPage * (page - 1);
    var high = (page * itemsPerPage) - 1;

    if(totalPages > curPage) //The current page is less than the number of pages
    {
        $('#next').show(); //Show the next page button
    }

    if(high > lastResults.ads.length - 1) //The last page is not full
    {
        high = lastResults.ads.length - 1; //Set the high value to the end of the array
    }

    $('#searchads').html(""); //Remove the existing searc results

    for (i = low; i <= high; i++) //Loop through each ad
    {
        //Create the HTML for each ad
        var div1 = document.createElement("div");
        div1.className = "col-xs-12";

        var div2 = document.createElement("div");
        div2.className = "media shop-list-media";

        var div3 = document.createElement("div");
        div3.className = "media-left";

        var a1 = document.createElement("a");
        a1.href = "/ad?id=" + lastResults.ads[i].id;

        var img = document.createElement("img");
        img.src = "/uploads/" + lastResults.ads[i].image[0];
        img.className = "img-responsive profile-image";

        var div4 = document.createElement("div");
        div4.className = "media-body";

        var h3 = document.createElement("h3");
        h3.innerHTML = lastResults.ads[i].title;

        var a2 = document.createElement("a");
        a2.href = "/ad?id=" + lastResults.ads[i].id;

        var h1 = document.createElement("h1");
        h1.innerHTML = "$" + lastResults.ads[i].price.toFixed(2);

        var p = document.createElement("p");
        p.innerHTML = lastResults.ads[i].description;
        p.className = "profile-ad-desc";

        var a3 = document.createElement("a");
        a3.href = "/ad?id=" + lastResults.ads[i].id;
        a3.innerHTML = "View Details";
        a3.className = "btn btn-default";

        var hr = document.createElement("hr");

        a1.appendChild(img);
        div3.appendChild(a1);

        h3.appendChild(a2);
        div4.appendChild(h3);
        div4.appendChild(h1);
        div4.appendChild(p);
        div4.appendChild(a3);

        div2.appendChild(div3);
        div2.appendChild(div4);

        div1.appendChild(div2);
        div1.appendChild(hr);

        $('#searchads').append(div1); //Add each ad to the page
    }
}

window.addEventListener('load', function() {
    $.fn.validator.Constructor.FOCUS_OFFSET = 200;

    //Hide all three refined search categories
    $('#general_filter').collapse('hide');
    $('#vehicles_filter').collapse('hide');
    $('#property_filter').collapse('hide');

    //Hide the page buttons
    $('#prev').hide();
    $('#next').hide();

    //Handle when the user clicks the previous page button
    $('#prev').on('click', function () {
            if(curPage == 2)
            {
                $('#prev').hide();
                $('#next').show();
            }

            curPage--;
            showResults(curPage);
        }
    );

    //Handle when the user clicks the next page button
    $('#next').on('click', function () {
            if(curPage == totalPages - 1)
            {
                $('#next').hide();
                $('#prev').show();
            }

            curPage++;
            showResults(curPage);
        }
    );

    //Show the correct category when the user chooses an option
    $('#sel1').on('change', function () {
            if($('#sel1').val() == "Property")
            {
              $('#general_filter').collapse('hide');
              $('#vehicles_filter').collapse('hide');
              $('#property_filter').collapse('show');
              $('#property-form').validator('update');
            }
            else if($('#sel1').val() == "General")
            {
              $('#general_filter').collapse('show');
              $('#vehicles_filter').collapse('hide');
              $('#property_filter').collapse('hide');
              $('#general-form').validator('update');
            }
            else if($('#sel1').val() == "Vehicle")
            {
              $('#general_filter').collapse('hide');
              $('#vehicles_filter').collapse('show');
              $('#property_filter').collapse('hide');
              $('#vehicle-form').validator('update');
            }
            else
            {
              $('#general_filter').collapse('hide');
              $('#vehicles_filter').collapse('hide');
              $('#property_filter').collapse('hide');
            }
        }
    );

    //Perform a normal search
    $('#search-form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            $.post("/system/search", $("#search-form").serialize(),
                function(data)
                {
                    if (data.success) //Ads were returned
                    {
                        lastResults = data; //Update the global search results
                        showResults(1); //Show the first page of results
                    }
                    else //No ads were returned
                    {
                        $('#searchads').html(data.message); //Add the error message to the page
                    }
                }, "json"
            );
        }
    });

    //Perform a refined general search
    $('#general-form').validator({
        custom: {
            validmin: function($el) { //Check if the min is valid in comparison to the max
                var maxEl = $el.data("validmin");

                if ($(maxEl).val().length > 0 && $el.val().length > 0 && parseInt($(maxEl).val()) < parseInt($el.val()))
                {
                    return "Please enter a valid minimum.";
                }

            },
            validmax: function($el) { //Check if the max is valid in comparison to the min
                var minEl = $el.data("validmax")

                if ($(minEl).val().length > 0 && $el.val().length > 0 && parseInt($(minEl).val()) > parseInt($el.val()))
                {
                    return "Please enter a valid maximum.";
                }
            }
        }
    }).on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            $('#search-form').validator('validate'); //Validate the regular search bar
            $('#general-form').validator('validate'); //Validate the

            if($('#search').val().match(/^[\w\s]+$/i)) //The regular search form is valid
            {
                $.post("/system/search", $("#search-form, #type-form, #general-form").serialize(),
                    function(data)
                    {
                        if (data.success) //Ads were returned
                        {
                            lastResults = data; //Update the global search results
                            showResults(1); //Show the first page of results
                        }
                        else //No ads were returned
                        {
                            $('#searchads').html(data.message); //Add the error message to the page
                        }
                    }, "json"
                );
            }
        }
    });

    //Perform a refined vehicle search
    $('#vehicle-form').validator({
        custom: {
            validmin: function($el) { //Check if the min is valid in comparison to the max
                var maxEl = $el.data("validmin");

                if ($(maxEl).val().length > 0 && $el.val().length > 0 && parseInt($(maxEl).val()) < parseInt($el.val()))
                {
                    return "Please enter a valid minimum.";
                }

            },
            validmax: function($el) { //Check if the max is valid in comparison to the min
                var minEl = $el.data("validmax")

                if ($(minEl).val().length > 0 && $el.val().length > 0 && parseInt($(minEl).val()) > parseInt($el.val()))
                {
                    return "Please enter a valid maximum.";
                }
            }
        }
    }).on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            $('#search-form').validator('validate'); //Validate the regular search bar
            $('#vehicle-form').validator('validate'); //Validate the

            if($('#search').val().match(/^[\w\s]+$/i)) //The regular search form is valid
            {
                $.post("/system/search", $("#search-form, #type-form, #vehicle-form").serialize(),
                    function(data)
                    {
                        if (data.success) //Ads were returned
                        {
                            lastResults = data; //Update the global search results
                            showResults(1); //Show the first page of results
                        }
                        else //No ads were returned
                        {
                            $('#searchads').html(data.message); //Add the error message to the page
                        }
                    }, "json"
                );
            }
        }
    });

    //Perform a refined property search
    $('#property-form').validator({
        custom: {
            validmin: function($el) { //Check if the min is valid in comparison to the max
                var maxEl = $el.data("validmin");

                if ($(maxEl).val().length > 0 && $el.val().length > 0 && parseInt($(maxEl).val()) < parseInt($el.val()))
                {
                    return "Please enter a valid minimum.";
                }

            },
            validmax: function($el) { //Check if the max is valid in comparison to the min
                var minEl = $el.data("validmax")

                if ($(minEl).val().length > 0 && $el.val().length > 0 && parseInt($(minEl).val()) > parseInt($el.val()))
                {
                    return "Please enter a valid maximum.";
                }
            }
        }
    }).on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            $('#search-form').validator('validate'); //Validate the regular search bar

            if($('#search').val().match(/^[\w\s]+$/i)) //The regular search form is valid
            {
                $.post("/system/search", $("#search-form, #type-form, #property-form").serialize(),
                    function(data)
                    {
                        if (data.success) //Ads were returned
                        {
                            lastResults = data; //Update the global search results
                            showResults(1); //Show the first page of results
                        }
                        else //No ads were returned
                        {
                            $('#searchads').html(data.message); //Add the error message to the page
                        }
                    }, "json"
                );
            }
        }
    });

    if($('#search').val().length) //The search bar was filled when the page loaded (the user clicked a tag on an ad)
    {
        $('#search-form').submit(); //Submit the search form
    }

}, false);