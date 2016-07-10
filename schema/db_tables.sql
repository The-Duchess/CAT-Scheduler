-- term --
-- contents:
-- 	name
--  start, end date
--  visible to students
--  editable (can set availability)
--  id
--  due date

DROP TABLE IF EXISTS Term;
CREATE TABLE Term (
Term_Name  varchar(20) NOT NULL,
Start_Date timestamp   default NULL,
End_Date   timestamp   default NULL,
Visible    boolean     default true,
Editable   boolean     default true,
term_id    bigserial PRIMARY KEY,
Due_DATE   timestamp default NULL
);


/* Student
Contents:
  Student PSUID
  Student Name
  Student Email
  Cat Nickname?
  Join Date
  Leave Date?
  Visible flag? (Instead of deleting students)
      Could also use the leave date to determine if they are visible or not
      Just placed here to decide later
*/

DROP TABLE IF EXISTS Student;
CREATE TABLE Student (
	Student_id		int 		NOT NULL PRIMARY KEY,
	Student_FirstName	varchar(50)	NOT NULL,
	Student_LastName  	varchar(50)	NOT NULL,
	Student_Email 		varchar(255) 	NOT NULL,
	Cat_Nickname 		varchar(50) 	default NULL,
	Join_Date 		timestamp   	default NULL,
	Leave_Date  		timestamp  	default NULL,
	Visible   	 	boolean    	default true
);


/* Availability
Contents:
  Student PSUID
  Term ID
  Monday Preference
	Tuseday Preference
	Wednesday Preference
	Thursday Preference
	Friday Preference
	Saturday Preference
*/
DROP TABLE IF EXISTS Availability;
CREATE TABLE Availability
(
  student_id integer REFERENCES Student(Student_id),
  term_id bigint REFERENCES Term(Term_id),
  mon_pref character(10)[] NOT NULL DEFAULT '{nnnnnnnnnn}'::bpchar[],
  tue_pref character(10)[] NOT NULL DEFAULT '{nnnnnnnnnn}'::bpchar[],
  wed_pref character(10)[] NOT NULL DEFAULT '{nnnnnnnnnn}'::bpchar[],
  thu_pref character(10)[] NOT NULL DEFAULT '{nnnnnnnnnn}'::bpchar[],
  fri_pref character(10)[] NOT NULL DEFAULT '{nnnnnnnnnn}'::bpchar[],
  sat_pref character(5)[] NOT NULL DEFAULT '{nnnnn}'::bpchar[],
  shift_pref smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (student_id, term_id)
);
