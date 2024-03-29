<style type="text/css">
body {
	 color: #000000;
	 background-image: url("<?php if(isset($FE_CONF->site["SECURE_SITE"]) && $FE_CONF->site["SECURE_SITE"] && $_SERVER['HTTPS']){ echo "https://".$_SERVER["SERVER_NAME"]."/imgdir"; } else { echo "http://".$_SERVER["SERVER_NAME"]."/imgdir"; } ?>/rc/media/image/reviewonly_watermark.gif");
	 background-repeat: repeat-y;
	 background-position: top center;
	 margin-top: 0px;
	 font-family: Arial, Helvetica, sans-serif;
}

div.legal-page {
	width: 600px;
	align: center;
	text-align: center;
}
div.legal-page * {
	margin: auto default;
}
div.legal-page * td {
	vertical-align: top;
}
.legal-50pctw {
	width: 50%;
	margin: auto;
}
.legal-60pctw {
	width: 60%;
	margin: auto;
}
.legal-80pctw {
	width: 80%;
	margin: auto;
}
.legal-90pctw {
	width: 90%;
	margin: auto;
}
.legal-100pctw {
	width: 100%;
	margin: auto;
}
div.legal-checkbox {
	width: 18px;
	height: 18px;
	border: 4px solid #000000;
	float: left;
	margin-right: 8px;
}
#wf-legal-checkbox-wrap {
	clear: both;
}
div.legal-checkbox-group {
	position: relative;
	display: inline;
	float: left;
	margin: 0px 10px;
	width: 46%;
}
div.legal-checkbox-group ul {
	margin: 0px;
	padding: 0px;
}

div.legal-checkbox-group ul li {
	list-style: none;
	margin: 0px;
	padding: 0px;
	clear: both;
	text-align: left;
	height: 2.5em;
}

#wf-legal-cancel {
	padding: 0px;
	margin: auto 10px;
}
#wf-legal-masthead {
	border: 5px solid #cccccc;
	padding: 5px;
}
#wf-legal-printbar {
	color: #ffffff;
	font-weight: bold;
	background-color: #000000;
	text-align: center;
	margin-bottom: 1em;
}
.legal-boxed, .legal-boxed td {
	border: 1px solid #000000;
}

#wf-legal-cancelauth, #wf-legal-cancelauth td {
	border-width: 0px !important;
}

#wf-legal-maininfo * {
	text-align: center;
}

#cxl-sig, #cxl-sig td {
    border: 0px !important;
}

form {
	margin: 0px;
}
.legal-underline {
	border-bottom: 1px solid #000000;
}
.bigbold {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 28px;
	line-height: 14px;
	font-weight: bold;
}
	
.bigboldu {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
	line-height: 18px;
	font-weight: bold;
	decoration: underline;
}
	
.norm {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	line-height: 14px;
}
	
.norm2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 11px;
}
	
.small {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	line-height: 9px;
}
	
.huge {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 80px;
	font-weight: bold;
}
	
.subhead {
	 font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	line-height: 16px;
	color: #000000;
}
	
.med {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	line-height: 10px;
}
	
br.break {
	page-break-before: always;
}
	
br.breakhere {
	page-break-before: always;
}

.sh-align-left {
	text-align: left !important;
    padding-left: 2px !important;
}
.sh-align-right {
	text-align: right !important;
    padding-right: 2px !important;
}
.sh-align-center {
	text-align: center !important;
    padding: auto 2px !important;
}

.sh-bold {
    font-weight: bold;
}

</style>

<?php include_once ("application_content.php"); ?>

