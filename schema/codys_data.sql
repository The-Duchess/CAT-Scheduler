--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.3
-- Dumped by pg_dump version 9.5.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: days; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE days AS ENUM (
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday'
);


ALTER TYPE days OWNER TO postgres;

--
-- Name: hours; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE hours AS ENUM (
    '8',
    '9',
    '10',
    '11',
    '12',
    '13',
    '14',
    '15',
    '16',
    '17'
);


ALTER TYPE hours OWNER TO postgres;

--
-- Name: preferences; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE preferences AS ENUM (
    'Preferred',
    'Available'
);


ALTER TYPE preferences OWNER TO postgres;

--
-- Name: shift_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE shift_type AS ENUM (
    'One 4-Hour',
    'Two 2-Hour',
    'No Preference'
);


ALTER TYPE shift_type OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: hour_block; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE hour_block (
    student_id integer NOT NULL,
    term_id integer NOT NULL,
    block_day days NOT NULL,
    block_hour hours NOT NULL,
    block_preference preferences NOT NULL
);


ALTER TABLE hour_block OWNER TO postgres;

--
-- Name: shift_preference; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE shift_preference (
    student_id integer NOT NULL,
    term_id integer NOT NULL,
    shift_preference shift_type
);


ALTER TABLE shift_preference OWNER TO postgres;

--
-- Name: student; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE student (
    student_id integer NOT NULL,
    student_username character varying(40) NOT NULL,
    join_date timestamp with time zone DEFAULT now(),
    leave_date timestamp with time zone,
    active boolean DEFAULT true
);


ALTER TABLE student OWNER TO postgres;

--
-- Name: student_student_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE student_student_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE student_student_id_seq OWNER TO postgres;

--
-- Name: student_student_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE student_student_id_seq OWNED BY student.student_id;


--
-- Name: term; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE term (
    term_id integer NOT NULL,
    term_name character varying(20) NOT NULL,
    start_date timestamp with time zone,
    end_date timestamp with time zone,
    due_date timestamp with time zone,
    visible boolean DEFAULT true,
    editable boolean DEFAULT true
);


ALTER TABLE term OWNER TO postgres;

--
-- Name: term_term_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE term_term_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE term_term_id_seq OWNER TO postgres;

--
-- Name: term_term_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE term_term_id_seq OWNED BY term.term_id;


--
-- Name: student_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY student ALTER COLUMN student_id SET DEFAULT nextval('student_student_id_seq'::regclass);


--
-- Name: term_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY term ALTER COLUMN term_id SET DEFAULT nextval('term_term_id_seq'::regclass);


--
-- Data for Name: hour_block; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY hour_block (student_id, term_id, block_day, block_hour, block_preference) FROM stdin;
3	2	Monday	8	Preferred
3	2	Monday	9	Available
3	2	Monday	10	Available
3	2	Monday	13	Preferred
3	2	Monday	14	Available
3	2	Monday	16	Preferred
3	2	Monday	17	Available
3	2	Tuesday	8	Preferred
3	2	Tuesday	9	Available
3	2	Tuesday	10	Available
3	2	Tuesday	13	Preferred
3	2	Tuesday	15	Available
3	2	Tuesday	16	Preferred
3	2	Tuesday	17	Available
3	2	Wednesday	8	Available
3	2	Wednesday	9	Available
3	2	Wednesday	10	Available
3	2	Wednesday	13	Available
3	2	Wednesday	15	Preferred
3	2	Wednesday	16	Preferred
3	2	Wednesday	17	Preferred
5	1	Monday	13	Preferred
5	1	Monday	14	Preferred
5	1	Monday	15	Preferred
5	1	Wednesday	16	Available
5	1	Friday	12	Available
2	1	Monday	12	Available
15	5	Monday	8	Available
15	5	Tuesday	8	Preferred
15	5	Wednesday	8	Available
15	5	Thursday	8	Preferred
15	5	Friday	8	Available
15	5	Monday	9	Available
15	5	Friday	9	Available
15	5	Tuesday	10	Available
15	5	Thursday	10	Available
15	5	Wednesday	11	Available
15	5	Thursday	12	Available
15	5	Wednesday	13	Available
15	5	Friday	13	Available
15	5	Tuesday	14	Available
15	5	Wednesday	14	Preferred
15	5	Saturday	14	Available
15	5	Monday	15	Available
15	5	Thursday	15	Preferred
15	5	Friday	15	Available
15	5	Tuesday	16	Available
15	5	Thursday	16	Available
15	5	Wednesday	17	Available
15	2	Monday	8	Available
15	2	Tuesday	8	Preferred
15	2	Wednesday	8	Available
15	2	Thursday	8	Preferred
15	2	Thursday	9	Preferred
15	2	Thursday	10	Preferred
15	2	Tuesday	11	Available
15	2	Friday	11	Preferred
15	2	Tuesday	12	Available
15	2	Friday	12	Available
15	2	Tuesday	13	Available
15	2	Friday	13	Preferred
3	2	Friday	8	Available
6	1	Monday	10	Available
15	2	Saturday	13	Available
15	2	Saturday	14	Available
15	2	Wednesday	15	Available
15	2	Thursday	15	Preferred
15	2	Friday	15	Preferred
15	2	Saturday	15	Available
15	2	Monday	17	Preferred
15	8	Wednesday	8	Available
3	2	Thursday	8	Available
3	2	Thursday	9	Available
3	2	Thursday	10	Available
3	2	Thursday	11	Available
3	2	Thursday	12	Available
3	2	Thursday	13	Available
3	2	Thursday	14	Available
3	2	Thursday	15	Preferred
3	2	Thursday	16	Preferred
3	2	Thursday	17	Preferred
3	2	Friday	9	Available
3	2	Friday	10	Available
3	2	Friday	11	Available
3	2	Friday	12	Available
3	2	Friday	13	Available
3	2	Friday	14	Available
3	2	Friday	15	Preferred
3	2	Friday	16	Preferred
3	2	Friday	17	Preferred
3	2	Saturday	11	Available
3	2	Saturday	12	Available
3	2	Saturday	13	Available
3	2	Saturday	14	Available
15	8	Monday	9	Available
15	8	Tuesday	9	Available
15	8	Wednesday	9	Available
15	8	Thursday	9	Available
15	8	Friday	9	Available
15	8	Monday	10	Available
15	8	Wednesday	10	Available
15	8	Friday	10	Available
15	8	Tuesday	11	Available
5	2	Monday	8	Available
5	2	Tuesday	8	Available
5	2	Wednesday	8	Available
5	2	Thursday	8	Available
15	8	Wednesday	11	Available
15	8	Thursday	11	Available
15	8	Monday	12	Available
5	2	Monday	10	Preferred
15	8	Wednesday	12	Available
5	2	Saturday	14	Available
6	2	Saturday	14	Preferred
5	2	Monday	14	Preferred
6	2	Thursday	11	Preferred
6	2	Saturday	15	Available
6	2	Saturday	16	Preferred
5	2	Saturday	15	Preferred
5	2	Saturday	16	Available
7	2	Wednesday	15	Available
15	8	Friday	12	Available
15	8	Wednesday	13	Preferred
15	8	Wednesday	14	Preferred
15	8	Wednesday	15	Preferred
15	8	Wednesday	16	Preferred
7	2	Thursday	16	Available
5	2	Thursday	16	Available
15	8	Wednesday	17	Preferred
3	1	Wednesday	12	Available
15	7	Monday	8	Available
15	7	Tuesday	8	Available
\.


--
-- Data for Name: shift_preference; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY shift_preference (student_id, term_id, shift_preference) FROM stdin;
15	3	Two 2-Hour
5	1	One 4-Hour
6	3	Two 2-Hour
4	1	No Preference
1	1	Two 2-Hour
4	2	No Preference
5	2	One 4-Hour
3	2	No Preference
6	1	One 4-Hour
7	2	One 4-Hour
6	2	Two 2-Hour
2	1	No Preference
15	5	One 4-Hour
15	2	No Preference
15	8	Two 2-Hour
15	7	Two 2-Hour
15	6	Two 2-Hour
\.


--
-- Data for Name: student; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY student (student_id, student_username, join_date, leave_date, active) FROM stdin;
2	TActiveStudent	2016-07-13 00:08:35.365459-07	\N	f
3	TAvailabilityStudent	2016-07-13 00:08:35.365459-07	\N	t
5	ealkadi	2016-07-13 00:08:35.365459-07	\N	t
4	cwyatt	2016-07-13 00:08:35.365459-07	\N	t
1	TStudent	2016-07-13 00:08:35.365459-07	2016-07-13 00:08:35.365459-07	t
6	eolkadi	2016-07-13 19:44:01.446511-07	\N	t
7	drakeley	2016-07-10 00:00:00-07	\N	t
8	test1	2016-07-13 21:18:53.140714-07	\N	t
9	test2	2016-07-13 21:19:06.201915-07	\N	t
10	test3	2016-07-13 21:19:20.497134-07	\N	t
11	test4	2016-07-13 21:22:19.669379-07	\N	t
12	test5	2016-07-13 21:22:19.689379-07	\N	t
13	test6	2016-07-13 21:22:19.704379-07	\N	t
14	test7	2016-07-13 21:22:19.721879-07	\N	t
15	dog01	2016-07-20 18:10:44.41077-07	\N	t
\.


--
-- Name: student_student_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('student_student_id_seq', 15, true);


--
-- Data for Name: term; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY term (term_id, term_name, start_date, end_date, due_date, visible, editable) FROM stdin;
2	Fall 2016	2016-09-26 00:00:00-07	2016-12-10 00:00:00-08	2016-09-19 00:00:00-07	t	t
1	Summer 2016	2016-06-20 00:00:00-07	2016-09-11 00:00:00-07	2016-06-13 00:00:00-07	t	f
3	OldTerm 2000	1999-12-31 00:00:00-08	2000-01-01 00:00:00-08	1999-12-25 00:00:00-08	t	f
5	Fall 2017	2017-09-25 00:00:00-07	2017-12-10 00:00:00-08	2017-09-18 00:00:00-07	t	t
4	Failure1	2016-07-25 00:00:00-07	2016-10-09 00:00:00-07	2016-07-18 00:00:00-07	t	t
6	Fall 2018	2018-09-24 00:00:00-07	2018-12-09 00:00:00-08	2018-09-17 00:00:00-07	t	t
7	bowzrs era	2016-07-25 00:00:00-07	2016-07-22 00:00:00-07	2016-07-18 00:00:00-07	t	t
8	Codys Test	2016-07-22 00:00:00-07	2016-10-06 00:00:00-07	2016-07-15 00:00:00-07	t	t
9	A'Test	2016-07-20 00:00:00-07	2016-07-21 00:00:00-07	2016-07-19 00:00:00-07	t	t
\.


--
-- Name: term_term_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('term_term_id_seq', 9, true);


--
-- Name: hour_block_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hour_block
    ADD CONSTRAINT hour_block_pkey PRIMARY KEY (student_id, term_id, block_day, block_hour);


--
-- Name: shift_preference_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY shift_preference
    ADD CONSTRAINT shift_preference_pkey PRIMARY KEY (student_id, term_id);


--
-- Name: student_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_pkey PRIMARY KEY (student_id);


--
-- Name: term_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY term
    ADD CONSTRAINT term_pkey PRIMARY KEY (term_id);


--
-- Name: hour_block_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hour_block
    ADD CONSTRAINT hour_block_student_id_fkey FOREIGN KEY (student_id) REFERENCES student(student_id);


--
-- Name: hour_block_term_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY hour_block
    ADD CONSTRAINT hour_block_term_id_fkey FOREIGN KEY (term_id) REFERENCES term(term_id);


--
-- Name: shift_preference_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY shift_preference
    ADD CONSTRAINT shift_preference_student_id_fkey FOREIGN KEY (student_id) REFERENCES student(student_id);


--
-- Name: shift_preference_term_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY shift_preference
    ADD CONSTRAINT shift_preference_term_id_fkey FOREIGN KEY (term_id) REFERENCES term(term_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

