  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
  <script type="text/javascript">
  <!--
    var coverflow = function() {
      var radius = 19;
      var side = 300;
      var long_side = 300;
      var offset_x = 0;
      var offset_y = 0;
      var pop_x = 0;
      var pop_y = 0;
      var offset = 6;
      var display_offset = 6;
      var queue = [];
      var flipping = false;
      /*
       * creates canvas elements to shape images of regular square
       */
      function createCanvasElement(img) {
        var canvas = document.createElement('canvas');
        canvas.setAttribute('width',  side);
        canvas.setAttribute('height', side);
        var _height = img.naturalHeight;
        var _width  = img.naturalWidth;
        var w = false; // width is shorter?
        if (_height > _width) w = true;
        var sx, sy, sw, sh;
        if (w) {
          var _side = _width;
          sx = 0;
          sy = _height / 2 - _side / 2;
        } else {
          var _side = _height;
          sx = _width / 2 - _side / 2;
          sy = 0;
        }
        sw = _side;
        sh = _side;

        var cx = canvas.getContext('2d');
        cx.beginPath();
        cx.moveTo(radius, 0);
        cx.lineTo(canvas.width - radius, 0);
        cx.quadraticCurveTo(canvas.width, 0, canvas.width, radius);
        cx.lineTo(canvas.width, canvas.height - radius);
        cx.quadraticCurveTo(canvas.width, canvas.height, canvas.width - radius, canvas.height);
        cx.lineTo(radius, canvas.height);
        cx.quadraticCurveTo(0, canvas.height, 0, canvas.height - radius);
        cx.lineTo(0, radius);
        cx.quadraticCurveTo(0, 0, radius, 0);
        cx.clip();
        cx.drawImage(img, sx, sy, sw, sh, 0, 0, side, side);
        $(canvas).click(coverflow.popup(img));
        return canvas;
      }
      /*
       * pop flow object from queue and excute flip motion.
       */
      function flip() {
        var flow = queue.shift();
        if (!flow) {
          flipping = false;
          return;
        }
        flipping = true;
        var _offset = display_offset + flow.vector;
        var speed = 0.05;
        if (queue.length < 4) speed = 0.1;
        if (queue.length < 2) speed = 0.2;
        var canvas = $('#coverflow li');
        $('#coverflow li').each(function(index) {
          var order = index + _offset;
          if (order <= 0) {
            $(this).attr('class', 'img coverflow0');
          } else if (order > 11) {
            $(this).attr('class', 'img coverflow12');
          } else {
            $(this).attr('class', 'img coverflow'+(order));
          }
        })
        display_offset = _offset;
        setTimeout(flip, speed * 1000 + 30);
      }
      /*
       * create flow object and push into queue
       */
      function slide(diff) {
        return function() {
          var _offset = offset + diff;
          if (_offset > 6 || _offset < (($('#coverflow li').length - 7) * -1)) return false;
          coverflow.move(diff * -1);
          offset = _offset;
          $('#range').val(_offset * -1);
          if (!flipping) flip();
        }
      }
      return {
        /*
         * initialization
         */
        init: function() {
          $('#dummy img').each(function(index) {
            var canvas = createCanvasElement(this);
            var li = $('<li class="img"/>');
            var order = index + 6;
            if (order <= 11) {
              li.addClass('coverflow'+order);
            } else {
              li.addClass('coverflow12');
            }
            $('#coverflow').append(li.append(canvas));
          });
          $('#left').click(slide(1));
          $('#right').click(slide(-1));
          $('#range').change(function() {
            var range = $(this).val();
            coverflow.move(parseInt(range) + offset);
            offset = range * -1;
            if (!flipping) flip();
          });
          $(window).keydown(function(e) {
            if (e.keyCode === 27) coverflow.popdown();
            if (e.keyCode === 39) slide(-1)();
            if (e.keyCode === 37) slide(1)();
          });
          $('#zoom_container').css('left', $('#container').css('margin-left'));
        },
        /*
         * show popup image
         */
        popup: function(img) {
          return function() {
            var _height = img.naturalHeight;
            var _width  = img.naturalWidth;
            var w = false; // width is shorter?
            if (_height > _width) w = true;
            var sx, sy, sw, sh;
            if (w) {
              sh = long_side;
              sw = long_side / _height * _width;
            } else {
              sh = long_side / _width * _height;
              sw = long_side;
            }

            var _img = $('<img/>');
            _img.attr('src', img.src);
            _img.attr('width', sw);
            _img.attr('height', sh);
            _img.attr('id', 'zoom');
            _img.css('margin', (((long_side/2)-sh)/2)+'px '+((985-sw)/2)+'px');
            _img.css('-webkit-opacity', 0);
            _img.click(function() {
              coverflow.popdown();
            });
            $('#zoom_container').show().append(_img);
            setTimeout(function() {_img.css('-webkit-opacity', 1);}, 10);
          }
        },
        /*
         * hide popup image
         */
        popdown: function() {
          $('#zoom').css('-webkit-opacity', 0);
          setTimeout(function() {
            $('#zoom').remove();
            $('#zoom_container').hide();
          }, 500);
        },
        /*
         * creates multiple flow objects by range movement and push into queue
         */
        move: function(diff) {
          coverflow.popdown();
          var abs = diff < 0 ? diff * -1 : diff;
          var vector = 1;
          if (diff > 0) vector = -1;
          for (var i = 0; i < abs; i++) {
            var flow = {'vector': vector, 'speed': 0.03}
            queue.push(flow);
          }
          return true;
        }
      }
    }();
  -->
  </script>
  <style>
    body {
      background-color:#000;
      background-repeat:no-repeat;
      overflow-x:hidden;
    }
    input#text {width:500px;}
    input#range {}
    textarea#text {width:500px; height:30px;}
    img#zoom {
      border:10px solid #eee;
      cursor:pointer;
      -webkit-opacity:1.0;
      -webkit-transition: all 0.2s ease-out;
      -webkit-box-shadow: #333 0px 10px 10px;
    }
    div#zoom_container {
      position:absolute;

      width:1000px;
      margin:0 auto;
      z-index:1000;
      display:none;
    }
    div#dummy img {display:none;}
    div#container {
      height: 150px;
      width:1000px;
      margin: 5px auto;
      -webkit-perspective: 700;
    }
    div#buttons {
      width:585px;
      padding:7px 20px 0px 20px;
      position:relative;
      margin:200px auto;
      text-align:center;
      background-color: rgba(200,200,200,0.3);
      border:1px solid #aaa;
      z-index:0;
      -webkit-border-radius: 20px;
    }
    header {
      font-family: Sans-Serif;
      font-size:12px;
      text-align:right;
      color: #fff;
    }
    input[type="button"]{ font-size: 24px;}
    input#range {

      width:300px;
    }
    ul {
      width:100%;
      list-style:none;
      margin:20px auto;
      -webkit-transform-style: preserve-3d;
    }
    li {
      -webkit-opacity: 1.0;
      height:100px;
    }
    li.img {
      position:absolute;
      -webkit-transition: all 0.3s ease-out;
      -webkit-transform-origin: 50% 5%;
      cursor:pointer;
    }
    li.img canvas {
      -webkit-box-reflect: below 10px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(0.8, transparent), to(rgba(255,255,255,0.8)));;
    }
    .coverflow0 {
      -webkit-opacity: 0.0;
      left:-5%;
    }
    .coverflow1 {
      -webkit-transform: rotateY(65deg) translate(0px, 100px);
      -webkit-opacity: 0.1;
      left:0%;
      z-index:0;
    }
    .coverflow2 {
      -webkit-transform: rotateY(65deg) translate(0px, 80px);
      -webkit-opacity: 0.3;
      left:5%;
      z-index:1;
    }
    .coverflow3 {
      -webkit-transform: rotateY(65deg) translate(0px, 60px);
      -webkit-opacity: 0.7;
      left:10%;
      z-index:2;
    }
    .coverflow4 {
      -webkit-transform: rotateY(65deg) translate(0px, 40px);
      left:15%;
      z-index:3;
    }
    .coverflow5 {
      -webkit-transform: rotateY(65deg) translate(0px, 20px);
      left:20%;
      z-index:4;
    }
    .coverflow6 {
      -webkit-transform: rotateY(0deg) scale(1.15) translate(0px, 0px);
      left:36.1%;
      z-index:10;
    }
    .coverflow7 {
      -webkit-transform: rotateY(-65deg) translate(0px, 20px);
      left:52.5%;
      z-index:4;
    }
    .coverflow8 {
      -webkit-transform: rotateY(-65deg) translate(0px, 40px);
      left:57.5%;
      z-index:3;
    }
    .coverflow9 {
      -webkit-transform: rotateY(-65deg) translate(0px, 60px);
      -webkit-opacity: 0.7;
      left:62.5%;
      z-index:2;
    }
    .coverflow10 {
      -webkit-transform: rotateY(-65deg) translate(0px, 80px);
      -webkit-opacity: 0.3;
      left:67.5%;
      z-index:1;
    }
    .coverflow11 {
      -webkit-transform: rotateY(-65deg) translate(0px, 100px);
      -webkit-opacity: 0.1;
      left:72.5%;
      z-index:0;
    }
    .coverflow12 {
      -webkit-opacity: 0.0;
      left:77.5%;
    }
    a:link {
      color: #999;
    }
  </style>
 </head>
 <body onload="coverflow.init()">

  <div id="dummy">

<?php

$x=explode("\n","2.jpg
3.jpg
4.jpg
5.jpg
6.jpg
7.jpg
8.jpg
9.jpg
10.jpg
11.jpg
12.jpg
13.jpg
14.jpg
15.jpg
16.jpg
17.jpg
18.jpg
19.jpg
20.jpg
21.jpg
22.jpg
23.jpg
24.jpg
25.jpg
26.jpg
27.jpg
28.jpg
29.jpg
30.jpg
31.jpg
32.jpg
33.jpg
34.jpg
35.jpg
36.jpg
37.jpg
38.jpg
39.jpg
40.jpg
41.jpg
42.jpg
43.jpg
44.jpg
45.jpg
46.jpg
47.jpg
48.jpg
49.jpg
50.jpg
51.jpg
52.jpg
53.jpg
54.jpg
55.jpg
56.jpg
57.jpg
58.jpg
59.jpg
60.jpg
61.jpg
62.jpg
63.jpg
64.jpg
65.jpg
66.jpg
67.jpg
68.jpg
69.jpg
70.jpg
71.jpg
72.jpg
73.jpg
74.jpg
75.jpg
76.jpg
77.jpg
78.jpg
79.jpg
80.jpg
81.jpg
82.jpg
83.jpg
84.jpg
85.jpg
86.jpg
87.jpg
88.jpg
89.jpg
90.jpg
91.jpg
92.jpg
93.jpg
94.jpg
95.jpg
96.jpg
97.jpg
98.jpg
99.jpg
100.jpg
101.jpg
102.jpg
103.jpg
104.jpg
105.jpg
106.jpg
107.jpg
108.jpg
109.jpg
110.jpg
111.jpg
112.jpg
113.jpg
114.jpg
115.jpg
116.jpg
117.jpg
118.jpg
119.jpg
120.jpg
121.jpg
122.jpg
123.jpg
124.jpg
125.jpg
126.jpg
127.jpg
128.jpg
129.jpg
130.jpg
131.jpg
132.jpg
133.jpg
134.jpg
135.jpg
136.jpg
137.jpg
138.jpg
139.jpg
140.jpg
141.jpg
142.jpg
143.jpg
144.jpg
145.jpg
146.jpg
147.jpg
148.jpg
149.jpg
150.jpg
151.jpg
152.jpg
153.jpg
154.jpg
155.jpg
156.jpg
157.jpg
158.jpg
159.jpg
160.jpg
161.jpg
162.jpg
163.jpg
164.jpg
165.jpg
166.jpg
167.jpg
168.jpg
169.jpg
170.jpg
171.jpg
172.jpg
173.jpg
174.jpg
175.jpg
176.jpg
177.jpg
178.jpg
179.jpg
180.jpg
181.jpg
182.jpg
183.jpg
184.jpg
185.jpg
186.jpg
187.jpg
188.jpg
189.jpg
190.jpg
191.jpg
192.jpg
193.jpg
194.jpg
195.jpg
196.jpg
197.jpg
198.jpg
199.jpg
200.jpg
201.jpg
202.jpg
203.jpg
204.jpg
205.jpg
206.jpg
207.jpg
208.jpg
209.jpg
210.jpg
211.jpg
212.jpg
213.jpg
214.jpg
215.jpg
216.jpg
217.jpg
218.jpg
219.jpg
220.jpg
221.jpg
222.jpg
223.jpg
224.jpg
225.jpg
226.jpg
227.jpg
228.jpg
229.jpg
230.jpg
231.jpg
232.jpg
233.jpg
234.jpg
235.jpg
236.jpg
237.jpg
238.jpg
239.jpg
240.jpg
241.jpg
242.jpg
243.jpg
244.jpg
245.jpg
246.jpg
247.jpg
248.jpg
249.jpg
250.jpg
251.jpg
252.jpg
253.jpg
254.jpg
255.jpg
256.jpg
257.jpg
258.jpg
259.jpg
260.jpg");



foreach($x as $v){

echo "<img src='/alien/lib/plug/ufo/$v'   > ";
  }
  ?>
  </div>
  <div id="container">
   <ul id="coverflow"></ul>
  </div>
  <div id="buttons">
   <!--input id="left" type="button" value="&larr;" /-->
   <input id="range" type="range" min="0" max="<?php count($x); ?>" value="0" />
   <!--input id="right" type="button" value="&rarr;" /-->
  </div>
  <div id="zoom_container"></div>

