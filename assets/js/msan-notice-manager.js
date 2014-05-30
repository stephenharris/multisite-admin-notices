if ( typeof MSAN_SCRIPT_DEBUG === 'undefined') { MSAN_SCRIPT_DEBUG = true;}
(function($) {

if( MSAN_SCRIPT_DEBUG ){
	console.log(msan);	
}
		
var noticeManager = msan.app = {
	Model: {},
	View: {},
	Collection: {},
};

msan.gettext = function( msgid ){
	if( this.locale[msgid] !== undefined ){
		return this.locale[msgid];
	}
	return msgid;
};


msan.add_query_arg = function( key, value, uri ){
	var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
	var separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	}else {
		return uri + separator + key + "=" + value;
	}
};

msan.format_date = function( format, timestamp ){
	//@see http://phpjs.org/functions/date/
	var that = this;
	var jsdate, f;
	// Keep this here (works, but for code commented-out below for file size reasons)
	// var tal= [];
	var txt_words = [
	'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
	'January', 'February', 'March', 'April', 'May', 'June',
	'July', 'August', 'September', 'October', 'November', 'December'
	];
	// trailing backslash -> (dropped)
	//a backslash followed by any character (including backslash) -> the character
	// empty string -> empty string
	var formatChr = /\\?(.?)/gi;
	var formatChrCb = function (t, s) {
		return f[t] ? f[t]() : s;
	};	
	var _pad = function (n, c) {
		n = String(n);
		while (n.length < c) {
			n = '0' + n;
		}
		return n;
	};
	f = {
			// Day
			d: function () {
				// Day of month w/leading 0; 01..31
				return _pad(f.j(), 2);
			},
			D: function () {
				// Shorthand day name; Mon...Sun
				return f.l().slice(0, 3);
			},
			j: function () {
				//Day of month; 1..31
				return jsdate.getDate();
			},
			l: function () {
				//Full day name; Monday...Sunday
				return txt_words[f.w()] + 'day';
			},
			N: function () {
				// ISO-8601 day of week; 1[Mon]..7[Sun]
				return f.w() || 7;
			},
			S: function () {
				// Ordinal suffix for day of month; st, nd, rd, th
				var j = f.j();
				var i = j % 10;
				if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
					i = 0;
				}
				return ['st', 'nd', 'rd'][i - 1] || 'th';
			},
			w: function () {
				// Day of week; 0[Sun]..6[Sat]
				return jsdate.getDay();
			},
			z: function () {
				// Day of year; 0..365
				var a = new Date(f.Y(), f.n() - 1, f.j());
				var b = new Date(f.Y(), 0, 1);
				return Math.round((a - b) / 864e5);
			},

			// Week
			W: function () {
				//ISO-8601 week number
				var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
				var b = new Date(a.getFullYear(), 0, 4);
				return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
			},

			//Month
			F: function () {
				// Full month name; January...December
				return txt_words[6 + f.n()];
			},
			m: function () {
				// Month w/leading 0; 01...12
				return _pad(f.n(), 2);
			},
			M: function () {
				//Shorthand month name; Jan...Dec
				return f.F().slice(0, 3);
			},
			n: function () {
				// Month; 1...12
				return jsdate.getMonth() + 1;
			},
			t: function () {
				// Days in month; 28...31
				return (new Date(f.Y(), f.n(), 0)).getDate();
			},

			// Year
			L: function () {
				// Is leap year?; 0 or 1
				var j = f.Y();
				return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
			},
			o: function () {
				//ISO-8601 year
				var n = f.n();
				var W = f.W();
				var Y = f.Y();
				return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
			},
			Y: function () {
				// Full year; e.g. 1980...2010
				return jsdate.getFullYear();
			},
			y: function () {
				// Last two digits of year; 00...99
				return f.Y().toString().slice(-2);
			},

			// Time
			a: function () {
				// am or pm
				return jsdate.getHours() > 11 ? 'pm' : 'am';
			},
			A: function () {
				// AM or PM
				return f.a().toUpperCase();
			},
			B: function () {
				// Swatch Internet time; 000..999
				var H = jsdate.getUTCHours() * 36e2;
				// Hours
				var i = jsdate.getUTCMinutes() * 60;
				// Minutes
				// Seconds
				var s = jsdate.getUTCSeconds();
				return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
			},
			g: function () {
				// 12-Hours; 1..12
				return f.G() % 12 || 12;
			},
			G: function () {
				//24-Hours; 0..23
				return jsdate.getHours();
			},
			h: function () {
				//12-Hours w/leading 0; 01..12
				return _pad(f.g(), 2);
			},
			H: function () {
				// 24-Hours w/leading 0; 00..23
				return _pad(f.G(), 2);
			},
			i: function () {
				//Minutes w/leading 0; 00..59
				return _pad(jsdate.getMinutes(), 2);
			},
			s: function () {
				//Seconds w/leading 0; 00..59
				return _pad(jsdate.getSeconds(), 2);
			},
			u: function () {
				//Microseconds; 000000-999000
				return _pad(jsdate.getMilliseconds() * 1000, 6);
			},

			// Timezone
			e: function () {
				// Timezone identifier; e.g. Atlantic/Azores, ...
				//The following works, but requires inclusion of the very large
				// timezone_abbreviations_list() function.
				/*              return that.date_default_timezone_get();*/
				throw 'Not supported (see source code of date() for timezone on how to add support)';
			},
			I: function () {
				// DST observed?; 0 or 1
				// Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
				//If they are not equal, then DST is observed.
				var a = new Date(f.Y(), 0);
				//Jan 1
				var c = Date.UTC(f.Y(), 0);
				// Jan 1 UTC
				var b = new Date(f.Y(), 6);
				// Jul 1
				// Jul 1 UTC
				var d = Date.UTC(f.Y(), 6);
				return ((a - c) !== (b - d)) ? 1 : 0;
			},
			O: function () {
				// Difference to GMT in hour format; e.g. +0200
				var tzo = jsdate.getTimezoneOffset();
				var a = Math.abs(tzo);
				return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
			},
			P: function () {
				// Difference to GMT w/colon; e.g. +02:00
				var O = f.O();
				return (O.substr(0, 3) + ':' + O.substr(3, 2));
			},
			T: function () {
				//Timezone abbreviation; e.g. EST, MDT, ...
				// The following works, but requires inclusion of the very
				//large timezone_abbreviations_list() function.
				/*              var abbr, i, os, _default;
				if (!tal.length) {
					tal = that.timezone_abbreviations_list();
				}
				if (that.php_js && that.php_js.default_timezone) {
					_default = that.php_js.default_timezone;
					for (abbr in tal) {
						for (i = 0; i < tal[abbr].length; i++) {
							if (tal[abbr][i].timezone_id === _default) {
								return abbr.toUpperCase();
							}
						}
					}
				}
				for (abbr in tal) {
					for (i = 0; i < tal[abbr].length; i++) {
						os = -jsdate.getTimezoneOffset() * 60;
						if (tal[abbr][i].offset === os) {
							return abbr.toUpperCase();
						}	
					}
				}
				*/
				return 'UTC';
			},
			Z: function () {
				// Timezone offset in seconds (-43200...50400)
				return -jsdate.getTimezoneOffset() * 60;
			},

			// Full Date/Time
			c: function () {
				// ISO-8601 date.
				return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
			},
			r: function () {
				//RFC 2822
				return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
			},
			U: function () {
				// Seconds since UNIX epoch
				return jsdate / 1000 | 0;
			}
	};
	this.date = function (format, timestamp) {
		that = this;
		jsdate = (timestamp === undefined ? new Date() : // Not provided
			(timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
				new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
		);
		return format.replace(formatChr, formatChrCb);
	};
	return this.date(format, timestamp);
};

//===============================================================
//Models
//===============================================================
noticeManager.Model.Controller = Backbone.Model.extend({
	initialize: function() {
		if( this.get( 'notices') ){
			this.notices = new noticeManager.Collection.Notices( this.get( 'notices') );
			this.set( 'notices', false );
		}
	},
});

noticeManager.Model.Notice = Backbone.Model.extend({
	url: function() {
		var url = msan.add_query_arg( 'action', 'msan-notice', msan.url );
		url = msan.add_query_arg( '_ajax_nonce', msan.nonce, url );
		if ( this.isNew() ) return url;
		return url + '&id=' + this.id;
    }
});


//===============================================================
//Collections
//===============================================================
noticeManager.Collection.Notices = Backbone.Collection.extend({	
	model: noticeManager.Model.Notice,
});

//===============================================================
//Views
//===============================================================
noticeManager.View.NoticeView = Backbone.View.extend({
	tagName: 'li',
	
	templateRead: _.template( $( '#tmpl-msan-notice' ).html( ) ),
	
	templateEdit: _.template( $( '#tmpl-msan-notice-edit' ).html( ) ),
	
    initialize: function() {
		_.bindAll(this, 'render' );
		
		this.listenTo( this.model, 'destroy', this.removeNotice, this );
	},
	
	events: {
		'click .msan-delete-notice': 'deleteNotice',
		'click .msan-edit-notice':   'editNotice',
		'click .msan-update-notice':   'updateNotice',
		'click .msan-cancel-update':   'cancelUpdateNotice',
	},
	
	render: function(){
		$( this.el ).html( this.templateRead( this.getTemplateArgs() ) );
		$( this.el ).attr( 'id', 'msan-notice-' + this.model.cid );
		return this;
	},
	
	renderEdit: function(){
		$( this.el ).html( this.templateEdit( this.getTemplateArgs() ) );
		return this;
	},
	
	getTemplateArgs:function(){
		var json = this.model.toJSON();
		var last_updated = new Date( this.model.get('last_updated') );
		json.last_update = msan.format_date( 'jS F Y H:i', last_updated );
		return json;
	},

	deleteNotice: function( ev ){
		ev.preventDefault();
		this.model.destroy();
	},
	
	updateNotice: function( ev ){
		ev.preventDefault();
		
		var message = $( 'textarea', this.el ).val();
		this.model.set( 'message', message );
		this.model.set( 'last_updated', msan.format_date( 'Y-m-d H:i:s' ) );
		this.model.save();
		this.render();
		this.model.trigger( 'move-to-top', this.model );
	},
	
	cancelUpdateNotice: function( ev ){
		ev.preventDefault();
		this.render();
	},
	
	removeNotice: function(){
		$(this.el).remove();
	},
	
	editNotice: function( ev ){
		ev.preventDefault();
		this.renderEdit();
		$( 'textarea', this.el ).focus();
	}
});

noticeManager.View.ControllerView = Backbone.View.extend({
	
	el: '#msan-notices',
	
	events: {
		'click .button': 'publishNotice',
	},
    
	initialize: function() {
		_.bindAll(this, 'render', 'addNotice', 'moveToTop' );

		var self = this;
		
		this.listenTo( this.model.notices, 'add', this.addNotice, this );
			this.listenTo( this.model.notices, 'move-to-top', function( notice ){
			self.moveToTop( notice );
		});
	},
	
	render: function(){
		
		var self = this;
		
		if( this.model.notices ){
			this.model.notices.each(function( notice ){
				self.addNotice( notice, false );
			});
			
		}
	},
	
	publishNotice: function( ev ){
		ev.preventDefault();
		
		var message = $( 'textarea', this.el ).val();
		
		if( !message ){
			return;
		}
		var notice = new noticeManager.Model.Notice({
			message: message,
			last_updated: msan.format_date( 'Y-m-d H:i:s' ) 
		});
		
		notice.save();
		this.model.notices.add( notice );
	},
	
	addNotice: function( notice, fadeIn ){

		fadeIn = ( typeof fadeIn == 'undefined ' ? true : fadeIn );
		
		noticeView = new noticeManager.View.NoticeView( { model: notice } );
		var noticeEl = noticeView.render().el;
		
		$list = $( 'ul', this.el );
		if( fadeIn ){
			$(noticeEl).css({opacity: 0}).prependTo( $list ).animate({opacity: 1}, 1500);
		}else{
			$(noticeEl).prependTo( $list );
		}

	},
	
	moveToTop: function( notice ){
		
		var $list      = $( 'ul', this.el );
		var listHeight = $list.innerHeight();
		var listTop    = $list.position().top;
		
		var $notice    = $( '#msan-notice-'+notice.cid );
		var noticeId   = $notice.attr("id");

		var elemHeight = $notice.height();
		var elemTop    = $notice.position().top;
		
		var liHtml     = $notice.clone().wrap('<div></div>').parent().html(); //outer HTML		
		
		var moveUp   = (listTop - elemTop );
		var moveDown = elemHeight;

		$( "li", this.el  ).each(function() {
			if ( $(this).attr("id") == noticeId ) {
				return false;
			}
			$(this).animate({"top": '+=' + moveDown}, 1000);
		});
		
		$notice.animate({"top": '+=' + moveUp}, 1000, function() {
			$notice.prependTo( $list );
			$("li").attr("style","");
		});
		
	}
});

//======================================
// Initialize
//======================================
$(document).ready(function(){
	
	var controller = new noticeManager.Model.Controller( {
		notices: msan.notices,
	} );
	
	var noticesControllerView = new noticeManager.View.ControllerView({ model: controller });
	noticesControllerView.render();
});
})(jQuery);