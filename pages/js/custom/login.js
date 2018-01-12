window.addEventListener('load', function()
{
    //Log the user in when they submit a valid form
    $('#login-form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            //Log the user in
            $.post("/system/login", $("#login-form").serialize(),
                function(data)
                {
                    if (data.success) //The user was logged in
                    {
                        //Hide any error messages and redirect the user to the homepage
                        document.getElementById("login-error").style.display = "none";
                        window.location.replace("/");
                    }
                    else //The user was not logged in
                    {
                        //Show the error message
                        document.getElementById("login-error").innerHTML = data.error;
                        document.getElementById("login-error").style.display = "block";
                    }
                }, "json"
            );
        }
    });
}, false);