var lastPostalCode = ""; //Stores the last entered postal code
var validPostalCode = false; //Stores whether or not the postal code is valid

window.addEventListener('load', function()
{
    //Validate the ad form with custom validators for the postal code and tags
    $('#adForm').validator({
        custom: {
            postalcode: function($el) {
                if (!$el.val().match(/^[ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVWXYZ][-|\s]?[0-9][ABCEGHJKLMNPRSTVWXYZ][0-9]$/i)) //The postal code is not formatted properly
                {
                    validPostalCode = false; //The postal code is invalid
                }
                else //The postal code is formatted properly
                {
                    if(lastPostalCode != $el.val()) //We haven't already validated this postal code
                    {
                        lastPostalCode = $el.val(); //Update the last postal code
                        validPostalCode = true; //Set the postal code as valid

                        //Use Google's geocoding API to check if the postal code exists
                        $.post("https://maps-api-ssl.google.com/maps/api/geocode/json?address=" + $el.val() + "&key=AIzaSyB68Lu051Yc0dFGcquz06xc5Iw31q2qkzA",
                            function(data)
                            {
                                if (data.status == "ZERO_RESULTS") //The postal code does not exist
                                {
                                    validPostalCode = false; //The postal code is invalid
                                    $('#postalcode').validator('validate'); //Revalidate the postal code box
                                }
                            }, "json"
                        );
                    }
                }

                if(!validPostalCode) //The postal code is not valid
                {
                    return "Please enter a valid postal code."; //Return an error message
                }
            },
            tags: function($el) {
                if (!$el.val().match(/^[a-zA-Z0-9,]+$/i))  //The tags are not formatted properly
                {
                    return "Please enter a valid set of tags." //Return an error message
                }
            }
        }
    }).on('submit', function (e) {
        if (!e.isDefaultPrevented()) //The form was validated and submitted
        {
            e.preventDefault();

            var uploadedImages = document.getElementById('images').files; //Get the images to upload
            var formData = new FormData(); //Create a FormData object

            for(i = 0; i < uploadedImages.length; i++) //Loop through the images
            {
              formData.append(i, uploadedImages[i]); //Append the images to the FormData object
            }

            $.ajax( //Use AJAX to upload the images in the background
            {
                url: '/core/upload',
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',

                success:
                    function(data) //The AJAX request was successful
                    {
                        if (!data[0].success) //The images failed to upload
                        {
                            //Show the error message
                            document.getElementById("ad-error").innerHTML = "An error occured.";
                            document.getElementById("ad-error").style.display = "block";
                        }
                        else//The images were uploaded successfully
                        {
                            var customData = $("#adForm").serializeArray(); //Store the form data as an array
                            var imagesArray = new Array(); //Create an array for the image names

                            for(i = 0; i < data.length; i++) //Loop through the returned image names
                            {
                                imagesArray.push(data[i][i]); //Add each image name to the image array
                            }

                            customData.push({name: 'images', value: imagesArray}); //Push the image array on to the form data

                            $.post("/system/" + adType + "ad", $.param(customData), //Post the ad
                                function(data)
                                {
                                    if (data.success) //The ad was posted
                                    {
                                        //Hide any error messages and redirect the user to the ad
                                        document.getElementById("ad-error").style.display = "none";
                                        window.location.replace("/ad?id=" + data.id);
                                    }
                                    else //The ad was not posted
                                    {
                                        //Show the error message
                                        document.getElementById("ad-error").innerHTML = data.error;
                                        document.getElementById("ad-error").style.display = "block";
                                    }
                                }, "json"
                            );
                        }
                    },
                fail:
                    function() //The AJAX request was unsuccessful
                    {
                        //Show an error message
                        document.getElementById("ad-error").innerHTML = "An error occurred.";
                        document.getElementById("ad-error").style.display = "block";
                    }
            });
        }
    });
}, false);