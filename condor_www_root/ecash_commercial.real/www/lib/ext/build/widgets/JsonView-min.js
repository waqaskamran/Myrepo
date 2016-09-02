/*
 * Ext JS Library 1.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext.JsonView=function(_1,_2,_3){Ext.JsonView.superclass.constructor.call(this,_1,_2,_3);var um=this.el.getUpdateManager();um.setRenderer(this);um.on("update",this.onLoad,this);um.on("failure",this.onLoadException,this);this.addEvents({"beforerender":true,"load":true,"loadexception":true});};Ext.extend(Ext.JsonView,Ext.View,{jsonRoot:"",refresh:function(){this.clearSelections();this.el.update("");var _5=[];var o=this.jsonData;if(o&&o.length>0){for(var i=0,_8=o.length;i<_8;i++){var _9=this.prepareData(o[i],i,o);_5[_5.length]=this.tpl.apply(_9);}}else{_5.push(this.emptyText);}this.el.update(_5.join(""));this.nodes=this.el.dom.childNodes;this.updateIndexes(0);},load:function(){var um=this.el.getUpdateManager();um.update.apply(um,arguments);},render:function(el,_c){this.clearSelections();this.el.update("");var o;try{o=Ext.util.JSON.decode(_c.responseText);if(this.jsonRoot){o=eval("o."+this.jsonRoot);}}catch(e){}this.jsonData=o;this.beforeRender();this.refresh();},getCount:function(){return this.jsonData?this.jsonData.length:0;},getNodeData:function(_e){if(_e instanceof Array){var _f=[];for(var i=0,len=_e.length;i<len;i++){_f.push(this.getNodeData(_e[i]));}return _f;}return this.jsonData[this.indexOf(_e)]||null;},beforeRender:function(){this.snapshot=this.jsonData;if(this.sortInfo){this.sort.apply(this,this.sortInfo);}this.fireEvent("beforerender",this,this.jsonData);},onLoad:function(el,o){this.fireEvent("load",this,this.jsonData,o);},onLoadException:function(el,o){this.fireEvent("loadexception",this,o);},filter:function(_16,_17){if(this.jsonData){var _18=[];var ss=this.snapshot;if(typeof _17=="string"){var _1a=_17.length;if(_1a==0){this.clearFilter();return;}_17=_17.toLowerCase();for(var i=0,len=ss.length;i<len;i++){var o=ss[i];if(o[_16].substr(0,_1a).toLowerCase()==_17){_18.push(o);}}}else{if(_17.exec){for(var i=0,len=ss.length;i<len;i++){var o=ss[i];if(_17.test(o[_16])){_18.push(o);}}}else{return;}}this.jsonData=_18;this.refresh();}},filterBy:function(fn,_1f){if(this.jsonData){var _20=[];var ss=this.snapshot;for(var i=0,len=ss.length;i<len;i++){var o=ss[i];if(fn.call(_1f||this,o)){_20.push(o);}}this.jsonData=_20;this.refresh();}},clearFilter:function(){if(this.snapshot&&this.jsonData!=this.snapshot){this.jsonData=this.snapshot;this.refresh();}},sort:function(_25,dir,_27){this.sortInfo=Array.prototype.slice.call(arguments,0);if(this.jsonData){var p=_25;var dsc=dir&&dir.toLowerCase()=="desc";var f=function(o1,o2){var v1=_27?_27(o1[p]):o1[p];var v2=_27?_27(o2[p]):o2[p];if(v1<v2){return dsc?+1:-1;}else{if(v1>v2){return dsc?-1:+1;}else{return 0;}}};this.jsonData.sort(f);this.refresh();if(this.jsonData!=this.snapshot){this.snapshot.sort(f);}}}});