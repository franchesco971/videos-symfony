/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(f){function g(a){var n=a||window.event,m=[].slice.call(arguments,1),l=0,k=!0,j=0,i=0;return a=f.event.fix(n),a.type="mousewheel",n.wheelDelta&&(l=n.wheelDelta/120),n.detail&&(l=-n.detail/3),i=l,n.axis!==undefined&&n.axis===n.HORIZONTAL_AXIS&&(i=0,j=-1*l),n.wheelDeltaY!==undefined&&(i=n.wheelDeltaY/120),n.wheelDeltaX!==undefined&&(j=-1*n.wheelDeltaX/120),m.unshift(a,l,j,i),(f.event.dispatch||f.event.handle).apply(this,m)}var e=["DOMMouseScroll","mousewheel"];if(f.event.fixHooks){for(var h=e.length;h;){f.event.fixHooks[e[--h]]=f.event.mouseHooks}}f.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var b=e.length;b;){this.addEventListener(e[--b],g,!1)}}else{this.onmousewheel=g}},teardown:function(){if(this.removeEventListener){for(var b=e.length;b;){this.removeEventListener(e[--b],g,!1)}}else{this.onmousewheel=null}}},f.fn.extend({mousewheel:function(b){return b?this.bind("mousewheel",b):this.trigger("mousewheel")},unmousewheel:function(b){return this.unbind("mousewheel",b)}})})(jQuery);
(function(a){a.fn.ribbonSlider=function(r){var r=jQuery.extend({speed:300,pause:0,handlePlayer:true,showControls:true},r);var e=a(this);var b=a(this).find("li");var g=a(this).find("ul");var h=e.width();var q=g.width();var m=false;var f;function n(){var s=0;b.each(function(){s+=a(this).outerWidth(true)});g.width(s)}function l(s){b.removeClass("current");s.addClass("current")}function d(){e.parent().removeClass("limit-left limit-right");if(g.position().left>=0){e.parent().addClass("limit-left")}else{if(g.position().left<=h-q){e.parent().addClass("limit-right");return false}}return true}function o(){e.parent().append('<a class="arrow arrow-left" href="#"></a><a class="arrow arrow-right" href="#"></a>');e.parent().find(".arrow").click(function(){if(a(this).hasClass("arrow-left")){c(b.filter(".current").prev("li"))}else{c(b.filter(".current").next("li"))}return false})}function i(){if(a.isFunction(a.fn.mousewheel)){e.mousewheel(function(s,t){if(t>0){c(b.filter(".current").prev("li"))}else{c(b.filter(".current").next("li"))}clearInterval(f);return false})}if(a.isFunction(a.fn.draggable)){g.draggable({axis:"x",drag:function(){if(m){return false}if(g.position().left>0||(q+g.position().left)<h){m=true;var t=-(q-h);if(g.position().left>0){t=0}g.animate({left:t},r.speed,function(){m=false;d()})}var s=0;b.each(function(){if(a(this).position().left>-g.position().left){s=a(this).index();return false}});l(b.filter(":eq("+s+")"));clearInterval(f)},stop:function(){d()}})}}function c(s){if(m==true||s.length==0){return}var t=s.position().left;if((q-t)<h){t=q-h}g.stop().animate({left:-t},r.speed,function(){if((q-t)>h){l(s)}d();m=false})}function k(){f=setInterval(function(){if(d()){c(b.filter(".current").next("li"))}else{c(b.filter(":first-child"))}},r.pause);e.add(e.parent().find(".arrow")).click(function(){clearInterval(f)})}function p(){b.filter(":first").addClass("current");n();if(r.showControls){o()}i();if(r.pause!=0){k()}}function j(){n();h=e.width();q=g.width()}p();a(document).ready(function(){j()})}})(jQuery);