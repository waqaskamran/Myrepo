var OxOec18=["head","script","language","javascript","type","text/javascript","src","id","undefined","Microsoft.XMLHTTP","readyState","onreadystatechange","","document","length","element \x27","\x27 not found","returnValue","preventDefault","cancelBubble","stopPropagation","onchange","oninitialized","command","commandui","commandvalue","oncommand","string","event","_fireEventFunction","parentNode","_IsCuteEditor","True","readOnly","_IsRichDropDown","null","value","selectedIndex","nodeName","TR","cells","display","style","nextSibling","innerHTML","\x3Cimg src=\x22","/Images/t-minus.gif\x22\x3E","CuteEditor_CollapseTreeDropDownItem(this,\x22","\x22)","onclick","none","/Images/t-plus.gif\x22\x3E","CuteEditor_ExpandTreeDropDownItem(this,\x22","//TODO: event not found? throw error ?","all","UNSELECTABLE","on","tabIndex","-1","contentWindow","contentDocument","parentWindow","frames","frameElement","//TODO:frame contentWindow not found?","caller","arguments","parent","top","opener","srcElement","target","//TODO: srcElement not found? throw error ?","fromElement","relatedTarget","toElement","keyCode","clientX","clientY","offsetX","offsetY","button","ctrlKey","altKey","shiftKey","CuteEditor_GetEditor(this).ExecImageCommand(this.getAttribute(\x27Command\x27),this.getAttribute(\x27CommandUI\x27),this.getAttribute(\x27CommandArgument\x27),this)","CuteEditor_GetEditor(this).PostBack(this.getAttribute(\x27Command\x27))","this.onmouseout();CuteEditor_GetEditor(this).DropMenu(this.getAttribute(\x27Group\x27),this)","ResourceDir","Theme","/Themes/","/Images/all.png","/Images/blank2020.gif","IMG","Command","Group","ThemeIndex","width","20px","height","backgroundImage","url(",")","backgroundPosition","0 -","px","className","separator","CuteEditorButton","CuteEditor_ButtonCommandOver(this)","onmouseover","CuteEditor_ButtonCommandOut(this)","onmouseout","CuteEditor_ButtonCommandDown(this)","onmousedown","CuteEditor_ButtonCommandUp(this)","onmouseup","oncontextmenu","ondragstart","PostBack","ondblclick","_ToolBarID","_CodeViewToolBarID","_FrameID"," CuteEditorFrame"," CuteEditorToolbar","buttonInitialized","isover","CuteEditorButtonOver","CuteEditorButtonDown","CuteEditorDown","border","solid 1px #0A246A","backgroundColor","#b6bdd2","padding","1px","solid 1px #f5f5f4","inset 1px","IsCommandDisabled","CuteEditorButtonDisabled","IsCommandActive","CuteEditorButtonActive","(","href","location",",DanaInfo=",",","+","scriptProperties","GetScriptProperty","/Scripts/IE_Implementation/CuteEditorImplementation.js?i=1","CuteEditorImplementation","function","GET","\x26getModified=1","loadScript","status","Failed to load impl time!","cursor","body","InitializeCode","block","contentEditable","no-drop"]; function include(Ox8f0,Ox2bd){var Ox8f1=document.getElementsByTagName(OxOec18[0x0]).item(0x0);var Ox8f2=document.getElementById(Ox8f0);if(Ox8f2){ Ox8f1.removeChild(Ox8f2) ;} ;var Ox8f3=document.createElement(OxOec18[0x1]); Ox8f3.setAttribute(OxOec18[0x2],OxOec18[0x3]) ; Ox8f3.setAttribute(OxOec18[0x4],OxOec18[0x5]) ; Ox8f3.setAttribute(OxOec18[0x6],Ox2bd) ; Ox8f3.setAttribute(OxOec18[0x7],Ox8f0) ; Ox8f1.appendChild(Ox8f3) ;}  ; function CreateXMLHttpRequest(){try{if( typeof (XMLHttpRequest)!=OxOec18[0x8]){return  new XMLHttpRequest();} ;if( typeof (ActiveXObject)!=OxOec18[0x8]){return  new ActiveXObject(OxOec18[0x9]);} ;} catch(x){return null;} ;}  ; function LoadXMLAsync(Ox8f5,Ox2bd,Ox153,Ox8f6){var Ox79b=CreateXMLHttpRequest(); function Ox8f7(){if(Ox79b[OxOec18[0xa]]!=0x4){return ;} ; Ox79b[OxOec18[0xb]]= new Function() ;var Ox1a7=Ox79b; Ox79b=null ; Ox153(Ox1a7) ;}  ; Ox79b[OxOec18[0xb]]=Ox8f7 ; Ox79b.open(Ox8f5,Ox2bd,true) ; Ox79b.send(Ox8f6||OxOec18[0xc]) ;}  ; function Window_GetElement(Ox140,Ox11d,Ox15b){var Oxd=Ox140[OxOec18[0xd]].getElementById(Ox11d);if(Oxd){return Oxd;} ;var Oxc2=Ox140[OxOec18[0xd]].getElementsByName(Ox11d);if(Oxc2[OxOec18[0xe]]>0x0){return Oxc2.item(0x0);} ;if(Ox15b){throw ( new Error(OxOec18[0xf]+Ox11d+OxOec18[0x10]));} ;return null;}  ; function Event_PreventDefault(Ox161){ Ox161=Event_GetEvent(Ox161) ; Ox161[OxOec18[0x11]]=false ;if(Ox161[OxOec18[0x12]]){ Ox161.preventDefault() ;} ;}  ; function Event_CancelBubble(Ox161){ Ox161=Event_GetEvent(Ox161) ; Ox161[OxOec18[0x13]]=true ;if(Ox161[OxOec18[0x14]]){ Ox161.stopPropagation() ;} ;return false;}  ; function Event_CancelEvent(Ox161){ Ox161=Event_GetEvent(Ox161) ; Event_PreventDefault(Ox161) ;return Event_CancelBubble(Ox161);}  ; function CuteEditor_AddMainMenuItems(Ox56d){}  ; function CuteEditor_AddDropMenuItems(Ox56d,Ox574){}  ; function CuteEditor_AddTagMenuItems(Ox56d,Ox576){}  ; function CuteEditor_AddVerbMenuItems(Ox56d,Ox576){}  ; function CuteEditor_OnInitialized(editor){}  ; function CuteEditor_OnCommand(editor,Ox57a,Ox57b,Oxad){}  ; function CuteEditor_OnChange(editor){}  ; function CuteEditor_FilterCode(editor,Ox188){return Ox188;}  ; function CuteEditor_FilterHTML(editor,Ox19e){return Ox19e;}  ; function CuteEditor_FireChange(editor){ window.CuteEditor_OnChange(editor) ; CuteEditor_FireEvent(editor,OxOec18[0x15],null) ;}  ; function CuteEditor_FireInitialized(editor){ window.CuteEditor_OnInitialized(editor) ; CuteEditor_FireEvent(editor,OxOec18[0x16],null) ;}  ; function CuteEditor_FireCommand(editor,Ox57a,Ox57b,Oxad){var Oxca=window.CuteEditor_OnCommand(editor,Ox57a,Ox57b,Oxad);if(Oxca==true){return true;} ;var Ox582={}; Ox582[OxOec18[0x17]]=Ox57a ; Ox582[OxOec18[0x18]]=Ox57b ; Ox582[OxOec18[0x19]]=Oxad ; Ox582[OxOec18[0x11]]=true ; CuteEditor_FireEvent(editor,OxOec18[0x1a],Ox582) ;if(Ox582[OxOec18[0x11]]==false){return true;} ;}  ; function CuteEditor_FireEvent(editor,Ox584,Ox585){if(Ox585==null){ Ox585={} ;} ;var Ox586=editor.getAttribute(Ox584);if(Ox586){if( typeof (Ox586)==OxOec18[0x1b]){ editor[OxOec18[0x1d]]= new Function(OxOec18[0x1c],Ox586) ;} else { editor[OxOec18[0x1d]]=Ox586 ;} ; editor._fireEventFunction(Ox585) ;} ;}  ; function CuteEditor_GetEditor(element){for(var Oxdc=element;Oxdc!=null;Oxdc=Oxdc[OxOec18[0x1e]]){if(Oxdc.getAttribute(OxOec18[0x1f])==OxOec18[0x20]){return Oxdc;} ;} ;return null;}  ; function CuteEditor_DropDownCommand(element,Ox8f9){var editor=CuteEditor_GetEditor(element);if(editor[OxOec18[0x21]]){return ;} ;if(element.getAttribute(OxOec18[0x22])==OxOec18[0x20]){var Oxce=element.GetValue();if(Oxce==OxOec18[0x23]){ Oxce=OxOec18[0xc] ;} ;var Ox117=element.GetText();if(Ox117==OxOec18[0x23]){ Ox117=OxOec18[0xc] ;} ; element.SetSelectedIndex(0x0) ; editor.ExecCommand(Ox8f9,false,Oxce,Ox117) ;} else {if(!editor[OxOec18[0x21]]&&element[OxOec18[0x24]]){var Oxce=element[OxOec18[0x24]];if(Oxce==OxOec18[0x23]){ Oxce=OxOec18[0xc] ;} ; element[OxOec18[0x25]]=0x0 ; editor.ExecCommand(Ox8f9,false,Oxce,Ox117) ;} else { element[OxOec18[0x25]]=0x0 ;} ;} ; editor.FocusDocument() ;}  ; function CuteEditor_ExpandTreeDropDownItem(src,Ox644){var Ox612=null;while(src!=null){if(src[OxOec18[0x26]]==OxOec18[0x27]){ Ox612=src ;break ;} ; src=src[OxOec18[0x1e]] ;} ;var Ox16=Ox612[OxOec18[0x28]].item(0x0); Ox612[OxOec18[0x2b]][OxOec18[0x2a]][OxOec18[0x29]]=OxOec18[0xc] ; Ox16[OxOec18[0x2c]]=OxOec18[0x2d]+Ox644+OxOec18[0x2e] ; Ox612[OxOec18[0x31]]= new Function(OxOec18[0x2f]+Ox644+OxOec18[0x30]) ;}  ; function CuteEditor_CollapseTreeDropDownItem(src,Ox644){var Ox612=null;while(src!=null){if(src[OxOec18[0x26]]==OxOec18[0x27]){ Ox612=src ;break ;} ; src=src[OxOec18[0x1e]] ;} ;var Ox16=Ox612[OxOec18[0x28]].item(0x0); Ox612[OxOec18[0x2b]][OxOec18[0x2a]][OxOec18[0x29]]=OxOec18[0x32] ; Ox16[OxOec18[0x2c]]=OxOec18[0x2d]+Ox644+OxOec18[0x33] ; Ox612[OxOec18[0x31]]= new Function(OxOec18[0x34]+Ox644+OxOec18[0x30]) ;}  ; function Event_GetEvent(Ox161){ Ox161=Event_FindEvent(Ox161) ;if(Ox161==null){ Debug_Todo(OxOec18[0x35]) ;} ;return Ox161;}  ; function Element_GetAllElements(p){var arr=[];for(var i=0x0;i<p[OxOec18[0x36]][OxOec18[0xe]];i++){ arr.push(p[OxOec18[0x36]].item(i)) ;} ;return arr;}  ; function Element_SetUnselectable(element){ element.setAttribute(OxOec18[0x37],OxOec18[0x38]) ; element.setAttribute(OxOec18[0x39],OxOec18[0x3a]) ;var arr=Element_GetAllElements(element);var len=arr[OxOec18[0xe]];if(!len){return ;} ;for(var i=0x0;i<len;i++){ arr[i].setAttribute(OxOec18[0x37],OxOec18[0x38]) ; arr[i].setAttribute(OxOec18[0x39],OxOec18[0x3a]) ;} ;}  ; function Frame_GetContentWindow(Ox267){if(Ox267[OxOec18[0x3b]]){return Ox267[OxOec18[0x3b]];} ;if(Ox267[OxOec18[0x3c]]){if(Ox267[OxOec18[0x3c]][OxOec18[0x3d]]){return Ox267[OxOec18[0x3c]][OxOec18[0x3d]];} ;} ;var Ox140;if(Ox267[OxOec18[0x7]]){ Ox140=window[OxOec18[0x3e]][Ox267[OxOec18[0x7]]] ;if(Ox140){return Ox140;} ;} ;var len=window[OxOec18[0x3e]][OxOec18[0xe]];for(var i=0x0;i<len;i++){ Ox140=window[OxOec18[0x3e]][i] ;if(Ox140[OxOec18[0x3f]]==Ox267){return Ox140;} ;if(Ox140[OxOec18[0xd]]==Ox267[OxOec18[0x3c]]){return Ox140;} ;} ; Debug_Todo(OxOec18[0x40]) ;}  ; function Array_IndexOf(arr,Ox163){for(var i=0x0;i<arr[OxOec18[0xe]];i++){if(arr[i]==Ox163){return i;} ;} ;return -0x1;}  ; function Array_Contains(arr,Ox163){return Array_IndexOf(arr,Ox163)!=-0x1;}  ; function clearArray(Ox166){for(var i=0x0;i<Ox166[OxOec18[0xe]];i++){ Ox166[i]=null ;} ;}  ; function Event_FindEvent(Ox161){if(Ox161&&Ox161[OxOec18[0x12]]){return Ox161;} ;if(window[OxOec18[0x1c]]){return window[OxOec18[0x1c]];} ;return Event_FindEvent_FindEventFromWindows();}  ; function Event_FindEvent_FindEventFromCallers(){var Ox169=Event_GetEvent[OxOec18[0x41]];for(var i=0x0;i<0x64;i++){if(!Ox169){break ;} ;var Ox161=Ox169[OxOec18[0x42]][0x0];if(Ox161&&Ox161[OxOec18[0x12]]){return Ox161;} ; Ox169=Ox169[OxOec18[0x41]] ;} ;}  ; function Event_FindEvent_FindEventFromWindows(){var arr=[];return Ox16b(window); function Ox16b(Ox140){if(Ox140==null){return null;} ;if(Ox140[OxOec18[0x1c]]){return Ox140[OxOec18[0x1c]];} ;if(Array_Contains(arr,Ox140)){return null;} ; arr.push(Ox140) ;var Ox16c=[];if(Ox140[OxOec18[0x43]]!=Ox140){ Ox16c.push(Ox140.parent) ;} ;if(Ox140[OxOec18[0x44]]!=Ox140[OxOec18[0x43]]){ Ox16c.push(Ox140.top) ;} ;if(Ox140[OxOec18[0x45]]){ Ox16c.push(Ox140.opener) ;} ;for(var i=0x0;i<Ox140[OxOec18[0x3e]][OxOec18[0xe]];i++){ Ox16c.push(Ox140[OxOec18[0x3e]][i]) ;} ;for(var i=0x0;i<Ox16c[OxOec18[0xe]];i++){try{var Ox161=Ox16b(Ox16c[i]);if(Ox161){return Ox161;} ;} catch(x){} ;} ;return null;}  ;}  ; function Event_GetSrcElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOec18[0x46]]){return Ox161[OxOec18[0x46]];} ;if(Ox161[OxOec18[0x47]]){return Ox161[OxOec18[0x47]];} ; Debug_Todo(OxOec18[0x48]) ;return null;}  ; function Event_GetFromElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOec18[0x49]]){return Ox161[OxOec18[0x49]];} ;if(Ox161[OxOec18[0x4a]]){return Ox161[OxOec18[0x4a]];} ;return null;}  ; function Event_GetToElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOec18[0x4b]]){return Ox161[OxOec18[0x4b]];} ;if(Ox161[OxOec18[0x4a]]){return Ox161[OxOec18[0x4a]];} ;return null;}  ; function Event_GetKeyCode(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x4c]];}  ; function Event_GetClientX(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x4d]];}  ; function Event_GetClientY(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x4e]];}  ; function Event_GetOffsetX(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x4f]];}  ; function Event_GetOffsetY(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x50]];}  ; function Event_IsLeftButton(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x51]]==0x1;}  ; function Event_IsCtrlKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x52]];}  ; function Event_IsAltKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x53]];}  ; function Event_IsShiftKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOec18[0x54]];}  ; function CuteEditor_BasicInitialize(editor){var Ox60c= new Function(OxOec18[0x55]);var Ox97d= new Function(OxOec18[0x56]);var Ox8fe= new Function(OxOec18[0x57]);var Ox8ff=editor.GetScriptProperty(OxOec18[0x58]);var Ox900=editor.GetScriptProperty(OxOec18[0x59]);var Ox901=Ox8ff+OxOec18[0x5a]+Ox900+OxOec18[0x5b];var Ox902=Ox8ff+OxOec18[0x5c];var images=editor.getElementsByTagName(OxOec18[0x5d]);var len=images[OxOec18[0xe]];for(var i=0x0;i<len;i++){var img=images[i];var Oxc1=img.getAttribute(OxOec18[0x5e]);var Ox574=img.getAttribute(OxOec18[0x5f]);if(!(Oxc1||Ox574)){continue ;} ;var Ox903=img.getAttribute(OxOec18[0x60]);if(parseInt(Ox903)>=0x0){ img[OxOec18[0x2a]][OxOec18[0x61]]=OxOec18[0x62] ; img[OxOec18[0x2a]][OxOec18[0x63]]=OxOec18[0x62] ; img[OxOec18[0x6]]=Ox902 ; img[OxOec18[0x2a]][OxOec18[0x64]]=OxOec18[0x65]+Ox901+OxOec18[0x66] ; img[OxOec18[0x2a]][OxOec18[0x67]]=OxOec18[0x68]+(Ox903*0x14)+OxOec18[0x69] ; img[OxOec18[0x2a]][OxOec18[0x29]]=OxOec18[0xc] ;} ;if(img[OxOec18[0x6a]]!=OxOec18[0x6b]){ img[OxOec18[0x6a]]=OxOec18[0x6c] ; img[OxOec18[0x6e]]= new Function(OxOec18[0x6d]) ; img[OxOec18[0x70]]= new Function(OxOec18[0x6f]) ; img[OxOec18[0x72]]= new Function(OxOec18[0x71]) ; img[OxOec18[0x74]]= new Function(OxOec18[0x73]) ;} ;if(!img[OxOec18[0x75]]){ img[OxOec18[0x75]]=Event_CancelEvent ;} ;if(!img[OxOec18[0x76]]){ img[OxOec18[0x76]]=Event_CancelEvent ;} ;if(Oxc1){var Ox169=img.getAttribute(OxOec18[0x77])==OxOec18[0x20]?Ox97d:Ox60c;if(img[OxOec18[0x31]]==null){ img[OxOec18[0x31]]=Ox169 ;} ;if(img[OxOec18[0x78]]==null){ img[OxOec18[0x78]]=Ox169 ;} ;} else {if(Ox574){if(img[OxOec18[0x31]]==null){ img[OxOec18[0x31]]=Ox8fe ;} ;} ;} ;} ;var Ox678=Window_GetElement(window,editor.GetScriptProperty(OxOec18[0x79]),true);var Ox679=Window_GetElement(window,editor.GetScriptProperty(OxOec18[0x7a]),true);var Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOec18[0x7b]),true); Ox675[OxOec18[0x6a]]+=OxOec18[0x7c] ; Ox678[OxOec18[0x6a]]+=OxOec18[0x7d] ; Ox679[OxOec18[0x6a]]+=OxOec18[0x7d] ; Element_SetUnselectable(Ox678) ; Element_SetUnselectable(Ox679) ;}  ; function CuteEditor_ButtonOver(element){if(!element[OxOec18[0x7e]]){ element[OxOec18[0x75]]=Event_CancelEvent ; element[OxOec18[0x70]]=CuteEditor_ButtonOut ; element[OxOec18[0x72]]=CuteEditor_ButtonDown ; element[OxOec18[0x74]]=CuteEditor_ButtonUp ; Element_SetUnselectable(element) ; element[OxOec18[0x7e]]=true ;} ; element[OxOec18[0x7f]]=true ; element[OxOec18[0x6a]]=OxOec18[0x80] ;}  ; function CuteEditor_ButtonOut(){var element=this; element[OxOec18[0x6a]]=OxOec18[0x6c] ; element[OxOec18[0x7f]]=false ;}  ; function CuteEditor_ButtonDown(){if(!Event_IsLeftButton()){return ;} ;var element=this; element[OxOec18[0x6a]]=OxOec18[0x81] ;}  ; function CuteEditor_ButtonUp(){if(!Event_IsLeftButton()){return ;} ;var element=this;if(element[OxOec18[0x7f]]){ element[OxOec18[0x6a]]=OxOec18[0x80] ;} else { element[OxOec18[0x6a]]=OxOec18[0x82] ;} ;}  ; function CuteEditor_ColorPicker_ButtonOver(element){if(!element[OxOec18[0x7e]]){ element[OxOec18[0x75]]=Event_CancelEvent ; element[OxOec18[0x70]]=CuteEditor_ColorPicker_ButtonOut ; element[OxOec18[0x72]]=CuteEditor_ColorPicker_ButtonDown ; Element_SetUnselectable(element) ; element[OxOec18[0x7e]]=true ;} ; element[OxOec18[0x7f]]=true ; element[OxOec18[0x2a]][OxOec18[0x83]]=OxOec18[0x84] ; element[OxOec18[0x2a]][OxOec18[0x85]]=OxOec18[0x86] ; element[OxOec18[0x2a]][OxOec18[0x87]]=OxOec18[0x88] ;}  ; function CuteEditor_ColorPicker_ButtonOut(){var element=this; element[OxOec18[0x7f]]=false ; element[OxOec18[0x2a]][OxOec18[0x83]]=OxOec18[0x89] ; element[OxOec18[0x2a]][OxOec18[0x85]]=OxOec18[0xc] ; element[OxOec18[0x2a]][OxOec18[0x87]]=OxOec18[0x88] ;}  ; function CuteEditor_ColorPicker_ButtonDown(){var element=this; element[OxOec18[0x2a]][OxOec18[0x83]]=OxOec18[0x8a] ; element[OxOec18[0x2a]][OxOec18[0x85]]=OxOec18[0xc] ; element[OxOec18[0x2a]][OxOec18[0x87]]=OxOec18[0x88] ;}  ; function CuteEditor_ButtonCommandOver(element){ element[OxOec18[0x7f]]=true ;if(element[OxOec18[0x8b]]){ element[OxOec18[0x6a]]=OxOec18[0x8c] ;} else { element[OxOec18[0x6a]]=OxOec18[0x80] ;} ;}  ; function CuteEditor_ButtonCommandOut(element){ element[OxOec18[0x7f]]=false ;if(element[OxOec18[0x8d]]){ element[OxOec18[0x6a]]=OxOec18[0x8e] ;} else {if(element[OxOec18[0x8b]]){ element[OxOec18[0x6a]]=OxOec18[0x8c] ;} else { element[OxOec18[0x6a]]=OxOec18[0x6c] ;} ;} ;}  ; function CuteEditor_ButtonCommandDown(element){if(!Event_IsLeftButton()){return ;} ; element[OxOec18[0x6a]]=OxOec18[0x81] ;}  ; function CuteEditor_ButtonCommandUp(element){if(!Event_IsLeftButton()){return ;} ;if(element[OxOec18[0x8b]]){ element[OxOec18[0x6a]]=OxOec18[0x8c] ;return ;} ;if(element[OxOec18[0x7f]]){ element[OxOec18[0x6a]]=OxOec18[0x80] ;} else {if(element[OxOec18[0x8d]]){ element[OxOec18[0x6a]]=OxOec18[0x8e] ;} else { element[OxOec18[0x6a]]=OxOec18[0x6c] ;} ;} ;}  ;var CuteEditorGlobalFunctions=[CuteEditor_GetEditor,CuteEditor_ButtonOver,CuteEditor_ButtonOut,CuteEditor_ButtonDown,CuteEditor_ButtonUp,CuteEditor_ColorPicker_ButtonOver,CuteEditor_ColorPicker_ButtonOut,CuteEditor_ColorPicker_ButtonDown,CuteEditor_ButtonCommandOver,CuteEditor_ButtonCommandOut,CuteEditor_ButtonCommandDown,CuteEditor_ButtonCommandUp,CuteEditor_DropDownCommand,CuteEditor_ExpandTreeDropDownItem,CuteEditor_CollapseTreeDropDownItem,CuteEditor_OnInitialized,CuteEditor_OnCommand,CuteEditor_OnChange,CuteEditor_AddVerbMenuItems,CuteEditor_AddTagMenuItems,CuteEditor_AddMainMenuItems,CuteEditor_AddDropMenuItems,CuteEditor_FilterCode,CuteEditor_FilterHTML]; function SetupCuteEditorGlobalFunctions(){for(var i=0x0;i<CuteEditorGlobalFunctions[OxOec18[0xe]];i++){var Ox169=CuteEditorGlobalFunctions[i];var name=Ox169+OxOec18[0xc]; name=name.substr(0x8,name.indexOf(OxOec18[0x8f])-0x8).replace(/\s/g,OxOec18[0xc]) ;if(!window[name]){ window[name]=Ox169 ;} ;} ;}  ; SetupCuteEditorGlobalFunctions() ;var __danainfo=null;var danaurl=window[OxOec18[0x91]][OxOec18[0x90]];var danapos=danaurl.indexOf(OxOec18[0x92]);if(danapos!=-0x1){var pluspos1=danaurl.indexOf(OxOec18[0x93],danapos+0xa);var pluspos2=danaurl.indexOf(OxOec18[0x94],danapos+0xa);if(pluspos1!=-0x1&&pluspos1<pluspos2){ pluspos2=pluspos1 ;} ; __danainfo=danaurl.substring(danapos,pluspos2)+OxOec18[0x94] ;} ; function CuteEditor_GetScriptProperty(name){var Oxce=this[OxOec18[0x95]][name];if(Oxce&&__danainfo){if(name==OxOec18[0x58]){return Oxce+__danainfo;} ;var Ox731=this[OxOec18[0x95]][OxOec18[0x58]];if(Oxce.substr(0x0,Ox731.length)==Ox731){return Ox731+__danainfo+Oxce.substring(Ox731.length);} ;} ;return Oxce;}  ; function CuteEditor_SetScriptProperty(name,Oxce){if(Oxce==null){ this[OxOec18[0x95]][name]=null ;} else { this[OxOec18[0x95]][name]=String(Oxce) ;} ;}  ; function CuteEditorInitialize(Ox910,Ox911){var editor=Window_GetElement(window,Ox910,true); editor[OxOec18[0x95]]=Ox911 ; editor[OxOec18[0x96]]=CuteEditor_GetScriptProperty ;var Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOec18[0x7b]),true);var editwin=Frame_GetContentWindow(Ox675);var editdoc=editwin[OxOec18[0xd]];var Ox912=false;var Ox913;var Ox914=false;var Ox915=editor.GetScriptProperty(OxOec18[0x58])+OxOec18[0x97]; function Ox916(){if( typeof (window[OxOec18[0x98]])==OxOec18[0x99]){return ;} ;try{ LoadXMLAsync(OxOec18[0x9a],Ox915+OxOec18[0x9b],Ox917) ;} catch(x){ include(OxOec18[0x9c],Ox915) ;var Ox97e=setInterval(function (){if(window[OxOec18[0x98]]){ clearInterval(Ox97e) ;if(Ox912){ Ox919() ;} ;} ;} ,0x64);} ;}  ; function Ox917(Ox1a7){if(Ox1a7[OxOec18[0x9d]]!=0xc8){ alert(OxOec18[0x9e]) ;return ;} ; CuteEditorInstallScriptCode(Ox1a7.responseText,OxOec18[0x98]) ;if(Ox912){ Ox919() ;} ;}  ; function Ox919(){if(Ox914){return ;} ; Ox914=true ; window.CuteEditorImplementation(editor) ;try{ editor[OxOec18[0x2a]][OxOec18[0x9f]]=OxOec18[0xc] ;} catch(x){} ;try{ editdoc[OxOec18[0xa0]][OxOec18[0x2a]][OxOec18[0x9f]]=OxOec18[0xc] ;} catch(x){} ;var Ox91a=editor.GetScriptProperty(OxOec18[0xa1]);if(Ox91a){ editor.Eval(Ox91a) ;} ;}  ; function Ox91b(){if(!window[OxOec18[0xd]][OxOec18[0xa0]].contains(editor)){return ;} ;try{ Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOec18[0x7b]),true) ; editwin=Frame_GetContentWindow(Ox675) ; editdoc=editwin[OxOec18[0xd]] ; x=editdoc[OxOec18[0xa0]] ;} catch(x){ setTimeout(Ox91b,0x64) ;return ;} ;if(!editdoc[OxOec18[0xa0]]){ setTimeout(Ox91b,0x64) ;return ;} ;if(!Ox912){ Ox675[OxOec18[0x2a]][OxOec18[0x29]]=OxOec18[0xa2] ; editdoc[OxOec18[0xa0]][OxOec18[0xa3]]=true ; Ox912=true ; setTimeout(Ox91b,0x64) ;return ;} ;if( typeof (window[OxOec18[0x98]])==OxOec18[0x99]){ Ox919() ;} else {try{ editdoc[OxOec18[0xa0]][OxOec18[0x2a]][OxOec18[0x9f]]=OxOec18[0xa4] ;} catch(x){} ;} ;}  ; CuteEditor_BasicInitialize(editor) ; Ox916() ; Ox91b() ;}  ; function CuteEditorInstallScriptCode(Ox897,Ox898){ eval(Ox897) ; window[Ox898]=eval(Ox898) ;}  ;