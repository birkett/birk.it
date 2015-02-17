function addslashes(str)
{
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

function doaction()
{
    $(".box").hide("slow");
    $(".box").promise().done(function () {
        var box = document.getElementById("box");
        box.className = "box";
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4) {
                switch (xmlhttp.status) {
                    case 200: box.className += " success"; break;
                    case 400: box.className += " failed"; break;
                }

                box.value = xmlhttp.response;
            }

        }
        xmlhttp.open("POST", "index.php", true);
        var inputurl = encodeURIComponent(addslashes(box.value));
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("&input=" + inputurl);
    });
    $(".box").show("slow");

}
