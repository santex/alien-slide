<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset=utf-8" />
<title>Untitled Document</title>

  <script src="http://code.jquery.com/jquery-latest.js"></script>

<title>Web Socket</title>
<style>
#chat { width: 170px; }
.them { font-weight: bold; }
.them:before { content: 'them '; color: #bbb; font-size: 14px; }
.you { font-style: italic; }
.you:before { content: 'you '; color: #bbb; font-size: 14px; font-weight: bold; }
#log {
  overflow: auto;
  list-style: none;
  padding: 0;
/*  margin: 0;*/
}
#log li {

}
.widgetPoll{width:200px;}
</style>
</head>
<body onload="openConnection();">
<article>
  <form id="form" >
    <input type="text" id="chat"   placeholder="search" />
    <input id="submit" type="submit" name="submit" value="send" />
  </form>
  <p id="status">Not connected</p>
  <p><span id="connected">0</span></p>

  <ul id="log"></ul>
</article>
<script>
// let's invite Firefox to the party.
if (window.MozWebSocket) {
  window.WebSocket = window.MozWebSocket;
}







      function iterateAttributesAndFormHTMLLabels(o){
          var s = '';
          for(var a in o){
              if (typeof o[a] == 'object'){
                  s+='<hr /><h5>'+a+'</h5>';
                  s+=iterateAttributesAndFormHTMLLabels(o[a]);
              }else{
                  if(a.match("[0-9]")){
                  s+='<li>'+o[a]+'</li>';
                  }else{
                  s+='<div>'+a+':'+o[a]+'</div>';
                  }

              }//end if
          }//end for
          return s;
      }//end function


function openConnection() {
  // uses global 'conn' object
  if (conn.readyState === undefined || conn.readyState > 1) {
    conn = new WebSocket('ws://0.0.0.0:3001');
    conn.onopen = function () {
      state.className = 'success';
      state.innerHTML = 'Socket open';
    };

    conn.onmessage = function (event) {
       console.log(event.data);
      var message = JSON.parse(event.data);
      if (!(/^\d+$/).test(message)) {
        log.innerHTML = '<pre>' + iterateAttributesAndFormHTMLLabels(message) + '</pre>' + log.innerHTML;
      } else {
        connected.innerHTML = message;
      }
    };

    conn.onclose = function (event) {
      state.className = 'fail';
      state.innerHTML = 'Socket closed';
    };
  }
}

var connected = document.getElementById('connected'),
    log = document.getElementById('log'),
    chat = document.getElementById('chat'),
    form = chat.form,
    conn = {},
    state = document.getElementById('status'),
    entities = {
      '<' : '&lt;',
      '>' : '&gt;',
      '&' : '&amp;'
    };

if (window.WebSocket === undefined) {
  state.innerHTML = 'Sockets not supported';
  state.className = 'fail';
} else {
  state.onclick = function () {
    if (conn.readyState !== 1) {
      conn.close();
      setTimeout(function () {
        openConnection();
      }, 250);
    }
  };


  window.addEventListener("submit",  function (event) {
    event.preventDefault();

    // if we're connected
    if (conn.readyState === 1) {
      conn.send(chat.value.toString());
      log.innerHTML = '<li class="you">' + chat.value + '</li>' + log.innerHTML;

      chat.value = '';
    }
  });

  openConnection();
}

</script>
