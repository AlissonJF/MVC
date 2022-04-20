<?php
 class param{

	public $param="";
	public $val="";
	public $maxlen=-1;
	public $tipo=""; 

	/*
       tipo can be:
	SQLT_BFILEE or OCI_B_BFILE - for BFILEs;
	SQLT_CFILEE or OCI_B_CFILEE - for CFILEs;
	SQLT_CLOB or OCI_B_CLOB - for CLOBs;
	SQLT_BLOB or OCI_B_BLOB - for BLOBs;
	SQLT_RDD or OCI_B_ROWID - for ROWIDs;
	SQLT_NTY or OCI_B_NTY - for named datatypes;
	SQLT_INT or OCI_B_INT - for integers;
	SQLT_CHR - for VARCHARs;
	SQLT_BIN or OCI_B_BIN - for RAW columns;
	SQLT_LNG - for LONG columns;
	SQLT_LBI - for LONG RAW columns;
	SQLT_RSET - for cursors created with oci_new_cursor();
	SQLT_BOL or OCI_B_BOL - for PL/SQL BOOLEANs (Requires OCI8 2.0.7 and Oracle Database 12c)
	*/
	public function __construct($p,$v,$m,$t){
		$this->param=$p;
		$this->val=$v;
		$this->maxlen=$m;
		$this->tipo=$t;
	}
	

}
