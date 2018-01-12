window.addEventListener('load', function()
{
    //Get the 12 newest ads when the page finishes loading
    $.post("/system/latestads",
        function(data)
        {
            if (data.success) //Ads were returned
            {
                for (i = 0; i < data.ads.length; i++) //Loop through each ad
                {
                    //Create the HTML for each ad
                    var div1 = document.createElement("div");
                    div1.className = "col-sm-6 col-md-3";

                    var div2 = document.createElement("div");
                    div2.className = "product-box";

                    var div3 = document.createElement("div");
                    div3.className = "product-thumb";

                    var img = document.createElement("img");
                    img.src = "/uploads/" + data.ads[i].image[0];
                    img.className = "img-responsive home-image";

                    var div4 = document.createElement("div");
                    div4.className = "product-overlay";

                    var span1 = document.createElement("span");

                    var a1 = document.createElement("a");
                    a1.className = "btn btn-primary";
                    a1.innerHTML = "View Details";
                    a1.href = "/ad?id=" + data.ads[i].id;

                    var div5 = document.createElement("div");
                    div5.className = "product-desc";

                    var span2 = document.createElement("span");
                    span2.className = "product-price pull-right home-price";
                    span2.innerHTML = "$" + data.ads[i].price.toFixed(2);

                    var h5 = document.createElement("h5");
                    h5.className = "home-title product-name";

                    var a2 = document.createElement("a");
                    a2.innerHTML = data.ads[i].title;
                    //a2.className = "home-title";
                    a2.href = "/ad?id=" + data.ads[i].id;

                    span1.appendChild(a1);
                    div4.appendChild(span1);
                    div3.appendChild(img);
                    div3.appendChild(div4);

                    h5.appendChild(a2);
                    div5.appendChild(span2);
                    div5.appendChild(h5);

                    div2.appendChild(div3);
                    div2.appendChild(div5);

                    div1.appendChild(div2);

                    $('#latestads').append(div1); //Add each ad to the page
                }
            }
            else //No ads were returned
            {
                $('#latestads').html(data.message); //Add the error message to the page
            }
        }, "json"
    );
}, false);