/*
 * Based on Dustin Diaz's 'ify' script, which is why I've kept his license.
 * The regular expressions are different from his though, and IMHO more complete.
 *
 * Modified by Remy Sharp / http://remysharp.com / @rem
 * Last updated: $Date$
 *
 * Dustin's Credit:
 * Twita@talinkahashifyer
 * http://www.dustindiaz.com
 * http://www.dustindiaz.com/basement/ify.html
 *
 * Copyright (c) 2009 Dustin Diaz
 * licensed under public BSD
 */
window.ify = function() {
	return {
		"link": function(t) {
			return t.replace(/[a-z]+:\/\/[a-z0-9-_]+\.[a-z0-9-_:~%&\?\/.=]+[^:\.,\)\s*$]/ig, function(m) {
				return '<a href="' + m + '">' + ((m.length > 25) ? m.substr(0, 24) + '...' : m) + '</a>';
			});
		},
		"at": function(t) {
			return t.replace(/(^|[^\w]+)\@([a-zA-Z0-9_]{1,15}(\/[a-zA-Z0-9-_]+)*)/g, function(m, m1, m2) {
				return m1 + '@<a href="http://twitter.com/' + m2 + '">' + m2 + '</a>';
			});
		},
		"hash": function(t) {
			return t.replace(/(^|[^\w'"]+)\#([a-zA-Z0-9_]+)/g, function(m, m1, m2) {
				return m1 + '#<a href="http://search.twitter.com/search?q=%23' + m2 + '">' + m2 + '</a>';
			});
		},
		"clean": function(tweet) {
			return this.hash(this.at(this.link(tweet)));
		}
	};
}();

function relative_time(time_value) {
	var values = time_value.split(" "),
		parsed_date = Date.parse(values[1] + " " + values[2] + ", " + values[5] + " " + values[3]),
		date = new Date(parsed_date),
		relative_to = (arguments.length > 1) ? arguments[1] : new Date(),
		delta = parseInt((relative_to.getTime() - parsed_date) / 1000),
		r = '';
	
	function formatTime(date) {
		var hour = date.getHours(),
			min = date.getMinutes() + "",
			ampm = 'AM';
		
		if (hour == 0) {
			hour = 12;
		} else if (hour == 12) {
			ampm = 'PM';
		} else if (hour > 12) {
			hour -= 12;
			ampm = 'PM';
		}
		
		if (min.length == 1) {
			min = '0' + min;
		}
		
		return hour + ':' + min + ' ' + ampm;
	}
	
	function formatDate(date) {
		var ds = date.toDateString().split(/ /),
			mon = monthDict[date.getMonth()],
			day = date.getDate()+'',
			dayi = parseInt(day),
			year = date.getFullYear(),
			thisyear = (new Date()).getFullYear(),
			th = 'th';
		
		// anti-'th' - but don't do the 11th, 12th or 13th
		if ((dayi % 10) == 1 && day.substr(0, 1) != '1') {
			th = 'st';
		} else if ((dayi % 10) == 2 && day.substr(0, 1) != '1') {
			th = 'nd';
		} else if ((dayi % 10) == 3 && day.substr(0, 1) != '1') {
			th = 'rd';
		}
		
		if (day.substr(0, 1) == '0') {
			day = day.substr(1);
		}
		
		return mon + ' ' + day + th + (thisyear != year ? ', ' + year : '');
	}
	
	delta = delta + (relative_to.getTimezoneOffset() * 60);

	if (delta < 5) {
		r = 'less than 5 seconds ago';
	} else if (delta < 30) {
		r = 'half a minute ago';
	} else if (delta < 60) {
		r = 'less than a minute ago';
	} else if (delta < 120) {
		r = '1 minute ago';
	} else if (delta < (45*60)) {
		r = (parseInt(delta / 60)).toString() + ' minutes ago';
	} else if (delta < (2*90*60)) { // 2* because sometimes read 1 hours ago
		r = 'about 1 hour ago';
	} else if (delta < (24*60*60)) {
		r = 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
	} else {
		if (delta < (48*60*60)) {
			r = formatTime(date) + ' yesterday';
		} else {
			r = formatTime(date) + ' ' + formatDate(date);
			// r = (parseInt(delta / 86400)).toString() + ' days ago';
		}
	}

	return r;
}