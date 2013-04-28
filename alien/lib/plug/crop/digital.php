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

$x=explode("\n","lynx2art.jpg
m1ocean.jpg
m1planet.jpg
m2ocean.jpg
m2planet.jpg
m3ocean.jpg
m4ocean.jpg
m4plane1.jpg
m4plane2.jpg
mars-ci.jpg
mars-ice.jpg
mars-sur.jpg
mars-tubes.jpg
mars-tubes2.jpg
mars-tubes3.jpg
mars-tubes4.jpg
mars0.jpg
mars0b.jpg
mars0bb.jpg
mars0ci.jpg
mars0clo.jpg
mars0sur.jpg
mars1air.jpg
mars1bio.jpg
mars1ch4.jpg
mars1clo.jpg
mars1gwa.jpg
mars1imp.jpg
mars1int.jpg
mars1lava.jpg
mars1met.jpg
mars1mv.jpg
mars1oce.jpg
mars1old.jpg
mars1roc.jpg
mars1spc.jpg
mars1str.jpg
mars1vl2.jpg
mars1vol.jpg
mars2air.jpg
mars2bio.jpg
mars2ch4.jpg
mars2gwa.jpg
mars2imp.jpg
mars2int.jpg
mars2lava.jpg
mars2met.jpg
mars2mv.jpg
mars2oce.jpg
mars2old.jpg
mars2roc.jpg
mars2spc.jpg
mars2str.jpg
mars2vl2.jpg
mars2vol.jpg
mars3oce.jpg
mars3roc.jpg
mars3str.jpg
mars4.jpg
marscap2.jpg
marscap3.jpg
marscaps.gif
marsdust.jpg
marsglob.jpg
marsice2.jpg
marsicep.jpg
marslife.gif
marsnca1.jpg
marsnca2.jpg
marsncap.jpg
marstor0.jpg
marstorm.jpg
mat-foss.jpg
mathild1.jpg
mathild2.jpg
mathilde.jpg
mer1back.jpg
mer1magf.jpg
mer1quar.jpg
mer2back.jpg
mer2magf.jpg
mer2quar.jpg
mer3back.jpg
mer3quar.jpg
mic-mats.jpg
mira.gif
miradark.jpg
miradark2.jpg
mix-bac1.jpg
moon&ear.jpg
moon-man.jpg
moon.jpg
moon0.jpg
moon00.jpg
moon0col.jpg
moon0man.jpg
moon1col.jpg
moon1cor.jpg
moon1cra.jpg
moon1cut.jpg
moon1far.jpg
moon1ice.jpg
moon2col.jpg
moon2cor.jpg
moon2cra.jpg
moon2cut.jpg
moon2far.jpg
moon2ice.jpg
moon3cor.jpg
moon3cra.jpg
moon3cut.jpg
moon3far.jpg
moon3ice.jpg
moon4cra.jpg
mooncomp.gif
moonhill1.jpg
moonhill2.jpg
moonhills.jpg
mplanet1.jpg
mplanet2.jpg
mw1arms.jpg
mw2arms.jpg
redgiant.jpg
reioniza1.jpg
reioniza2.jpg
reioniza3.jpg
remnants.jpg
rhea1rin.jpg
rhea2rin.jpg
rho-cas2.jpg
rho1cas.jpg
rho2cas.jpg
rhocas1a.jpg
rhocas1b.jpg
rhocas2a.jpg
rmc163a2a.jpg
rmc163a2b.jpg
rmc163a2c.jpg
rock1var.jpg
rock2var.jpg
rxj1856a.jpg
s1clust.jpg
s2-a.jpg
s2clust.jpg
s2orb-a.jpg
s3clust.jpg
sag1cann.jpg
sag1dwf.jpg
sag1loc.jpg
sag1mw.jpg
sat0moon.jpg
sat0ring.gif
sat1all.jpg
sat1band.jpg
sat1clou.jpg
sat1down.jpg
sat1dr.jpg
sat1dri.jpg
sat1hex.jpg
sat1hurr.jpg
sat1ring.gif
sat1ring.jpg
sat1sr.jpg
sat1stor.jpg
sat1xray.jpg
sat2all.jpg
sat2band.jpg
sat2clou.jpg
sat2down.jpg
sat2dr.jpg
sat2dri.jpg
sat2hex.jpg
sat2hurr.jpg
sat2ring.jpg
sat2sr.jpg
sat2stor.jpg
sat2xray.jpg
sat3ring.jpg
sat3sr.jpg
sat3stor.jpg
sat4ring.jpg
satring2.jpg
saturn.gif
saturn.jpg
saturn0.jpg
saturn1.jpg
saturns.jpg
scr0630a.jpg
sdssj10a.jpg
seaweed.jpg
sedna1cp.jpg
sedna2.jpg
sedna2cp.jpg
sedna3.jpg
sgr-a1.jpg
sgr-a2.jpg
sgr-ag1.jpg
sgr-ag2.jpg
shack1cr.jpg
shack2cr.jpg
shiva1c.jpg
shiva2c.jpg
sirius1a.jpg
sn-star.jpg
sn06gal1.jpg
sn06gal2.jpg
sn1987a.jpg
sn1987a1.jpg
sn1987a1m.jpg
sn1987a1r.jpg
sn1987a1w.jpg
sn1987a2.jpg
sn1987a2m.jpg
sn1987a2r.jpg
sn1987a2w.jpg
so0253a.jpg
so25map1.jpg
so25map2.jpg
spectra1.jpg
spiral1g.jpg
spiral2g.jpg
ssa22-a.jpg
titan1.jpg
titan1at.jpg
titan2.jpg
titan2at.jpg
titanatm.jpg
uranus-s.jpg
uranus.gif
uranus00.jpg
uranus1.jpg
uranus2.jpg
v-smash1.jpg
v-smash2.jpg
veg2moon.jpg
vegalyra.jpg
ven1atmo.jpg
ven2atmo.jpg
venus.gif
venus.jpg
venus0.gif
venus1.gif
venus1o.jpg
venus1si.jpg
venus1sp.jpg
venus1sw.jpg
venus1uv.jpg
venus2.gif
venus2.jpg
venus2o.jpg
venus2si.jpg
venus2sp.jpg
venus2sw.jpg
venus2uv.jpg
venus3.gif
venus3.jpg
venus3d.jpg
venus3o.jpg
venus4.jpg
venuslandscape2.jpg
verdigellas.gif
z24a.jpg
zeta2map.jpg
zetafish.gif
zetafish2.gif
zetahill.gif
zodiacal1.jpg
zodiacal2.jpg");



foreach($x as $v){

echo "<img src='/alien/lib/plug/crop/$v'   > ";
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

