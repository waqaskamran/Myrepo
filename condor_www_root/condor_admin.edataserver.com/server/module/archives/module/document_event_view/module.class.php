<?php

require_once(SERVER_CODE_DIR . "module_interface.iface.php");
require_once(LIB_DIR . "condor_api.php");

class Module implements Module_Interface
{
	private $server;
	private $request;
	private $transport;
	private $action;
	private $data;
	private $form_validation;
	private $document_query;
	private $condor;

	public function __construct(Server $server, $request, $module_name)
	{
		$this->server = $server;
		$this->transport = $server->transport;
		$this->action = ($request->action) ? $request->action : 'doc_view';
		$this->request = $request;

		// set mode
		$this->mode = ($this->request->mode) ? $this->request->mode : 'default';

		// add initial module levels
		$this->transport->Add_Levels($module_name, $this->mode);
		
		$this->document_query = new Condor_Document_Query($this->server);
		
		$this->condor = Condor_API::Get_API_Object($this->server);
		
		if ($this->server->active_id['document_id'] && !$this->request->document_id)
		{
			$this->request->document_id = $this->server->active_id['document_id'];
			$this->document_id = $this->request->document_id;
		} 
		elseif($this->request->document_id)
		{
			$this->server->active_id['document_id'] = $this->request->document_id;
			$this->document_id = $this->request->document_id;
		}
		else 
		{
			$this->transport->Set_Levels('application', 'archives_search', 'default');
			$this->action = null;
			return true;
		}
		
		
	}

	public function Main()
	{
		$this->transport->action = $this->request->action;
		$this->data = new stdClass();
		$this->data->request = $this->request;
		$this->data->errors = array();

		if($this->transport->action == 'view_history' && 
			is_numeric($this->request->dispatch_id))
		{
			$this->data->recipient = $this->request->recipient;
			$this->data->sender = $this->request->sender;
			$this->data->transport = $this->request->transport;
			
			$this->data->dispatch_history = $this->document_query->Fetch_Dispatch_History(
				$this->request->dispatch_id
			);
		}
		elseif (is_numeric($this->request->document_id))
		{
			$this->data->document = $this->document_query->Fetch_Document_All(
				$this->request->document_id
			);
			if ($this->data->document->type == 'INCOMING')
			{
				$this->transport->section_manager->Disable_Section('document_resend');
			}
		}

		$this->transport->Set_Data($this->data);

		//print "<pre>" . var_export($this->data, true) . "</pre>";

		return TRUE;
	}	
}
