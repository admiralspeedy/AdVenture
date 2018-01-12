function deleteConfirm(event) //Confirm ad deletion
{
    if(event.target.innerHTML == "Delete Ad") //The user is trying to delete an ad
    {
        event.preventDefault(); //Don't delete it on the first click

        //Update the button to confirm deletion
        event.target.innerHTML = "Are you sure?";
        event.target.style = "margin-left:5px;color:red";
    }
}

window.addEventListener('load', function()
{
    //Get the user's ID from the hidden element
    var id = Number($('#userid').html());

    //Get the user's ads
    $.post("/system/userads?id=" + id,
        function(data)
        {
            if (data.success) //Ads were returned
            {
                for (i = 0; i < data.ads.length; i++) //Loop through each ad
                {
                    //Create the HTML for each ad
                    var div1 = document.createElement("div");
                    div1.className = "col-xs-12";

                    var div2 = document.createElement("div");
                    div2.className = "media shop-list-media";

                    var div3 = document.createElement("div");
                    div3.className = "media-left";

                    var a1 = document.createElement("a");
                    a1.href = "/ad?id=" + data.ads[i].id;

                    var img = document.createElement("img");
                    img.src = "/uploads/" + data.ads[i].image[0];
                    img.className = "img-responsive profile-image";

                    var div4 = document.createElement("div");
                    div4.className = "media-body";

                    var h3 = document.createElement("h3");
                    h3.innerHTML = data.ads[i].title;

                    var a2 = document.createElement("a");
                    a2.href = "/ad?id=" + data.ads[i].id;

                    var h1 = document.createElement("h1");
                    h1.innerHTML = "$" + data.ads[i].price.toFixed(2);

                    var p = document.createElement("p");
                    p.innerHTML = data.ads[i].description;
                    p.className = "profile-ad-desc";

                    var a3 = document.createElement("a");
                    a3.href = "/ad?id=" + data.ads[i].id;
                    a3.innerHTML = "View Details";
                    a3.className = "btn btn-default";

                    var a4 = document.createElement("a");
                    a4.href = "/delete?id=" + data.ads[i].id;
                    a4.style = "margin-left:5px";
                    a4.innerHTML = "Delete Ad";
                    a4.className = "btn btn-default";
                    a4.addEventListener("click", deleteConfirm);

                    var hr = document.createElement("hr");

                    a1.appendChild(img);
                    div3.appendChild(a1);

                    h3.appendChild(a2);
                    div4.appendChild(h3);
                    div4.appendChild(h1);
                    div4.appendChild(p);
                    div4.appendChild(a3);
                    div4.appendChild(a4);

                    div2.appendChild(div3);
                    div2.appendChild(div4);

                    div1.appendChild(div2);
                    div1.appendChild(hr);

                    $('#userads').append(div1); //Add each ad to the page
                }
            }
            else //No ads were returned
            {
                $('#userads').html(data.message); //Add the error message to the page
            }
        }, "json"
    );

    //Handle the message button
    $('#messageBtn').click(function(){
        $.post("/system/openconvo?userid=" + $("#userid").html(), //Get the conversation ID
            function(data)
            {
                if(data.success) //An ID was returned
                {
                    window.open("/convo?id=" + data.id, "", "width=500,height=660,resizable=no,toolbar=no"); //Open the conversation
                }
            }, "json"
        );
    });

    //Handle the ban button
    $('#banBtn').click(function(){


        if($('#banBtn').html() == "Ban") //The admin is clicking the ban button for the first time
        {
            //Update the button to confirm
            $('#banBtn').html("Are you sure?");
            $('#banBtn').css("background-color", "red");
        }
        else //The admin is clicking the ban button for the second time
        {
            $.post("/system/ban?userid=" + $("#userid").html(), //Ban the user
                function()
                {
                    window.location.href = '/'; //Send the admin back to the homepage
                }
            );
        }
    });
}, false);