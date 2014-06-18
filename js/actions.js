//Hide or show the input box
function HideBox() { $(".box").hide("slow"); }
function ShowBox() { $(".box").show("slow"); }

//Make a string safe client side
function addslashes(str)
{
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

//Actual work 
function doaction()
{
    HideBox();
	//Only proceed when the animation is finished
    $(".box").promise().done(function () {
		//Remove any decoration from the box
		document.getElementById("box").className = "box";
		
        if (window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest(); }
        else { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
				//Add decoration to the box and update its text
                var box = document.getElementById("box");
                switch (xmlhttp.status) {
                    case 200:
                        box.value = xmlhttp.response;
                        box.className += " success";
                        break;
                    case 400:
                        box.value = xmlhttp.response;
                        box.className += " failed";
                        break;
                }
            }
        }
        xmlhttp.open("POST", "handler.php", true);

        var inputurl = encodeURIComponent(addslashes(document.getElementById("box").value));
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("&input=" + inputurl);

    });
	//When done, show the box again
    ShowBox();
}