--Inserts an example student into the database
INSERT INTO student (Student_username, join_date, leave_date, active)
    VALUES ('dog01', DEFAULT, DEFAULT, TRUE);

--Inserts an example new term into the database	
INSERT INTO term (Term_Name, Start_Date, End_Date, Visible, Editable, term_id, Due_DATE)
    VALUES ('Summer 2016', '2016-06-20 00:00', '2016-09-11 00:00', TRUE, TRUE, default, '2016-06-13 00:00');
	
--Inserts another example new term into the database	
INSERT INTO term (Term_Name, Start_Date, End_Date, Visible, Editable, term_id, Due_DATE)
    VALUES ('Fall 2016', '2016-09-26 00:00', '2016-12-10 00:00', TRUE, TRUE, default, '2016-09-19 00:00');

/*	
--Delete a student from the database (Usually you never want to remove data thats why visible flag is used)	
DELETE FROM student
WHERE student_id = 920054997;
*/

--Set Visibility to the default setting(In this case true)
UPDATE student
SET Visible = default
WHERE student_id = 920054997;

--Set Leave/Finished Date (Probably prefer to actually select date)
UPDATE student
SET leave_date = current_timestamp
WHERE student_id = 920054997;
