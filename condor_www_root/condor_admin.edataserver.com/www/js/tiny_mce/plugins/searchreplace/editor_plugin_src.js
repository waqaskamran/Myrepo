/**
 * $RCSfile$
 * $Revision: 818 $
 * $Date: 2006-04-12 14:19:13 -0700 (Wed, 12 Apr 2006) $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import theme	specific language pack */
tinyMCE.importPluginLanguagePack('searchreplace', 'en,tr,sv,zh_cn,fa,fr_ca,fr,de,pl,pt_br,cs,nl,da,he,nb,hu,ru,ru_KOI8-R,ru_UTF-8,nn,fi,cy,es,is,zh_tw,zh_tw_utf8,sk');

var TinyMCE_SearchReplacePlugin = {
	getInfo : function() {
		return {
			longname : 'Search/Replace',
			author : 'Moxiecode Systems',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_searchreplace.html',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		inst.addShortcut('ctrl', 'f', 'lang_searchreplace_search_desc', 'mceSearch', true);
	},

	getControlHTML : function(cn)	{
		switch (cn) {
			case "search":
				return tinyMCE.getButtonHTML(cn, 'lang_searchreplace_search_desc', '{$pluginurl}/images/search.gif', 'mceSearch', true);
			case "replace":
				return tinyMCE.getButtonHTML(cn, 'lang_searchreplace_replace_desc', '{$pluginurl}/images/replace.gif', 'mceSearchReplace', true);
		}
		return "";
	},

	/**
	 * Executes	the	search/replace commands.
	 */
	execCommand : function(editor_id, element, command,	user_interface,	value) {
		var instance = tinyMCE.getInstanceById(editor_id);

		function defValue(key, default_value) {
			value[key] = typeof(value[key]) == "undefined" ? default_value : value[key];
		}

		function replaceSel(search_str, str, back) {
			instance.execCommand('mceInsertContent', false, str);
		}

		if (!value)
			value = new Array();

		// Setup defualt values
		defValue("editor_id", editor_id);
		defValue("searchstring", "");
		defValue("replacestring", null);
		defValue("replacemode", "none");
		defValue("casesensitive", false);
		defValue("backwards", false);
		defValue("wrap", false);
		defValue("wholeword", false);
		defValue("inline", "yes");

		// Handle commands
		switch (command) {
			case "mceResetSearch":
				tinyMCE.lastSearchRng = null;
				return true;

			case "mceSearch":
				if (user_interface) {
					// Open search dialog
					var template = new Array();

					if (value['replacestring'] != null) {
						template['file'] = '../../plugins/searchreplace/replace.htm'; // Relative to theme
						template['width'] = 320;
						template['height'] = 100 + (tinyMCE.isNS7 ? 20 : 0);
						template['width'] += tinyMCE.getLang('lang_searchreplace_replace_delta_width', 0);
						template['height'] += tinyMCE.getLang('lang_searchreplace_replace_delta_height', 0);
					} else {
						template['file'] = '../../plugins/searchreplace/search.htm'; // Relative to theme
						template['width'] = 310;
						template['height'] = 105 + (tinyMCE.isNS7 ? 25 : 0);
						template['width'] += tinyMCE.getLang('lang_searchreplace_search_delta_width', 0);
						template['height'] += tinyMCE.getLang('lang_searchreplace_replace_delta_height', 0);
					}

					instance.execCommand('SelectAll');

					if (tinyMCE.isMSIE) {
						var r = instance.selection.getRng();
						r.collapse(true);
						r.select();
					} else
						instance.selection.getSel().collapseToStart();

					tinyMCE.openWindow(template, value);
				} else {
					var win = tinyMCE.getInstanceById(editor_id).contentWindow;
					var doc = tinyMCE.getInstanceById(editor_id).contentWindow.document;
					var body = tinyMCE.getInstanceById(editor_id).contentWindow.document.body;

					// Whats the point
					if (body.innerHTML == "") {
						alert(tinyMCE.getLang('lang_searchreplace_notfound'));
						return true;
					}

					// Handle replace current
					if (value['replacemode'] == "current") {
						replaceSel(value['string'], value['replacestring'], value['backwards']);

						// Search next one
						value['replacemode'] = "none";
						tinyMCE.execInstanceCommand(editor_id, 'mceSearch', user_interface, value, false);

						return true;
					}

					if (tinyMCE.isMSIE) {
						var rng = tinyMCE.lastSearchRng ? tinyMCE.lastSearchRng : doc.selection.createRange();
						var flags = 0;

						if (value['wholeword'])
							flags = flags | 2;

						if (value['casesensitive'])
							flags = flags | 4;

						if (!rng.findText) {
							alert('This operation is currently not supported by this browser.');
							return true;
						}

						// Handle replace all mode
						if (value['replacemode'] == "all") {
							while (rng.findText(value['string'], value['backwards'] ? -1 : 1, flags)) {
								rng.scrollIntoView();
								rng.select();
								rng.collapse(false);
								replaceSel(value['string'], value['replacestring'], value['backwards']);
							}

							alert(tinyMCE.getLang('lang_searchreplace_allreplaced'));
							return true;
						}

						if (rng.findText(value['string'], value['backwards'] ? -1 : 1, flags)) {
							rng.scrollIntoView();
							rng.select();
							rng.collapse(value['backwards']);
							tinyMCE.lastSearchRng = rng;
						} else
							alert(tinyMCE.getLang('lang_searchreplace_notfound'));
					} else {
						if (value['replacemode'] == "all") {
							while (win.find(value['string'], value['casesensitive'], value['backwards'], value['wrap'], value['wholeword'], false, false))
								replaceSel(value['string'], value['replacestring'], value['backwards']);

							alert(tinyMCE.getLang('lang_searchreplace_allreplaced'));
							return true;
						}

						if (!win.find(value['string'], value['casesensitive'], value['backwards'], value['wrap'], value['wholeword'], false, false))
							alert(tinyMCE.getLang('lang_searchreplace_notfound'));
					}
				}
				return true;

			case "mceSearchReplace":
				value['replacestring'] = "";

				tinyMCE.execInstanceCommand(editor_id, 'mceSearch', user_interface, value, false);
				return true;
		}

		// Pass to next handler in chain
		return false;
	}
};

tinyMCE.addPlugin("searchreplace", TinyMCE_SearchReplacePlugin);
