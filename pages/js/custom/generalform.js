function remainingDescriptionChars(event) //Show a count of the remaining characters for a text area
{
    //Update the description character counter
    var maxChar = 500;
    var text = document.getElementById("adDescription").value;
    var textLength = text.length;
    document.getElementById("adDescriptionCharCount").innerHTML = text.length;
}

var adType = "general"; //Set the ad type

window.addEventListener('load', function()
{
    document.getElementById("adDescription").addEventListener("keyup", remainingDescriptionChars); //Add an event listener for the character counter
}, false);