var OxO9c6b=["divpreview","idSource","Width","Height","TargetUrl","chk_Transparency","chk_AllowFullScreen","value","innerHTML","","$5","\x26","checked","wmode=\x22transparent\x22","allowfullscreen=\x22true\x22","\x3Cembed src=\x22","\x22 width=\x22","\x22 height=\x22","\x22 "," "," type=\x22application/x-shockwave-flash\x22 pluginspage=\x22http://www.macromedia.com/go/getflashplayer\x22 \x3E\x3C/embed\x3E\x0A","\x3Cobject xcodebase=","\x22http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab\x22"," height=\x22","\x22 classid=","\x22clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\x22 \x3E"," \x3Cparam name=\x22Movie\x22 value=\x22","\x22 /\x3E","\x3Cparam name=\x22wmode\x22 value=\x22transparent\x22/\x3E","\x3Cparam name=\x22allowFullScreen\x22 value=\x22true\x22/\x3E","\x3C/object\x3E"];var divpreview=Window_GetElement(window,OxO9c6b[0x0],true);var idSource=Window_GetElement(window,OxO9c6b[0x1],true);var Width=Window_GetElement(window,OxO9c6b[0x2],true);var Height=Window_GetElement(window,OxO9c6b[0x3],true);var TargetUrl=Window_GetElement(window,OxO9c6b[0x4],true);var chk_Transparency=Window_GetElement(window,OxO9c6b[0x5],true);var chk_AllowFullScreen=Window_GetElement(window,OxO9c6b[0x6],true);var editor=Window_GetDialogArguments(window); function do_preview(){var Ox48=GetEmbed();if(Ox48){if(idSource[OxO9c6b[0x7]]!=Ox48&&idSource[OxO9c6b[0x7]]!=null){ idSource[OxO9c6b[0x7]]=Ox48 ;} ; divpreview[OxO9c6b[0x8]]=Ox48 ;} ;}  ; function do_insert(){var Ox48=GetEmbed();if(Ox48){ editor.PasteHTML(Ox48) ;} ; Window_CloseDialog(window) ;}  ; function do_Close(){ Window_CloseDialog(window) ;}  ; function GetEmbed(){var Ox54b=OxO9c6b[0x9];if(idSource[OxO9c6b[0x7]]!=OxO9c6b[0x9]&&idSource[OxO9c6b[0x7]]!=null){ Ox54b=idSource[OxO9c6b[0x7]] ;var Ox54c=/(<object[^\>]*>[\s|\S]*?)(<embed[^\>]*?)(\ssrc=\s*)\s*("|')(.+?)\4([^>]*)(.*<\/embed>)[\s|\S]*?<\/object>/gi;if(Ox54b.match(Ox54c)){ Ox54b=Ox54b.replace(Ox54c,OxO9c6b[0xa]) ;} ;if(Ox54b.indexOf(OxO9c6b[0xb])!=-0x1){ TargetUrl[OxO9c6b[0x7]]=Ox54b.substring(0x0,Ox54b.indexOf(OxO9c6b[0xb])) ;} ;} else {return ;} ;var Ox54d=OxO9c6b[0x9];var Ox285,Ox259,Ox2d9,Ox2da; Ox285=Width[OxO9c6b[0x7]]+OxO9c6b[0x9] ; Ox259=Height[OxO9c6b[0x7]]+OxO9c6b[0x9] ; Ox2d9=chk_Transparency[OxO9c6b[0x7]] ;if(Ox54b==OxO9c6b[0x9]){ divpreview[OxO9c6b[0x8]]=OxO9c6b[0x9] ;return ;} ;var Ox2dd,Ox54e; Ox54e=OxO9c6b[0x9] ; Ox2dd=chk_Transparency[OxO9c6b[0xc]]?OxO9c6b[0xd]:OxO9c6b[0x9] ; Ox54e=chk_AllowFullScreen[OxO9c6b[0xc]]?OxO9c6b[0xe]:OxO9c6b[0x9] ;var Ox2e3=OxO9c6b[0xf]+Ox54b+OxO9c6b[0x10]+Ox285+OxO9c6b[0x11]+Ox259+OxO9c6b[0x12]+Ox54e+OxO9c6b[0x13]+Ox2dd+OxO9c6b[0x14];var Ox2e4=OxO9c6b[0x15]+OxO9c6b[0x16]+OxO9c6b[0x17]+Ox259+OxO9c6b[0x10]+Ox285+OxO9c6b[0x18]+OxO9c6b[0x19]+OxO9c6b[0x1a]+Ox54b+OxO9c6b[0x1b];if(chk_Transparency[OxO9c6b[0xc]]){ Ox2e4=Ox2e4+OxO9c6b[0x1c] ;} ;if(chk_AllowFullScreen[OxO9c6b[0xc]]){ Ox2e4=Ox2e4+OxO9c6b[0x1d] ;} ; Ox2e4=Ox2e4+Ox2e3+OxO9c6b[0x1e] ;return Ox2e4;}  ;