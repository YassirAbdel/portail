(window.webpackJsonp=window.webpackJsonp||[]).push([["app"],{ng4s:function(t,e,n){n("sZ/o");var i=n("EVdn");n("m8cj"),i((function(){var t=i("#search-input"),e=t.data("suggest");t.typeahead({minLength:2,items:5,highlighter:function(t){var e=this.reversed[t],n='<div class="typeahead">';return e.title&&(n+='<div class="suggestion">'+e.title+"</div>"),n+="</div>"},source:function(t,n){var s=this;i.ajax({url:e,type:"GET",data:{q:t},success:function(t){var e={},a=[];i.each(t,(function(t,n){e[n.title]=n,a.push(n.title)})),s.reversed=e,n(a)}})},updater:function(t){return elem.title},matcher:function(){return!0}})}))},"sZ/o":function(t,e,n){}},[["ng4s","runtime",0]]]);