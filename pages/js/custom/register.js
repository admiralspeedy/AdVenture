window.addEventListener('load', function()
{
    //Register the user in when they submit a valid form
    $('#register-form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            //Register the user
            $.post("/system/register", $("#register-form").serialize(),
                function(data)
                {
                    if (data.success) //The user was registered
                    {
                        //Hide any error messages and redirect the user to the homepage
                        document.getElementById("register-error").style.display = "none";
                        window.location.replace("/");
                    }
                    else //The user was not registered
                    {
                        //Show the error message
                        document.getElementById("register-error").innerHTML = data.error;
                        document.getElementById("register-error").style.display = "block";
                    }
                }, "json"
            );
        }
    });
}, false);