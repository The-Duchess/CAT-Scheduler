-- term --
-- contents:
--  id
--  name
--  start, end date
--  due date
--  visible to students
--  editable (can set availability)

DROP TABLE IF EXISTS term;
CREATE TABLE term
(
  term_id serial,
  term_name character varying(20) NOT NULL,
  start_date timestamp with time zone NOT NULL,
  end_date timestamp with time zone NOT NULL,
  due_date timestamp with time zone NOT NULL,
  visible boolean DEFAULT true,
  editable boolean DEFAULT true,
  CONSTRAINT term_pkey PRIMARY KEY (term_id)
);

/* Student
Contents:
  Student ID (Not psu id)
  Student Username
  Join Date
  Leave Date
  Active flag
*/

DROP TABLE IF EXISTS Student;
CREATE TABLE student
(
  student_id serial,
  student_username character varying(40) NOT NULL,
  join_date timestamp with time zone DEFAULT now(),
  leave_date timestamp with time zone,
  active boolean DEFAULT true,
  CONSTRAINT student_pkey PRIMARY KEY (student_id)
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
  term_id integer REFERENCES Term(Term_id),
  shift_preference shift_type,
  PRIMARY KEY (student_id, term_id)
);

/*ENUM Types for Hour_Block*/
CREATE TYPE days AS ENUM ('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
CREATE TYPE hours AS ENUM('8','9','10','11','12','13','14','15','16','17');
CREATE TYPE preferences AS ENUM('Preferred', 'Available');
CREATE TYPE shift_type AS ENUM('One 4-Hour', 'Two 2-Hour', 'No Preference');

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
  term_id integer REFERENCES Term(Term_id),
  block_day days NOT NULL,
  block_hour hours NOT NULL,
  block_preference preferences NOT NULL,
  PRIMARY KEY (student_id, term_id, block_day, block_hour)
);
