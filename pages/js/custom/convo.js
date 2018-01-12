function addMessages(data) //Add the specified messages to the page
{
    for (i = 0; i < data.msgs.length; i++) //Loop through each message
    {
        //Create the HTML for each
        var div1 = document.createElement("div");
        div1.className = "myConvoMessage";

        var div2 = document.createElement("div");
        div2.className = "modal-dialog modal-lg";

        var div3 = document.createElement("div");
        div3.className = data.msgs[i].type;

        var div4 = document.createElement("div");
        div4.className = "modal-header";

        var span = document.createElement("span");
        span.className = "readMessageTime";
        span.innerHTML = data.msgs[i].time;

        var h4 = document.createElement("h4");
        h4.className = "readMessageTitle";
        h4.innerHTML = data.msgs[i].sender;

        var div5 = document.createElement("div");
        div5.className = "readMessage";
        div5.innerHTML = data.msgs[i].message;

        div4.append(span);
        div4.append(h4);

        div3.append(div4);
        div3.append(div5);

        div2.append(div3)

        div1.append(div2);

        $('#convoContainer').append(div1); //Add each message to the conversation
        $("#convoContainer").scrollTop(function() { return this.scrollHeight; }); //Scroll the message box to the bottom
    }
}

function pollMessages() //Poll for new messages
{
    window.setTimeout(function(){
        $.post("/system/pollmessages?convoid=" + $("#convoID").html(),
            function(data)
            {
                if (data.success) //New messages were returned
                {
                    addMessages(data); //Add the new messages to the conversation
                }

                pollMessages(); //Poll for messages
            }, "json"
        );
    }, 1000);
}

window.addEventListener('load', function()
{
    //Get the conversation's existing messages
    $.post("/system/messages?convoid=" + $("#convoID").html(),
        function(data)
        {
            if (data.success) //Messages were returned
            {
                addMessages(data); //Add the new messages to the conversation
            }

            pollMessages(); //Poll for messages
        }, "json"
    );

    //Send the specified message
    $("#newMessage").validator().on('submit', function (e) {
        if (!e.isDefaultPrevented())
        {
            e.preventDefault();

            //Create the form data to send to the server
            var customData = $("#newMessage").serializeArray(); //Store the form data as an array
            customData.push({name: 'convo', value: $("#convoID").html()}); //Push the

            $('#newMessageText').val(""); //Empty out the message box

            $.post("/system/send", $.param(customData)); //Send the message
        }
    });

    //The user pressed enter on the message box
    $('#newMessageText').keypress(function (e) {
        if(e.which == 13) //Enter
        {
            e.preventDefault(); //Don't create a new line
            $("#newMessage").submit(); //Send the message
        }
    });
}, false);