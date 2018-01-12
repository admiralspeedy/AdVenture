var wasEmpty = false; //Stores whether or not the page started with no conversations (for clearing the empty message)

function pollConvos() //Poll for new or updated conversations
{
    //Poll for new or updated conversations
    window.setTimeout(function(){
        $.post("/system/pollconversations",
            function(data)
            {
                if (data.success) //New or updated conversations were returned
                {
                    if($("#" + data.msgs[0].convo).length) //The conversation is already in the list
                    {
                        //Update the existing conversation
                        $("#" + data.msgs[0].convo + "message").html("<b>" + data.msgs[0].sender + "</b>: " + data.msgs[0].message);
                        $("#" + data.msgs[0].convo + "time").html(data.msgs[0].time);
                        $("#" + data.msgs[0].convo + "bg").attr('class', data.msgs[0].type);
                    }
                    else //The conversation is not already in the list
                    {
                        addConvos(data); //Add the conversation to the page
                    }
                }

                pollConvos(); //Poll for conversations again
            }, "json"
        );
    }, 1000);
}

function openConvo(convo) //Open the specified conversation in a new window
{
    window.open("/convo?id=" + convo, "", "width=500,height=660,resizable=no,toolbar=no"); //Open the specified conversation in a new window
}

function addConvos(data) //Add the specified conversations to the page
{
    if(wasEmpty) //The page started empty
    {
        $('#conversations').html(""); //Clear out the error message
        wasEmpty = false; //Change the empty state
    }

    for (i = 0; i < data.msgs.length; i++) //Loop through each conversation
    {
        //Create the HTML for each conversation
        var a = document.createElement("a");
        a.href = "#"
        a.id = data.msgs[i].convo;
        let convo = data.msgs[i].convo;
        a.addEventListener("click", function() { openConvo(convo); } );

        var div1 = document.createElement("div");
        div1.className = "modal-dialog modal-lg";

        var div2 = document.createElement("div");
        div2.className = data.msgs[i].type;
        div2.id = data.msgs[i].convo + "bg";

        var div3 = document.createElement("div");
        div3.className = "modal-header";

        var span = document.createElement("span");
        span.className = "readMessageTime";
        span.id = data.msgs[i].convo + "time";
        span.innerHTML = data.msgs[i].time;

        var h4 = document.createElement("h4");
        h4.className = "readMessageTitle";
        h4.innerHTML = data.msgs[i].otherName;

        var div4 = document.createElement("div");
        div4.className = "readMessage";
        div4.id = data.msgs[i].convo + "message";
        div4.innerHTML = "<b>" + data.msgs[i].sender + "</b>: " + data.msgs[i].message;

        div3.append(span);
        div3.append(h4);

        div2.append(div3);
        div2.append(div4);

        div1.append(div2)

        a.append(div1);

        $('#conversations').prepend(a); //Add each conversation to the page
    }
}

window.addEventListener('load', function()
{
    //Get the user's conversations
    $.post("/system/conversations",
        function(data)
        {
            if (data.success) //Conversations were returned
            {
                addConvos(data); //Add the conversations to the page
            }
            else //No conversations were returned
            {
                wasEmpty = true; //Mark the page as being empty
                $('#conversations').html(data.message); //Add the error message to the page
            }

            pollConvos(); //Start polling for conversations
        }, "json"
    );
}, false);