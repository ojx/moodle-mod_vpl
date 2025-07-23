#!/bin/bash
# This file is part of VPL for Moodle - http://vpl.dis.ulpgc.es/
# Script for running HTML using the PHP Built-in web server
# Copyright (C) 2022 onwards Juan Carlos Rodríguez-del-Pino
# License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
# Author Juan Carlos Rodríguez-del-Pino <jcrodriguez@dis.ulpgc.es>

# @vpl_script_description JavaScript with linked P5js processing library

# load common script and check programs
. common_script.sh

check_program php php5
PHP=$PROGRAM
if [ "$1" == "version" ] ; then
	get_program_version -v
fi

compile_typescript
compile_scss
PHPCONFIGFILE=$($PHP -i 2>/dev/null | grep "Loaded Configuration File" | sed 's/^[^\/]*//' )
if [ "$PHPCONFIGFILE" == "" ] ; then
	touch .php.ini
else
	cp $PHPCONFIGFILE .php.ini
fi
#Configure session
SESSIONPATH=$HOME/.php_sessions
mkdir $SESSIONPATH
#Generate php.ini
cat >> .php.ini <<END_OF_INI

session.save_path="$SESSIONPATH"
error_reporting=E_ALL
display_errors=On
display_startup_errors=On
END_OF_INI

#Generate router
cat >> .router.php << 'END_OF_PHP'
<?php
$path = urldecode(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH));
$file = '.' . $path;
if(is_file($file) ||
   is_file($file . '/index.php') ||
   is_file($file . '/index.html') ){
      unset($path, $file);
      return false;
}
$pclean = htmlentities($path);
http_response_code(404);
header(':', true, 404);
?>
<!doctype html>
<html><head><title>404 Not found</title>
<style>h1{background-color: aqua;text-align:center} code{font-size:150%}</style>
</head>
<body><h1>404 Not found</h1><p>The requested resource <code><?php echo "'$pclean'"; ?></code>
was not found on this server</body></html>
END_OF_PHP
# Calculate IP 127.X.X.X: (random port)
if [ "$UID" == "" ] ; then
	echo "Error: UID not set"
fi
export serverPort=$((10000+$RANDOM%50000))
export serverIP="127.$((1+$UID/1024%64)).$((1+$UID/16%64)).$((10+$UID%16))"
echo "$serverIP:$serverPort" > .vpl_localserveraddress
cat common_script.sh > vpl_webexecution
cat >> vpl_webexecution <<END_OF_SCRIPT
#!/bin/bash
$PHP -c .php.ini -S "$serverIP:$serverPort" .router.php
END_OF_SCRIPT

width=0
height=0
result=$(grep -rn "createCanvas[[:space:]]*(" sketch.js)
size=${#result}
# (as)[[:space:]]*(
if [ $size -gt 15 ]
then
    value=${result#*[[:space:]]*\(}
    # sed -E 's/-[^[:space:]]+//g' <<< "$value"
    # (.*?),/'
    if [[ "$value" =~ ([0-9]+)([[:space:]]*)[,]([[:space:]]*)([0-9]+)([[:space:]]*)[\)] ]]
    then
        # echo $value
        [[ "$value" =~ ([0-9]+) ]]
        width=${BASH_REMATCH[1]}
        value=${value#*,}
        # echo $value
        [[ "$value" =~ ([0-9]+) ]]
        height=${BASH_REMATCH[1]}
        # echo $width
        # echo $height
    # else
        # echo "No comma!"
    fi
# else
    # echo "Canvas not found"
fi

cat <<EOF >index.html
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Processing Application</title>
   <script>
        var _flag = true;


        console.log = function() {
           window.opener.postMessage({con: "log", args: JSON.stringify(arguments)}, "*");
        };

        console.warn = function() {
            window.opener.postMessage({con: "warn", args: JSON.stringify(arguments)}, "*");
        };

        console.error = function() {
            if (_flag && typeof arguments[0] === 'object' && arguments[0] instanceof Event && arguments[0].type === 'error') {
                let props = prototypeProperties(arguments[0]);
               // alert(JSON.stringify(arguments[0].constructor.name));
                if (arguments[0].target instanceof HTMLImageElement) {
                  //  alert(arguments[0].target.src);
                    _handleGlobalError("Cannot load image: '" +  arguments[0].target.src + "'", '');
                } else {
                    alert(JSON.stringify(props));
                }
            } else if (_flag && typeof arguments[0] === 'object' && arguments[0] instanceof TypeError) {
                //alert(JSON.stringify(arguments[0], [ "response", "caller", "arguments", "data", "reason", "url", "cause", "message", "stack", "lineNumber", "columnNumber"]));
               // let props = prototypeProperties(arguments[0]);
                //alert(JSON.stringify(props));

                let imIdx = arguments[0].stack.indexOf("default.loadImage");
                let brIdx = arguments[0].stack.indexOf('(');
                if (imIdx > 0 && brIdx > 0 && imIdx < brIdx) { // image being fetched
                    const [, filename, line, column ] = arguments[0].stack.match(/(sketch.js):(\d*):(\d*)/);
                    if (filename === 'sketch.js') {
                        _handleGlobalError("Cannot fetch image", '', line, column);
                    } else {
                         _handleGlobalError("Cannot fetch image resourse", '');
                    }
                }

                if (arguments[0] instanceof Error) {

                }

            } else {
                window.opener.postMessage({con: "error", args: JSON.stringify(arguments)}, "*");
            }
        };

        function prototypeProperties(obj) {
          var result = [];
          for (var prop in obj) {
            if (!obj.hasOwnProperty(prop)) {
              result.push(prop);
            }
          }
          return result;
        }

        function _handleGlobalError(message, url, linenumber, charNumber, err) {
            //console.log("Error Occured!");
            let str = message;
            if (linenumber !== undefined) {
                str += ' on (or near) line ' + linenumber;
            }
            let arr = [str];
            let imgUrl = false;
            window.opener.postMessage({con: "error", args: JSON.stringify(arr)}, "*");
            if (_flag === true || (millis && millis() < 2000)) {
                let html = "<div class=\"_error-container\"><div style=\"text-align: center; margin-top: 10px\"><span style=\"font-family: 'Lato', sans-serif; display: inline-block; padding: 2px 5px; border: solid 1px darkred; border-radius: 5px; background-color: darkred; color: whitesmoke\">~ ERROR ~</span></div>";

                if (message.indexOf("Unexpected end of input") >= 0) {
                    html += "<div>" + str + "</div>";
                    html += "<div>&#128161; You may be missing a closing brace!</div>";
                } else if (message.indexOf(" is not defined") > 0 && message.indexOf(" is not defined") < 20) {
                    let spc = str.indexOf(' ');
                    let wrd = str.substring(0, spc);
                    let rest = str.substring(spc);
                    html += "<div><span>" + wrd + "</span>" + rest + "</div>";
                    html += "<div>&#128161; This could be a typo, check your identifier names!</div>";
                    html += "<div>&#128161; You may be using a variable or function that hasn't been declared yet!</div>";
                } else if (message.indexOf(" is not a constructor") > 0 && message.indexOf(" is not a constructor") < 20) {
                    let spc = str.indexOf(' ');
                    let wrd = str.substring(0, spc);
                    let rest = str.substring(spc);
                    html += "<div><span>" + wrd + "</span>" + rest + "</div>";
                    html += "<div>&#128161; This could be a typo, check the spelling!</div>";
                    html += "<div>&#128161; You may be using a JavaScript library not included in this sketch!</div>";
                } else if (message.indexOf("Uncaught ReferenceError: Cannot access '") >= 0) {
                    let idx = str.indexOf("Uncaught ReferenceError: Cannot access '") + "Uncaught ReferenceError: Cannot access '".length;
                    let rest = message.substring(idx);
                    let spc = rest.indexOf("'");
                    let wrd = rest.substring(0, spc);
                    rest = rest.substring(spc + 1);
                    html += "<div>" + str.substring(0, idx - 1) + " <span>" + wrd + "</span>" + rest + "</div>";
                    html += "<div>&#128161; Be sure to declare your variables and functions before using them!</div>";
                } else if (message.indexOf("Uncaught ReferenceError:") >= 0) {
                    let idx = str.indexOf("Uncaught ReferenceError:") + "Uncaught ReferenceError:".length + 1;
                    let rest = message.substring(idx);
                    let spc = rest.indexOf(' ');
                    let wrd = rest.substring(0, spc);
                    rest = rest.substring(spc);
                    html += "<div>" + str.substring(0, idx) + " <span>" + wrd + "</span>" + rest + "</div>";
                    html += "<div>&#128161; Check for typos!</div>";
                } else if (message.indexOf("SyntaxError: Unexpected identifier") >= 0) {
                    let idx = str.indexOf("SyntaxError: Unexpected identifier") + "SyntaxError: Unexpected identifier".length + 2;
                    let rest = message.substring(idx);
                    let spc = rest.indexOf("'");
                    let wrd = rest.substring(0, spc);
                    rest = rest.substring(spc + 1);
                    html += "<div>" + str.substring(0, idx - 2) + " <span>" + wrd + "</span>" + rest + "</div>";
                    html += "<div>&#128161; Did you start a function without an opening brace?</div>";
                } else if (message.indexOf("Uncaught SyntaxError: missing ) ") >= 0) {
                    let idx = str.indexOf("Uncaught SyntaxError: missing ) ") + "Uncaught SyntaxError: missing ) ".length - 2;
                    let rest = message.substring(idx + 1);
                    html += "<div>" + str.substring(0, idx) + " <span>)</span>" + rest + "</div>";
                    html += "<div>&#128161; Check that your parenthesis match <span>(</span> and <span>)</span></div>";
                    html += "<div>&#128161; Check that any strings have matching quotations!</span></div>";
                } else if (message.indexOf("SyntaxError: Invalid destructuring assignment") >= 0) {
                    html += "<div>" + str + "</div>";
                    html += "<div>&#128161; You may be missing a closing parenthsis for a loop or conditional statement!</div>";
                } else if (message.indexOf("Cannot fetch image") == 0) {
                    html += "<div>" + str + "</div>";
                    html += "<div>&#128161; Check the file is included in your project or the URL is spelled correctly</div>";
                    html += "<div>&#128161; Some websites will not allow you to fetch their images on remote servers</div>";
                } else if (message.indexOf("Cannot load image:") == 0) {
                    imgUrl = str.substring(20);
                    imgUrl = imgUrl.substring(0, imgUrl.length - 1);
                    let dojoIdx = imgUrl.indexOf("https://dojojail.com/");
                    if (dojoIdx == 0) {
                        imgUrl = imgUrl.substring(21);
                    }
                  //  alert(imgUrl);
                    html += "<div>Cannot load image:<br/><span>" + imgUrl + "</span></div>";
                    if (dojoIdx == 0) {
                        html += "<div>&#128161; Check the file is included in your project and that the spelling is correct</div>";
                    } else {
                        html += "<div>&#128161; Check the URL of this resource</div>";
                    }
                } else {
                    html += "<div>" + str + "</div>";
                }
                html += "</div>"; // close container

                document.body.innerHTML = html;
                fetch("sketch.js", { method: "GET"}).then((response) => { return response.text(); }).then((data) => {
                    let lines = data.split(/\r?\n/);

                    if (imgUrl !== false) {
                        let srch = imgUrl;
                        let idx = imgUrl.lastIndexOf('/');

                        if (idx >= 0) {
                            let srch = imgUrl.substring(idx + 1);
                        }

                        for (let _i = 0; _i < lines.length; _i++) {
                            if (lines[_i].indexOf(srch) >= 0) {
                                linenumber = _i + 1;
                                charNumber = lines[_i].indexOf(srch) + 1;
                                break;
                            }
                        }
                    }

                    if (linenumber !== undefined && linenumber <= lines.length) {
                        linenumber = parseInt(linenumber);
                        html += "<div style=\"white-space: pre-wrap; padding-left: 30px; font-family: Menlo, 'Consolas','Roboto Mono', 'Courier', monospace;\">";
                        let len = ("" + linenumber).length;
                        if (linenumber < lines.length) {
                            if (("" + (linenumber + 1)).length > len) {
                                len++;
                            }
                        }
                        if (linenumber > 1) {
                            let xtra = "";
                            if (("" + (linenumber - 1)).length < len) {
                                xtra = " ";
                            }
                            html += "<span style=\"color: gray\">line " + (linenumber - 1) + ":</span> " + xtra + lines[linenumber - 2] + "<br/>";
                        }
                        let xtra = "";
                        if (("" + linenumber).length < len) {
                            xtra = " ";
                        }
                        html += "<span style=\"background-color: #FFFFAA\"><span style=\"color: gray\">line " + linenumber + ":</span> " + xtra  + lines[linenumber - 1] + "</span><br/>";
                        if (charNumber && charNumber > 0) {
                            html += "       ";
                            for (let _i = 0; _i < charNumber; _i++) {
                                html += " ";
                            }
                            html += xtra + "^<br/>";
                        }
                        if (linenumber < lines.length) {
                            html += "<span style=\"color: gray\">line " + (linenumber + 1) + ":</span> " + lines[linenumber] + "<br/>";
                        }
                        html += "</div>";
                        document.body.innerHTML = html;
                    }
                });
                window.onresize = null;
                window.resizeTo(_origWidth, 380);
                if (typeof noloop === 'function') {
                    noloop();
                }
                if (typeof remove === 'function') {
                    remove();
                }
                window["mousePressed"] = null;
                window["mouseClicked"] = null;
                window["mouseMoved"] = null;
                window["mouseDragged"] = null;
                window["mouseReleased"] = null;
                window["mouseWheel"] = null;
                window["touchEnded"] = null;
                window["touchMoved"] = null;
                window["touchStarted"] = null;
                window["keyPressed"] = null;
                window["keyReleased"] = null;
                window["keyTyped"] = null;
                window["deviceMoved"] = null;
                window["deviceShaken"] = null;
                window["deviceTurned"] = null;
            }
        }

        window.addEventListener('unhandledrejection', function (e) {
            //console.log(e);, ["message", "arguments", "type", "name"]. // e.reason.message
            // alert("Error occurred: " + JSON.stringify(e.reason, ["message", "arguments", "type", "name", "line"]));

             if (e.reason && e.reason.stack) {
                const [, filename, line, column ] = e.reason.stack.match(/\/(sketch.js):(\d*):(\d*)/); // WAS: .match(/\/([\/\w-_\.]+\.js):(\d*):(\d*)/);
                _handleGlobalError(e.reason.message, filename, line, column);

                //alert(line + "|" + column);
               //alert("Error occurred: " + JSON.stringify(e.reason, ["message", "arguments", "type", "name", "line", "error", "stack"]));
            }
        });

        window.onerror = function(message, url, linenumber, charNumber, err) {
            _handleGlobalError(message, url, linenumber, charNumber, err);
        }

    </script>
   </script>
    <!-- Include p5.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.10.0/p5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.10.0/addons/p5.sound.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/gh/molleindustria/p5.play/lib/p5.play.js"></script> -->
    <!-- <script src="/p/scripts/p5.sound.js"></script> -->
    <script>
        var _dotInterval;
        var _dot = 4;
        var _origWidth, _origHeight;
        var _width = $width;
        var _height = $height;

        _dotInterval = setInterval(_tickDots, 220);

        function _tickDots() {
            if (document.getElementById("_dot0")) {
                _dot = (_dot + 1) % 5;
                if (_dot == 4) {
                   document.getElementById("_dot0").style.visibility = "hidden";
                   document.getElementById("_dot1").style.visibility = "hidden";
                   document.getElementById("_dot2").style.visibility = "hidden";
                   document.getElementById("_dot3").style.visibility = "hidden";
                } else {
                   document.getElementById("_dot" + _dot).style.visibility = "visible";
                }
            } else {
                clearInterval(_dotInterval);
            }
        }

         function _adjust() {
            document.body.style.zoom = "100%";

            if (_width > 0 && _height > 0) {
                _sizeTo(_width, _height);

         //       window.moveTo(newX, window.screenTop);
            }

            window.onmessage = function(ev) {
                if (_flag === true) {
                   if (ev.data && ev.data.status && ev.data.status === 'ok' && window["width"] > 0) {
                        ev.preventDefault();
                        //console.log(ev);
                        // _win = ev.source;
                        window.opener.postMessage("done", "*");
                        if (Math.abs(width - _width) > 5 || Math.abs(height - _height) > 5) {
                            _sizeTo(width, height);
                 //           let newX = Math.round(window.screenLeft + ((ev.data.width - window.outerWidth ) / 2));
                        }
                        _flag = false;
                        // console.log("remote test 2");
                   }
                }
            }
            window.onbeforeunload = function(ev) {
                window.opener.postMessage("closing", "*");
            }
            window.onblur = function() { this.close(); };
        }

        function _sizeTo(_w, _h) {
            //let scale = window.devicePixelRatio;

            const y = window.top.outerHeight / 2 + window.top.screenY - (_h / 2);
            const x = window.top.outerWidth / 2 + window.top.screenX - (_w / 2);

            _origWidth = Math.round(_w + window.outerWidth - window.innerWidth);
            _origHeight = Math.round(_h + window.outerHeight - window.innerHeight);

            window.resizeTo(_origWidth, _origHeight);
            window.moveTo(x, y); // WAS: window.screenTop

            window.onresize = function() {
                window.resizeTo(_origWidth, _origHeight);
            }
        }

    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');
        main {
            display: block;
            text-align: center;
            margin: 0px;
            padding: 0px;
        }
        body {
            margin: 0px;
            padding: 0px;
            background-color: transparent;
            height: 100%;
            overflow: hidden;
        }
        div._error-container {
            margin-bottom: 20px;
        }
        div._error-container div {
            color: darkred;
            font-family: 'Lato', sans-serif; margin: 10px 10px
        }
        div._error-container span {
            font-weight: bold;
            border-bottom: dotted 1px #aaaaaa;
            font-family: Menlo, 'Consolas','Roboto Mono', 'Courier', monospace;
        }
        div#p5_loading {
            text-align: center;
            vertical-align: middle;
            margin-top: 25%;
            width: 100%;
            font-family: 'Lato', sans-serif;
            font-size: 12pt;
        }
    </style>
    <meta charset="utf-8" />
  </head>
  <body onload="_adjust()">
    <script src="sketch.js"></script>
    <div id="p5_loading" style="">
      <span style="background-color: #eeeeee; padding: 4px 12px; border: solid 1px #eeeeee; border-radius: 8px">Loading Program<span style="visibility: hidden" id="_dot0">&nbsp;&nbsp; .</span><span style="visibility: hidden" id="_dot1">&nbsp; .</span><span style="visibility: hidden" id="_dot2">&nbsp; .</span><span style="visibility: hidden" id="_dot3">&nbsp; .</span></span></div>
  </body>
</html>
EOF

chmod +x vpl_webexecution
# cat .router.php
# ls -a
# cat .router.php
# grep -Rnw '.' -e "Open"
# cat vpl_webexecution
apply_run_mode