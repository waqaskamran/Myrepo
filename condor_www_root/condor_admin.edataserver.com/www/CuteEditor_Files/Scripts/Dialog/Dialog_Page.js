var OxOd820=["Table1","Table2","inp_title","inp_doctype","inp_description","inp_keywords","PageLanguage","HTMLEncoding","bgcolor","bgcolor_Preview","fontcolor","fontcolor_Preview","Backgroundimage","btnbrowse","TopMargin","BottomMargin","LeftMargin","RightMargin","MarginWidth","MarginHeight","btnok","btncc","editor","window","document","body","head","title","innerHTML","value","DOCTYPE","meta","length","name","keywords","content","description","httpEquiv","content-type","content-language","background","color","style","backgroundColor","bgColor","topMargin","bottomMargin","leftMargin","rightMargin","marginWidth","marginHeight","onclick","","ValidNumber","Please enter a valid color number.","text","childNodes","parentNode","http-equiv","Content-Type","Content-Language","\x3CMETA ","=\x22","\x22 CONTENT=\x22","\x22\x3E","META"];var Table1=Window_GetElement(window,OxOd820[0x0],true);var Table2=Window_GetElement(window,OxOd820[0x1],true);var inp_title=Window_GetElement(window,OxOd820[0x2],true);var inp_doctype=Window_GetElement(window,OxOd820[0x3],true);var inp_description=Window_GetElement(window,OxOd820[0x4],true);var inp_keywords=Window_GetElement(window,OxOd820[0x5],true);var PageLanguage=Window_GetElement(window,OxOd820[0x6],true);var HTMLEncoding=Window_GetElement(window,OxOd820[0x7],true);var bgcolor=Window_GetElement(window,OxOd820[0x8],true);var bgcolor_Preview=Window_GetElement(window,OxOd820[0x9],true);var fontcolor=Window_GetElement(window,OxOd820[0xa],true);var fontcolor_Preview=Window_GetElement(window,OxOd820[0xb],true);var Backgroundimage=Window_GetElement(window,OxOd820[0xc],true);var btnbrowse=Window_GetElement(window,OxOd820[0xd],true);var TopMargin=Window_GetElement(window,OxOd820[0xe],true);var BottomMargin=Window_GetElement(window,OxOd820[0xf],true);var LeftMargin=Window_GetElement(window,OxOd820[0x10],true);var RightMargin=Window_GetElement(window,OxOd820[0x11],true);var MarginWidth=Window_GetElement(window,OxOd820[0x12],true);var MarginHeight=Window_GetElement(window,OxOd820[0x13],true);var btnok=Window_GetElement(window,OxOd820[0x14],true);var btncc=Window_GetElement(window,OxOd820[0x15],true);var obj=Window_GetDialogArguments(window);var editor=obj[OxOd820[0x16]];var editwin=obj[OxOd820[0x17]];var editdoc=obj[OxOd820[0x18]];var body=editdoc[OxOd820[0x19]];var head=obj[OxOd820[0x1a]];var title=head.getElementsByTagName(OxOd820[0x1b])[0x0];if(title){ inp_title[OxOd820[0x1d]]=title[OxOd820[0x1c]] ;} ; inp_doctype[OxOd820[0x1d]]=obj[OxOd820[0x1e]] ;var metas=head.getElementsByTagName(OxOd820[0x1f]);for(var m=0x0;m<metas[OxOd820[0x20]];m++){if(metas[m][OxOd820[0x21]].toLowerCase()==OxOd820[0x22]){ inp_keywords[OxOd820[0x1d]]=metas[m][OxOd820[0x23]] ;} ;if(metas[m][OxOd820[0x21]].toLowerCase()==OxOd820[0x24]){ inp_description[OxOd820[0x1d]]=metas[m][OxOd820[0x23]] ;} ;if(metas[m][OxOd820[0x25]].toLowerCase()==OxOd820[0x26]){ HTMLEncoding[OxOd820[0x1d]]=metas[m][OxOd820[0x23]] ;} ;if(metas[m][OxOd820[0x25]].toLowerCase()==OxOd820[0x27]){ PageLanguage[OxOd820[0x1d]]=metas[m][OxOd820[0x23]] ;} ;} ;if(editdoc[OxOd820[0x19]][OxOd820[0x28]]){ Backgroundimage[OxOd820[0x1d]]=editdoc[OxOd820[0x19]][OxOd820[0x28]] ;} ;if(editdoc[OxOd820[0x19]][OxOd820[0x2a]][OxOd820[0x29]]){ fontcolor[OxOd820[0x1d]]=editdoc[OxOd820[0x19]][OxOd820[0x2a]][OxOd820[0x29]] ; fontcolor[OxOd820[0x2a]][OxOd820[0x2b]]=fontcolor[OxOd820[0x1d]] ; fontcolor_Preview[OxOd820[0x2a]][OxOd820[0x2b]]=fontcolor[OxOd820[0x1d]] ;} ;var body_bgcolor=editdoc[OxOd820[0x19]][OxOd820[0x2a]][OxOd820[0x2b]]||editdoc[OxOd820[0x19]][OxOd820[0x2c]];if(body_bgcolor){ bgcolor[OxOd820[0x1d]]=body_bgcolor ; bgcolor[OxOd820[0x2a]][OxOd820[0x2b]]=body_bgcolor ; bgcolor_Preview[OxOd820[0x2a]][OxOd820[0x2b]]=body_bgcolor ;} ;if(Browser_IsWinIE()){if(body[OxOd820[0x2d]]){ TopMargin[OxOd820[0x1d]]=body[OxOd820[0x2d]] ;} ;if(body[OxOd820[0x2e]]){ BottomMargin[OxOd820[0x1d]]=body[OxOd820[0x2e]] ;} ;if(body[OxOd820[0x2f]]){ LeftMargin[OxOd820[0x1d]]=body[OxOd820[0x2f]] ;} ;if(body[OxOd820[0x30]]){ RightMargin[OxOd820[0x1d]]=body[OxOd820[0x30]] ;} ;if(body[OxOd820[0x31]]){ MarginWidth[OxOd820[0x1d]]=body[OxOd820[0x31]] ;} ;if(body[OxOd820[0x32]]){ MarginHeight[OxOd820[0x1d]]=body[OxOd820[0x32]] ;} ;} else {if(body.getAttribute(OxOd820[0x2d])){ TopMargin[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x2d]) ;} ;if(body.getAttribute(OxOd820[0x2e])){ BottomMargin[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x2e]) ;} ;if(body.getAttribute(OxOd820[0x2f])){ LeftMargin[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x2f]) ;} ;if(body.getAttribute(OxOd820[0x30])){ RightMargin[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x30]) ;} ;if(body.getAttribute(OxOd820[0x12])){ MarginWidth[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x31]) ;} ;if(body.getAttribute(OxOd820[0x32])){ MarginHeight[OxOd820[0x1d]]=body.getAttribute(OxOd820[0x32]) ;} ;} ; btnok[OxOd820[0x33]]=function btnok_onclick(){try{if(Browser_IsWinIE()){ body[OxOd820[0x2d]]=TopMargin[OxOd820[0x1d]] ; body[OxOd820[0x2e]]=BottomMargin[OxOd820[0x1d]] ; body[OxOd820[0x2f]]=LeftMargin[OxOd820[0x1d]] ; body[OxOd820[0x30]]=RightMargin[OxOd820[0x1d]] ;if(MarginWidth[OxOd820[0x1d]]){ body[OxOd820[0x31]]=MarginWidth[OxOd820[0x1d]] ;} ;if(MarginHeight[OxOd820[0x1d]]){ body[OxOd820[0x32]]=MarginHeight[OxOd820[0x1d]] ;} ;} else { body.setAttribute(OxOd820[0x2d],TopMargin.value) ; body.setAttribute(OxOd820[0x2e],BottomMargin.value) ; body.setAttribute(OxOd820[0x2f],LeftMargin.value) ; body.setAttribute(OxOd820[0x30],RightMargin.value) ; body.setAttribute(OxOd820[0x31],MarginWidth.value) ; body.setAttribute(OxOd820[0x32],MarginHeight.value) ;if(body.getAttribute(OxOd820[0x2d])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x2d]) ;} ;if(body.getAttribute(OxOd820[0x2e])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x2e]) ;} ;if(body.getAttribute(OxOd820[0x2f])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x2f]) ;} ;if(body.getAttribute(OxOd820[0x30])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x30]) ;} ;if(body.getAttribute(OxOd820[0x31])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x31]) ;} ;if(body.getAttribute(OxOd820[0x32])==OxOd820[0x34]){ body.removeAttribute(OxOd820[0x32]) ;} ;} ;} catch(er){ alert(CE_GetStr(OxOd820[0x35])) ;return ;} ;try{ editdoc[OxOd820[0x19]][OxOd820[0x2a]][OxOd820[0x2b]]=bgcolor[OxOd820[0x1d]] ; editdoc[OxOd820[0x19]][OxOd820[0x2a]][OxOd820[0x29]]=fontcolor[OxOd820[0x1d]] ;if(Backgroundimage[OxOd820[0x1d]]){ editdoc[OxOd820[0x19]][OxOd820[0x28]]=Backgroundimage[OxOd820[0x1d]] ;} else { body.removeAttribute(OxOd820[0x28]) ;} ;} catch(er){ alert(OxOd820[0x36]) ;return ;} ;if(!title){ title=document.createElement(OxOd820[0x1b]) ; head.appendChild(title) ;} ;if(Browser_IsWinIE()){ title[OxOd820[0x37]]=inp_title[OxOd820[0x1d]] ;} else {var Ox356=document.createTextNode(inp_title.value);try{ title.removeChild(title[OxOd820[0x38]].item(0x0)) ;} catch(x){} ; title.appendChild(Ox356) ;} ;for(var m=0x0;m<metas[OxOd820[0x20]];m++){var Ox25=metas[m];if(Ox25){if(Ox25[OxOd820[0x21]].toLowerCase()==OxOd820[0x22]||Ox25[OxOd820[0x21]].toLowerCase()==OxOd820[0x24]||Ox25[OxOd820[0x21]].toLowerCase()==OxOd820[0x26]||Ox25[OxOd820[0x21]].toLowerCase()==OxOd820[0x27]){ Ox25[OxOd820[0x39]].removeChild(Ox25) ; Ox25=null ;} ;} ;} ;try{if(inp_keywords[OxOd820[0x1d]]){ Ox357(OxOd820[0x21],OxOd820[0x22],inp_keywords.value) ;} ;if(inp_description[OxOd820[0x1d]]){ Ox357(OxOd820[0x21],OxOd820[0x24],inp_description.value) ;} ;if(HTMLEncoding[OxOd820[0x1d]]){ Ox357(OxOd820[0x3a],OxOd820[0x3b],HTMLEncoding.value) ;} ;if(PageLanguage[OxOd820[0x1d]]){ Ox357(OxOd820[0x3a],OxOd820[0x3c],PageLanguage.value) ;} ;} catch(x){} ; function Ox357(Ox358,name,Ox1b0){var Ox359;if(Browser_IsWinIE()){ Ox359=editdoc.createElement(OxOd820[0x3d]+Ox358+OxOd820[0x3e]+name+OxOd820[0x3f]+Ox1b0+OxOd820[0x40]) ;} else {var metas=head.getElementsByTagName(OxOd820[0x1f]);for(var m=0x0;m<metas[OxOd820[0x20]];m++){if(metas[m][OxOd820[0x21]].toLowerCase()==name.toLowerCase()){ metas[m][OxOd820[0x39]].removeChild(metas[m]) ;} ;} ;var Ox359=editdoc.createElement(OxOd820[0x41]); Ox359.setAttribute(Ox358,name) ; Ox359.setAttribute(OxOd820[0x23],Ox1b0) ;} ; head.appendChild(Ox359) ;}  ; Window_SetDialogReturnValue(window,inp_doctype[OxOd820[0x1d]]+OxOd820[0x34]) ; Window_CloseDialog(window) ;}  ; btnbrowse[OxOd820[0x33]]=function btnbrowse_onclick(){ function Ox27c(Oxca){if(Oxca){ Backgroundimage[OxOd820[0x1d]]=Oxca ;} ;}  ; editor.SetNextDialogWindow(window) ;if(Browser_IsSafari()){ editor.ShowSelectImageDialog(Ox27c,Backgroundimage.value,Backgroundimage) ;} else { editor.ShowSelectImageDialog(Ox27c,Backgroundimage.value) ;} ;}  ; btncc[OxOd820[0x33]]=function btncc_onclick(){ Window_CloseDialog(window) ;}  ; fontcolor[OxOd820[0x33]]=fontcolor_Preview[OxOd820[0x33]]=function fontcolor_onclick(){ SelectColor(fontcolor,fontcolor_Preview) ;}  ; bgcolor[OxOd820[0x33]]=bgcolor_Preview[OxOd820[0x33]]=function bgcolor_onclick(){ SelectColor(bgcolor,bgcolor_Preview) ;}  ;