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


/* Shift_Preference
Contents:
  Student PSUID
  Term ID
	Schedule Preference
  
*/
DROP TABLE IF EXISTS Shift_Preference;
CREATE TABLE Shift_Preference
(
	student_id integer REFERENCES Student(Student_id),
	term_id bigint REFERENCES Term(Term_id),
	shift_preference smallint NOT NULL DEFAULT 0,
	PRIMARY KEY (student_id, term_id)
);

/*ENUM Types for Hour_Block*/
CREATE TYPE days AS ENUM ('mon','tue','wed','thu','fri','sat');
CREATE TYPE hours AS ENUM('8','9','10','11','12','13','14','15','16','17');
CREATE TYPE preferences AS ENUM ('P','A','N');

/* Hour_Block
Contents:
  Student PSUID
  Term ID
  Day
  Hour
  Block Preference
*/
DROP TABLE IF EXISTS Hour_Block;
CREATE TABLE Hour_Block
(
	student_id integer REFERENCES Student(Student_id),
	term_id bigint REFERENCES Term(Term_id),
	block_day days NOT NULL,
	block_hour hours NOT NULL,
	block_preference preferences NOT NULL,
	PRIMARY KEY (student_id, term_id, block_day, block_preference)
);
