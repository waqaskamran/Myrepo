<?php

require_once(CLIENT_CODE_DIR . "display_module.iface.php");
require_once(LIB_DIR . "form.class.php");
require_once(COMMON_LIB_DIR . "dropdown_dates.1.php");
require_once(CUSTOMER_LIB . "list_available_reports.php");

/**
 * @package Reporting
 * @category Display
 */
abstract class Report_Parent implements Display_Module
{
	/**#@+
	 * How to total columns
	 */
	public static $TOTAL_AS_SUM           = 1;
	public static $TOTAL_AS_COUNT         = 2;
	public static $TOTAL_AS_AVERAGE 	  = 3;
	/**#@-*/

	/**#@+
	 * Format (existance) of date dropdowns
	 */
	public static $DATE_DROPDOWN_NONE     = 1;
	public static $DATE_DROPDOWN_SPECIFIC = 2;
	public static $DATE_DROPDOWN_RANGE    = 3;
	public static $AGE_RANGE    = 4;
	/**#@-*/

	/**#@+
	 * Column formats
	 */
	const FORMAT_DATE = 'date';
	const FORMAT_TIME = 'time';
	const FORMAT_CENTERED_TIME = 'time_centered';
	const FORMAT_DATETIME = 'datetime';
	const FORMAT_NUMBER = 'number';
	const FORMAT_ABS = 'abs';
	const FORMAT_DECIMAL = 'decimal';
	const FORMAT_CURRENCY = 'currency';
	const FORMAT_CURRENCY_NODECIMAL = 'currency_nodecimal';
	const FORMAT_ID = 'id_number';
	const FORMAT_TEXT = 'text';
	const FORMAT_SSN = 'ssn';
	const FORMAT_PERCENT = 'percent';
	/**#@-*/

	/**
	 * Data from Server side
	 * @var Transport
	 * @access protected
	 */
	protected $transport;

	/**
	 * Inofmration used to build report headers (date/company dropdown menus)
	 * @var    array
	 * @access protected
	*/
	protected $prompt_reference_data;

	/**
	 * Information needed to create Agents list
	 * @var    array
	 * @access protected
	*/
	protected $prompt_reference_agents;

	/**
	 * More information used to build report headers
	 * @var    array
	 * @access protected
	 */
	protected $search_criteria;

	/**
	 * Message for the user
	 * @var    string
	 * @access protected
	 */
	protected $search_message;

	/**
	 * Built up report data
	 * @var    object
	 * @access protected
	 */
	protected $data;

	/**
	 * Raw report data
	 * @var    array
	 * @access protected
	 */
	protected $search_results;

	/**
	 * Column titles used in output
	 * @var    array
	 * @access protected
	 */
	protected $column_names;
	
	/**
	 * Column width used in output
	 * @var    array
	 * @access protected
	 */
	protected $column_width;	

	/**
	 * Column formats, possible values are: date, time, datetime, number,
	 * decimal, currency, currency_nodecimal, text, and ssn.
	 *
	 * The default format for all fields is text.
	 * @var    array
	 * @access protected
	 */
	protected $column_format;

	/**
	 * Which column totals are wanted
	 * @var    array
	 * @access protected
	 */
	protected $totals;

	/**
	 * Title of the report (top of display and download reports)
	 * @var    string
	 * @access protected
	 */
	protected $report_title;

	/**
	 * Number of companies being shown
	 * @var    integer
	 * @access protected
	 */
	protected $num_companies;

	/**
	 * Number of columns in the report
	 * @var    integer
	 * @access protected
	 */
	protected $num_columns;

	/**
	 * Name of the downloaded file (date will be attached)
	 * @var    string
	 * @access protected
	 */
	protected $download_file_name;
	
	/**
	 * Choose to run the report in AJAX mode
	 * @var    string
	 * @access protected
	 */
	protected $ajax_reporting;	

	/**
	 * Type of date selection dropdowns
	 * @var    integer
	 * @access protected
	 */
	protected $date_dropdown;

	/**
	 * Whether or not to use a queue dropdown
	 * @var integer
	 * @access protected
	 */
	protected $queue_dropdown;

	/**
	 * Which company user is logged into
	 * @var    string
	 * @access protected
	 */
	protected $auth_company_name;

	/**
	 * Which columns should be sortable
	 * @var    array
	 * @access protected
	 */
	protected $sort_columns;

	/**
	 * Which columns' data should link and to where
	 * @var    array
	 * @access protected
	 */
	protected $link_columns;

	/**
	 * Should the loan status type selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $loan_status;

	/**
	 * Should the loan type selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */

	protected $loan_type;
	/**
	 * List of available loan types and ids
	 * @var    array
	 * @access protected
	 */
	protected $loan_type_list;

	/**
	 * Should the loan type selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $payment_arrange_type;

	/**
	 * Should the react type selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $react_type;

	/**
	 * Should the chargeback type selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $chargeback_report;

	/**
	 * Should the nsf type selection boxss be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $nsf_type;

	/**
	 * Should the agent selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
	protected $agent_list;
    protected $agent_list_include_unassigned;

	/**
	 * Should the company selection box be displayed?
	 * @var    boolean
	 * @access protected
	 */
        protected $company_list;

	/**
	 * Should the company selection box be displayed with All?
	 * @var    boolean
	 * @access protected
	 */
	protected $company_list_no_all;

	/**
	 * Should the company selection box auto-submit a form?
	 * Fill with the form name to submit
	 * Empty string will not auto-submit
	 *
	 * @var    string
	 * @access protected
	 */
	protected $company_auto_submit;

	/**
	 * Conditions for totals
	 * @var    array
	 * @access protected
	 */
	protected $totals_conditions;

	/**
	 * Used by the linking code to store the current data row for any replacements
	 * @var    string
	 * @access protected
	 */
	protected $parse_data_row;

	/**
	 * Used by Display_Application to make sure we need to send display data
	 *   Sometimes we don't like for a download call.
	 * @var    boolean
	 * @access public
	 */
	public $send_display_data = true;

	/**
	 * Used by Get_Data_HTML, this determines whether or not to use the
	 * nowrap option in the table data tags for the headers.
	 * @var boolean
	 * @access protected
	 */
	protected $wrap_header;

	/**
	 * Used by Get_Data_HTML, this determines whether or not to use the
	 * nowrap option in the table data tags for the data.
	 * @var boolean
	 * @access protected
	 */
	protected $wrap_data;

	protected $is_4_ssn; //mantis:4416

	/**
	 * Used by Get_Data_HTML, this sets the height of the report's body.
	 * @var int
	 * @access protected
	 */
	protected $report_table_height;

	/**
	 * The type of product being used in the reports (card, loan, etc)
	 *
	 * @var String
	 */
	protected $product_type;

        /**
         * Defines if end calendar date has a limit
         *
         * @var Bool
         */
        protected $max_end_date;
        /**
         * Defines number of days forward into the future a date can be selected
         *
         * @var int  
         */
        protected $max_days_forward;

	/**
	 * Check the specific report classes to see what the variables are set to.
	 * @todo Move all the parameter defaults into the parameters.
	 * @param Transport $transport data from the server side
	 * @param string    $module_name not used
	 * @access public
	 */
	public function __construct(ECash_Transport $transport, $module_name)
	{
		if (defined("SCRIPT_TIME_LIMIT_SECONDS"))
		{
			set_time_limit(SCRIPT_TIME_LIMIT_SECONDS);
		}

		$this->product_type = ECash::getConfig()->PRODUCT_TYPE?ECash::getConfig()->PRODUCT_TYPE:'Loan';

		// Basic setup for children that dont use these
		if( empty($this->report_title) )
			$this->report_title = "";
		if( empty($this->column_names) || ! is_array($this->column_names) )
			$this->column_names = array();
		if( empty($this->column_width) || ! is_array($this->column_width) )
			$this->column_width = array();			
		if( empty($this->sort_columns) || ! is_array($this->sort_columns) )
			$this->sort_columns = array();
		if( empty($this->link_columns) || ! is_array($this->link_columns) )
			$this->link_columns = array();
		if( empty($this->totals) || ! is_array($this->totals) )
			$this->totals = array( 'company' => array(), 'grand' => array() );
		if( empty($this->totals['company']) || ! is_array($this->totals['company']) )
			$this->totals['company'] = array();
		if( empty($this->totals['grand']) || ! is_array($this->totals['grand']) )
			$this->totals['grand'] = array();
		if( ! isset($this->totals_conditions) )
			$this->totals_conditions = null;
		if( empty($this->date_dropdown) || gettype($this->date_dropdown) != gettype(self::$DATE_DROPDOWN_NONE) )
			$this->date_dropdown = self::$DATE_DROPDOWN_NONE;
		if( !isset($this->loan_type) || ! is_bool($this->loan_type) )
			$this->loan_type = false;
		if( !isset($this->payment_arrange_type) || ! is_bool($this->payment_arrange_type) )
			$this->payment_arrange_type = false;
        if( !isset($this->react_type) || ! is_bool($this->react_type) )
            $this->react_type = false;
        if( !isset($this->chargeback_report) || ! is_bool($this->chargeback_report) )
            $this->chargeback_report = false;
        if( !isset($this->nsf_type) || ! is_bool($this->nsf_type) )
            $this->nsf_type = false;
		if( !isset($this->agent_list) || ! is_bool($this->agent_list) )
			$this->agent_list = FALSE;
		if( !isset($this->ajax_reporting) || ! is_bool($this->ajax_reporting) )
			$this->ajax_reporting = FALSE;			
        if( !isset($this->agent_list_include_unassigned) || ! is_bool($this->agent_list_include_unassigned) )
            $this->agent_list_include_unassigned = TRUE;
		if( !isset($this->company_list) || ! is_bool($this->company_list) )
			$this->company_list = TRUE;
		if( !isset($this->company_list) || ! is_bool($this->company_list) )
			$this->company_list_no_all = false;
		if( empty($this->company_auto_submit) || ! is_string($this->company_auto_submit) )
			$this->company_auto_submit = "";
		if( ! isset($this->download_file_name) || ! is_string($this->download_file_name) )
			$this->download_file_name = null;
		if(! isset($this->wrap_data))
			$this->wrap_data = true;
		if(! isset($this->wrap_header))
			$this->wrap_header = true;
		if(empty($this->max_end_date))
			$this->max_end_date = false;
		if(empty($this->max_days_forward))
			$this->max_days_forward = 0;

		// Basic data setup
		// All this comes from the transport class
		$this->transport               = ECash::getTransport();
		$this->data                    = new stdClass();
		$temp                          = ECash::getTransport()->Get_Data();
		
		$this->report_title            = ( isset($this->report_title) ? $this->report_title : "Report" );
		$this->search_results          = ( isset($temp->search_results) && count($temp->search_results) > 0 ? $temp->search_results : null );
		$this->search_criteria         = ( isset($temp->search_criteria)         ? $temp->search_criteria         : null );
		$this->prompt_reference_data   = ( isset($temp->prompt_reference_data)   ? $temp->prompt_reference_data   : null );
		$this->prompt_reference_agents = ( isset($temp->prompt_reference_agents) ? $temp->prompt_reference_agents : null );
		$this->search_message          = ( isset($temp->search_message)          ? $temp->search_message          : null );
		$this->auth_company_name       = ( isset($temp->auth_company_name)       ? $temp->auth_company_name       : null );
		$this->download_file_name      = ( isset($this->download_file_name)      ? $this->download_file_name      :
						 preg_replace("/\s/", "_", $this->report_title) . date('Ymd') . ".xls" );
		$this->loan_type_list          = ( isset($temp->loan_type_list)) ? $temp->loan_type_list : array('name_short' => 'all', 'name_short' => 'standard', 'name_short' => 'fcp');

		//throw new Exception("Trace Test");
		
		// Verify there is data
		if( isset($this->search_results) && is_array($this->search_results))
		{
			// Verify there are column names, formatting, & totals conditions for all columns
			//   If not, don't print those columns
			foreach( $this->column_names as $data_col_name => $not_used )
			{
				// Ensure the conditions are all set and have returns and ;
				if( ! isset($this->totals_conditions[$data_col_name]) )
				{
					$this->totals_conditions[$data_col_name] = "return true;";
				}
				else
				{
					$this->totals_conditions[$data_col_name] = "return " . $this->totals_conditions[$data_col_name] . ";";
				}

				if( empty($this->totals['company'][$data_col_name]) )
				{
					if( in_array($data_col_name, $this->totals['company'], true) )
					{
						$this->totals['company'][$data_col_name] = self::$TOTAL_AS_SUM;
						unset($this->totals['company'][array_search($data_col_name, $this->totals)]);
					}
				}
				if( empty($this->totals['grand'][$data_col_name]) )
				{
					if( in_array($data_col_name, $this->totals['grand'], true) )
					{
						$this->totals['grand'][$data_col_name] = self::$TOTAL_AS_SUM;
						unset($this->totals['grand'][array_search($data_col_name, $this->totals)]);
					}
				}
			} // end foreach company

			// Find out how many columns there are
			$this->num_columns   = count($this->column_names);
			/* Mantis:1508#2 */
			$this->num_companies = count($this->search_results) - (isset($this->search_results["summary"]) ? 1 : 0);
		} // end data validation

		if( in_array('rows', $this->totals['company'], true) )
		{
			$this->totals['company']['rows'] = self::$TOTAL_AS_COUNT;
			unset($this->totals['company'][array_search('rows', $this->totals)]);
		}

		if( in_array('rows', $this->totals['grand'], true) )
		{
			$this->totals['grand']['rows'] = self::$TOTAL_AS_COUNT;
			unset($this->totals['grand'][array_search('rows', $this->totals)]);
		}

		if( ! is_array($this->totals_conditions) )
		{
			$this->totals_conditions = array();
		}

		//mantis:4416
		if (in_array("ssn_last_four_digits", $temp->read_only_fields))
			$this->is_4_ssn = true;
		else
			$this->is_4_ssn = false;
	}

	public function Set_Mode($mode)
	{
		$this->mode = $mode;
	}

	public function Set_View($view)
	{
		$this->view = $view;
	}

	public function Include_Template()
	{
		return true;
	}

	public function Get_Header()
	{
		$scripts  = "<link rel=\"stylesheet\" href=\"js/calendar/calendar-dp.css\">\n";
        include_once(WWW_DIR . "include_js.php");
		$scripts .= include_js();
		return $scripts;
	}

	public function Get_Body_Tags()
	{
		return null;
	}

	public function Get_Hotkeys()
	{
		//skip hotkeys for those who don't want them
		if(ECash::getConfig()->USE_HOTKEYS === FALSE)
			return '';
        include_once(WWW_DIR . "include_js.php");
		$scripts = include_js(Array('reporting_hotkeys'));
		return $scripts;

	}

	public function Get_Menu_HTML()
	{
		$html = "";
		return $html;
	}

	/**
	 * @access protected
	 */
	protected function Replace($matches)
	{
		$return_value = NULL;

		if(isset($this->data->{$matches[1]}))
		{
			$return_value = $this->data->{$matches[1]};
		}

		return $return_value;
	}

	public function __get($var)
	{
		$internal_name = str_replace('get_', '', $var);
		return ($this->$internal_name);
	}

	protected function Get_Column_Headers( $html = true, $grand_totals = null )
	{
		$column_headers = "";
		$wrap_header = $this->wrap_header ? '' : 'nowrap';

		// For company headers (with sort links)
		if( $html === true && ! isset($grand_totals) )
		{
			// Column names
			$column_headers .= "    <tr>\n";
			foreach( $this->column_names as $data_col_name => $column_name )
			{
				//print_r($this)
				// make the column name a sort link if wanted
				if( in_array( $data_col_name, $this->sort_columns ) &! $this->ajax_reporting)
				{
					$column_headers .= "     <th $wrap_header class=\"report_head\"><a href=\"?module=reporting&mode=".$_REQUEST['mode']."&sort=" . urlencode($data_col_name) . "\">$column_name</a></th>\n";
				}
				elseif ($this->ajax_reporting && !in_array($data_col_name,array_keys($this->totals['company'])))
				{
					$column_headers .= "     <th $wrap_header class=\"report_head\"></th>\n";
				}
				else
				{
					$column_headers .= "     <th $wrap_header class=\"report_head\">$column_name</th>\n";
				}
			}
			$column_headers .= "    </tr>\n";
		}
		else if( $html === true ) // For grand totals (no sort links)
		{
			// Column names again for eash reference
			$column_headers .= "    <tr>\n";
			foreach( $this->column_names as $data_col_name => $column_name )
			{
				// Only print the column headers for columns showing a grand total
				if( isset($grand_totals[$data_col_name]) )
				{
					$column_headers .= "     <th class=\"report_head\">$column_name</th>\n";
				}
				else
				{
					$column_headers .= "     <th></th>\n";
				}
			}
			$column_headers .= "    </tr>\n";
		}
		else // For downloading (tab seperated)
		{
			$column_headers .= "";

			if (isset($grand_totals)) {
				foreach( $this->column_names as $data_name => $column_name )
				{
					if( !empty($this->totals['grand'][$data_name]) )
					{
						$column_headers .= $column_name . "\t";
					}
					else
					{
						$column_headers .= "\t";
					}
				}
			} else {
				foreach( $this->column_names as $data_name => $column_name ) {
					$column_headers .= $column_name . "\t";
				}
			}

			$column_headers = substr( $column_headers, 0, -1 ) . "\n";
		}

		return $column_headers;
	}

	/**
	 * Gets the html for all the report options (date dropdowns, loan type, company dropdown, and buttons)
	 *
	 * @param stdClass the subtitution object
	 */
	protected function Get_Form_Options_HTML(stdClass &$substitutions)
	{

		$substitutions->report_mode = $_REQUEST["mode"];
		$substitutions->start_date_title  = "
		<script type=\"text/javascript\">
		function selectHand(cal, date) 
		{
			cal.sel.value = date;
			var arrdate = cal.sel.value.split('/');
			var el = document.getElementById(cal.frmTarget + 'month');
			el.value = arrdate[0];
			var el = document.getElementById(cal.frmTarget + 'day');
			el.value = arrdate[1];
			var el = document.getElementById(cal.frmTarget + 'year');
			el.value = arrdate[2];
			cal.callCloseHandler();
		}

		function ReportCalendar(target, x, y, has_max_end, days_forward_max)
		{
			has_max_end = typeof(has_max_end) != 'undefined' ? has_max_end : false;
			days_forward_max = typeof(days_forward_max) != 'undefined' ? days_forward_max : 0;
			var el = document.getElementById(target + 'display');
			if (calendar != null)
			{
				calendar.onSelected = selectHand;
				calendar.hide();
				calendar.parseDate(el.value);
			}
			else
			{
				var cal = new Calendar(true, serverdate, selectHand, closeHandler);
				calendar = cal;
				cal.setRange(1900, 2070);
				calendar.create();
				calendar.parseDate(el.value);
	
			}
			if(has_max_end)
			{
				var enddate = new Date(serverdate);
				enddate.setDate(enddate.getDate() + days_forward_max)		
				cal.setDisabledHandler(function (thisdate) {
				thisdate.setHours(0);
				thisdate.setMinutes(0);
				thisdate.setSeconds(0);
				thisdate.setMilliseconds(0);
//				not after end
				if (enddate != null && thisdate > enddate) return true;
//				 Never before funding date
			});
			}
			calendar.frmTarget = target;
			calendar.sel = el;
			//calendar.pt_dropdown = pt_dropdown;

			// Don't show *at* the element, b/c the position might be jacked.
			// Show at the cursor location
			calendar.showAt(x, y);

			// Need this to hide the calendar
			Calendar.addEvent(document, 'mousedown', checkCalendar);

			return false;
		}

		</script>
		";

		switch ($this->date_dropdown)
		{
			case self::$DATE_DROPDOWN_SPECIFIC:
				$extra_attribute = 'onClick="ReportCalendar(\'specific_date_\', event.clientX, event.clientY)"';
				$substitutions->start_date_title .= '<span>Date :</span>';
				$substitutions->start_date       = '<span>' . $this->Date_Calander( "specific_date_", "specific", $extra_attribute )
					. ' (<a style="text-decoration: underline;" href="#" ' . $extra_attribute . '>select</a>)</span>';
				$substitutions->end_date_title   = "";
				$substitutions->end_date         = "";
				break;

			case self::$DATE_DROPDOWN_RANGE:
				$extra_attribute = 'onClick="ReportCalendar(\'start_date_\', event.clientX, event.clientY)"';
				$substitutions->start_date_title .= '<span>Start Date :</span>';
				$substitutions->start_date       = '<span style="white-space: nowrap;">' . $this->Date_Calander( "start_date_", "start", $extra_attribute )
					. ' (<a style="text-decoration: underline;" href="#" ' . $extra_attribute . '>select</a>)</span>';

				if($this->max_end_date)
				{
					$max_end_date = 'true';
				}
				else
				{
					$max_end_date = 'false';
				}
				$extra_attribute = 'onClick="ReportCalendar(\'end_date_\', event.clientX, event.clientY, ' . $max_end_date . ',' . $this->max_days_forward . ')"';
				$substitutions->end_date_title   = '<span>End Date :</span>';
				$substitutions->end_date         = '<span style="white-space: nowrap;">' . $this->Date_Calander( "end_date_", "end", $extra_attribute )
					. ' (<a style="text-decoration: underline;" href="#" ' . $extra_attribute . '>select</a>)</span>';
				break;
			case self::$AGE_RANGE:
			
				if(isset($this->search_criteria['min_days']))
					$min_days = $this->search_criteria['min_days'];
				else
					$min_days = 0;
				if(isset($this->search_criteria['max_days']))
					$max_days = $this->search_criteria['max_days'];
				else
					$max_days =0;
					
				$substitutions->start_date_title .= '<span>Min. Days :</span>';
				$substitutions->start_date       = '<span style="white-space: nowrap;"><input type=text id=min_days name=min_days value=' . $min_days . ' size=3 maxlength=3 onblur = "return strip_all_but(this,keybNumeric,((window.event)?window.event:event));" onkeypress="return editKeyBoard(this,keybNumeric,((window.event)?window.event:event));"></span>';

				$substitutions->end_date_title   = '<span>Max. Days :</span>';
				$substitutions->end_date         = '<span style="white-space: nowrap;"><input type=text id=max_days name=max_days value=' . $max_days . ' size=3 maxlength=3 onblur = "return strip_all_but(this,keybNumeric,((window.event)?window.event:event));" onkeypress="return editKeyBoard(this,keybNumeric,((window.event)?window.event:event));"></span>';
				break;
			case self::$DATE_DROPDOWN_NONE:
			default:
				$substitutions->start_date_title = "";
				$substitutions->end_date_title   = "";
				$substitutions->start_date       = "";
				$substitutions->end_date         = "";
				break;
		}
		
		if( $this->loan_type === true )
		{
			//if product type is not being displayed, make sure that the construct method is being called... or else!
			$substitutions->loan_type_select_list  = '<span>'. $this->product_type .' Type : </span><span><select name="loan_type" size="1" style="width:auto;">';
			$selected = ($this->search_criteria['loan_type'] === 'all') ? 'selected' : ''; 
			
			$substitutions->loan_type_select_list .= "<option value=\"all\" $selected>All</option>";

			
			if(is_array($this->loan_type_list))
			{
				foreach($this->loan_type_list as $loan_type)
				{
					$selected = ($this->search_criteria['loan_type'] === $loan_type['name_short']) ? 'selected' : ''; 
				
					$substitutions->loan_type_select_list .= "<option value=\"{$loan_type['name_short']}\" $selected>{$loan_type['name']}</option>";
				}
			}
			
			$substitutions->loan_type_select_list .= '</select></span>';
		}

		if( $this->payment_arrange_type === true )
		{
			$substitutions->achtype_select_list  = '<span>Date Search : </span><span><select name="payment_arrange_type" size="1" style="width:100px;"></span>';
			switch( $this->search_criteria['payment_arrange_type'] )
			{
				case 'date_created':
					$substitutions->achtype_select_list .= '<option value="date_created" selected>Created Date</option>';
					$substitutions->achtype_select_list .= '<option value="date_effective">Payment Date</option>';
					break;
				case 'date_effective':
					$substitutions->achtype_select_list .= '<option value="date_created">Created Date</option>';
					$substitutions->achtype_select_list .= '<option value="date_effective" selected>Payment Date</option>';
					break;
				default:
					$substitutions->achtype_select_list .= '<option value="date_created">Created Date</option>';
					$substitutions->achtype_select_list .= '<option value="date_effective">Payment Date</option>';
					break;
			}
			$substitutions->achtype_select_list .= '</select>';
		}

		if( $this->react_type === true)
		{
			$substitutions->react_type_select_list  = '<span>Show: </span><span><select name="react_type" size="1"></span>';
			switch( $this->search_criteria['react_type'] )
			{
				case 'yes':
					$substitutions->react_type_select_list .= '<option value="all">All Loans</option>';
					$substitutions->react_type_select_list .= '<option value="yes" selected>Only Reacts</option>';
					$substitutions->react_type_select_list .= '<option value="no">Only New Loans</option>';
					break;
				case 'no':
					$substitutions->react_type_select_list .= '<option value="all">All Loans</option>';
					$substitutions->react_type_select_list .= '<option value="yes">Only Reacts</option>';
					$substitutions->react_type_select_list .= '<option value="no" selected>Only New Loans</option>';
					break;
				case 'all':
					$substitutions->react_type_select_list .= '<option value="all" selected>All Loans</option>';
					$substitutions->react_type_select_list .= '<option value="yes">Only Reacts</option>';
					$substitutions->react_type_select_list .= '<option value="no">Only New Loans</option>';
					break;
				default:
					$substitutions->react_type_select_list .= '<option value="all" selected>All Loans</option>';
					$substitutions->react_type_select_list .= '<option value="yes">Only Reacts</option>';
					$substitutions->react_type_select_list .= '<option value="no">Only New Loans</option>';
					break;
			}
			$substitutions->react_type_select_list .= '</select>';
		}

		if( $this->chargeback_report === true)
		{
			$substitutions->reptype_select_list = '<span>Show: </span><span><select name="chargeback_type" size="1" style="width:150px;"></span>';
			switch( $this->search_criteria['chargeback_type'] )
			{
				case 'all':
					$substitutions->reptype_select_list .= '<option value="all" selected>All</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback">Chargebacks</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback_reversal">Chargeback Reversals</option>';
					break;
				case 'chargeback':
					$substitutions->reptype_select_list .= '<option value="all">All</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback" selected>Chargebacks</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback_reversal">Chargeback Reversals</option>';
					break;
				case 'chargeback_reversal':
					$substitutions->reptype_select_list .= '<option value="all">All</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback">Chargebacks</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback_reversal" selected>Chargeback Reversals</option>';
					break;
				default:
					$substitutions->reptype_select_list .= '<option value="all">All</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback">Chargebacks</option>';
					$substitutions->reptype_select_list .= '<option value="chargeback_reversal">Chargeback Reversals</option>';
					break;
			}
			$substitutions->reptype_select_list .= '</select>';
		}

		if( $this->nsf_type === true)
		{
			$substitutions->achtype_select_list  = '<span>ACH Type: </span><span><select name="achtype" size="1" style="width:95px;"></span>';
			switch( $this->search_criteria['achtype'] )
			{
				case 'debit':
					$substitutions->achtype_select_list .= '<option value="debit" selected>Debit Report</option>';
					$substitutions->achtype_select_list .= '<option value="credit">Credit Report</option>';
					break;
				case 'credit':
					$substitutions->achtype_select_list .= '<option value="debit">Debit Report</option>';
					$substitutions->achtype_select_list .= '<option value="credit" selected>Credit Report</option>';
					break;
				default:
					$substitutions->achtype_select_list .= '<option value="debit">Debit Report</option>';
					$substitutions->achtype_select_list .= '<option value="credit">Credit Report</option>';
					break;
			}
			$substitutions->achtype_select_list .= '</select>';

			$substitutions->reptype_select_list  = '<span>Show: </span><span><select name="reptype" size="1" style="width:225px;"></span>';
			switch( $this->search_criteria['reptype'] )
			{
				case 'nsfper':
					$substitutions->reptype_select_list .= '<option value="nsfper" selected>Reported/Non Reported</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_newloan">Reported/Non Reported (New Loans)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_react">Reported/Non Reported (Reacts)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfperstattypek">Reported/Non Reported by Status type</option>';
					break;
				case 'nsfpercusttypek_newloan':
					$substitutions->reptype_select_list .= '<option value="nsfper">Reported/Non Reported</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_newloan" selected>Reported/Non Reported (New Loans)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_react">Reported/Non Reported (Reacts)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfperstattypek">Reported/Non Reported by Status type</option>';
					break;
				case 'nsfpercusttypek_react':
					$substitutions->reptype_select_list .= '<option value="nsfper">Reported/Non Reported</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_newloan">Reported/Non Reported (New Loans)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_react" selected>Reported/Non Reported (Reacts)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfperstattypek">Reported/Non Reported by Status type</option>';
					break;
				case 'nsfperstattypek':
					$substitutions->reptype_select_list .= '<option value="nsfper">Reported/Non Reported</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_newloan">Reported/Non Reported (New Loans)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_react">Reported/Non Reported (Reacts)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfperstattypek" selected>Reported/Non Reported by Status type</option>';
					break;
				default:
					$substitutions->reptype_select_list .= '<option value="nsfper">Reported/Non Reported</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_newloan">Reported/Non Reported (New Loans)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfpercusttypek_react">Reported/Non Reported (Reacts)</option>';
					$substitutions->reptype_select_list .= '<option value="nsfperstattypek">Reported/Non Reported by Status type</option>';
					break;
			}
			$substitutions->reptype_select_list .= '</select>';
		}

		if( TRUE === $this->agent_list )
		{
			$substitutions->agent_select_list  .= '
				<script language=javascript>
				function SelectAllList(CONTROL)
				{
					for(var i = 0;i < CONTROL.length;i++)
					{
						CONTROL.options[i].selected = true;
					}
				}

				function DeselectAllList(CONTROL)
				{
					for(var i = 0;i < CONTROL.length;i++)
					{
						CONTROL.options[i].selected = false;
					}
				}
				</script>
			';

			$substitutions->agent_select_list  .= '<table cellpadding=0 cellspacing=0><tr><td nowrap>Agent <br>(select multiple)</td><td>';
			$substitutions->agent_select_list  .= '<select id="agent_selector" name="agent_id[]" multiple size="4">';

			$selected = array();
			if(!is_array($this->search_criteria['agent_id']))
			{
				$this->search_criteria['agent_id'] = array();
			}
			foreach($this->search_criteria['agent_id'] as $id)
			{
				$selected[$id] = TRUE;
			}
			if(0 == count($selected))
			{
				$selected[0] = TRUE;
			}

			foreach($this->Get_Agent_List() as $agent_id => $agent_display_name)
			{
				$substitutions->agent_select_list .= ""
					. "<option value=\""
					. htmlentities($agent_id)
					. "\""
					. (isset($selected[$agent_id]) ? " selected=\"selected\"" : "")
					. ">"
					. htmlentities($agent_display_name)
					. "</option>"
					;
			}

			$substitutions->agent_select_list .= "</select>";
			$substitutions->agent_select_list .= '</td><td align=center>';
			$substitutions->agent_select_list .= '<input type=button name="All" value="All" onClick="SelectAllList(this.form.agent_selector)"><br>';
			$substitutions->agent_select_list .= '<input type=button name="None" value="None" OnClick="DeselectAllList(this.form.agent_selector)">';
			$substitutions->agent_select_list .= '</td></tr></table>';
		}

		if( $this->loan_status === true )
		{
			$temp = 'Loan Status : <select name="loan_status_type" size="1" style="width:90px;">';

			asort($this->search_criteria['status_ids']);
			foreach( $this->search_criteria['status_ids'] as $id => $hard_coded_long_name )
			{
				if( isset($this->search_criteria['loan_status_type']) &&
					$this->search_criteria['loan_status_type'] == $id )
					$temp .= "<option value=\"{$id}\" selected>{$hard_coded_long_name}</option>";
				else
					$temp .= "<option value=\"{$id}\">{$hard_coded_long_name}</option>";
			}

			$temp .= '</select>';

			$substitutions->loan_status_select_list = $temp;
		}

		$substitutions->company_select_list = $this->Company_Dropdown();

		if($this->report_title == "Follow Up Report")
		{
			$all     = "";
			$under   = "";
			$verify  = "";
			$collect = "";
			switch( $this->search_criteria['follow_up_queue'] )
			{
				case 'all':
					$all = "SELECTED";
					break;
				case 'underwriting':
					$under = "SELECTED";
					break;
				case 'verification':
					$verify = "SELECTED";
					break;
				case 'collections':
					$collect = "SELECTED";
					break;
			}
			$substitutions->follow_up_queue_list = <<<EOS
Queue : <select name="follow_up_queue" size="1" style="width:90px;">
<option value="all" {$all}>All</option>
<option value="underwriting" {$under}>Underwriting</option>
<option value="verification" {$verify}>Verification</option>
<option value="collections" {$collect}>Collections</option>
</select>
EOS;
		}


		if($this->report_title == 'Manual Payment Report')
		{
			$all     			= "";
			$credit   			= "";
			$moneygram  		= "";
			$check              = "";
			$moneyorder 		= "";
			$westernunion 		= "";
			$tier2 				= "";
			$debt_consolidation	= "";

			switch( $this->search_criteria['payment_type'] )
			{
				case 'all':
					$all = "SELECTED";
					break;
				case 'credit':
					$credit = "SELECTED";
					break;
				case 'moneygram':
					$moneygram = "SELECTED";
					break;
				case 'personal_check':
					$check = "SELECTED";
					break;
				case 'moneyorder':
					$moneyorder = "SELECTED";
					break;
				case 'westernunion':
					$westernunion = "SELECTED";
					break;
				case 'tier2':
					$tier2 = "SELECTED";
					break;
				case 'debt_consolidation':
					$debt_consolidation = "SELECTED";
					break;
			}
			$substitutions->payment_type_list = <<<EOS
Payment Type : <select name="payment_type" size="1" >
<option value="all" {$all}>All</option>
<option value="credit" {$credit}>Credit Card</option>
<option value="moneygram" {$moneygram}>Moneygram</option>
<option value="personal_check" {$check}>Personal Check</option>
<option value="moneyorder" {$moneyorder}>Money Order</option>
<option value="westernunion" {$westernunion}>Western Union</option>
<option value="tier2" {$tier2}>Tier 2 Recovery</option>
<option value="debt_consolidation" {$debt_consolidation}>Debt Consolidation</option>
</select>
EOS;
		}

		if($this->report_title == "Status History Report" || $this->report_title == "Status Overview Report")
		{
			$substitutions->status_type_list = $this->Get_Status_Dropdown();
		}

		if($this->queue_dropdown)
		{
			$queue_manager = ECash::getFactory()->getQueueManager();
		//	$queue_group   = $queue_manager->getQueueGroup('automated');
		//	$queues        = $queue_group->getQueues();			
			$queues		   = $queue_manager->getQueues();

			//require_once(SQL_LIB_DIR."/queues.lib.php");
			$selected = $this->search_criteria['queue_name'];

			//echo "<pre>"; print_r($queues); die();

			$list = "Queue : <select name='queue_name' size='1' style='width: 140px;'>";
			foreach($queues as $queue_index => $queue)
			{
				if(is_string($queue_index))
				{
					$model = $queue->getModel();

					$is_selected = ($selected == $model->name_short ? "selected=\"selected\"" : "");
					$queue_name = htmlentities($model->name);
					$list .= "<option value='$model->name_short' $is_selected>$queue_name</option>";
				}
			}
			$list .= "</select>";

			$substitutions->queue_name_list = $list;
		}

		if($this->report_title == "Status Group Overview Report" )
		{
			$list = "Status : <select name='status' size='1' style='width:178px;'>\n";

			$groups_status = array(	"collections" => "Collections",
					"customers" => "Customers",
					"underwriting" => "Underwriting",
					"verification" => "Verification",
					"prospects" => "Prospects",
					"inactive" => "Inactive"
					);
			ksort($groups_status);
			foreach($groups_status as $key_status => $disp_status)
			{
				$selected = "";

				if(($this->search_criteria['status'] != NULL) && ($this->search_criteria['status'] == $key_status))
				{
					$selected = "SELECTED";
				}

				$list .= "<option value='{$key_status}' $selected>$disp_status</option>\n";
			}

			$list .= "</select>\n";
			$substitutions->status_name_list = $list;
		}
		if($this->report_title == "Customer Marketing Report" )
		{
			$list = "Status : <select name='status' size='1' style='width:178px;'>\n";

			$statuses = array("all" => "All",
							  "inactive" => "Inactive",
							  "denied" => "Denied",
							  "withdrawn" => "Withdrawn",
							  "active" => "Active",
							  "open" => "All open loans"
					);
			ksort($statuses);
			foreach($statuses as $key_status => $disp_status)
			{
				$selected = "";

				if(($this->search_criteria['status'] != NULL) && ($this->search_criteria['status'] == $key_status))
				{
					$selected = "SELECTED";
				}

				$list .= "<option value='{$key_status}' $selected>$disp_status</option>\n";
			}

			$list .= "</select>\n";
			$substitutions->status_name_list = $list;
		}

		/**
		 * Nasty piece of code to add the various contact attributes in the report header
		 * for [#35246] - Justin wrote this and I ripped out the id's because I rewrote the
		 * queries and needed to compare the flag name with the attributes the user
		 * selected and it was easier than using a hard-coded ID. - Brian.
		 */
		if($this->report_title == "Customer Marketing Report" || $this->report_title == "Queue Summary Report" || $this->report_title == "Status Overview Report")
		{
			$i = 1;
			$attr_per_row = 2;
			$show_attributes = array('bad_info', 'do_not_contact', 'do_not_market', 'do_not_loan');
			$list = "<table><tr><td style=\"text-align: left;\">Filter:</td>\n";
			$row_end = FALSE;

			//die(print_r($this->search_criteria, TRUE));
			foreach($show_attributes as $attribute)
			{
				if($row_end)
				{
					$list .= '<tr><td></td>';
					$row_end = FALSE;
				}

				$checked = '';
				if(($this->search_criteria['attributes'] != NULL) && (in_array($attribute, $this->search_criteria['attributes'])))
				{
					$checked = 'checked="checked"';
				}
				$list .= '<td style="text-align: left;"><input type="checkbox" name="attributes[]" id="' . $attribute . '" value="' . $attribute .'" ' . $checked . '/><label for="' . $attribute . '">' . ucwords(str_replace('_', ' ', $attribute)) . "</label></td>\n";

				if($i % $attr_per_row == 0)
				{
					$row_end = TRUE;
					$list .= "</tr>\n";
				}
				$i++;
			}
			
			$list .= '</table>';

			$substitutions->filter_list = $list;
		}

		if ($this->report_title == "Status Group Overview Report" || $this->report_title == "Status Overview Report")
		{
			$pos_selected = "";
			$neg_selected = "";
			$zero_selected = "";

			if(($this->search_criteria['balance_type'] != NULL) && ($this->search_criteria['balance_type'] == 'positive'))
			{
				$pos_selected = "SELECTED";
			}
			else if ($this->search_criteria['balance_type'] == 'negative')
			{
				$neg_selected = "SELECTED";
			}
			else if ($this->search_criteria['balance_type'] == 'zero')
			{
				$zero_selected = "SELECTED";
			}

			$list = "Balance Type : <select name='balance_type' size='1' style='width:100px;'>\n";
			$list .= "<option value='positive' $pos_selected>Positive</option>\n";
			$list .= "<option value='negative' $neg_selected>Negative</option>\n";
			$list .= "<option value='zero' $zero_selected>Zero Balance</option>\n";
			$list .= "</select>\n";

			$substitutions->balance_type_list = $list;
		}
	}

	/**
	 * Gets the html for the data section of the report
	 * also updates running totals
	 * used only by Get_Module_HTML()
	 *
	 * @param  string name of the company
	 * @param  &array running totals
	 * @param  bool nowrap use the nowrap option in the table data tag
	 * @return string
	 * @access protected
	 */
	protected function Get_Data_HTML($company_data, &$company_totals)
        {
                $row_toggle = true;  // Used to alternate row colors
                $line       = "";

		$wrap_data   = $this->wrap_data ? '' : 'nowrap';

		for( $x = 0 ; $x < count($company_data) ; ++$x )
		{
			$td_class = ($row_toggle = ! $row_toggle) ? "align_left" : "align_left_alt";


			// 1 row of data
			$line .= "    <tr>\n";
			foreach( $this->column_names as $data_name => $column_name )
			{
				if (empty($company_totals[$data_name])) $company_totals[$data_name] = 0;
				$align = 'left';
				$data = $this->Format_Field($data_name,  isset($company_data[$x][$data_name]) ? $company_data[$x][$data_name] : null, false, true, $align);
				// the the data link to somewhere?
				if( count($this->link_columns) > 0 && isset($this->link_columns[$data_name]) && isset($company_data[$x]['mode']))
				{
					// do any replacements necessary in the link
					$this->parse_data_row = $company_data[$x];
					$href  = preg_replace_callback("/%%%(.*?)%%%/", array($this, 'Link_Parse'), $this->link_columns[$data_name]);
					$line .= "     <td $wrap_data class=\"$td_class\" style=\"text-align: $align;\"><a href=\"#\" onClick=\"parent.window.location='$href'\">" . $data . "</a></td>\n";
				}
				else
				{
					$line .= "     <td $wrap_data class=\"$td_class\" style=\"text-align: $align;\">" . $data . "</td>\n";
				}

				// If the col's data matches the criteria, total it up
				if( $this->check_eval($company_data[$x], $data_name) && isset($this->totals['company'][$data_name]) )
				{
					switch($this->totals['company'][$data_name])
					{
						case self::$TOTAL_AS_COUNT:
							$company_totals[$data_name]++;
							break;
						case self::$TOTAL_AS_SUM:
							$company_totals[$data_name] += $company_data[$x][$data_name];
							break;
						case self::$TOTAL_AS_AVERAGE;
							$company_totals[$data_name] += ($company_data[$x][$data_name]/count($company_data));
						default:
							// Dont do anything, somebody screwed up
					}
				}
			}
			$company_totals['rows']++;
			$line .= "    </tr>\n";
		}

		return $line;
	}

	/**
	 * Checks the total_conditions for specified column does necessary replacements, evals it and returns result
	 *
	 * @param  array   column's data to check
	 * @param  string  column condition to check
	 * @return boolean
	 */
	protected function check_eval($line, $column)
	{
		$condition = isset($this->totals_conditions[$column]) ? $this->totals_conditions[$column] : null;
		$data = isset($line[$column]) ? $line[$column] : null;
		$conditional = str_replace( "%%%var%%%", addslashes($data), $condition );
		return eval($conditional);
	}

	/**
	 * Gets the html for 1 company's totals
	 * and updates running totals
	 * used only by Get_Module_HTML()
	 *
	 * @param  array  name of the company (ufc, d1, etc)
	 * @param  &array running totals so far
	 * @return string
	 * @access protected
	 */
	protected function Get_Total_HTML($company_name, &$company_totals)
	{
		$line = "";

		// If column 1 has no totals, totals header will go in column 1
		//    else, put totals header on own line
		reset($this->column_names);
		$total_own_line = (! empty($this->totals['company'][$this->column_names[key($this->column_names)]]) ? true : false);

		// If the total header should be on its own line,
		//    or only the # of rows is desired
		if( $total_own_line ||
			( count($this->totals['company']) == 1 &&
			  ! empty($this->totals['company']['rows'])))
		{
			if( ! empty($this->totals['company']['rows']) )
			{
				$line .= "    <tr><th class=\"report_foot\" colspan=\"{$this->num_columns}\" nowrap>$company_name Totals ";
				$line .= ": " . $company_totals['rows'] . " row" . ($company_totals['rows']!=1?"s":"") . "</th></tr>\n";
			}
			else
			{
				$line .= "    <tr><th class=\"report_foot\" colspan=\"{$this->num_columns}\">$company_name Totals</th></tr>\n";
			}
		}

		if( (! empty($this->totals['company']['rows']) && count($this->totals['company']) > 1 ) ||
			(empty($this->totals['company']['rows']) && count($this->totals['company']) > 0 ))
		{
			$line .= "    <tr>\n";
			foreach( $this->column_names as $data_name => $column_name )
			{
				if( ! empty($this->totals['company'][$data_name]) )
				{
					$align = 'left';
					$data = $this->Format_Field($data_name,  $company_totals[$data_name], true, true, $align);
					$line .= "     <th class=\"report_foot\" style=\"text-align: $align;\">$data</th>\n";
				}
				else if( ! $total_own_line )
				{
					if( ! empty($this->totals['company']['rows']) )
					{
						$line .= "     <th class=\"report_foot\">$company_name Totals ";
						$line .= ": " . $company_totals['rows'] . " row" . ($company_totals['rows']!=1?"s":"") . "</th>\n";
					}
					else
					{
						$line .= "     <th class=\"report_foot\">$company_name Totals</th>\n";
					}

					// Don't put the total header field again
					$total_own_line = ! $total_own_line;
				}
				else
				{
					$line .= "     <th class=\"report_foot\"></th>\n";
				}
			}
			$line .= "    </tr>\n";
		}

		return $line;
	}

	/**
	 * Gets the output for 1 company's totals
	 * and updates running totals
	 * used only by Get_Module_HTML()
	 *
	 * Basically a rewritten Get_Total_HTML for use with the display_downloads
	 *
	 * @param  array  name of the company (ufc, d1, etc)
	 * @param  &array running totals so far
	 * @return string
	 * @access protected
	 */
	protected function Get_Company_Total_Line($company_name, &$company_totals)
	{
		$line = "";

		// If column 1 has no totals, totals header will go in column 1
		//    else, put totals header on own lines
		reset($this->column_names);
		$total_own_line = (! empty($this->totals['company'][$this->column_names[key($this->column_names)]]) ? true : false);

		// If the total header should be on its own line,
		//    or only the # of rows is desired
		if( $total_own_line ||
			( count($this->totals['company']) == 1 &&
			  ! empty($this->totals['company']['rows'])))
		{
			if( ! empty($this->totals['company']['rows']) )
			{
				// Odd for loop meant to tab us to the right column
				for ($num_tabs = 1; $num_tabs < $this->num_columns; $num_tabs++)
				{
					$line .= "\t";
				}
				$line .= "$company_name Totals " . $company_totals['rows'] . " row" . ($company_totals['rows']!=1?"s":"") . "\n";
			}
			else
			{
				$line .= "    <tr><th class=\"report_foot\" colspan=\"{$this->num_columns}\">$company_name Totals</th></tr>(680)\n";
			}
		}

		if( (! empty($this->totals['company']['rows']) && count($this->totals['company']) > 1 ) ||
			(empty($this->totals['company']['rows']) && count($this->totals['company']) > 0 ))
		{
			$line .= "\n";
			foreach( $this->column_names as $data_name => $column_name )
			{
				if( ! empty($this->totals['company'][$data_name]) )
				{
					$line .= $this->Format_Field($data_name, $company_totals[$data_name], true, false) . "\t";
				}
				else if( ! $total_own_line )
				{
					if( ! empty($this->totals['company']['rows']) )
					{
						$line .= "$company_name Totals : ";
						$line .= $company_totals['rows'] . " row" . ($company_totals['rows']!=1?"s":"") . "\t";
					}
					else
					{
						$has_data = false;
						// Added to filter empty total lines [richardb][#11566]
						foreach( $this->column_names as $sub_data_name => $sub_column_name )
						{
							if( ! empty($this->totals['company'][$sub_data_name]) )
							{
								$has_data = true;
							}
							
						}
						
						if($has_data) $line .= "$company_name Totals\t";				
					}

					// Don't put the total header field again
					$total_own_line = ! $total_own_line;
				}
				else
				{
					$line .= "\t";
				}
			}
			// removes the last tab if we're at the end of the loop and replaces it with a newline
			$line = substr( $line, 0, -1 ) . "\n";
			//$line .= "\n";
		}

		return $line;
	}

	/**
	 * Gets the html for the grand totals
	 * used only by Get_Module_HTML()
	 *
	 * @param  array  the grand totals for the entire report
	 * @return string
	 * @access protected
	 */
	protected function Get_Grand_Total_HTML($grand_totals)
	{
		$line = "";

		if( ! empty($this->totals['grand']['rows']) )
		{
			$line .= "    <tr><th class=\"report_foot\" colspan=\"{$this->num_columns}\" ";
			$line .= "style=\"border-top: thin solid black;\">Grand Totals ";
			$line .= ": " . $grand_totals['rows'] . " rows</th></tr>\n";
		}
		else
		{
			$line .= "    <tr><th class=\"report_foot\" colspan=\"{$this->num_columns}\" ";
			$line .= "style=\"border-top: thin solid black;\">Grand Totals</th></tr>\n";
		}

		// Column grand totals
		if( (! empty($this->totals['grand']['rows']) && count($this->totals['grand']) > 1 ) ||
			(empty($this->totals['grand']['rows']) && count($this->totals['grand']) > 0 ))
		{
			// Column names again for eash reference
			$line .= $this->Get_Column_Headers( true, $grand_totals );

			$line .= "    <tr>\n";
			foreach( $this->column_names as $data_name => $column_name )
			{
				if( ! empty($this->totals['grand'][$data_name]) )
				{
					$line .= "     <th class=\"report_foot\" style=\"text-align: right;\">" . $this->Format_Field($data_name, $grand_totals[$data_name]) . "</th>\n";
				}
				else
				{
					$line .= "     <th class=\"report_foot\"></th>\n";
				}
			}
			$line .= "    </tr>\n";
		} // end column grand totals

		return $line;
	}

	/**
	 * Gets the text for the grand totals
	 * used only by Download_Data()
	 *
	 * @param  array  the grand totals for the entire report
	 * @return string
	 * @access protected
	 */
	protected function Get_Grand_Total_Line($grand_totals)
	{
		$line = "";

		if( ! empty($this->totals['grand']['rows']) )
		{
			$line .= "Grand Totals : " . $grand_totals['rows'] . " rows\n";
		}
		else
		{
			$line .= "Grand Totals\n";
		}

		// Column grand totals
		if( (! empty($this->totals['grand']['rows']) && count($this->totals['grand']) > 1 ) ||
			(empty($this->totals['grand']['rows']) && count($this->totals['grand']) > 0 ))
		{
			// Column names again for eash reference
			$line .= $this->Get_Column_Headers( false, $grand_totals );

			// An extra tab to skip the company name field which is usually first
			foreach( $this->column_names as $data_name => $column_name )
			{
				if( ! empty($this->totals['grand'][$data_name]) )
				{
					$line .= $this->Format_Field($data_name, $grand_totals[$data_name],false,false) . "\t";
				}
				else
				{
					$line .= "\t";
				}
			}
			// removes the last tab if we're at the end of the loop and replaces it with a newline
			$line = substr( $line, 0, -1 ) . "\n";
		} // end column grand totals

		return $line;
	}

	/**
	 * Addtional info for the top of the company
	 *
	 * @param string  $company name of the company currently working
	 * @param boolean $html should html code be returned?
	 * @return string
	 * @access protected
	 */
	protected function Get_Company_Head($company, $html = true)
	{
		return "";
	}

	/**
	 * Additional info for the bottom of the company
	 *
	 * @param string  $company name of the company currently working
	 * @param boolean $html should html code be returned?
	 * @return string
	 * @access protected
	 */
	protected function Get_Company_Foot($company, $html = true)
	{
		return "";
	}

	/**
	 * Addtional info for the top of the report
	 *
	 * @param boolean $html should html code be returned?
	 * @return string
	 * @access protected
	 */
	protected function Get_Report_Head($html = true)
	{
		return "";
	}

	/**
	 * Additional info for the bottom of the report
	 *
	 * @param boolean $html should html code be returned?
	 * @return string
	 * @access protected
	 */
	protected function Get_Report_Foot($html = true)
	{
		return "";
	}


	
	public function Get_Module_HTML_Data()
	{
		$mode = ECash::getTransport()->page_array[2];


		$substitutions = new stdClass();

		$substitutions->report_title = $this->report_title;

		// Get the date dropdown & loan type html stuff
		$this->Get_Form_Options_HTML( $substitutions );

		$substitutions->search_message    = "<tr><td>&nbsp;</td></tr>";
		$substitutions->search_result_set = "<tr><td><div id=\"report_result\" class=\"reporting\"></div></td></tr>";

		while (!is_null($next_level = ECash::getTransport()->Get_Next_Level()))
		{
			if ($next_level == 'message')
			{
				$substitutions->search_message = "<tr><td class='align_left' style='color: red'>{$this->search_message}</td></tr>\n";
			}
			else if ($next_level == 'report_results' && $this->num_companies > 0)
			{
				// First turn on the download link
				$substitutions->download_link = "[ <a href=\"?module=reporting&mode=" . urlencode($mode) . "&action=download_report\" class=\"download\">Download Displayed Data to Excel</a> ]";

				// results header
				$result_body  = "<tr>\n";
				$result_body .= " <td class=\"align_left\">\n";
				$result_body .= "  <div id=\"report_result\" class=\"reporting\">\n";
				$result_body .= "   <table class=\"report\">\n";
				// addtional html to toss at the top of the report data, could be anything
				$result_body .= $this->Get_Report_Head();

				$grand_totals = array();
				foreach( $this->totals['grand'] as $which => $unused )
				{
					$grand_totals[$which] = 0;
				}

				//Use this to create unique tbody ID's
				$Company_Counter = 0;
				// for each company
				foreach( $this->search_results as $company_name => $company_data )
				{
					$Company_Counter++;
					
					if ($company_name == 'summary')
					{
						continue;
					}

					$result_body .= "<thead id=\"scrollable_thead\">\n";
					// If company_totals is set, this is the 2nd+ company
					//    Insert a blank line between the two companies
					if (isset($company_totals))
					{
						$result_body .= "    <tr><td colspan=\"{$this->num_columns}\" class=\"align_left_alt\">&nbsp;</td></tr>\n";
					}

					// Addtional data for the start of each company, could be anything
					$result_body .= $this->Get_Company_Head($company_name);

					$company_totals  = array();
					foreach ($this->column_names as $data_name => $column_name)
					{
						$company_totals[$data_name] = 0;
					}

					$company_totals['rows'] = 0;

					// company header
					$result_body .= "    <tr><td colspan=\"{$this->num_columns}\" class=\"report_head\">" . strtoupper($company_name) . "</td></tr>\n";

					// Column names
					$result_body .= $this->Get_Column_Headers();

					// Company data
					$result_body .= "   </thead>\n";
					$result_body .= "   <tbody id=\"scrollable_tbody_${Company_Counter}\" style=\"overflow:auto; overflow-y:auto; overflow-x:hidden; margin-right:20px;\">\n";
					$result_body .= $this->Get_Data_HTML($company_data, $company_totals);
					//$result_body .= "   </tbody>\n";
					//$result_body .= "   <tfoot id=\"scrollable_tfoot\">\n";

					// company totals
					if (count($this->totals['company']) > 0)
					{
						$result_body .= $this->Get_Total_HTML($company_name, $company_totals);
					}

					// Additional data for the end of each company, could be anything
					$result_body .= $this->Get_Company_Foot($company_name);

					// Add the company totals to the grand totals
					foreach ($grand_totals as $key => $value)
					{
						// Flash report (and maybe others) does something special with the totals
						if (isset($company_totals[$key]))
						{
							$grand_totals[$key] += $company_totals[$key];
						}
					}
					
					$result_body .= "	</tbody>\n";
				} // end foreach company

				// grand totals
				// dont show grand totals if only 1 company... exact same #s are in company totals above it
				if (count($this->totals['grand']) > 0 && $this->num_companies > 1)
				{
					$result_body .= $this->Get_Grand_Total_HTML($grand_totals);
				}

				// Additional data for the bottom of the report data, could be anything
				$result_body .= $this->Get_Report_Foot();

				// This is here for insolent children (that don't call parent::__construct() )
				if (!is_numeric($this->report_table_height))
				{
					$this->report_table_height = 305;
				}

				// results footer
				//$result_body .= "    </tbody>\n";
				//$result_body .= "    </tfoot>\n";
				$result_body .= "   </table>\n";
				$result_body .= "  </div>\n";
				$result_body .= "<script type=\"text/javascript\">

var scroll_div = document.getElementById('report_result');
if (scroll_div.clientWidth > 770)
{
    scroll_div.style.width = '770px';
    scroll_div.style.overflowX = 'auto';
//    scroll_div.style.overflowY = 'hidden';
}

var company_counter = 1;

while(document.getElementById('scrollable_tbody_' + company_counter))
{
	var scroll_tbody = document.getElementById('scrollable_tbody_' + company_counter);
	company_counter = company_counter + 1;
	
	if (scroll_tbody.clientHeight > {$this->report_table_height})
	{
	    scroll_tbody.style.height = '{$this->report_table_height}px';
	
	    for (var i = 0; i < scroll_tbody.rows.length; i++) 
		{
	
	        var td = scroll_tbody.rows[i].cells[scroll_tbody.rows[i].cells.length - 1];
	        td.style.paddingRight = '18px;'
	
	    }
	
	//    var scroll_tfoot = document.getElementById('scrollable_tfoot');
	//    for (var i = 0; i < scroll_tfoot.rows.length; i++) {
	
	//        var tf = scroll_tfoot.rows[i].cells[scroll_tfoot.rows[i].cells.length - 1];
	//        tf.style.paddingRight = '18px;'
	
	//    }
	
	    var scroll_thead = document.getElementById('scrollable_thead');
	    for (var i = 0; i < scroll_thead.rows.length; i++) 
		{
	
	        var th = scroll_thead.rows[i].cells[scroll_thead.rows[i].cells.length - 1];
	        th.style.paddingRight = '18px;'
	
	    }
	}
}
</script>";
				//$result_body .= "  </div>\n";
				$result_body .= " </td>\n";
				$result_body .= "</tr>\n";

				$substitutions->search_result_set = $result_body;

				if ($this->num_companies == 1)
				{
					$message = "Data for {$this->num_companies} company displayed.";
				}
				else
				{
					$message = "Data for {$this->num_companies} companies displayed.";
				}

				$substitutions->search_message = "<span style=\"color: darkblue\">$message</span>\n";
			}
			else if ($next_level == 'report_results')
			{
				$message = "No application data was found that meets the specified report criteria.";
				$substitutions->search_message = "<span style=\"color: darkblue\">$message</span>\n";
			}
		}

		return $substitutions;		
	}
	
	public function Get_Module_AJAX_Data()
	{
		$mode = ECash::getTransport()->page_array[2];
		// New Page, New Items
		unset($_SESSION['reports'][$mode]["xml_items"]);
		
		$total_rows = 0;
         // substitutions to make in the html template
		$substitutions = new stdClass();

		$substitutions->report_title = $this->report_title;

		// Get the date dropdown & loan type html stuff
		$this->Get_Form_Options_HTML( $substitutions );

		$substitutions->search_message    = "";
		$substitutions->download_link	  = "";
		$substitutions->search_result_set = "";
		$result_body_foot = null;

		$grand_totals = array();
		foreach( $this->totals['grand'] as $which => $unused )
		{
			$grand_totals[$which] = 0;
		}

		
		while( ! is_null($next_level = ECash::getTransport()->Get_Next_Level()) )
		{
			if( $next_level == 'message' )
			{
				$substitutions->search_message = "<span style='color: red'>{$this->search_message}<span>\n";
			}
			else if( $next_level == 'report_results' && $this->num_companies > 0 )
			{
				// First turn on the download link
				if ($substitutions->download_link == '')
				{
					$substitutions->download_link = "[ <a href=\"?module=reporting&mode=" . urlencode($mode) . "&action=download_report\" class=\"download\">Download Displayed Data to Excel</a> ]";				
				}

				// Export The Message
				if ($substitutions->search_message == '')
				{
					if ($this->num_companies == 1)
					{
						$message = "Data for {$this->num_companies} company displayed.";
					}
					else
					{
						$message = "Data for {$this->num_companies} companies displayed.";
					}
	
					$substitutions->search_message = "<span style=\"color: darkblue\">$message</span>\n";	
				}

				// Create Result Set
				if ($substitutions->search_result_set == '')
				{
					
					foreach ($this->column_names as $field => $field_name)
					{
						$company_totals[$field] = 0;
						$width = (isset($this->column_width[$field])) ? $this->column_width[$field] : 100;
						$json_header[] = "\n{header: \"{$field_name}\", width: {$width}}";
						$filter_options[] = "<option value=\'$field\'>$field_name</option>";
					}

					
					$result_body = "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/grid-report.css?".time()."\" />\n";
					$result_body .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/tabs.css?".time()."\" />\n";
					$result_body .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/toolbar-report.css?".time()."\" />\n";
					//$result_body .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/reports.css\" />\n";
					$result_body .= "<script src=\"js/yui/utilities/utilities.js?timer=".time()."\" ></script>\n";
					$result_body .= "<script src=\"js/lib/yui-ext.js?timer=".time()."\"></script>\n";
															
					$result_body .= "<tr>\n";
					$result_body .= " <td class=\"align_left\">\n";
					$result_body .= "  <div id=\"report_result\" class=\"reporting\">\n";

					$result_body .= "	<div id=\"result_tab_panel\">\n";
					$result_body .= "		<div id=\"result_tab\" class=\"tab-content\">\n";
					$result_body .= "			<div id=\"ajax_result\" style=\"width:780px; height:350px;background: #EEEEEE;\"></div>\n";
					
					$result_body .= "<div id='report_sum' style=\"height:350px;overflow: auto;\"><table style=\"border-spacing: 2px;\">";
					
					$footer_cols     = $this->Get_Column_Headers();
					
					foreach ($this->search_results as $company_name => $company_data)
                    {
                    	$company_totals = array();
                    	$company_totals['rows'] = 0;
                    	
                    	$total_rows += count($company_data);
						$this->Get_Data_HTML($company_data, $company_totals);

						// Show Extended company data
						$result_body_foot = $this->Get_Total_HTML(strtoupper($company_name), $company_totals);
						if ($result_body_foot != '')
						{
							$result_body .= $this->Get_Company_Head($company_name);
							$result_body .= $footer_cols;
							$result_body .= $result_body_foot;
							$result_body .= $this->Get_Company_Foot($company_name);
							
							$footer_cols = '';
						}
						
						// I wonder how long grand totals have never worked for AJAX reports
						foreach ($grand_totals as $key => $value)
						{
							if (isset($company_totals[$key]))
							{
								$grand_totals[$key] += $company_totals[$key];
							}
						}

                    }

                    if (count($this->totals['grand']) > 0 && $this->num_companies > 1)
					{
						$result_body .= $this->Get_Grand_Total_HTML($grand_totals);
					}
					
					$result_body .= "</table></div>";
					
					$result_body .= "		</div>\n";
					$result_body .= "	</div>";									

					$result_body .= "  </div>\n";
                    $result_body .= " </td>\n";
                    $result_body .= "</tr>\n";

					// YUI EXT Selection Box
					$result_body .= "<script type=\"text/javascript\">\n";
					$result_body .= "sm = new YAHOO.ext.grid.SingleSelectionModel();\n";
					$result_body .= "cm = new YAHOO.ext.grid.DefaultColumnModel([".implode(",",$json_header)."]);\n";
					$result_body .= "cm.defaultSortable = true;\n";

					$result_body .= "dm = new YAHOO.ext.grid.XMLDataModel({
					    tagName: 'ITEM',
					    totalTag: 'TOTAL_ITEMS',
					    fields: ['".implode("','",array_keys($this->column_names))."']
					});\n";

					$result_body .= "dm.createParams_original = dm.createParams;\n";
					$result_body .= "dm.createParams = function(pageNum, sortColumn, sortDir){
					    params = dm.createParams_original(pageNum, sortColumn, sortDir);
					    params['sort'] = dm.schema.fields[sortColumn];
					    if(document.getElementById('filter_field')) 
						{
							params['filter_field'] = document.getElementById('filter_field').value;
					    }
					    if(document.getElementById('filter_text')) 
						{
							params['filter_text'] = document.getElementById('filter_text').value;
					    }
					    return params;
					};\n";

					// initialize paging
					$result_body .= "dm.initPaging('/?module=reporting&mode=".ECash::getTransport()->page_array[2]."&action=ajax_report_data', 100);\n";
					$result_body .= "grid = new YAHOO.ext.grid.Grid('ajax_result', dm, cm, sm);\n";
					$result_body .= "grid.render();\n";

					// toolbar
					$result_body .= "var toolbar = grid.getView().getPageToolbar();\n";
					$result_body .= "toolbar.addSeparator();\n";
					$result_body .= "toolbar.addText('{$total_rows} total record(s)');\n";
					$result_body .= "toolbar.addSeparator();\n";
					$result_body .= "toolbar.addText('Filter:');\n";
					$result_body .= "toolbar.addText('<select name=filter_field id=filter_field>".implode("",$filter_options)."</select>');\n";
					$result_body .= "toolbar.addText('<input type=text name=filter_text id=filter_text>');\n";
					$jsbtn = "dm.loadPage(1);";
					$result_body .= "toolbar.addText('<input type=button name=button_submit id=button_submit value=Update onClick=$jsbtn>');\n";
					$jsbtn = "document.getElementById(\'filter_text\').value=\'\';dm.loadPage(1);";
					$result_body .= "toolbar.addText('<input type=button name=button_clear id=button_clear value=Reset onClick=$jsbtn>');\n";					
					// the grid is ready, load page 1 of items
					$result_body .= "dm.loadPage(1);\n";

					$result_body .= "var tabs = new YAHOO.ext.TabPanel('result_tab_panel');\n";
					$result_body .= "tab1 = tabs.addTab( 'result_tab', \"Report\" );\n";
					$result_body .= "tabs.activate('result_tab');\n";

					$result_body .= "tab2 = tabs.addTab( 'report_sum', \"Summary\" );\n";
					if($result_body_foot == '')
					{
						$result_body .= "tabs.disableTab('report_sum');\n";
					}

					$result_body .= "  </script>\n";                    

                    $substitutions->search_result_set = $result_body;
				}
			}
			else if ($next_level == 'report_results')
			{
				$message = "No application data was found that meets the specified report criteria.";
				$substitutions->search_message = "    <tr><td class=\"align_left\" style=\"color: darkblue\">$message</td></tr>\n";
			}

		}
		// Set Defaults
		if($substitutions->search_message == "")
			$substitutions->search_message = "<tr><td>&nbsp;</td></tr>";
		
		if($substitutions->search_result_set == "")
			$substitutions->search_result_set = "<tr><td><div id=\"report_result\" class=\"reporting\"></div></td></tr>";
		
		
		return $substitutions;
	}
	/**
	 * Builds and returns the html for the report
	 *
	 * @return string
	 * @access public
	 */
	public function Get_Module_HTML()
	{
		//echo "<!-- Module Name: " . ECash::getTransport()->page_array[2] . " -->\n";
		$mode = ECash::getTransport()->page_array[2];
		switch($mode)
		{
			case 'applicant_status':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_app_staus_report.html" );
				break;
			case 'manual_payment':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_manual_payment_report.html" );
				break;
			case 'external_transactions':
			case 'transaction_history':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_transaction_history_report.html" );
				break;
			case 'status_history':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_status_history_report.html" );
				break;
			case 'status_overview':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_status_overview_report.html" );
				break;
			case 'status_group_overview':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_status_group_overview_report.html" );
				break;
			case 'fraud_full_queue':
			case 'fraud_queue':
			case 'queue':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_queue_report.html" );
				break;
			case 'queue_overview':
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_queue_overview_report.html" );
				break;
			case 'process_status':
				$form = new Form ( CLIENT_MODULE_DIR . "/reporting/view/display_process_status_report.html");
				break;
			case 'daily_cash':
				$form = new Form ( CLIENT_MODULE_DIR . "/reporting/view/display_daily_cash_report.html");
				break;
			case 'collection_queue_contents':
				$form = new Form ( CLIENT_MODULE_DIR . "/reporting/view/display_collection_queue_contents_report.html");
				break;
			case 'export_application_data':
				$form = new Form ( CLIENT_MODULE_DIR . "/reporting/view/display_export_application_data_report.html");
				break;
			case 'agent_internal_recovery':
				$form = new Form ( CLIENT_MODULE_DIR . "/reporting/view/display_agent_internal_recovery_report.html");
				break;
			case 'follow_up':				
			default:				
				$form = new Form( CLIENT_MODULE_DIR . "/reporting/view/display_report.html" );
		}

		$sub = ($this->ajax_reporting) ? $this->Get_Module_AJAX_Data(): $this->Get_Module_HTML_Data();
		return $form->As_String($sub);
	}

	public function Download_Data()
	{
		// Holds output
		$dl = new ECash_DownloadableReportContainer();

		//mantis:4324
		$generic_data = ECash::getTransport()->Get_Data();

		if($generic_data->is_upper_case)
			$dl->useUpper();
		
		unset($generic_data);
		//end mantis:4324	

		$dl->add($this->report_title . " - Run Date: " . date('m/d/Y') . "\n");

		if( !empty($this->prompt_reference_agents))
		{
			$agents = $this->Get_Agent_List();
			
			if(isset($this->search_criteria['agent_id']))
			{
				foreach($this->search_criteria['agent_id'] as $agent_id)
				{
					if(isset($agents[$agent_id]))
					{
						$dl->add("For agent: ".$agents[$agent_id]."\n");
					}
				}
			}
		}

		// Is the report run for a specific date, date range, or do dates not matter?
		switch($this->date_dropdown)
		{
			case self::$DATE_DROPDOWN_RANGE:
				if (isset($this->search_criteria['start_date_MM']))
				{
					$dl->add("Date Range: " . $this->search_criteria['start_date_MM']   . '/'
										    . $this->search_criteria['start_date_DD']   . '/'
										    . $this->search_criteria['start_date_YYYY'] . " to "
										    . $this->search_criteria['end_date_MM']     . '/'
										    . $this->search_criteria['end_date_DD']     . '/'
										    . $this->search_criteria['end_date_YYYY']   . "\n");
				}
				break;
			case self::$DATE_DROPDOWN_SPECIFIC:
				if (isset($this->search_criteria['specific_date_MM']))
				{
					$dl->add("Date: " . $this->search_criteria['specific_date_MM'] . '/'
									 	. $this->search_criteria['specific_date_DD'] . '/'
									 	. $this->search_criteria['specific_date_YYYY'] . "\n");
				}
				break;
			case self::$DATE_DROPDOWN_NONE:
			default:
				// Nothing to do
				break;
		}

		$total_rows = 0;

		// An empty array for the grand totals
		$grand_totals = array();
		foreach( $this->totals['grand'] as $which => $unused )
		{
			$grand_totals[$which] = 0;
		}

		$dl->add("\n");
		$dl->add($this->Get_Column_Headers(FALSE));

		// Sort through each company's data
		foreach ($this->search_results as $company_name => $company_data)
		{
			// Short-circuit the loop if this is the "summary" data.
			if ($company_name == 'summary')
			{
				continue;
			}

			// An array of company totals which gets added to grand_totals
			$company_totals = array();
			foreach ($this->column_names as $data_name => $column_name)
			{
				$company_totals[$data_name] = 0;
			}

			// If isset($x), this is the 2nd+ company, insert a blank line to seperate the data
			if (isset($x))
			{
				$dl->add("\n");
			}

			$cols = '';
			foreach (array_keys($company_data) as $x)
			{
				foreach (array_keys($this->column_names) as $data_col_name)
				{
                    $this->totals['company'][$data_col_name] = isset($this->totals['company'][$data_col_name]) ? $this->totals['company'][$data_col_name] : null;
                    $company_data[$x][$data_col_name] = isset($company_data[$x][$data_col_name]) ? $company_data[$x][$data_col_name]: null;
					$cols .= $this->Format_Field($data_col_name, $company_data[$x][$data_col_name], false, false) . "\t";
                    switch($this->totals['company'][$data_col_name])
                    {
                        case self::$TOTAL_AS_COUNT:
                            $company_totals[$data_col_name]++;
                            break;
                        case self::$TOTAL_AS_SUM:
                            $company_totals[$data_col_name] += $company_data[$x][$data_col_name];
                            break;
                        case self::$TOTAL_AS_AVERAGE;
                            $company_totals[$data_col_name] += ($company_data[$x][$data_col_name]/count($company_data));
                        default:
                            // Dont do anything, somebody screwed up
                    }
				}

				// removes the last tab if we're at the end of the loop and replaces it with a newline
				$cols = substr($cols, 0, -1) . "\n";
			}

			$dl->add($cols);

			$total_rows += count($company_data);
			$company_totals['rows'] = count($company_data);

			// If there's more than one company, show a company totals line
			if (count($this->totals['company']) > 0)
			{
				// Was commented by JRS: [Mantis:1651]... Uncommented by [tonyc][mantis:5861]
				$dl->add($this->Get_Company_Total_Line($company_name, $company_totals) . "\n\n");
			}

			// Add the company totals to the grand totals
			foreach ($grand_totals as $key => $value)
			{
				// Flash report (and maybe others) does something special with the totals
				if (isset($company_totals[$key]))
				{
					$grand_totals[$key] += $company_totals[$key];
				}
			}
		}

		// grand totals
		// dont show grand totals if only 1 company... exact same #s are in company totals above it
		if (count($this->totals['grand']) > 0 && $this->num_companies > 1)
		{
			$dl->add($this->Get_Grand_Total_Line($grand_totals));
		}

		/* Mantis:1508#2 */
		if(isset($this->search_results['summary']))
		{
			$dl->add("\n\n"); // This ends the "Count = ..." row and one empty row

			$company_names = array_keys($this->search_results);
			// Next line commented out: Additional change from Mantis:1508
			// $company_names[] = "Grand";
			$this->search_results['summary']['Grand'] = array();
			$grand_totals =& $this->search_results['summary']['Grand'];

			foreach ($company_names as $company_name)
			{
				if ($company_name == 'summary')
				{
					continue;
				}

				$dl->add("${company_name} Totals:\tCount\tDebit\tCredit\n"); // Add header line

				foreach($this->search_results['summary'][$company_name] as $item => $data)
				{
					if('notes' == $item || 'code' == $item)
					{
						$dl->add(ucwords($item)."\n"); // Name of subsection

						foreach( $data as $special => $data2 )
						{
							if( 'Grand' != $company_name )
							{
								if( ! isset( $grand_totals[$item] ) || ! isset( $grand_totals[$item][$special] ) )
								{
									$grand_totals[$item][$special] = array(
											'count'  => 0,
											'debit'  => 0,
											'credit' => 0,
											);
								}

								$grand_totals[$item][$special]['count' ] += $data2['count' ];
								$grand_totals[$item][$special]['debit' ] += $data2['debit' ];
								$grand_totals[$item][$special]['credit'] += $data2['credit'];
							}

							$dl->add( $special
									.	"\t"
									.	$data2['count']
									.	"\t"
									.	number_format($data2['debit'],2,".",",")
									.	"\t"
									.	number_format($data2['credit'],2,".",",")
									.	"\n");
						}
					}
					else
					{
						if( 'Grand' != $company_name )
						{
							if( ! isset( $grand_totals[$item] ) )
							{
								$grand_totals[$item] = array(
										'count'  => 0,
										'debit'  => 0,
										'credit' => 0,
										);
							}

							$grand_totals[$item]['count' ] += $data['count' ];
							$grand_totals[$item]['debit' ] += $data['debit' ];
							$grand_totals[$item]['credit'] += $data['credit'];
						}

						$dl->add( $item
								.	"\t"
								.	$data['count']
								.	"\t"
								.	number_format($data['debit'],2,".",",")
								.	"\t"
								.	number_format($data['credit'],2,".",",")
								.	"\n");
					}
				}

				$dl->add("\n"); // Add one empty row beneath this company
			}
		}

		// for the html headers
		header( "Accept-Ranges: bytes\n");
		header( "Content-Length: " . $dl->getLength() . "\n");
		header( "Content-Disposition: attachment; filename={$this->download_file_name}\n");
		header( "Content-Type: application/vnd.ms-excel\n\n");

		$dl->output();
	}
	
	public function Download_XML_Data()
	{
		// Holds output
		$dl_data = "<root>\n";
		$dl_data .= "<Report_Title>$this->report_title</Report_Title>\n";
		$dl_data .= "<RunDate>" . date('m/d/Y') . "</RunDate>\n";


		if( TRUE === $this->agent_list )
		{
			$agents = $this->Get_Agent_List();
			foreach($this->search_criteria['agent_id'] as $agent_id)
			{
				if(isset($agents[$agent_id]))
				{
					$dl_data .= "<Agent>".$agents[$agent_id]."</Agent>\n";
				}
				else
				{
					$dl_data .= "<Agent>Unassigned</Agent>\n";
				}
			}
		}

		// Is the report run for a specific date, date range, or do dates not matter?
		switch($this->date_dropdown)
		{
			case self::$DATE_DROPDOWN_RANGE:
				// GF 8641: If <DateRange> is inside this conditional block, it will not validate
				// if condition is not met. 
				$dl_data .= "<DateRange>";
				if (isset($this->search_criteria['start_date_MM']))
				{
					$dl_data .= $this->search_criteria['start_date_MM']   . '/'
							  . $this->search_criteria['start_date_DD']   . '/'
							  . $this->search_criteria['start_date_YYYY'] . " to "
							  . $this->search_criteria['end_date_MM']     . '/'
							  . $this->search_criteria['end_date_DD']     . '/'
							  . $this->search_criteria['end_date_YYYY'];
				}
				$dl_data .= "</DateRange>\n";										   
				break;
			case self::$DATE_DROPDOWN_SPECIFIC:
				$dl_data .= "<Date>";

				if (isset($this->search_criteria['specific_date_MM']))
				{
					$dl_data .= $this->search_criteria['specific_date_MM'] . '/'
							  . $this->search_criteria['specific_date_DD'] . '/'
							  . $this->search_criteria['specific_date_YYYY'];
				}
				$dl_data .= "</Date>\n";					 
				break;
			case self::$DATE_DROPDOWN_NONE:
			default:
				// Nothing to do
				break;
		}

		$total_rows = 0;

		// An empty array for the grand totals
		$grand_totals = array();
		foreach( $this->totals['grand'] as $which => $unused )
		{
			$grand_totals[$which] = 0;
		}

		// Sort through each company's data
		$dl_data .= "<ITEMS>\n";
		if(!isset($_SESSION['reports'][$_REQUEST["mode"]]["xml_items"]) ||
		   (
		   	$_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_field"] != $_REQUEST["sortColumn"] ||
		   	$_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_direction"] != $_REQUEST["sortDir"]
		   )
		)
		{
			$_SESSION['reports'][$_REQUEST["mode"]]["xml_items"] = array();
			unset($_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_items"]);
			foreach( $this->search_results as $company_name => $company_data )
			{
				if($company_name == 'summary')
				{
					continue;
				}
				// An array of company totals which gets added to grand_totals
				$company_totals = array();
				foreach( $this->column_names as $data_name => $column_name )
				{
					$company_totals[$data_name] = 0;
				}
				foreach( $company_data as $x => $value )
				{
					$data_item = "\t<ITEM>\n";
					$data_item .= "\t\t<company_name>$company_name</company_name>\n";
					foreach( $this->column_names as $data_col_name => $not_used )
					{
			            if( count($this->link_columns) > 0 && isset($this->link_columns[$data_col_name]) && isset($company_data[$x]['mode']))
			            {
			               // do any replacements necessary in the link
			               $this->parse_data_row = $company_data[$x];
			               $href  = preg_replace_callback("/%%%(.*?)%%%/", array($this, 'Link_Parse'), $this->link_columns[$data_col_name]);
			               $line_item = $this->Format_Field($data_col_name, $company_data[$x][$data_col_name]);
			               $line_item = htmlentities("<a href='#' onClick=\"parent.window.location='$href'\">{$line_item}</a>");
			            }
			            else
			            {
			            	$line_item = htmlentities(stripslashes($this->Format_Field($data_col_name, isset($company_data[$x][$data_col_name]) ? $company_data[$x][$data_col_name] : null)));
			            }						
						
						$data_item .= "\t<{$data_col_name}>".$line_item."</{$data_col_name}>\n";
						$company_totals[$data_col_name] += isset($company_data[$x][$data_col_name]) ? $company_data[$x][$data_col_name] : 0;
					}
					$data_item .= "\t</ITEM>\n";
					$_SESSION['reports'][$_REQUEST["mode"]]["xml_items"][] = $data_item; 
					
				}
			}
		}
		
		// Process Filiter and Pages		
		$report_object = $_SESSION['reports'][$_REQUEST["mode"]];
		$search_method = "NORMAL";
		if(count($report_object["xml_items"]))
		{	
			$xmlitems = $report_object["xml_items"];

			// Process Filter
			if(trim($_REQUEST["filter_text"]) == "")
			{
				// Use Main List if no filter
				$search_method = "NON_FILTERED";
				$_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_items"] = null;
				$report_object = $_SESSION['reports'][$_REQUEST["mode"]];
			}
			else if(	($_REQUEST["filter_field"] != $_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_field"]) ||
						($_REQUEST["filter_text"] != $_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_text"]) ||
						($_REQUEST["sort"] != $_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_field"]) ||
						($_REQUEST["sortDir"] != $_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_direction"])) 
			{
				// Ok we found out that we want to filter or we have new filter criteria
				// so now we need to create a subset of filitered data
				$search_method = "FILTERED";
				$_REQUEST["page"] = 1;
				$_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_items"] = array();
				$xmlitems = array();
				
				for($i=0; $i<count($_SESSION['reports'][$_REQUEST["mode"]]["xml_items"]); $i++)
				{
					$line_item = $_SESSION['reports'][$_REQUEST["mode"]]["xml_items"][$i];
					$parse_items = split("\n",$line_item);					
					for($x=0; $x<count($parse_items); $x++)
					{
						// We found a macth so save it
						if(stristr($parse_items[$x],"<{$_REQUEST["filter_field"]}>"))
						{
							if(stristr($parse_items[$x],$_REQUEST["filter_text"]))
								$xmlitems[] = $line_item;
								
							break;
						}
					}
				}
				$_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_items"] = $xmlitems;
				$report_object = $_SESSION['reports'][$_REQUEST["mode"]];						
			}
			
			// If we have filitered Items use it instead of mainlist
			$xmlitems = is_array($report_object["xml_filter_items"]) 
						? $report_object["xml_filter_items"] 
						: $report_object["xml_items"];
			
			$startpage = ($_REQUEST["page"] - 1) * $_REQUEST["pageSize"];
			
			$dl_data .= implode("\n",array_slice($xmlitems,$startpage,$_REQUEST["pageSize"]));
		}
		
		$_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_field"] = $_REQUEST["filter_field"];
		$_SESSION['reports'][$_REQUEST["mode"]]["xml_filter_text"] = $_REQUEST["filter_text"];
		$_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_field"] =  $_REQUEST["sort"];		
		$_SESSION['reports'][$_REQUEST["mode"]]["xml_sort_direction"] = $_REQUEST["sortDir"];
		
		$dl_data .= "</ITEMS>\n";
		$dl_data .= "<FILTER_FIELD>{$_REQUEST["filter_field"]}</FILTER_FIELD>\n";
		$dl_data .= "<FILTER_TEXT>{$_REQUEST["filter_text"]}</FILTER_TEXT>\n";
		$dl_data .= "<SORT_COL>{$_REQUEST["sort"]}</SORT_COL>\n";
		$dl_data .= "<SORT_DIR>{$_REQUEST["sortDir"]}</SORT_DIR>\n";
		$dl_data .= "<SEARCH_METHOD>{$search_method}</SEARCH_METHOD>\n";
		$dl_data .= "<TOTAL_ITEMS>".count($xmlitems)."</TOTAL_ITEMS>\n";
		$dl_data .= "</root>";
		header( "Content-Type: text/xml\n\n");

		echo $dl_data;
	}

	protected function Date_Dropdown( $select_prefix, $option_prefix )
	{
		// Generalize me (fix me)
		// NO $this->data->search_criteria
		//    move search_criteria into a different transport var
		$date_drop = new Dropdown_Dates();

		$date_drop->Set_Prefix($select_prefix);
		$date_day_selx   = (isset($this->search_criteria[$option_prefix . '_date_DD'])  ) ? ($this->search_criteria[$option_prefix . '_date_DD']  ) : date('d');
		$date_month_selx = (isset($this->search_criteria[$option_prefix . '_date_MM'])  ) ? ($this->search_criteria[$option_prefix . '_date_MM']  ) : date('m');
		$date_year_selx  = (isset($this->search_criteria[$option_prefix . '_date_YYYY'])) ? ($this->search_criteria[$option_prefix . '_date_YYYY']) : date('Y');

		$date_drop->Set_Day($date_day_selx);
		$date_drop->Set_Month($date_month_selx);
		$date_drop->Set_Year($date_year_selx);

		return $date_drop->Fetch_Drop_All();
	}

	protected function Date_Calander( $select_prefix, $option_prefix, $extra_attributes='' )
	{
		$date_day_selx   = (isset($this->search_criteria[$option_prefix . '_date_DD'])  ) ? ($this->search_criteria[$option_prefix . '_date_DD']  ) : date('d');
		$date_month_selx = (isset($this->search_criteria[$option_prefix . '_date_MM'])  ) ? ($this->search_criteria[$option_prefix . '_date_MM']  ) : date('m');
		$date_year_selx  = (isset($this->search_criteria[$option_prefix . '_date_YYYY'])) ? ($this->search_criteria[$option_prefix . '_date_YYYY']) : date('Y');

		$date_code = "<input type=text {$extra_attributes} id='{$select_prefix}display' name='{$select_prefix}display' value='{$date_month_selx}/{$date_day_selx}/{$date_year_selx}' class=\"disabled\" style=\"cursor: pointer; font-family: monospace;\" size=\"10\" readonly=\"readonly\">\n";
		$date_code .= "<input type=hidden id='{$select_prefix}month' name='{$select_prefix}month' value='{$date_month_selx}'>\n";
		$date_code .= "<input type=hidden id='{$select_prefix}day' name='{$select_prefix}day' value='{$date_day_selx}'>\n";
		$date_code .= "<input type=hidden id='{$select_prefix}year' name='{$select_prefix}year' value='{$date_year_selx}'>\n";
		return $date_code;
	}

	// Build the company dropdown menu
	protected function Company_Dropdown()
	{
		if(!$this->company_list)
		{
			return("");
		}

		// Generalize me (fix me)
		// NO $this->data->prompt_reference_data
		//    move prompt_reference_data into different transport var
		// NO $this->data->search_criteria
		//    move search_criteria into a different transport var

		// GForge #17422: Make this larger. Done. [benb]
		$company_drop = "Company : <select name=\"company_id\" size=\"1\" style=\"width:75px\"";
		if ($this->company_auto_submit)
		{
			$company_drop .= " onChange=\"document.getElementById('{$this->company_auto_submit}').submit();\"";
		}
		$company_drop .= ">";


		foreach( $this->prompt_reference_data as $co_obj )
		{
			// Some reports cant use All companies
			if((!$this->company_list_no_all && $co_obj->{'company_name'} == 'All') || $co_obj->{'company_name'} != 'All')
			{
				if( in_array(trim($co_obj->company_name), $this->auth_company_name) ||
				(trim($co_obj->company_name) == 'All' && count($this->auth_company_name) > 1) )
				{
					if( isset($this->search_criteria['company_id']) )
					{
						$selx = ( trim($co_obj->company_id  ) == trim($this->search_criteria['company_id']) ? 'SELECTED' : '' );
					}
					else
					{
						$selx = (trim($co_obj->company_name) == trim(ECash::getTransport()->company) ? 'SELECTED' : '' );
					}

					if( $co_obj->{'company_name'} != 'All' )
					$co_obj->{'company_name'} = strtoupper($co_obj->{'company_name'});
				}

				$company_drop .= "<option value={$co_obj->{'company_id'}} $selx>{$co_obj->{'company_name'}}</option>";
			}
		}

		$company_drop .= "</select>";

		return($company_drop);
	}

	public function __set($var, $value)
	{
		$exit_success = false;

		switch( $var )
		{
			case 'set_column_names':
				if( is_array($value) && count($value) == $this->num_columns )
				{
					$this->column_names = $value;
					$exit_success = true;
				}
				break;
			case 'set_sort_columns':
				if( is_array($value) && count($value) <= $this->num_columns )
				{
					$this->sort_columns = $value;
					$exit_success = true;
				}
				break;
			case 'set_link_columns':
				if( is_array($value) )
				{
					$this->link_column = $value;
					$exit_success = true;
				}
				break;
			case 'set_totals':
				if( is_array($value) && count($value) <= 2 && count($value) > 0 )
				{
					if( ! isset($value['company']) )
					{
						$value['company'] = array();
					}

					if( ! isset($value['grand']) )
					{
						$value['grand'] = array();
					}

					if( count($value['company']) <= $this->num_columns + 1 ||
					count($value['grand'])   <= $this->num_columns + 1 )
					{
						$this->totals = $value;
						$exit_success = true;
					}
				}
				break;
			case 'set_date_dropdown':
				if( is_bool($value) )
				{
					$this->date_dropdown = $value;
					$exit_success = true;
				}
				break;
			case 'set_download_file_name':
				if( is_string($value) && strlen($value) > 0 )
				{
					$this->download_file_name = $value;
					$exit_success = true;
				}
				break;
			case 'set_report_title':
				if( is_string($value) && strlen($value) > 0 )
				{
					$this->report_title = $value;
					$exit_success = true;
				}
				break;
		}

		return $exit_success;
	}


	// performs replacements in links passed in $this->link_columns
	//    replaces all %data_col_name% with appropriate data from that row
	protected function Link_Parse( $matches )
	{
		$var = $matches[1];

		if( isset($this->parse_data_row[$var]) )
			return $this->parse_data_row[$var];
		else
			return '';
	}

	/**
	 * Used to format field data for printing
	 *
	 * @param string  $name column name to format
	 * @param string  $data field data
	 * @param boolean $totals formatting totals or data?
	 * @param boolean $html format for html?
	 * @return string
	 * @access protected
	 */
	protected function Format_Field( $name, $data, $totals = false, $html = true, &$align = null)
	{
		if(is_null($data)) return $data;

		if (isset($this->column_format[$name])) 
		{
			$format = $this->column_format[$name];
		} 
		else 
		{
			$format = 'text';
		}

		switch ($format) 
		{
			case self::FORMAT_DATE :
				$align = 'right';
				return date('m/d/y',strtotime($data));
				break;
			case self::FORMAT_TIME :
				$align = 'right';
				return date('g:i a',strtotime($data));
				break;
			case self::FORMAT_CENTERED_TIME :
				$align = 'center';
				return date('g:i a',strtotime($data));
				break;
			case self::FORMAT_DATETIME :
				$align = 'right';
				return date('m/d/y g:i a',strtotime($data));
				break;
			case self::FORMAT_PERCENT :
				$align = 'right';
				return number_format($data,2)."%";
				break;
			case self::FORMAT_NUMBER :
				$align = 'right';
				return number_format($data);
				break;
			case self::FORMAT_ABS :
				$align = 'right';
				if($totals)
					return(number_format($data));
				else
					return(number_format(abs($data)));
				break;
			case self::FORMAT_DECIMAL :
				$align = 'right';
				return number_format($data, 2);
				break;
			case self::FORMAT_CURRENCY :
				$align = 'right';
				if ($data < 0)
				{
					$html_format = '<span style="color: red">\$'. number_format(abs($data), 2) .'</span>';
					return (@$_REQUEST["action"] == "download_report") ? '($'. number_format(abs($data), 2).')' : $html_format;
				}
				else
				{
					$data = number_format(abs($data), 2);
					return (@$_REQUEST["action"] == "download_report") ? '$'. $data : '\$'. $data;
				}
				break;
			case self::FORMAT_CURRENCY_NODECIMAL :
				$align = 'right';
				if ($data < 0)
				{
					$html_format = '<span style="color: red">\$'. number_format(abs($data)) .'</span>';
					return (@$_REQUEST["action"] == "download_report") ? '($'. number_format(abs($data)).')' : $html_format;
				}
				else
				{
					$data = number_format(abs($data));
					return (@$_REQUEST["action"] == "download_report") ? '$'. $data : '\$'. $data;
				}
				return '\$'. $data;
				break;
			case self::FORMAT_SSN :
				$align = 'left';
				if($this->is_4_ssn) //mantis:4416
					return 'XXX-XX-'.substr($data, 5, 4);
				else
					return substr($data, 0, 3).'-'.substr($data, 3, 2).'-'.substr($data, 5, 4); //mantis:4760
				break;
			case self::FORMAT_ID :
				$align = 'right';
				return $data;
				break;
			case self::FORMAT_TEXT :
			default:
				$align = 'left';
				return ucwords($data);
				break;
		}
		return $data;
	}

	protected function Get_Status_Dropdown()
	{	
			$statusarray = array();

			if($this->search_criteria['status_type'] != NULL)
			{
				$statusarray[$this->search_criteria['status_type']] = 'SELECTED';
			}

			$asf = ECash::getFactory()->getReferenceList('ApplicationStatusFlat');
			// This should really use toName() as well

			$pulldowndata = array(
				'Active' => array( $asf->toId('active::servicing::customer::*root') ),
				'Additional' => array( $asf->toId('addl::verification::applicant::*root') ),
				'Agree' => array( $asf->toId('agree::prospect::*root') ),
				'Agree (Preact)' => array( $asf->toId('preact_agree::prospect::*root') ),
				'Amortization' => array( $asf->toId('amortization::bankruptcy::collections::customer::*root') ),
				'Applicant' => array( $asf->toId('applicant::*root') ),
				'Approved' => array( $asf->toId('queued::underwriting::applicant::*root'),
									 $asf->toId('dequeued::underwriting::applicant::*root')),
				'Approved (Preact)' => array( $asf->toId('preact::underwriting::applicant::*root') ),
				'Approved Followup' => array( $asf->toId('follow_up::underwriting::applicant::*root') ),
				'Arrangements' => array( $asf->toId('arrangements::collections::customer::*root') ),
				'Arrangements Failed' => array( $asf->toId('arrangements_failed::arrangements::collections::customer::*root') ),
				'Arrangements Hold' => array( $asf->toId('hold::arrangements::collections::customer::*root') ),
				'Bankruptcy' => array( $asf->toId('bankruptcy::collections::customer::*root') ),
				'Bankruptcy Notification' => array( $asf->toId('dequeued::bankruptcy::collections::customer::*root'),
													$asf->toId('queued::bankruptcy::collections::customer::*root'),
													$asf->toId('unverified::bankruptcy::collections::customer::*root') ),
				'Bankruptcy Verified' => array( $asf->toId('verified::bankruptcy::collections::customer::*root') ),
				'Chargeoff' => array( $asf->toId('chargeoff::collections::customer::*root') ),
				'Collections' => array( $asf->toId('collections::customer::*root') ),
				'Collections (Dequeued)' => array( $asf->toId('indef_dequeue::collections::customer::*root') ),
				'Collections Contact' => array( $asf->toId('dequeued::contact::collections::customer::*root'),
												$asf->toId('queued::contact::collections::customer::*root') ),
				'Collections New' => array( $asf->toId('new::collections::customer::*root') ),
				'Collections Rework' => array( $asf->toId('collections_rework::collections::customer::*root') ),
				'Confirmed' => array( $asf->toId('dequeued::verification::applicant::*root'),
									  $asf->toId('queued::verification::applicant::*root') ),
				'Confirmed Followup' => array( $asf->toId('follow_up::verification::applicant::*root') ),
				'Contact' => array( $asf->toId('contact::collections::customer::*root') ),
				'Contact Followup' => array( $asf->toId('follow_up::contact::collections::customer::*root') ),
				'Denied' => array( $asf->toId('denied::applicant::*root') ),
				'Duplicate' => array( $asf->toId('duplicate::applicant::*root') ),
				'Fraud' => array( $asf->toId('fraud::applicant::*root') ),
				'Fraud Confirmed' => array( $asf->toId('confirmed::fraud::applicant::*root') ),
				'Funding Failed' => array( $asf->toId('funding_failed::servicing::customer::*root') ),
				'High Risk' => array( $asf->toId('high_risk::applicant::*root') ),
				'Hotfile' => array( $asf->toId('hotfile::verification::applicant::*root') ),
				'In Process' => array( $asf->toId('in_process::prospect::*root')),
				'In Review (F)' => array( $asf->toId('dequeued::fraud::applicant::*root'),
										  $asf->toId('queued::fraud::applicant::*root') ),
				'In Review (F) Follow Up' => array( $asf->toId('follow_up::fraud::applicant::*root') ),
				'In Review (HR)' => array( $asf->toId('dequeued::high_risk::applicant::*root') ),
				'In Review (HR) Follow Up' => array( $asf->toId('follow_up::high_risk::applicant::*root') ),
				'In Review (HR) Queued' => array( $asf->toId('queued::high_risk::applicant::*root') ),
				'Inactive (Paid)' => array( $asf->toId('paid::customer::*root') ),
				'Inactive (Recovered)' => array( $asf->toId('recovered::external_collections::*root') ),
				'Inactive (Internal Recovered)' => array( $asf->toId('internal_recovered::external_collections::*root') ),
				'Inactive (Settled)'            => array( $asf->toId('settled::customer::*root') ),
				'Made Arrangements' => array( $asf->toId('current::arrangements::collections::customer::*root') ),
				'Past Due' => array( $asf->toId('past_due::servicing::customer::*root') ),
				'Pending Approval' => array( $asf->toId('pending_approval::applicant::*root') ),
				'Pending Expiration' => array( $asf->toId('pend_expire::underwriting::applicant::*root') ),
				'Pre-Fund' => array( $asf->toId('approved::servicing::customer::*root') ),
				'Prospect' => array( $asf->toId('prospect::*root') ),
				'QC Arrangements' => array( $asf->toId('arrangements::quickcheck::collections::customer::*root') ),
				'QC Ready' => array( $asf->toId('ready::quickcheck::collections::customer::*root') ),
				'QC Returned' => array( $asf->toId('return::quickcheck::collections::customer::*root') ),
				'QC Sent' => array( $asf->toId('sent::quickcheck::collections::customer::*root') ),
				'Quick Check' => array( $asf->toId('quickcheck::collections::customer::*root') ),
				'Second Tier (Pending)' => array( $asf->toId('pending::external_collections::*root') ),
				'Second Tier (Sent)' => array( $asf->toId('sent::external_collections::*root') ),
				'Servicing' => array( $asf->toId('servicing::customer::*root') ),
				'Servicing Hold' => array( $asf->toId('hold::servicing::customer::*root') ),
				'Skip Trace' => array( $asf->toId('skip_trace::collections::customer::*root') ),
				'Soft Fax' => array( $asf->toId('soft_fax::prospect::*root') ),
				'Underwriting' => array( $asf->toId('underwriting::applicant::*root') ),
				'Verification' => array( $asf->toId('verification::applicant::*root') ),
				'Withdrawn' => array( $asf->toId('withdrawn::applicant::*root') ),
			);

			// GF #13015: Only display unsigned statuses for companies allowed to see them. [benb]
			if(ECash::getConfig()->ALLOW_UNSIGNED_APPS)
			{
				$pulldowndata['Pending'] = array($asf->toId('pending::prospect::*root'));
				$pulldowndata['Prospect Confirmed'] = array($asf->toId('confirmed::prospect::*root'));
				$pulldowndata['Confirm Declined'] = array($asf->toId('confirm_declined::prospect::*root'));
				$pulldowndata['Declined'] = array($asf->toId('declined::prospect::*root'));
				$pulldowndata['Disagree'] = array($asf->toId('disagree::prospect::*root'));
				$pulldowndata['Prospect Confirmed (Preact)'] = array($asf->toId('preact_confirmed::prospect::*root'));
				$pulldowndata['Pending (Preact)'] = array($asf->toId('preact_pending::prospect::*root'));
			}

			$list  = "Status : <select name='status_type' size='1' style='width:140px;'>\n";
			foreach($pulldowndata as $name => $keys) 
			{
				$list .= "<option value='" . implode(',', $keys) . "' " . $statusarray[implode(',', $keys)] . ">" . $name . "</option>\n";
			}
			$list .= "</select>\n";
			
			return $list;
			
	}
	
	protected function Get_Agent_List()
	{
		$return = array();
        if($this->agent_list_include_unassigned)
        {
			$return[0] = "Unassigned";
        }
		
		foreach($this->prompt_reference_agents as $agent_id => $agent_name)
		{
			$return[$agent_id] = $agent_name;
		}

		return($return);
	}
}

?>
