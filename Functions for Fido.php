<?php
//Date Updated:	7/26/2019
//Created By: 	Cody
//Purpose:		To view the functions we currently have
//			Also Mostly because this file is useful for (Cody to) reference
//WARNING:	This file is not meant to be used in code or run as errors will just be returned
	//This file was created by hand, Please check the "Date Updated" At the top to see how old this is
		//Also if this file isn't useful to anyone or I deam useless I will not be updating it

/*
Destinations Shown to allow for easier including of these functions
Files Checked (In order of appearance):
	Source\Utility\utils.php
	Source\Query\Availability.php
	Source\Query\Student.php
	Source\Query\Term.php
	Source\API\Utility.php
	Source\API\Admin.php
	Source\API\Student.php
*/

//Source\Utility\utils.php (depreciated)
	date_selector($submit_ident, $future=1) 
		//  PARAMETERS:
		//      submit_ident:   indetifying name for submit object
		//      future:         how many years into the future to allow, default 1
	
	
	
	

//Source\Query\Availability.php

	retrieve_availability_for_student($student_id, $term_id, $kwargs=null)
	//  Retrieves availibility for select term and select student
		//  PARAMETERS:
		//		student_id: the id of the student to get availability for
		//		term_id: the id of the term that is being selected for availability listing
		//      kwargs: associative array of keyword arguments
		//          block_day: specify if we want only a select day from that term for the student
		//          block_hour: specify if we want only a select hour from that term for the student
		//				Specifying both will allow for selecting a specific day and time for the student
		
		
	insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null)
	//  Inserts an availibility block into the Hour_Block table
		//  PARAMETERS:
		//      input_term_id: the id of the term the availability submission is for
		//      input_day: the day of the week the entry is for
		//      input_hour: the hour block the entry is for
		//      input_pref: specify whether this a 'available' or 'preferred' hour block
		//      kwargs: associative array of keyword arguments
		//          student_username: specify if want to use a username different from the logged in user
		//          student_id: specify if want to use a student id different from the logged in user
	


	update_availability_blocks($input_term_id, $input_blocks, $kwargs=null)
	//  Updates the hour_block table to contain only the hour blocks specified in the input
		//  PARAMETERS:
		//      input_term_id: the id of the term the availability submission is for
		//      input_blocks: array of hour blocks to be used to edit the hour_block table
		//      kwargs: associative array of keyword arguments
		//          student_username: specify if want to use a username different from the logged in user
		//          student_id: specify if want to use a student id different from the logged in user
		//
		//  NOTE: if specifying kwargs, if both student_username and student_id are specified, student_id will override student_username.
	
	retrieve_shift_preference($studentid, $termid)
	// retrieves the shift preference for a specific student by a specific term
		//  PARAMETERS:
		//		studentid: The id of the student to retrieve shift preference for
		//      input_term_id: the id of the term to retrieve shift preference for
	
	add_student_shift_preference($student_id, $term_id, $pref)
	// add the shift preference for a student
		//  PARAMETERS:
		//		student_id:	The id of the student to retrieve shift preference for
		//      term_id:	The id of the term to retrieve shift preference for
		//		pref:		Selected preference to add to student on term
		//		Choices:    'One 4-Hour'
		//					'Two 2-Hour'
		//					'No Preference'
		
	retrieve_availability_for_term($term_id, $kwargs=null)
	//Retrieve all availability for selected term
		//  PARAMETERS:
		//      term_id:	The id of the term to retrieve availability for
		//      kwargs: associative array of keyword arguments
		//			None used (Could remove from this function?)
		
		
	
		
		
		
		
//Source\Query\Student.php
	get_student_id_by_username($input_username)
	// retrieves a student id based on the give username
		//  PARAMETERS:
		//      input_username: username of the student you want to get the id of
	
	deactivate_student($id)
	// deactivate a student in the students table
			// set the Active value to false
			// all other values can be default and are not required to change
		//  PARAMETERS:
		//      id: The id of the student you want to set active flag to false
		
	add_student($student_uname, $joind)	
	// add a student to the students table
	// PARAMETERS:		
		// 		student_uname:			Student Username that is being added
		// 		student's join time:	Join date of student(Database Default is Current_time or Now()
	
	student_update($id, $fields)
	//  Updates a student in the database, returns FALSE on a failure
	//  PARAMETERS:
		//      id:     the id of the student to update
		//      fields: an associative array of db data fields and their
		//              updated values. only fields passed as keys in
		//              this array will be updated
		//			"student_username" => string,
		//				String to change username to
		//			"join_date" => DateTime,
		//				Date to set the Join date to
		//			"leave_date" => DateTime,
		//				Date to set the Leave date to
		//			"active" => boolean);
		//				Either True or False to set the active flag

	retrieve_students_no_availability($id, $kwargs=null)
	//  Retrieves a results object containing all terms regardless of editability
    //  sorted by student username, otherwise FALSE
    //  PARAMETERS:
    //      id:     the id of the term
    //      kwargs: associative array of keyword arguments
    //          ascend:     if the results should be in ascending order, default TRUE
    //          limit:      the max number of students to retrieve, default none (null)
	
	retrieve_student_info($termid)
	// retrieves an array of students constrained by a specific term
	//  PARAMETERS:
    //      id:     the id of the term
	
//Source\Query\Term.php
	term_retrieve_all($kwargs=null)
	//  Retrieves a results object containing all terms regardless of visibility
	//  or editability, otherwise false
	//  PARAMETERS:
	//      kwargs: associative array of keyword arguments
	//          order_by:   what field(s) to order the results by, default term_id
	//          ascend:     if the results should be in ascending order, default false
	//          limit:      the max number of terms to retrieve, default none (null)
	
	term_retrieve_visible_all($kwargs=null)
	//  Retrieves a results object containing all terms regardless of editability,
	//  otherwise false
	//  PARAMETERS:
	//      kwargs: associative array of keyword arguments
	//          order_by:   what field(s) to order the results by, default term_id
	//          ascend:     if the results should be in ascending order, default false
	//          limit:      the max number of terms to retrieve, default none (null)
	
	term_retrieve_editable_terms($kwargs=null)
	//  Retrieves a results object containing terms that are not currently editable
	//  PARAMETERS:
	//      kwargs: associative array of keyword arguments
	//          term_id: search for a specific term by id instead of a general list
	
	term_retrieve_by_start($kwargs=null)
	//  Retrieves a results object containing all terms that start before
	//  or at a certain date, otherwise FALSE
	//  PARAMETERS:
	//      kwargs: associative array of keyword arguments
	//          start:  the start date to compare to, default 1 year before now
	//          ascend: if the results should be in ascending order, default false
	//          limit:  the max number of terms to retrieve, default none (null)
	
	term_retrieve_visible_by_start($kwargs=null)
	//  Retrieves a results object containing all terms that start before
	//  or at a certain date and are visible, otherwise FALSE
	//  PARAMETERS:
	//      kwargs: associative array of keyword arguments
	//          start:      the start date to compare to, default 1 year before now
	//          ascend:     if the results should be in ascending order, default false
	//          editable:   if the returned terms should require being editable, default false
	//          limit:      the max number of terms to retrieve, default none (null)
	
	term_update($id, $fields)
	//  Updates a term in the database, returns FALSE on a failure
	//  PARAMETERS:
	//      id:     the id of the term to update
	//      fields: an associative array of db data fields and their
	//              updated values. only fields passed as keys in
	//              this array will be updated
	//      check:  whether to verify there are no invalid fields in
	//              the fields parameter, small hit to performance.
	//              default TRUE
	
	add_term($name, $start, $end, $due)
	//  Returns result object of query if successful, FALSE otherwise
	//  PARAMETERS:
	//      name:		the name to set the term to have
	//		start:		The start date for the term
	//		end:		The end date for the term
	//		due:		The Due date students must submit by
	
	deactivate_term($id)
	// deactivate a student in the students table
	// set the Visible value to false
	// all other values can be default and are not required to change
	//  PARAMETERS:
	//      id:     the id of the term to deactivate
	
	
//Source\API\Utility.php
	dropdown_select_term($subIdent, $kwargs=null)
	//  Creates a dropdown menu with a list of visible and editable terms
	//  and returns an associative array of that terms data fields from
	//  the database, otherwise FALSE.
	//  PARAMETERS:
	//      subIdent:   identifier of submission button
	//      kwargs:     associative array of keyword arguments to change functionality,
	//                  this is also passed as a parameter to the internal query, see
	//                  Source/Query/Term.php::term_retrieve_visible_by_start for more
	//                  information
	//          view_only_alert:    if the dropdown should indicate terms that are
	//                              non-editable, default false
	
	fido_db_connect()
	//  Database connection to the Fido MVP database
	//	SHOULD ONLY USE ON PRODUCTION CODE
	//		DO NOT USE ON TEST CODE!
	
	cody_db_connect()
	//Will change to test_db_connect() sometime
	//ATTENTION: This function will or should be removed before release as it will no longer work
	
	is_term_editable($termid)
	//Determines if a term is editable to display in the dropdown
	//Will return a boolean for editablility
	//This should probably be moved to the Term.php in the Query folder
	//  PARAMETERS:
	//      termid:   Id of the term you are checking
	
	
//Source\API\Admin.php
	list_students_no_availability($term_id)
	//  Generates HTML code for displaying a list of students
	//  who have not submitted availability for a given term,
	//  returns TRUE if successful otherwise FALSE.
	//  PARAMETERS:
	//      term_id:    the id of the term in question
	
//Source\API\Student.php
	get_students()
	//  Returns a results object containing info on the students in
	//  	the database if successful, otherwise FALSE
	
	get_student_array($result=null)
	//  Returns an array of all student information from a results
	//  object, otherwise FALSE.
	
	display_students_list($students=null)
	//  Echos an html list of all students in the database.
	//  Returns TRUE if successful, otherwise FALSE 
?>