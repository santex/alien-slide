<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" />
    <title>You are not alone</title>

    <link id="prettify-link" href="lib/css/prettify.css" rel="stylesheet" disabled />
    <link href="http://localhost/alien/lib/css/moon.css" class="theme" rel="stylesheet"  media="screen" />
    <link href="http://localhost/alien/lib/css/sand.css" class="theme" rel="stylesheet" />
    <link href="http://localhost/alien/lib/css/sea_wave.css" class="theme" rel="stylesheet" />
    <link href="http://localhost/alien/lib/css/default.css" class="theme" rel="stylesheet"  />
    <link href="http://localhost/alien/lib/css/common.css" rel="stylesheet" media="screen" />

    <script src="http://localhost/alien/lib/js/jquery-latest.js"></script>

    <!--script src="http://code.jquery.com/jquery-latest.js"></script-->
  <link href="http://127.0.0.1:5984/app/_design/base/basex.css" media="screen" rel="stylesheet" type="text/css">

   <style>

          pre{
              margin:30px;
              height:90%;
              background:#0000ff;
              color:#fff;
              font-weight:bold;
              font-size: 11px;
            }
            pre h1{
              font-size: 20px;
              margin:10px;
              color:black;
              padding:10px;
            }
              address{
              font-size: 12px;
              top-margin:10px;
              color:black;

            }


            #landing-slide p {

              color:#000;
              font-size: 35px;
            }
            p#disclaimer-message {
              font-size: 12px;
              background-color:#0000ff;
              color:#000;
            }

    </style>

  <title>You are not alone</title>
  </head>
  <body>

    <canvas id="canvas">    </canvas>

    <div id="ui">
      <div id="fps"></div>

      <input id="chat" type="text" style="opacity: 0; width: 70px; margin-left: -35px;">


      <h1>micros</h1>

      <div id="instructions">

      </div>

       <div id="fwa">


       </div><!-- #fwa -->

    <aside id="info">
      <section id="share">


      </section>

        <section id="wtf">
        </section>
        <section id="concept">
        </section>
      </aside>
            <aside id="frogMode">

                <section id="tadpoles">
                    <h4></h4>
                    <ul id="tadpoleList">
                    </ul>
                </section>
                <section id="console">
                    <h4></h4>
                </section>
            </aside>

      <div id="cant-connect">
        Ooops.<br>
      </div>
      <div id="unsupported-browser">
        <p>
          Your browser doesn't support our technology. Either it is too old or <a rel="external" href="http://en.wikipedia.org/wiki/WebSocket">WebSockets</a> is disabled.
          We recommend one of the following browsers.
        </p>
        <ul>
          <li><a rel="external" href="http://www.google.com/chrome">Google Chrome</a></li>
          <li><a rel="external" href="http://apple.com/safari">Safari 4</a></li>
          <li><a rel="external" href="http://www.mozilla.com/firefox/">Firefox 4</a></li>
          <li><a rel="external" href="http://www.opera.com/">Opera 11</a></li>
        </ul>
        <p>
          <a href="#" id="force-init-button">I don't care. Let me in!</a>
        </p>
      </div>

    </div>

    <script src="http://localhost/alien/lib/js/Keys.js"></script>


<div id="main-container" style="color:ffffcc; top:10px; position:absolute;">
<div id="main">
<ul id="toc-list"><li></li></ul>
<div style="display:none;" id="webSocketSupp"></div>
<div style="display:none;" id="noWebSocketSupp">
<p>Uh-oh, the browser you're using doesn't have native support for WebSocket. That means you can't run this demo.</p>  <p>The following link lists the browsers that support WebSocket:</p>
</div>

<div id="echo">
<div id="echo-config">

<div id="chatText"></div>

<div style="display:none;">
<button id="send" class="box">Send</button>
<input id="wsUri" size="13" value="ws://127.0.0.1:3000" disabled="">
 <input type="checkbox" id="secureCb" onclick="toggleTls();" style="display:none;" disabled="">
   <span id="secureCbLabel" style="font-size: smaller; color: rgb(153, 153, 153); display: none;">Use secure WebSocket (TLS)</span>
    <button id="connect" disabled="">Connect</button>
    <button id="disconnect">Disconnect</button>
    <button id="clearLogBut">Clear log</button>
</div>

     <div id="consoleLog" style="color:ffffcc;"><p style="word-wrap: break-word;">SENT: hi</p></div>
      </div>
<div class="clearfix"></div>

 <script>
 var secureCb;
 var secureCbLabel;
 var wsUri;
 var consoleLog;
 var connectBut;
 var disconnectBut;
 var sendMessage;
 var sendBut;
 var clearLogBut;
 function echoHandlePageLoad()
 {
 if (window.WebSocket)
 {
 document.getElementById("webSocketSupp").style.display = "block";
 }
 else
 {
 document.getElementById("noWebSocketSupp").style.display = "block";
 }
 secureCb = document.getElementById("secureCb");
 secureCb.checked = false;
 secureCb.onclick = toggleTls;
 secureCbLabel = document.getElementById("secureCbLabel")
 wsUri = document.getElementById("wsUri");
 toggleTls();
 connectBut = document.getElementById("connect");
 connectBut.onclick = doConnect;
 disconnectBut = document.getElementById("disconnect");
 disconnectBut.onclick = doDisconnect;
 sendMessage = document.getElementById("chatText");
 sendBut = document.getElementById("send");
 sendBut.onclick = doSend;
 consoleLog = document.getElementById("consoleLog");
 clearLogBut = document.getElementById("clearLogBut");
 clearLogBut.onclick = clearLog;
 setGuiConnected(false);
 document.getElementById("disconnect").onclick = doDisconnect;
 document.getElementById("send").onclick = doSend;
 }
 function toggleTls()
 {
 var wsPort = (window.location.port.toString() === "" ? "" : ":"+window.location.port)
 if (wsUri.value === "") {
 wsUri.value = "ws://" + window.location.hostname.replace("www", "echo") + wsPort;
 }
 if (secureCb.checked)
 {
 wsUri.value = wsUri.value.replace("ws:", "wss:");
 }
 else
 {
 wsUri.value = wsUri.value.replace ("wss:", "ws:");
 }
 }
 function doConnect()
 {
 if (window.MozWebSocket)
 {
 logToConsole('<span style="color: red;"><strong>Info:</strong> This browser supports WebSocket using the MozWebSocket constructor</span>');
 window.WebSocket = window.MozWebSocket;
 }
 else if (!window.WebSocket)
 {
 logToConsole('<span style="color: red;"><strong>Error:</strong> This browser does not have support for WebSocket</span>');
 return;
 }
 // prefer text messages
 var uri = wsUri.value;
 if (uri.indexOf("?") == -1) {
 uri += "?encoding=text";
 } else {
 uri += "&encoding=text";
 }
 websocket = new WebSocket(uri);
 websocket.onopen = function(evt) { onOpen(evt) };
 websocket.onclose = function(evt) { onClose(evt) };
 websocket.onmessage = function(evt) { onMessage(evt) };
 websocket.onerror = function(evt) { onError(evt) };
 }
 function doDisconnect()
 {
 websocket.close()
 }
 function doSend()
 {

 logToConsole("SENT: " + $("#chatText").html());
 websocket.send($("#chatText").html());
 }
 function writeElem(message,i){
 var xmessage = '<li class="todo-view"><span class="todo-content" tabindex="'+i+'">'+message+'</span></div></li>';
 $("#todo-list").append(xmessage);
 }
 function logToConsole(message)
 {
 var pre = document.createElement("p");
 pre.style.wordWrap = "break-word";
 pre.innerHTML = getSecureTag()+message;
 consoleLog.appendChild(pre);
 while (consoleLog.childNodes.length > 500)
 {
 consoleLog.removeChild(consoleLog.firstChild);
 }
 consoleLog.scrollTop = consoleLog.scrollHeight;
 }
 function onOpen(evt)
 {
 logToConsole("CONNECTED");
 setGuiConnected(true);
 }
 function onClose(evt)
 {
 logToConsole("DISCONNECTED");
 setGuiConnected(false);
 }
 function onMessage(evt)
 {
 if(evt.data.match("{")){
 var elem = JSON.parse(evt.data.toString());
 var xlist = elem.responce[0];
 $("#todo-list").empty();

 for(a in xlist){
iterateAttributesAndFormHTMLLabels(xlist[a],a);
 }}
 }
 function onError(evt)
 {
 logToConsole('<span style="color: red;">ERROR:</span> ' + evt.data);
 }
 function setGuiConnected(isConnected)
 {
 wsUri.disabled = isConnected;
 connectBut.disabled = isConnected;
 disconnectBut.disabled = !isConnected;
 sendBut.disabled = !isConnected;
 secureCb.disabled = isConnected;
 var labelColor = "black";
 if (isConnected)
 {
 labelColor = "#999999";
 }
 secureCbLabel.style.color = labelColor;
 }
 function clearLog()
 {
 while (consoleLog.childNodes.length > 0)
 {
 consoleLog.removeChild(consoleLog.lastChild);
 }
 $("#todo-list").empty();
 }
 function getSecureTag()
 {
 if (secureCb.checked)
 {
 return 'lock';
 }
 else
 {
 return '';
 }
 }
 window.addEventListener("load", echoHandlePageLoad, false);
 </script>
 <script>
 $('#webSocketSupp').fadeIn(250);
 setTimeout(function(){$('#webSocketSupp').fadeOut(250,function(){
 document.getElementById("connect").click();
 $('#webSocketSupp').remove();
 $('#connect').click(1);
 })},250);
 </script>


 <ul id="todo-list" style="overflow:scrolling;"></ul>
</div></div></div>

<div id="canvas">
</div>

<script>


      function iterateAttributesBase(o){
          var s = '';
          var show = "";
          var img ="";
var meta;
          for(var a in o){

              if(o.value &&
                 o.value[1].spawn){


                      if(o.value[0]){




        meta ='<span style="padd  ing:8px;color:black;"><b>spawn</b>'+o.value[1].spawn+'&nbsp;|&nbsp;';

         if(o.value[3].image && o.value[3].image.length)  {
                                    meta+='<b>image</b>:&nbsp;'+o.value[3].image.length+'&nbsp;|&nbsp;';
                                    meta+='<b>headings</b>:&nbsp;'+o.value[3].headings.length+'&nbsp;|&nbsp;';
                                    meta+='<b>related</b>:&nbsp;'+o.value[3].related.length+'&nbsp;|&nbsp;';
                                    meta+='<b>categories</b>:&nbsp;'+o.value[3].categories.length+'&nbsp;|&nbsp;';
                                    meta+='<b>members</b>:&nbsp;'+o.value[3].members.length+'</span>';
        }

                      }}
              if (typeof o[a] == 'object'){
              s += iterateAttributesBase(o[a]);
              }else{

//                s+='<hr><br><a href="'+o[a]+'" target="new"  style="color:blue"><font color=blue>'+o[a]+'</a>';

              }//end if
          }//end for

          return meta;
      }//end function




 function xArticles(query)
 {

      var DB = "http://localhost:5984/table";
      $.ajax({
         url: DB+'/_design/base/_view/articles?reduce=false&'+
        'start_key=["'+query+'"]&end_key=["'+query+'ZZZ"]',

                beforeSend:function(){},
                success: function (data){

                    var view = JSON.parse(data);

                    var x=0;

                    var tasks = [];
                    var html = "<h2>"+view.rows.length+"</h2>";
                    $('#concept').empty()
                    $('#concept').append(html)


                },
  error:function(){
    $('#concept').html('<p class="error"><strong>Oops!</strong> Try that again in a few moments.</p>');
  }
             });


 }
 function xSend(message)
 {


      //alert('Index: ' + $(message).html());
      var sendMessage = $.trim($(message).html()).split(":");
      clearLog();
      var q = $.trim(sendMessage[1]);
      $("#chatText").html(q);
      $("#send").click();

      xArticles(q);



 }

 function iterateAttributesAndFormHTMLLabels(o,xi){
 var s = '';

if(xi<10){
 $("#todo-list").append('<li onclick="xSend(this);" class="todo-view " style="margin:1px;background:silver;color:green;><span style="display:inline;color:#fff;"><b>'+'['+o.sp +']</b>:'+o.nb+'</span></li>');
}
 return s;
}


</script>





        <nav id="helpers">

      <button title="Previous slide" id="nav-prev" class="nav-prev">⇽</button>
      <button title="Jump to a random slide" id="slide-no">5</button>
      <button title="Next slide" id="nav-next" class="nav-next">⇾</button>
      <menu>
        <button type="checkbox" data-command="toc" title="Table of Contents" class="toc">TOC</button>
        <!-- <button type="checkbox" data-command="resources" title="View Related Resources">☆</button> -->
        <button type="checkbox" data-command="notes" title="View Slide Notes">✏</button>
        <button type="checkbox" data-command="source" title="View slide source">↻</button>
        <button type="checkbox" data-command="help" title="View Help">?</button>



      </menu>


    </nav>



    <div class="presentation">

      <div id="presentation-counter">Disclosure & singularity...</div>
      <div class="slides" id="toc-list">

        <div class="slide" id="landing-slide">



<pre>
<adress>
  the first lie was out of fear, all others out of greed! the military industrial complex stole the future <a href="http://localhost/alien/lib/plug/more.html" style="color:red;" target="blank">more</a></adress>
      <h1 style="float:right;">You are not alone</h1>
                       i!~!!))!!!!!!!!!!!!!!!!!!!!!!!!
                    i!!!{!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!i
                 i!!)!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
              '!h!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            '!!`!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!i
             /!!!~!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          ' ':)!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            ~:!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          ..!!!!!\!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
           `!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
           ~ ~!!!)!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!~
          ~~'~{!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!:'~
          {-{)!!{!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!:!
          `!!!!{!~!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!':!!!
          ' {!!!{>)`!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!)!~..
          :!{!!!{!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -!!:
              ~:!4~/!!!!!!!!!!!!!!!!!!!~!!!!!!!!!!!!!!!!!!!!!!!!!!
               :~!!~)(!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                ``~!!).~!!!!!!!!!!!!!{!!!!!!!!!!!!!!!!!!!!!!!!!!!!!:
                      ~  '!\!!!!!!!!!!(!!!!!!!!!!!!!!!!!!!!!!4!!!~:
                     '      '--`!!!!!!!!/:\!!{!!((!~.~!!`?~-      :
                        ``-.    `~!{!`)(>~/ \~                   :
             .                \  : `{{`. {-   .-~`              /
              .          !:       .\\?.{\   :`      .          :!
              \ :         `      -~!{:!!!\ ~     :!`         .>!
              '  ~          '    '{!!!{!!!t                 ! !!
               '!  !.            {!!!!!!!!!              .~ {~!
                ~!!..`~:.       {!!!!!!!!!!:          .{~ :LS{
                 `!!!!!!h:!?!!!!!!!!!!!!!(!!!!::..-~~` {!!!!.
                   4!!!!!!!!!!!!!!!!!!!!!~!{!~!!!!!!!!!!!!'
                    `!!!!!!!!!!!!!!!!!!!!(~!!!!!!!!!!!!!~
                      `!!!!!!!!!!!{\``!!``(!!!!!!!!!~~  .
                       `!!!!!!!!!!!!!!!!!!!!!!!!(!:
                         .!!!!!!!!!!!!!!!!!!!!!\~
                         .`!!!!!!!/`.;;~;;`~!! '
                           -~!!!!!!!!!!!!!(!!/ .
                              `!!!!!!!!!!!!!!'
                                `\!!!!!!!!!~



</pre>

               <section class="middle">

            <p>Press <span id="left-init-key" class="key">&rarr;</span> key to advance.</p>
          </section>
          <aside class="note">
            <section>
              Welcome! (blueboy to et)
            </section>
          </aside>
</div>
   <div class="slide" id="crop-slide">
               <section class="middle">
   <iframe src="http://localhost/alien/lib/plug/crop.php" width="94%" height="90%"></iframe>
          </section>
  <aside class="note">
            <section>
              some tiny collection of crop circles
            </section>
          </aside>
      </div>
   <div class="slide" id="stream-slide">
               <section class="middle">
   <iframe src="http://localhost/alien/lib/plug/audio-podcast/index.html" width="85%" height="90%"></iframe>
          </section>
            <aside class="note">
            <section>
              lets here what nasa and the science people bullshit
            </section>
          </aside>
      </div>

  <div class="slide" id="playlist-slide">
               <section class="middle">
      <iframe src="http://localhost/alien/lib/plug/player/playlist.html" width="94%" height="90%"></iframe>

          </section>
            <aside class="note">
            <section>
              music hbr 1 radio or custom playlist
            </section>
          </aside>
      </div>

  <div class="slide" id="bud-slide">
               <section class="middle">
   <img src="http://localhost/alien/lib/images/bud-001.jpg" width="94%" height="90%">
          </section>

            <aside class="note">
            <section>
                hope we can become friends one day!
            </section>
          </aside>
      </div>

  <div class="slide" id="micro-search-slide">
               <section class="middle">
  <iframe src="http://localhost/alien/lib/plug/local/index.html" width="94%" height="80%"></iframe>

          </section>

            <aside class="note">
            <section>
                last local cli search
            </section>
          </aside>
      </div>


  <div class="slide" id="reaktor-slide">
               <section class="middle">
   <img src="http://localhost/alien/lib/images/reactor-003.png" width="94%" height="90%">
          </section>

            <aside class="note">
            <section>
              synthetic knowledge
            </section>
          </aside>
      </div>


   <div class="slide" id="jpl-slide">
               <section class="middle">
   <iframe src="http://localhost/alien/lib/plug/crop/digital.php" width="94%" height="90%"></iframe>
          </section>

            <aside class="note">
            <section>
              fraction of jpl cleanout
            </section>
          </aside>
      </div>


   <div class="slide" id="ufo-slide">
               <section class="middle">
   <iframe src="http://localhost/alien/lib/plug/ufo/digital.php" width="94%" height="90%"></iframe>
          </section>
            <aside class="note">
            <section>
              some saucers
            </section>
          </aside>
      </div>


   <div class="slide" id="dense-slide">
               <section class="middle">
   <iframe src="http://localhost/alien/lib/plug/dense/web.php" width="94%" height="90%"></iframe>
          </section>
            <aside class="note">
            <section>
              web density
            </section>
          </aside>
      </div>


<?php

$ae = range(0,1);
foreach($ae as $y) {

print <<<EOF
<div class="slide" id="controls-slide$y">
  <header>&rarr;</header>
  <style>
  #controls-slide$y li, #controls-slide$y p {
  font-size: 32px;
  }
  #controls-slide$y .key {
  bottom: 2px;
  }
  </style>
  <section><ul><li></li></ul></section>
            <aside class="note">
            <section>
              $y
            </section>
          </aside>
  </div>

EOF;


}
?>
      </div> <!-- slides -->
      <div id="speaker-note" class="invisible" style="display: none;">1
      </div>
      <!-- speaker note -->
      <aside id="help" class="sidebar" style="display: none;">
        <table>
          <caption style="color:green">Active Memory</caption>
          <tbody>
            <tr>
              <th>Move Around</th>
              <td>&larr;&nbsp;&rarr;</td>
            </tr>
            <tr>
              <th>Change Theme</th>
              <td>t</td>
            </tr>
            <tr>
              <th>Speaker Notes</th>
              <td>n</td>
            </tr>
            <tr>
              <th>Toggle 3D</th>
              <td>3</td>
            </tr>
            <tr><td>



            </td></tr>
            </tr>
          </tbody>
        </table>
      </aside>
    </div> <!-- presentation -->


    <!--[if lt IE 9]>
    <script
      src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js">
    </script>
    <script>CFInstall.check({ mode: "overlay" });</script>
    <![endif]-->

<section id="wrapper">

<article>
  <p><span id="status" class="online">offline</span></p>
</article>
<script>

var statusElem = document.getElementById('status')

setInterval(function () {
  statusElem.className = navigator.onLine ? 'online' : 'offline';
  statusElem.innerHTML = navigator.onLine ? 'online' : 'offline';
}, 250);
</script>
</section>
    <script src="http://localhost/alien/lib/js/utils.js"></script>
  </body>
</html>
