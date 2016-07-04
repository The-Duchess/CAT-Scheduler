-- term --
-- contents:
-- 	name
--  start, end date
--  visible to students
--  editable (can set availability)
--  id
--  due date

DROP TABLE IF EXISTS 'Term'
CREATE TABLE 'Term' (
Term_Name  varchar(20) NOT NULL,
Start_Date timestamp   default NULL,
End_Date   timestamp   default NULL,
Visible    boolean     default true
Editable   boolean     default true,
term_id    bigserial PRIMARY KEY,
Due_DATE   timestamp default NULL 
);
