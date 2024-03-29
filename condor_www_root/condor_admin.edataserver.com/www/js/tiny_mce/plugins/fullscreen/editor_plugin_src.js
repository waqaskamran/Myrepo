/**
 * $RCSfile$
 * $Revision: 818 $
 * $Date: 2006-04-12 14:19:13 -0700 (Wed, 12 Apr 2006) $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('fullscreen', 'en,tr,sv,cs,fr_ca,zh_cn,da,he,nb,de,hu,ru,ru_KOI8-R,ru_UTF-8,nn,es,cy,is,pl,nl,fr,pt_br');

var TinyMCE_FullScreenPlugin = {
	getInfo : function() {
		return {
			longname : 'Fullscreen',
			author : 'Moxiecode Systems',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_fullscreen.html',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "fullscreen":
				return tinyMCE.getButtonHTML(cn, 'lang_fullscreen_desc', '{$pluginurl}/images/fullscreen.gif', 'mceFullScreen');
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceFullScreen":
				if (tinyMCE.getParam('fullscreen_is_enabled')) {
					// In fullscreen mode
					window.opener.tinyMCE.execInstanceCommand(tinyMCE.getParam('fullscreen_editor_id'), 'mceSetContent', false, tinyMCE.getContent(editor_id));
					top.close();
				} else {
					tinyMCE.setWindowArg('editor_id', editor_id);

					var win = window.open(tinyMCE.baseURL + "/plugins/fullscreen/fullscreen.htm", "mceFullScreenPopup", "fullscreen=yes,menubar=no,toolbar=no,scrollbars=no,resizable=yes,left=0,top=0,width=" + screen.availWidth + ",height=" + screen.availHeight);
					try { win.resizeTo(screen.availWidth, screen.availHeight); } catch (e) {}
				}
		
				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (tinyMCE.getParam('fullscreen_is_enabled'))
			tinyMCE.switchClass(editor_id + '_fullscreen', 'mceButtonSelected');

		return true;
	}
};

tinyMCE.addPlugin("fullscreen", TinyMCE_FullScreenPlugin);
