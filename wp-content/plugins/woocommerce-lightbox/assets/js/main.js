jQuery(function(){

	jQuery('.product').magnificPopup({
		type:'inline',
		midClick: true,
		gallery:{
			enabled:true
		},
		delegate: 'a.wpb_wl_preview',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		},
	  	closeOnContentClick: false,
	});


});