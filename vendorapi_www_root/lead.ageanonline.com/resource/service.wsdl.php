<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0"?>';
?>

<definitions name="tss" targetNamespace="urn:tss"
	xmlns="http://schemas.xmlsoap.org/wsdl/"
	xmlns:tns="urn:tss"
	xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema">

	<types/>

	<message name="User_DataRequest">
		<part name="xml_in" type="xsd:string"/>
	</message>
	<message name="User_DataResponse">
		<part name="xml_out" type="xsd:string"/>
	</message>

	<portType name="tssPort">
		<operation name="User_Data">
			<input message="tns:User_DataRequest"/>
			<output message="tns:User_DataResponse"/>
		</operation>
	</portType>

	<binding name="tssBinding" type="tns:tssPort">
		<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
		<operation name="User_Data">
			<soap:operation soapAction="urn:tss#soap_demo#User_Data"/>
			<input>
				<soap:body use="encoded" namespace="urn:tss" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</input>
			<output>
				<soap:body use="encoded" namespace="urn:tss" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>
			</output>
		</operation>
	</binding>

	<service name="tssService">
		<documentation/>
		<port name="tssPort" binding="tns:tssBinding">
			<soap:address location="<?php echo $url ?>"/>
		</port>
	</service>

</definitions>