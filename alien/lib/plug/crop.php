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

$x=explode("\n","10.231.jpg
2003_julio_80.jpg
2003_julio_81.jpg
248421303223441.jpg
3_g.jpg
400px-CropCircleW.jpg
521251303223441.jpg
589021303223440.jpg
5_g.jpg
6a00e551962103883300e553e108598833-800wi.jpg
8_g.jpg
937341303223441.jpg
967871303223441.jpg
Berwick Bassett nr Avebury, Wiltshire Crop Circle 9th Jun 2001.jpg
Chilbo~6.jpg
Circulos en Inglaterra 7.jpg
Circulos en campos ingleses 1.jpg
Crop08_400x300.jpg
CropCircleDM0107_700x500.jpg
Cultivos_03.jpg
F201008051439333075895642.jpg
Mayan Crop Circle 2012 Woolstone Hill, near Uffington, Oxfordshire, Reported 13th August 2005 .jpg
NetherlandsButterflyMetamorphosisTransformationCropCircle.jpg
OVNIS_circulos2.jpg
a23.jpg
barburycastle.jpg
c2cl3.jpg
cerchi_grano_croce_cristiana.jpg
cerchio-nel-grano2008.jpg
cerchio1.jpg
circulo cultivo 3.jpg
circulo1_1.jpg
circulos de cosecha.jpg
circulos-de-las-cosechas-milk-hill.jpg
circulos-de-las-cosechas.jpg
circulos_cosechas_108.jpg
circulos_cosechas_109.jpg
circulos_cosechas_110.jpg
circulos_cosechas_111.jpg
circulos_cosechas_112.jpg
circulos_cosechas_114.jpg
circulos_cosechas_115.jpg
circulos_cosechas_119.jpg
circulos_cosechas_123.jpg
circulos_cosechas_124.jpg
circulos_cosechas_126.jpg
circulos_cosechas_128.jpg
circulos_cosechas_129.jpg
circulos_cosechas_132.jpg
circulos_cosechas_133.jpg
circulos_cosechas_134.jpg
circulos_cosechas_137.jpg
circulos_cosechas_139.jpg
circulos_cosechas_140.jpg
circulos_cosechas_143.jpg
circulos_cosechas_144.jpg
circulos_cosechas_145.jpg
circulos_cosechas_146.jpg
circulos_cosechas_26.jpg
circulos_cosechas_31.jpg
circulos_cosechas_37.jpg
circulos_cosechas_4.jpg
circulos_cosechas_67.jpg
circulossembradosvk7.jpg
circultivo.jpg
crop circle 25 de julio ovni venezuela 2.jpg
crop-circle-circulos-en-el-pasto370x270.jpg
crop-circle-milk-hill-2009.jpg
crop4.jpg
crop_circle_1.jpg
cropcircle9.jpg
cropdelfin1.jpg
el-no-tan-misterio-circulos-cultivos-L-2.jpeg
elipse.JPG
mensaje-de-ar.jpg
milkhill20031.jpg
otrocirculo.jpg
ruota.jpg
spirale.jpg
triqueta.JPG
untitled.jpg");

foreach($x as $v){

echo "<img src='/alien/lib/images/$v'   > ";
  }
  ?>
  </div>
  <div id="container">
   <ul id="coverflow"></ul>
  </div>
  <div id="buttons">
   <!--input id="left" type="button" value="&larr;" /-->
   <input id="range" type="range" min="0" max="80" value="0" />
   <!--input id="right" type="button" value="&rarr;" /-->
  </div>
  <div id="zoom_container"></div>
