<?

function sm_sql_transaction_begin() {
# 	$GLOBALS["SM_PDO"]->query( "SET AUTOCOMMIT=1" );
# 	$GLOBALS["SM_PDO"]->query( "START TRANSACTION" );
 	$GLOBALS["SM_PDO"]->query( "BEGIN" );
}

function sm_sql_transaction_commit() {
 	$GLOBALS["SM_PDO"]->query( "COMMIT" );
}

function sm_sql_transaction_rollback() {
 	$GLOBALS["SM_PDO"]->query( "ROLLBACK" );
}

?>
