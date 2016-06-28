// This is the format a script wrapper function should follow

// NOTE: connection may not be an arguement if we decide to store the connection
//       variable as a global variable
// NOTE: number of arguements will vary depending on the script
function descriptive_name($connection, $arg1, $arg2) {

  // writing out the query in a var before the function call helps with readibility
  // as well as reduces chances of errors when inner quotes are necessary
  $query = 'INSERT into [TABLE] (col1, col2) VALUES($1,$2);'

  // The $'s are place holders for variables. We use the parameterized version
  // of pg_query() in order to help defend against SQL injection attacks
  return $pg_query_params($connection, $query, array($arg1, $arg2));
}

// the function will return a result object if the query is successful, FALSE otherwise
