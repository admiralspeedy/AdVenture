function remainingDescriptionChars(event) //Show a count of the remaining characters for a text area
{
    //Update the description character counter
    var maxChar = 500;
    var text = document.getElementById("adDescription").value;
    var textLength = text.length;
    document.getElementById("adDescriptionCharCount").innerHTML = text.length;
}

function remainingRestrictionChars(event) //Show a count of the remaining characters for a text area
{
    //Update the restrictions character counter
    var maxChar = 500;
    var text = document.getElementById("adRestrictionsPreferences").value;
    var textLength = text.length;
    document.getElementById("adRestrictionsPreferencesCharCount").innerHTML = text.length;
}

var adType = "property"; //Set the ad type

window.addEventListener('load', function()
{
    //Add event listeners for the character counters
    document.getElementById("adDescription").addEventListener("keyup", remainingDescriptionChars);
    document.getElementById("adRestrictionsPreferences").addEventListener("keyup", remainingRestrictionChars);
}, false);