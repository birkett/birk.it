/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Anthony Birkett
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * Javascript
 *
 * @category  Javascript
 * @package   birk.it
 * @author    Anthony Birkett <anthony@a-birkett.co.uk>
 * @copyright 2015 Anthony Birkett
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link      http://birk.it
 */

/**
 * Escape a string
 * @return string Escapted string
 */
﻿function addslashes(str)
{
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

/**
 * Handle the AJAX request
 * @return void
 */
function doaction()
{
    $(".box").hide("slow");
    $(".box").promise().done(
        function () {
            var box       = document.getElementById("box");
            box.className = "box";
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) {
                    switch (xmlhttp.status) {
                        case 200:
                            box.className += " success";
                            break;

                        default:
                            box.className += " failed";
                            break;
                    }

                    box.value = xmlhttp.response;
                }

            }
            xmlhttp.open("POST", "index.php", true);
            var inputurl = encodeURIComponent(addslashes(box.value));
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("&input=" + inputurl);
        }
    );
    $(".box").show("slow");

}
