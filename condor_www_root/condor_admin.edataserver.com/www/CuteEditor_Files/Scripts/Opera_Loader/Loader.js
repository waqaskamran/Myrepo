var OxOdad3=["isWinIE","isGecko","isSafari","isOpera","userAgent","ua","opera","safari","gecko","msie","compatMode","document","CSS1Compat","head","script","language","javascript","type","text/javascript","src","id","undefined","Microsoft.XMLHTTP","readyState","onreadystatechange","","length","all","childNodes","nodeType","\x0D\x0A","onchange","oninitialized","command","commandui","commandvalue","returnValue","oncommand","string","event","_fireEventFunction","parentNode","_IsCuteEditor","True","readOnly","_IsRichDropDown","null","value","selectedIndex","nodeName","TR","cells","display","style","nextSibling","innerHTML","\x3Cimg src=\x22","/Images/t-minus.gif\x22\x3E","CuteEditor_CollapseTreeDropDownItem(this,\x22","\x22)","onclick","onmousedown","none","/Images/t-plus.gif\x22\x3E","CuteEditor_ExpandTreeDropDownItem(this,\x22","contains","UNSELECTABLE","on","tabIndex","-1","//TODO: event not found? throw error ?","contentWindow","contentDocument","parentWindow","frames","frameElement","//TODO:frame contentWindow not found?","preventDefault","caller","arguments","parent","top","opener","srcElement","target","//TODO: srcElement not found? throw error ?","fromElement","relatedTarget","toElement","keyCode","clientX","clientY","offsetX","offsetY","button","ctrlKey","altKey","shiftKey","cancelBubble","stopPropagation","CuteEditor_GetEditor(this).ExecImageCommand(this.getAttribute(\x27Command\x27),this.getAttribute(\x27CommandUI\x27),this.getAttribute(\x27CommandArgument\x27),this)","CuteEditor_GetEditor(this).PostBack(this.getAttribute(\x27Command\x27))","this.onmouseout();CuteEditor_GetEditor(this).DropMenu(this.getAttribute(\x27Group\x27),this)","ResourceDir","Theme","/Themes/","/Images/all.png","/Images/blank2020.png","IMG","Command","Group","ThemeIndex","width","20px","height","backgroundImage","url(",")","backgroundPosition","0 -","px","onload","className","separator","CuteEditorButton","CuteEditor_ButtonCommandOver(this)","onmouseover","CuteEditor_ButtonCommandOut(this)","onmouseout","CuteEditor_ButtonCommandDown(this)","CuteEditor_ButtonCommandUp(this)","onmouseup","oncontextmenu","ondragstart","ondblclick","_ToolBarID","_CodeViewToolBarID","_FrameID"," CuteEditorFrame"," CuteEditorToolbar","cursor","no-drop","ActiveTab","View","Code","Edit","buttonInitialized","isover","CuteEditorButtonOver","CuteEditorButtonDown","CuteEditorDown","border","solid 1px #0A246A","backgroundColor","#b6bdd2","padding","1px","solid 1px #f5f5f4","inset 1px","IsCommandDisabled","CuteEditorButtonDisabled","IsCommandActive","CuteEditorButtonActive","cmd_fromfullpage","(","href","location",",DanaInfo=",",","+","scriptProperties","GetScriptProperty","/Scripts/Opera_Implementation/CuteEditorImplementation.js","CuteEditorImplementation","function","GET","responseText"," \x0D\x0A window.CuteEditorImplementation=CuteEditorImplementation","body","InitializeCode","block","contentEditable"," \x3Cbr /\x3E ","designMode","CuteEditorInitialize"];var _Browser_TypeInfo=null; function Browser__InitType(){if(_Browser_TypeInfo!=null){return ;} ;var Ox147={}; Ox147[OxOdad3[0x5]]=navigator[OxOdad3[0x4]].toLowerCase(),Ox147[OxOdad3[0x3]]=(Ox147[OxOdad3[0x5]].indexOf(OxOdad3[0x6])>-0x1),Ox147[OxOdad3[0x2]]=(Ox147[OxOdad3[0x5]].indexOf(OxOdad3[0x7])>-0x1),Ox147[OxOdad3[0x1]]=(!Ox147[OxOdad3[0x3]]&&!Ox147[OxOdad3[0x2]]&&Ox147[OxOdad3[0x5]].indexOf(OxOdad3[0x8])>-0x1),Ox147[OxOdad3[0x0]]=(!Ox147[OxOdad3[0x3]]&&Ox147[OxOdad3[0x5]].indexOf(OxOdad3[0x9])>-0x1) ; _Browser_TypeInfo=Ox147 ;}  ; Browser__InitType() ; function Browser_IsWinIE(){return _Browser_TypeInfo[OxOdad3[0x0]];}  ; function Browser_IsGecko(){return _Browser_TypeInfo[OxOdad3[0x1]];}  ; function Browser_IsOpera(){return _Browser_TypeInfo[OxOdad3[0x3]];}  ; function Browser_IsSafari(){return _Browser_TypeInfo[OxOdad3[0x2]];}  ; function Browser_UseIESelection(){return _Browser_TypeInfo[OxOdad3[0x0]];}  ; function Browser_IsCSS1Compat(){return window[OxOdad3[0xb]][OxOdad3[0xa]]==OxOdad3[0xc];}  ; function include(Ox8f0,Ox2bd){var Ox8f1=document.getElementsByTagName(OxOdad3[0xd]).item(0x0);var Ox8f2=document.getElementById(Ox8f0);if(Ox8f2){ Ox8f1.removeChild(Ox8f2) ;} ;var Ox8f3=document.createElement(OxOdad3[0xe]); Ox8f3.setAttribute(OxOdad3[0xf],OxOdad3[0x10]) ; Ox8f3.setAttribute(OxOdad3[0x11],OxOdad3[0x12]) ; Ox8f3.setAttribute(OxOdad3[0x13],Ox2bd) ; Ox8f3.setAttribute(OxOdad3[0x14],Ox8f0) ; Ox8f1.appendChild(Ox8f3) ;}  ; function CreateXMLHttpRequest(){try{if( typeof (XMLHttpRequest)!=OxOdad3[0x15]){return  new XMLHttpRequest();} ;if( typeof (ActiveXObject)!=OxOdad3[0x15]){return  new ActiveXObject(OxOdad3[0x16]);} ;} catch(x){return null;} ;}  ; function LoadXMLAsync(Ox8f5,Ox2bd,Ox153,Ox8f6){var Ox79b=CreateXMLHttpRequest(); function Ox8f7(){if(Ox79b[OxOdad3[0x17]]!=0x4){return ;} ; Ox79b[OxOdad3[0x18]]= new Function() ;var Ox1a7=Ox79b; Ox79b=null ;if(Ox153){ Ox153(Ox1a7) ;} ;}  ; Ox79b[OxOdad3[0x18]]=Ox8f7 ; Ox79b.open(Ox8f5,Ox2bd,true) ; Ox79b.send(Ox8f6||OxOdad3[0x19]) ;}  ; function Element_GetAllElements(p){var arr=[];if(Browser_IsWinIE()){for(var i=0x0;i<p[OxOdad3[0x1b]][OxOdad3[0x1a]];i++){ arr.push(p[OxOdad3[0x1b]].item(i)) ;} ;return arr;} ; Ox15d(p) ; function Ox15d(Oxd){var Ox15e=Oxd[OxOdad3[0x1c]];var Ox10=Ox15e[OxOdad3[0x1a]];for(var i=0x0;i<Ox10;i++){var Ox91=Ox15e.item(i);if(Ox91[OxOdad3[0x1d]]!=0x1){continue ;} ; arr.push(Ox91) ; Ox15d(Ox91) ;} ;}  ;return arr;}  ;var __ISDEBUG=false; function Debug_Todo(msg){if(!__ISDEBUG){return ;} ;throw ( new Error(msg+OxOdad3[0x1e]+Debug_Todo.caller));}  ; function Window_GetElement(Ox140,Ox11d,Ox15b){var Oxd=Ox140[OxOdad3[0xb]].getElementById(Ox11d);if(Oxd){return Oxd;} ;var Oxc2=Ox140[OxOdad3[0xb]].getElementsByName(Ox11d);if(Oxc2[OxOdad3[0x1a]]>0x0){return Oxc2.item(0x0);} ;return null;}  ; function CuteEditor_AddMainMenuItems(Ox56d){}  ; function CuteEditor_AddDropMenuItems(Ox56d,Ox574){}  ; function CuteEditor_AddTagMenuItems(Ox56d,Ox576){}  ; function CuteEditor_AddVerbMenuItems(Ox56d,Ox576){}  ; function CuteEditor_OnInitialized(editor){}  ; function CuteEditor_OnCommand(editor,Ox57a,Ox57b,Oxad){}  ; function CuteEditor_OnChange(editor){}  ; function CuteEditor_FilterCode(editor,Ox188){return Ox188;}  ; function CuteEditor_FilterHTML(editor,Ox19e){return Ox19e;}  ; function CuteEditor_FireChange(editor){ window.CuteEditor_OnChange(editor) ; CuteEditor_FireEvent(editor,OxOdad3[0x1f],null) ;}  ; function CuteEditor_FireInitialized(editor){ window.CuteEditor_OnInitialized(editor) ; CuteEditor_FireEvent(editor,OxOdad3[0x20],null) ;}  ; function CuteEditor_FireCommand(editor,Ox57a,Ox57b,Oxad){var Oxca=window.CuteEditor_OnCommand(editor,Ox57a,Ox57b,Oxad);if(Oxca==true){return true;} ;var Ox582={}; Ox582[OxOdad3[0x21]]=Ox57a ; Ox582[OxOdad3[0x22]]=Ox57b ; Ox582[OxOdad3[0x23]]=Oxad ; Ox582[OxOdad3[0x24]]=true ; CuteEditor_FireEvent(editor,OxOdad3[0x25],Ox582) ;if(Ox582[OxOdad3[0x24]]==false){return true;} ;}  ; function CuteEditor_FireEvent(editor,Ox584,Ox585){if(Ox585==null){ Ox585={} ;} ;var Ox586=editor.getAttribute(Ox584);if(Ox586){if( typeof (Ox586)==OxOdad3[0x26]){ editor[OxOdad3[0x28]]= new Function(OxOdad3[0x27],Ox586) ;} else { editor[OxOdad3[0x28]]=Ox586 ;} ; editor._fireEventFunction(Ox585) ;} ;}  ; function CuteEditor_GetEditor(element){for(var Oxdc=element;Oxdc!=null;Oxdc=Oxdc[OxOdad3[0x29]]){if(Oxdc.getAttribute(OxOdad3[0x2a])==OxOdad3[0x2b]){return Oxdc;} ;} ;return null;}  ; function CuteEditor_DropDownCommand(element,Ox8f9){var editor=CuteEditor_GetEditor(element);if(editor[OxOdad3[0x2c]]){return ;} ;if(element.getAttribute(OxOdad3[0x2d])==OxOdad3[0x2b]){var Oxce=element.GetValue();if(Oxce==OxOdad3[0x2e]){ Oxce=OxOdad3[0x19] ;} ;var Ox117=element.GetText();if(Ox117==OxOdad3[0x2e]){ Ox117=OxOdad3[0x19] ;} ; element.SetSelectedIndex(0x0) ; editor.ExecCommand(Ox8f9,false,Oxce,Ox117) ;} else {if(element[OxOdad3[0x2f]]){var Oxce=element[OxOdad3[0x2f]];if(Oxce==OxOdad3[0x2e]){ Oxce=OxOdad3[0x19] ;} ; element[OxOdad3[0x30]]=0x0 ; editor.ExecCommand(Ox8f9,false,Oxce,Ox117) ;} else { element[OxOdad3[0x30]]=0x0 ;} ;} ; editor.FocusDocument() ;}  ; function CuteEditor_ExpandTreeDropDownItem(src,Ox644){var Ox612=null;while(src!=null){if(src[OxOdad3[0x31]]==OxOdad3[0x32]){ Ox612=src ;break ;} ; src=src[OxOdad3[0x29]] ;} ;var Ox16=Ox612[OxOdad3[0x33]].item(0x0); Ox612[OxOdad3[0x36]][OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x19] ; Ox16[OxOdad3[0x37]]=OxOdad3[0x38]+Ox644+OxOdad3[0x39] ; Ox612[OxOdad3[0x3c]]= new Function(OxOdad3[0x3a]+Ox644+OxOdad3[0x3b]) ; Ox612[OxOdad3[0x3d]]= new Function(OxOdad3[0x3a]+Ox644+OxOdad3[0x3b]) ;}  ; function CuteEditor_CollapseTreeDropDownItem(src,Ox644){var Ox612=null;while(src!=null){if(src[OxOdad3[0x31]]==OxOdad3[0x32]){ Ox612=src ;break ;} ; src=src[OxOdad3[0x29]] ;} ;var Ox16=Ox612[OxOdad3[0x33]].item(0x0); Ox612[OxOdad3[0x36]][OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x3e] ; Ox16[OxOdad3[0x37]]=OxOdad3[0x38]+Ox644+OxOdad3[0x3f] ; Ox612[OxOdad3[0x3c]]= new Function(OxOdad3[0x40]+Ox644+OxOdad3[0x3b]) ; Ox612[OxOdad3[0x3d]]= new Function(OxOdad3[0x40]+Ox644+OxOdad3[0x3b]) ;}  ; function Element_Contains(element,Ox106){if(!Browser_IsOpera()){if(element[OxOdad3[0x41]]){return element.contains(Ox106);} ;} ;for(;Ox106!=null;Ox106=Ox106[OxOdad3[0x29]]){if(element==Ox106){return true;} ;} ;return false;}  ; function Element_SetUnselectable(element){ element.setAttribute(OxOdad3[0x42],OxOdad3[0x43]) ; element.setAttribute(OxOdad3[0x44],OxOdad3[0x45]) ;var arr=Element_GetAllElements(element);var len=arr[OxOdad3[0x1a]];if(!len){return ;} ;for(var i=0x0;i<len;i++){ arr[i].setAttribute(OxOdad3[0x42],OxOdad3[0x43]) ; arr[i].setAttribute(OxOdad3[0x44],OxOdad3[0x45]) ;} ;}  ; function Event_GetEvent(Ox161){ Ox161=Event_FindEvent(Ox161) ;if(Ox161==null){ Debug_Todo(OxOdad3[0x46]) ;} ;return Ox161;}  ; function Frame_GetContentWindow(Ox267){if(Ox267[OxOdad3[0x47]]){return Ox267[OxOdad3[0x47]];} ;if(Ox267[OxOdad3[0x48]]){if(Ox267[OxOdad3[0x48]][OxOdad3[0x49]]){return Ox267[OxOdad3[0x48]][OxOdad3[0x49]];} ;} ;var Ox140;if(Ox267[OxOdad3[0x14]]){ Ox140=window[OxOdad3[0x4a]][Ox267[OxOdad3[0x14]]] ;if(Ox140){return Ox140;} ;} ;var len=window[OxOdad3[0x4a]][OxOdad3[0x1a]];for(var i=0x0;i<len;i++){ Ox140=window[OxOdad3[0x4a]][i] ;if(Ox140[OxOdad3[0x4b]]==Ox267){return Ox140;} ;if(Ox140[OxOdad3[0xb]]==Ox267[OxOdad3[0x48]]){return Ox140;} ;} ; Debug_Todo(OxOdad3[0x4c]) ;}  ; function Array_IndexOf(arr,Ox163){for(var i=0x0;i<arr[OxOdad3[0x1a]];i++){if(arr[i]==Ox163){return i;} ;} ;return -0x1;}  ; function Array_Contains(arr,Ox163){return Array_IndexOf(arr,Ox163)!=-0x1;}  ; function Event_FindEvent(Ox161){if(Ox161&&Ox161[OxOdad3[0x4d]]){return Ox161;} ;if(Browser_IsGecko()){return Event_FindEvent_FindEventFromCallers();} else {if(window[OxOdad3[0x27]]){return window[OxOdad3[0x27]];} ;return Event_FindEvent_FindEventFromWindows();} ;return null;}  ; function Event_FindEvent_FindEventFromCallers(){var Ox169=Event_GetEvent[OxOdad3[0x4e]];for(var i=0x0;i<0x64;i++){if(!Ox169){break ;} ;var Ox161=Ox169[OxOdad3[0x4f]][0x0];if(Ox161&&Ox161[OxOdad3[0x4d]]){return Ox161;} ; Ox169=Ox169[OxOdad3[0x4e]] ;} ;}  ; function Event_FindEvent_FindEventFromWindows(){var arr=[];return Ox16b(window); function Ox16b(Ox140){if(Ox140==null){return null;} ;if(Ox140[OxOdad3[0x27]]){return Ox140[OxOdad3[0x27]];} ;if(Array_Contains(arr,Ox140)){return null;} ; arr.push(Ox140) ;var Ox16c=[];if(Ox140[OxOdad3[0x50]]!=Ox140){ Ox16c.push(Ox140.parent) ;} ;if(Ox140[OxOdad3[0x51]]!=Ox140[OxOdad3[0x50]]){ Ox16c.push(Ox140.top) ;} ;if(Ox140[OxOdad3[0x52]]){ Ox16c.push(Ox140.opener) ;} ;for(var i=0x0;i<Ox140[OxOdad3[0x4a]][OxOdad3[0x1a]];i++){ Ox16c.push(Ox140[OxOdad3[0x4a]][i]) ;} ;for(var i=0x0;i<Ox16c[OxOdad3[0x1a]];i++){try{var Ox161=Ox16b(Ox16c[i]);if(Ox161){return Ox161;} ;} catch(x){} ;} ;return null;}  ;}  ; function Event_GetSrcElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOdad3[0x53]]){return Ox161[OxOdad3[0x53]];} ;if(Ox161[OxOdad3[0x54]]){return Ox161[OxOdad3[0x54]];} ; Debug_Todo(OxOdad3[0x55]) ;return null;}  ; function Event_GetFromElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOdad3[0x56]]){return Ox161[OxOdad3[0x56]];} ;if(Ox161[OxOdad3[0x57]]){return Ox161[OxOdad3[0x57]];} ;return null;}  ; function Event_GetToElement(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Ox161[OxOdad3[0x58]]){return Ox161[OxOdad3[0x58]];} ;if(Ox161[OxOdad3[0x57]]){return Ox161[OxOdad3[0x57]];} ;return null;}  ; function Event_GetKeyCode(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x59]];}  ; function Event_GetClientX(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x5a]];}  ; function Event_GetClientY(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x5b]];}  ; function Event_GetOffsetX(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x5c]];}  ; function Event_GetOffsetY(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x5d]];}  ; function Event_IsLeftButton(Ox161){ Ox161=Event_GetEvent(Ox161) ;if(Browser_IsWinIE()){return Ox161[OxOdad3[0x5e]]==0x1;} ;if(Browser_IsGecko()){return Ox161[OxOdad3[0x5e]]==0x0;} ;return Ox161[OxOdad3[0x5e]]==0x0;}  ; function Event_IsCtrlKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x5f]];}  ; function Event_IsAltKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x60]];}  ; function Event_IsShiftKey(Ox161){ Ox161=Event_GetEvent(Ox161) ;return Ox161[OxOdad3[0x61]];}  ; function Event_PreventDefault(Ox161){ Ox161=Event_GetEvent(Ox161) ; Ox161[OxOdad3[0x24]]=false ;if(Ox161[OxOdad3[0x4d]]){ Ox161.preventDefault() ;} ;}  ; function Event_CancelBubble(Ox161){ Ox161=Event_GetEvent(Ox161) ; Ox161[OxOdad3[0x62]]=true ;if(Ox161[OxOdad3[0x63]]){ Ox161.stopPropagation() ;} ;return false;}  ; function Event_CancelEvent(Ox161){ Ox161=Event_GetEvent(Ox161) ; Event_PreventDefault(Ox161) ;return Event_CancelBubble(Ox161);}  ; function CuteEditor_BasicInitialize(editor){var Ox8fd=Browser_IsOpera();var Ox60c= new Function(OxOdad3[0x64]);var Ox97d= new Function(OxOdad3[0x65]);var Ox8fe= new Function(OxOdad3[0x66]);var Ox8ff=editor.GetScriptProperty(OxOdad3[0x67]);var Ox900=editor.GetScriptProperty(OxOdad3[0x68]);var Ox901=Ox8ff+OxOdad3[0x69]+Ox900+OxOdad3[0x6a];var Ox902=Ox8ff+OxOdad3[0x6b];var images=editor.getElementsByTagName(OxOdad3[0x6c]);var len=images[OxOdad3[0x1a]];for(var i=0x0;i<len;i++){var img=images[i];var Oxc1=img.getAttribute(OxOdad3[0x6d]);var Ox574=img.getAttribute(OxOdad3[0x6e]);if(!(Oxc1||Ox574)){continue ;} ;var Ox903=img.getAttribute(OxOdad3[0x6f]);if(parseInt(Ox903)>=0x0){ img[OxOdad3[0x35]][OxOdad3[0x70]]=OxOdad3[0x71] ; img[OxOdad3[0x35]][OxOdad3[0x72]]=OxOdad3[0x71] ; img[OxOdad3[0x13]]=Ox902 ; img[OxOdad3[0x35]][OxOdad3[0x73]]=OxOdad3[0x74]+Ox901+OxOdad3[0x75] ; img[OxOdad3[0x35]][OxOdad3[0x76]]=OxOdad3[0x77]+(Ox903*0x14)+OxOdad3[0x78] ; img[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x19] ;} ;if(!Oxc1&&!Ox574){if(Ox8fd){ img[OxOdad3[0x79]]=CuteEditor_OperaHandleImageLoaded ;} ;continue ;} ;if(img[OxOdad3[0x7a]]!=OxOdad3[0x7b]){ img[OxOdad3[0x7a]]=OxOdad3[0x7c] ; img[OxOdad3[0x7e]]= new Function(OxOdad3[0x7d]) ; img[OxOdad3[0x80]]= new Function(OxOdad3[0x7f]) ; img[OxOdad3[0x3d]]= new Function(OxOdad3[0x81]) ; img[OxOdad3[0x83]]= new Function(OxOdad3[0x82]) ;} ;if(!img[OxOdad3[0x84]]){ img[OxOdad3[0x84]]=Event_CancelEvent ;} ;if(!img[OxOdad3[0x85]]){ img[OxOdad3[0x85]]=Event_CancelEvent ;} ;if(Oxc1){var Ox169=Ox60c;if(img[OxOdad3[0x3c]]==null){ img[OxOdad3[0x3c]]=Ox169 ;} ;if(img[OxOdad3[0x86]]==null){ img[OxOdad3[0x86]]=Ox169 ;} ;} else {if(Ox574){if(img[OxOdad3[0x3c]]==null){ img[OxOdad3[0x3c]]=Ox8fe ;} ;} ;} ;} ;var Ox678=Window_GetElement(window,editor.GetScriptProperty(OxOdad3[0x87]),true);var Ox679=Window_GetElement(window,editor.GetScriptProperty(OxOdad3[0x88]),true);var Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOdad3[0x89]),true); Ox675[OxOdad3[0x7a]]+=OxOdad3[0x8a] ; Ox678[OxOdad3[0x7a]]+=OxOdad3[0x8b] ; Ox679[OxOdad3[0x7a]]+=OxOdad3[0x8b] ; Element_SetUnselectable(Ox678) ; Element_SetUnselectable(Ox679) ;try{ editor[OxOdad3[0x35]][OxOdad3[0x8c]]=OxOdad3[0x8d] ;} catch(x){} ;var Ox6f7=editor.GetScriptProperty(OxOdad3[0x8e]);switch(Ox6f7){case OxOdad3[0x91]: Ox678[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x19] ;break ;case OxOdad3[0x90]: Ox679[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x19] ;break ;case OxOdad3[0x8f]:break ;;;;} ;}  ; function CuteEditor_OperaHandleImageLoaded(){var img=this;if(img[OxOdad3[0x35]][OxOdad3[0x34]]){ img[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x3e] ; setTimeout(function Ox905(){ img[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0x19] ;} ,0x1) ;} ;}  ; function CuteEditor_ButtonOver(element){if(!element[OxOdad3[0x92]]){ element[OxOdad3[0x84]]=Event_CancelEvent ; element[OxOdad3[0x80]]=CuteEditor_ButtonOut ; element[OxOdad3[0x3d]]=CuteEditor_ButtonDown ; element[OxOdad3[0x83]]=CuteEditor_ButtonUp ; Element_SetUnselectable(element) ; element[OxOdad3[0x92]]=true ;} ; element[OxOdad3[0x93]]=true ; element[OxOdad3[0x7a]]=OxOdad3[0x94] ;}  ; function CuteEditor_ButtonOut(){var element=this; element[OxOdad3[0x7a]]=OxOdad3[0x7c] ; element[OxOdad3[0x93]]=false ;}  ; function CuteEditor_ButtonDown(){if(!Event_IsLeftButton()){return ;} ;var element=this; element[OxOdad3[0x7a]]=OxOdad3[0x95] ;}  ; function CuteEditor_ButtonUp(){if(!Event_IsLeftButton()){return ;} ;var element=this;if(element[OxOdad3[0x93]]){ element[OxOdad3[0x7a]]=OxOdad3[0x94] ;} else { element[OxOdad3[0x7a]]=OxOdad3[0x96] ;} ;}  ; function CuteEditor_ColorPicker_ButtonOver(element){if(!element[OxOdad3[0x92]]){ element[OxOdad3[0x84]]=Event_CancelEvent ; element[OxOdad3[0x80]]=CuteEditor_ColorPicker_ButtonOut ; element[OxOdad3[0x3d]]=CuteEditor_ColorPicker_ButtonDown ; Element_SetUnselectable(element) ; element[OxOdad3[0x92]]=true ;} ; element[OxOdad3[0x93]]=true ; element[OxOdad3[0x35]][OxOdad3[0x97]]=OxOdad3[0x98] ; element[OxOdad3[0x35]][OxOdad3[0x99]]=OxOdad3[0x9a] ; element[OxOdad3[0x35]][OxOdad3[0x9b]]=OxOdad3[0x9c] ;}  ; function CuteEditor_ColorPicker_ButtonOut(){var element=this; element[OxOdad3[0x93]]=false ; element[OxOdad3[0x35]][OxOdad3[0x97]]=OxOdad3[0x9d] ; element[OxOdad3[0x35]][OxOdad3[0x99]]=OxOdad3[0x19] ; element[OxOdad3[0x35]][OxOdad3[0x9b]]=OxOdad3[0x9c] ;}  ; function CuteEditor_ColorPicker_ButtonDown(){var element=this; element[OxOdad3[0x35]][OxOdad3[0x97]]=OxOdad3[0x9e] ; element[OxOdad3[0x35]][OxOdad3[0x99]]=OxOdad3[0x19] ; element[OxOdad3[0x35]][OxOdad3[0x9b]]=OxOdad3[0x9c] ;}  ; function CuteEditor_ButtonCommandOver(element){ element[OxOdad3[0x93]]=true ;if(element[OxOdad3[0x9f]]){ element[OxOdad3[0x7a]]=OxOdad3[0xa0] ;} else { element[OxOdad3[0x7a]]=OxOdad3[0x94] ;} ;}  ; function CuteEditor_ButtonCommandOut(element){ element[OxOdad3[0x93]]=false ;if(element[OxOdad3[0xa1]]){ element[OxOdad3[0x7a]]=OxOdad3[0xa2] ;} else {if(element[OxOdad3[0x9f]]){ element[OxOdad3[0x7a]]=OxOdad3[0xa0] ;} else {if(element[OxOdad3[0x14]]!=OxOdad3[0xa3]){ element[OxOdad3[0x7a]]=OxOdad3[0x7c] ;} ;} ;} ;}  ; function CuteEditor_ButtonCommandDown(element){if(!Event_IsLeftButton()){return ;} ; element[OxOdad3[0x7a]]=OxOdad3[0x95] ;}  ; function CuteEditor_ButtonCommandUp(element){if(!Event_IsLeftButton()){return ;} ;if(element[OxOdad3[0x9f]]){ element[OxOdad3[0x7a]]=OxOdad3[0xa0] ;return ;} ;if(element[OxOdad3[0x93]]){ element[OxOdad3[0x7a]]=OxOdad3[0x94] ;} else {if(element[OxOdad3[0xa1]]){ element[OxOdad3[0x7a]]=OxOdad3[0xa2] ;} else { element[OxOdad3[0x7a]]=OxOdad3[0x7c] ;} ;} ;}  ;var CuteEditorGlobalFunctions=[CuteEditor_GetEditor,CuteEditor_ButtonOver,CuteEditor_ButtonOut,CuteEditor_ButtonDown,CuteEditor_ButtonUp,CuteEditor_ColorPicker_ButtonOver,CuteEditor_ColorPicker_ButtonOut,CuteEditor_ColorPicker_ButtonDown,CuteEditor_ButtonCommandOver,CuteEditor_ButtonCommandOut,CuteEditor_ButtonCommandDown,CuteEditor_ButtonCommandUp,CuteEditor_DropDownCommand,CuteEditor_ExpandTreeDropDownItem,CuteEditor_CollapseTreeDropDownItem,CuteEditor_OnInitialized,CuteEditor_OnCommand,CuteEditor_OnChange,CuteEditor_AddVerbMenuItems,CuteEditor_AddTagMenuItems,CuteEditor_AddMainMenuItems,CuteEditor_AddDropMenuItems,CuteEditor_FilterCode,CuteEditor_FilterHTML]; function SetupCuteEditorGlobalFunctions(){for(var i=0x0;i<CuteEditorGlobalFunctions[OxOdad3[0x1a]];i++){var Ox169=CuteEditorGlobalFunctions[i];var name=Ox169+OxOdad3[0x19]; name=name.substr(0x8,name.indexOf(OxOdad3[0xa4])-0x8).replace(/\s/g,OxOdad3[0x19]) ;if(!window[name]){ window[name]=Ox169 ;} ;} ;}  ; SetupCuteEditorGlobalFunctions() ;var __danainfo=null;var danaurl=window[OxOdad3[0xa6]][OxOdad3[0xa5]];var danapos=danaurl.indexOf(OxOdad3[0xa7]);if(danapos!=-0x1){var pluspos1=danaurl.indexOf(OxOdad3[0xa8],danapos+0xa);var pluspos2=danaurl.indexOf(OxOdad3[0xa9],danapos+0xa);if(pluspos1!=-0x1&&pluspos1<pluspos2){ pluspos2=pluspos1 ;} ; __danainfo=danaurl.substring(danapos,pluspos2)+OxOdad3[0xa9] ;} ; function CuteEditor_GetScriptProperty(name){var Oxce=this[OxOdad3[0xaa]][name];if(Oxce&&__danainfo){if(name==OxOdad3[0x67]){return Oxce+__danainfo;} ;var Ox731=this[OxOdad3[0xaa]][OxOdad3[0x67]];if(Oxce.substr(0x0,Ox731.length)==Ox731){return Ox731+__danainfo+Oxce.substring(Ox731.length);} ;} ;return Oxce;}  ; function CuteEditor_SetScriptProperty(name,Oxce){if(Oxce==null){ this[OxOdad3[0xaa]][name]=null ;} else { this[OxOdad3[0xaa]][name]=String(Oxce) ;} ;}  ; function CuteEditorInitialize(Ox910,Ox911){var editor=Window_GetElement(window,Ox910,true); editor[OxOdad3[0xaa]]=Ox911 ; editor[OxOdad3[0xab]]=CuteEditor_GetScriptProperty ;var Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOdad3[0x89]),true);var editwin=Frame_GetContentWindow(Ox675);var editdoc=editwin[OxOdad3[0xb]];var Ox912=false;var Ox913;var Ox914=false;var Ox915=editor.GetScriptProperty(OxOdad3[0x67])+OxOdad3[0xac]; function Ox916(){if( typeof (window[OxOdad3[0xad]])==OxOdad3[0xae]){return ;} ;var Ox1a7=CreateXMLHttpRequest(); Ox1a7.open(OxOdad3[0xaf],Ox915,true,null,null) ; Ox1a7[OxOdad3[0x18]]=function (){if(Ox1a7[OxOdad3[0x17]]<0x4){return ;} ;var Ox188=Ox1a7[OxOdad3[0xb0]]; Ox1a7=null ; eval(Ox188+OxOdad3[0xb1]) ;if(Ox912){ Ox919() ;} ;}  ; Ox1a7.send(OxOdad3[0x19]) ;}  ; function Ox919(){if(Ox914){return ;} ; Ox914=true ; window.CuteEditorImplementation(editor) ;try{ editor[OxOdad3[0x35]][OxOdad3[0x8c]]=OxOdad3[0x19] ;} catch(x){} ;try{ editdoc[OxOdad3[0xb2]][OxOdad3[0x35]][OxOdad3[0x8c]]=OxOdad3[0x19] ;} catch(x){} ;var Ox91a=editor.GetScriptProperty(OxOdad3[0xb3]);if(Ox91a){ editor.Eval(Ox91a) ;} ;}  ; function Ox91b(){if(!Element_Contains(window[OxOdad3[0xb]].body,editor)){return ;} ;try{ Ox675=Window_GetElement(window,editor.GetScriptProperty(OxOdad3[0x89]),true) ; editwin=Frame_GetContentWindow(Ox675) ; editdoc=editwin[OxOdad3[0xb]] ;var Ox1f0=editdoc[OxOdad3[0xb2]];} catch(x){ setTimeout(Ox91b,0x64) ;return ;} ;if(!editdoc[OxOdad3[0xb2]]){ setTimeout(Ox91b,0x64) ;return ;} ;if(!Ox912){ Ox675[OxOdad3[0x35]][OxOdad3[0x34]]=OxOdad3[0xb4] ;if(Browser_IsOpera()){ editdoc[OxOdad3[0xb2]][OxOdad3[0xb5]]=true ;} else {if(Browser_IsGecko()){ editdoc[OxOdad3[0xb2]][OxOdad3[0x37]]=OxOdad3[0xb6] ;} ; editdoc[OxOdad3[0xb7]]=OxOdad3[0x43] ;} ; Ox912=true ; setTimeout(Ox91b,0x32) ;return ;} ;if( typeof (window[OxOdad3[0xad]])==OxOdad3[0xae]){ Ox919() ;} else {} ;}  ; CuteEditor_BasicInitialize(editor) ; Ox916() ; Ox91b() ;}  ; function CuteEditorInstallScriptCode(Ox897,Ox898){ eval(Ox897) ; window[Ox898]=eval(Ox898) ;}  ; window[OxOdad3[0xb8]]=CuteEditorInitialize ;