/**
 * Created by jauhien on 8.1.15.
 */

function ChooseFunction()
{
    var vals = []
    $('input:checkbox[name="check[]"]').each(function() {
        if (this.checked) {
            vals.push(this.value);
        }
    });
    document.getElementById("demo").innerHTML = vals;
}

function httpGet(theUrl)
{
    var xmlHttp = null;

    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send( null );
    return xmlHttp.responseText;
}