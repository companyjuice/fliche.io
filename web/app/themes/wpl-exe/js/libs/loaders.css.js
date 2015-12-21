(function($){

  function wprotoMakeDivs( n ) {
    var arr = [];
    for (i = 1; i <= n; i++) {
      arr.push('<div></div>');
    }
    return arr;
  };

	$(document).ready(function(){
		
	  $('#wproto-preloader-inner.ball-pulse').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.ball-grid-pulse').html(wprotoMakeDivs(9));
	  $('#wproto-preloader-inner.ball-clip-rotate').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.ball-clip-rotate-pulse').html(wprotoMakeDivs(2));
	  $('#wproto-preloader-inner.square-spin').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.ball-clip-rotate-multiple').html(wprotoMakeDivs(2));
	  $('#wproto-preloader-inner.ball-pulse-rise').html(wprotoMakeDivs(5));
	  $('#wproto-preloader-inner.ball-rotate').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.cube-transition').html(wprotoMakeDivs(2));
	  $('#wproto-preloader-inner.ball-zig-zag').html(wprotoMakeDivs(2));
	  $('#wproto-preloader-inner.ball-zig-zag-deflect').html(wprotoMakeDivs(2));
	  $('#wproto-preloader-inner.ball-triangle-path').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.ball-scale').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.line-scale').html(wprotoMakeDivs(5));
	  $('#wproto-preloader-inner.line-scale-party').html(wprotoMakeDivs(4));
	  $('#wproto-preloader-inner.ball-scale-multiple').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.ball-pulse-sync').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.ball-beat').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.line-scale-pulse-out').html(wprotoMakeDivs(5));
	  $('#wproto-preloader-inner.line-scale-pulse-out-rapid').html(wprotoMakeDivs(5));
	  $('#wproto-preloader-inner.ball-scale-ripple').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.ball-scale-ripple-multiple').html(wprotoMakeDivs(3));
	  $('#wproto-preloader-inner.ball-spin-fade-loader').html(wprotoMakeDivs(8));
	  $('#wproto-preloader-inner.line-spin-fade-loader').html(wprotoMakeDivs(8));
	  $('#wproto-preloader-inner.triangle-skew-spin').html(wprotoMakeDivs(1));
	  $('#wproto-preloader-inner.pacman').html(wprotoMakeDivs(5));
	  $('#wproto-preloader-inner.ball-grid-beat').html(wprotoMakeDivs(9));
	  $('#wproto-preloader-inner.semi-circle-spin').html(wprotoMakeDivs(1));
		
	});

})( window.jQuery );