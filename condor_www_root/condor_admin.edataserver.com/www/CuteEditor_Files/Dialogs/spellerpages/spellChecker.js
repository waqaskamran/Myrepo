var OxO9d67=["popUpUrl","spellerpages/spellchecker.html","popUpName","spellchecker","popUpProps","replWordFlag","R","ignrWordFlag","I","replAllFlag","RA","ignrAllFlag","IA","fromReplAll","~RA","fromIgnrAll","~IA","wordFlags","currentTextIndex","currentWordIndex","spellCheckerWin","controlWin","wordWin","textArea","textInputs","_spellcheck","_getSuggestions","_setAsIgnored","_getTotalReplaced","_setWordText","_getFormInputs","openChecker","startCheck","checkTextBoxes","checkTextAreas","spellCheckAll","ignoreWord","ignoreAll","replaceWord","replaceAll","terminateSpell","undo","speller","^text$","^textarea$","^text(area)?$","length","Error: Word frame not available.","Error: \x22Not in dictionary\x22 text is missing.","Error: \x22Not in dictionary\x22 text is missing","replacementText","value","","No words changed.","No misspellings found.","One word changed."," words changed.","OnFinished","function","originalSpellings","evaluatedText","suggestions","forms","elements","type"]; function spellChecker(Ox4d){ this[OxO9d67[0x0]]=OxO9d67[0x1] ; this[OxO9d67[0x2]]=OxO9d67[0x3] ; this[OxO9d67[0x4]]=null ; this[OxO9d67[0x5]]=OxO9d67[0x6] ; this[OxO9d67[0x7]]=OxO9d67[0x8] ; this[OxO9d67[0x9]]=OxO9d67[0xa] ; this[OxO9d67[0xb]]=OxO9d67[0xc] ; this[OxO9d67[0xd]]=OxO9d67[0xe] ; this[OxO9d67[0xf]]=OxO9d67[0x10] ; this[OxO9d67[0x11]]= new Array() ; this[OxO9d67[0x12]]=0x0 ; this[OxO9d67[0x13]]=0x0 ; this[OxO9d67[0x14]]=null ; this[OxO9d67[0x15]]=null ; this[OxO9d67[0x16]]=null ; this[OxO9d67[0x17]]=Ox4d ; this[OxO9d67[0x18]]=arguments ; this[OxO9d67[0x19]]=_spellcheck ; this[OxO9d67[0x1a]]=_getSuggestions ; this[OxO9d67[0x1b]]=_setAsIgnored ; this[OxO9d67[0x1c]]=_getTotalReplaced ; this[OxO9d67[0x1d]]=_setWordText ; this[OxO9d67[0x1e]]=_getFormInputs ; this[OxO9d67[0x1f]]=openChecker ; this[OxO9d67[0x20]]=startCheck ; this[OxO9d67[0x21]]=checkTextBoxes ; this[OxO9d67[0x22]]=checkTextAreas ; this[OxO9d67[0x23]]=spellCheckAll ; this[OxO9d67[0x24]]=ignoreWord ; this[OxO9d67[0x25]]=ignoreAll ; this[OxO9d67[0x26]]=replaceWord ; this[OxO9d67[0x27]]=replaceAll ; this[OxO9d67[0x28]]=terminateSpell ; this[OxO9d67[0x29]]=undo ; window[OxO9d67[0x2a]]=this ;}  ; function checkTextBoxes(){ this[OxO9d67[0x18]]=this._getFormInputs(OxO9d67[0x2b]) ; this.openChecker() ;}  ; function checkTextAreas(){ this[OxO9d67[0x18]]=this._getFormInputs(OxO9d67[0x2c]) ; this.openChecker() ;}  ; function spellCheckAll(){ this[OxO9d67[0x18]]=this._getFormInputs(OxO9d67[0x2d]) ; this.openChecker() ;}  ; function openChecker(){ this[OxO9d67[0x14]]=window.open(this[OxO9d67[0x0]],this[OxO9d67[0x2]],this.popUpProps) ; this[OxO9d67[0x14]][OxO9d67[0x2a]]=this ;}  ; function startCheck(Ox53,controlWindowObj){ this[OxO9d67[0x16]]=Ox53 ; this[OxO9d67[0x15]]=controlWindowObj ; this[OxO9d67[0x16]].resetForm() ; this[OxO9d67[0x15]].resetForm() ; this[OxO9d67[0x12]]=0x0 ; this[OxO9d67[0x13]]=0x0 ; this[OxO9d67[0x11]]= new Array(this[OxO9d67[0x16]][OxO9d67[0x18]].length) ;for(var i=0x0;i<this[OxO9d67[0x11]][OxO9d67[0x2e]];i++){ this[OxO9d67[0x11]][i]=[] ;} ; this._spellcheck() ;return true;}  ; function ignoreWord(){var Ox55=this[OxO9d67[0x13]];var Ox56=this[OxO9d67[0x12]];if(!this[OxO9d67[0x16]]){ alert(OxO9d67[0x2f]) ;return false;} ;if(!this[OxO9d67[0x16]].getTextVal(Ox56,Ox55)){ alert(OxO9d67[0x30]) ;return false;} ;if(this._setAsIgnored(Ox56,Ox55,this.ignrWordFlag)){ this[OxO9d67[0x13]]++ ; this._spellcheck() ;} ;return true;}  ; function ignoreAll(){var Ox55=this[OxO9d67[0x13]];var Ox56=this[OxO9d67[0x12]];if(!this[OxO9d67[0x16]]){ alert(OxO9d67[0x2f]) ;return false;} ;var Ox58=this[OxO9d67[0x16]].getTextVal(Ox56,Ox55);if(!Ox58){ alert(OxO9d67[0x31]) ;return false;} ; this._setAsIgnored(Ox56,Ox55,this.ignrAllFlag) ;for(var i=Ox56;i<this[OxO9d67[0x16]][OxO9d67[0x18]][OxO9d67[0x2e]];i++){for(var Ox40=0x0;Ox40<this[OxO9d67[0x16]].totalWords(i);Ox40++){if((i==Ox56&&Ox40>Ox55)||i>Ox56){if((this[OxO9d67[0x16]].getTextVal(i,Ox40)==Ox58)&&(!this[OxO9d67[0x11]][i][Ox40])){ this._setAsIgnored(i,Ox40,this.fromIgnrAll) ;} ;} ;} ;} ; this[OxO9d67[0x13]]++ ; this._spellcheck() ;return true;}  ; function replaceWord(){var Ox55=this[OxO9d67[0x13]];var Ox56=this[OxO9d67[0x12]];if(!this[OxO9d67[0x16]]){ alert(OxO9d67[0x2f]) ;return false;} ;if(!this[OxO9d67[0x16]].getTextVal(Ox56,Ox55)){ alert(OxO9d67[0x31]) ;return false;} ;if(!this[OxO9d67[0x15]][OxO9d67[0x32]]){return false;} ;var Oxb=this[OxO9d67[0x15]][OxO9d67[0x32]];if(Oxb[OxO9d67[0x33]]){var Ox5a= new String(Oxb.value);if(this._setWordText(Ox56,Ox55,Ox5a,this.replWordFlag)){ this[OxO9d67[0x13]]++ ; this._spellcheck() ;} ;} ;return true;}  ; function replaceAll(){var Ox56=this[OxO9d67[0x12]];var Ox55=this[OxO9d67[0x13]];if(!this[OxO9d67[0x16]]){ alert(OxO9d67[0x2f]) ;return false;} ;var Ox58=this[OxO9d67[0x16]].getTextVal(Ox56,Ox55);if(!Ox58){ alert(OxO9d67[0x31]) ;return false;} ;var Oxb=this[OxO9d67[0x15]][OxO9d67[0x32]];if(!Oxb[OxO9d67[0x33]]){return false;} ;var Ox5a= new String(Oxb.value); this._setWordText(Ox56,Ox55,Ox5a,this.replAllFlag) ;for(var i=Ox56;i<this[OxO9d67[0x16]][OxO9d67[0x18]][OxO9d67[0x2e]];i++){for(var Ox40=0x0;Ox40<this[OxO9d67[0x16]].totalWords(i);Ox40++){if((i==Ox56&&Ox40>Ox55)||i>Ox56){if((this[OxO9d67[0x16]].getTextVal(i,Ox40)==Ox58)&&(!this[OxO9d67[0x11]][i][Ox40])){ this._setWordText(i,Ox40,Ox5a,this.fromReplAll) ;} ;} ;} ;} ; this[OxO9d67[0x13]]++ ; this._spellcheck() ;return true;}  ; function terminateSpell(){var msg=OxO9d67[0x34];var Ox5e=this._getTotalReplaced();if(Ox5e==0x0){if(!this[OxO9d67[0x16]]){ msg=OxO9d67[0x34] ;} else {if(this[OxO9d67[0x16]].totalMisspellings()){ msg+=OxO9d67[0x35] ;} else { msg+=OxO9d67[0x36] ;} ;} ;} else {if(Ox5e==0x1){ msg+=OxO9d67[0x37] ;} else { msg+=Ox5e+OxO9d67[0x38] ;} ;} ;if(msg){ alert(msg) ;} ;if(Ox5e>0x0){for(var i=0x0;i<this[OxO9d67[0x18]][OxO9d67[0x2e]];i++){if(this[OxO9d67[0x16]]){if(this[OxO9d67[0x16]][OxO9d67[0x18]][i]){ this[OxO9d67[0x18]][i][OxO9d67[0x33]]=this[OxO9d67[0x16]][OxO9d67[0x18]][i] ;} ;} ;} ;} ;if( typeof (this[OxO9d67[0x39]])==OxO9d67[0x3a]){ this.OnFinished(Ox5e) ;} ;return true;}  ; function undo(){var Ox56=this[OxO9d67[0x12]];var Ox55=this[OxO9d67[0x13]];if(this[OxO9d67[0x16]].totalPreviousWords(Ox56,Ox55)>0x0){ this[OxO9d67[0x16]].removeFocus(Ox56,Ox55) ;do{if(this[OxO9d67[0x13]]==0x0&&this[OxO9d67[0x12]]>0x0){ this[OxO9d67[0x12]]-- ; this[OxO9d67[0x13]]=this[OxO9d67[0x16]].totalWords(this.currentTextIndex)-0x1 ;if(this[OxO9d67[0x13]]<0x0){ this[OxO9d67[0x13]]=0x0 ;} ;} else {if(this[OxO9d67[0x13]]>0x0){ this[OxO9d67[0x13]]-- ;} ;} ;} while(this[OxO9d67[0x16]].totalWords(this.currentTextIndex)==0x0||this[OxO9d67[0x11]][this[OxO9d67[0x12]]][this[OxO9d67[0x13]]]==this[OxO9d67[0xf]]||this[OxO9d67[0x11]][this[OxO9d67[0x12]]][this[OxO9d67[0x13]]]==this[OxO9d67[0xd]]);;var Ox5f=this[OxO9d67[0x12]];var Ox60=this[OxO9d67[0x13]];var Ox61=this[OxO9d67[0x16]][OxO9d67[0x3b]][Ox5f][Ox60];if(this[OxO9d67[0x16]].totalPreviousWords(Ox5f,Ox60)==0x0){ this[OxO9d67[0x15]].disableUndo() ;} ;var i,Ox40,Ox62;switch(this[OxO9d67[0x11]][Ox5f][Ox60]){case this[OxO9d67[0x9]]:for( i=Ox5f ;i<this[OxO9d67[0x16]][OxO9d67[0x18]][OxO9d67[0x2e]];i++){for( Ox40=0x0 ;Ox40<this[OxO9d67[0x16]].totalWords(i);Ox40++){if((i==Ox5f&&Ox40>=Ox60)||i>Ox5f){ Ox62=this[OxO9d67[0x16]][OxO9d67[0x3b]][i][Ox40] ;if(Ox62==Ox61){ this._setWordText(i,Ox40,Ox62,undefined) ;} ;} ;} ;} ;break ;case this[OxO9d67[0xb]]:for( i=Ox5f ;i<this[OxO9d67[0x16]][OxO9d67[0x18]][OxO9d67[0x2e]];i++){for( Ox40=0x0 ;Ox40<this[OxO9d67[0x16]].totalWords(i);Ox40++){if((i==Ox5f&&Ox40>=Ox60)||i>Ox5f){ Ox62=this[OxO9d67[0x16]][OxO9d67[0x3b]][i][Ox40] ;if(Ox62==Ox61){ this[OxO9d67[0x11]][i][Ox40]=undefined ;} ;} ;} ;} ;break ;case this[OxO9d67[0x5]]: this._setWordText(Ox5f,Ox60,Ox61,undefined) ;break ;;;;} ; this[OxO9d67[0x11]][Ox5f][Ox60]=undefined ; this._spellcheck() ;} ;}  ; function _spellcheck(){var Ox64=this[OxO9d67[0x16]];if(this[OxO9d67[0x13]]==Ox64.totalWords(this.currentTextIndex)){ this[OxO9d67[0x12]]++ ; this[OxO9d67[0x13]]=0x0 ;if(this[OxO9d67[0x12]]<this[OxO9d67[0x16]][OxO9d67[0x18]][OxO9d67[0x2e]]){ this._spellcheck() ;return ;} else { this.terminateSpell() ;return ;} ;} ;if(this[OxO9d67[0x13]]>0x0){ this[OxO9d67[0x15]].enableUndo() ;} ;if(this[OxO9d67[0x11]][this[OxO9d67[0x12]]][this[OxO9d67[0x13]]]){ this[OxO9d67[0x13]]++ ; this._spellcheck() ;} else {var Ox65=Ox64.getTextVal(this[OxO9d67[0x12]],this.currentWordIndex);if(Ox65){ this[OxO9d67[0x15]][OxO9d67[0x3c]][OxO9d67[0x33]]=Ox65 ; Ox64.setFocus(this[OxO9d67[0x12]],this.currentWordIndex) ; this._getSuggestions(this[OxO9d67[0x12]],this.currentWordIndex) ;} ;} ;}  ; function _getSuggestions(Ox67,Ox68){ this[OxO9d67[0x15]].clearSuggestions() ;var Ox69=this[OxO9d67[0x16]][OxO9d67[0x3d]][Ox67][Ox68];if(Ox69){for(var Ox6a=0x0;Ox6a<Ox69[OxO9d67[0x2e]];Ox6a++){ this[OxO9d67[0x15]].addSuggestion(Ox69[Ox6a]) ;} ;} ; this[OxO9d67[0x15]].selectDefaultSuggestion() ;}  ; function _setAsIgnored(Ox67,Ox68,Ox6c){ this[OxO9d67[0x16]].removeFocus(Ox67,Ox68) ; this[OxO9d67[0x11]][Ox67][Ox68]=Ox6c ;return true;}  ; function _getTotalReplaced(){var Ox6e=0x0;for(var i=0x0;i<this[OxO9d67[0x11]][OxO9d67[0x2e]];i++){for(var Ox40=0x0;Ox40<this[OxO9d67[0x11]][i][OxO9d67[0x2e]];Ox40++){if((this[OxO9d67[0x11]][i][Ox40]==this[OxO9d67[0x5]])||(this[OxO9d67[0x11]][i][Ox40]==this[OxO9d67[0x9]])||(this[OxO9d67[0x11]][i][Ox40]==this[OxO9d67[0xd]])){ Ox6e++ ;} ;} ;} ;return Ox6e;}  ; function _setWordText(Ox67,Ox68,Ox70,Ox6c){ this[OxO9d67[0x16]].setText(Ox67,Ox68,Ox70) ; this[OxO9d67[0x11]][Ox67][Ox68]=Ox6c ;return true;}  ; function _getFormInputs(Ox72){var Ox73= new Array();for(var i=0x0;i<document[OxO9d67[0x3e]][OxO9d67[0x2e]];i++){for(var Ox40=0x0;Ox40<document[OxO9d67[0x3e]][i][OxO9d67[0x3f]][OxO9d67[0x2e]];Ox40++){if(document[OxO9d67[0x3e]][i][OxO9d67[0x3f]][Ox40][OxO9d67[0x40]].match(Ox72)){ Ox73[Ox73[OxO9d67[0x2e]]]=document[OxO9d67[0x3e]][i][OxO9d67[0x3f]][Ox40] ;} ;} ;} ;return Ox73;}  ;