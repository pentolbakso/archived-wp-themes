<section id="footer"><div class="container">
<div class="row">
  <div class="col-md-6">
    <h4>Tentang AnakCeria</h4>
    <p>TK Anak Ceria berada di bawah Yayasan Rumah Qaf, berdiri pada tahun 2014 di Bandung. 
    Meski dari umur terbilang masih muda, namun staff kami sudah memiliki pengalaman mengajar dan memiliki sertifikat.
    TK Anak Ceria menyediakan pendidikan terbaik untuk anak anda (2-6th), berada di lokasi strategis diantara perumahan Antapani Bandung</p>
  </div>
  <div class="col-md-3">
    <h4>Connect With Us</h4>
    <ul class="list-unstyled list-inline sosmed">
    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>     
    </ul>
  </div>
</div>
<p id="kodebonek">Web design by <a href="http://kodebonek.com">KodeBonek</a> - 2014</p>
</div></section>

</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
function initialize() {
  var myLatlng = new google.maps.LatLng(-6.922752,107.664887);
  var mapOptions = {
    zoom: 15,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  var image = '<?=get_bloginfo('template_directory')?>/daycare-icon.png';

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'TK Anak Ceria',
      icon: image
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
</script>
</html>