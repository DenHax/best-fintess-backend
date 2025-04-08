--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0 (Debian 17.0-1.pgdg120+1)
-- Dumped by pg_dump version 17.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: fit-admin
--

COPY public.client (client_uuid, client_surname, client_firstname, client_age, client_height, client_weight, client_hash_password, client_avatar_path, client_gender, client_phone) FROM stdin;
028e529e-20f7-404a-b879-90f6d909d3d3	Попочов	Иван	25	185	85	$2y$10$Ojf9V9wQhYdLkUxH/GEDpekuJ2Oa3VEVnZB9NPfOlh2WOTpFNMOHy	upload/avatar/default.jpg	мужчина	+79431234321
13edfc2b-449d-4e64-9db6-830ff635142e	Главный	Саша	52	180	90	$2y$10$Yp3iVxzEYvYdCbEwM1GdHu//UiWHq7Jk0/vQIHfVDcLgK/wFQh.mm	upload/avatar/67b50ea97c997.png	мужчина	89985342312
b897dbfd-fbc0-484f-9eec-6441e2672d6d	Ухин	Дмитрий	28	175	65	$2y$10$r/NdwiEe7mngwWLM/040PevBcj1jqmeOPMEgDFGtTHVa.33oTHbge	upload/avatar/67d6fa6d6b95b.png	мужчина	84315743677
c79c9868-2970-425c-b318-5387f1667aea	Толкадьин	Илья	19	178	89	$2y$10$E03fLmvRZpCRvXKC.83IY.U4xKTlhkOYjesfdTR5oVmAYHrfr8oH6	upload/avatar/67b514d10a0c5.png	мужчина	+79529521212
ce682e6b-dc1b-4f6c-9def-673040dc9609	Новый	Алексей	56	178	65	$2y$10$PS0H7lKI2R9Gxi31D72rIOOipO9pz.VfAJZdDg9ArONVGS40k8srq	upload/avatar/67be0e344a913.png	мужчина	+79999999999
d9e34b23-7bf5-42de-a3ef-3730b6b6a31d	Семина	Алиса	33	200	100	$2y$10$kfCtjQQBEK20aR7KFu/BQuLgeksgk9MWXeUmaQ3klY8oLLthSnH.q	upload/avatar/67b50d15f189c.jpg	женщина	+70090090909
f150baa8-aa6b-468d-98e3-712a16e15a08	Виджесингх	Нелли	20	155	50	$2y$10$fIzMb8X9wl3xb2.KID.TX./ezbVCEGoFx.xG10w/gfbXAV4R0Obs2	upload/avatar/67d898bab0ca2.jpg	женщина	+79854223455
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: fit-admin
--

COPY public.role (role_id, role_name, role_description) FROM stdin;
1	тренер	Тренирует людей
2	админ	Администратор
\.


--
-- Data for Name: client_role; Type: TABLE DATA; Schema: public; Owner: fit-admin
--

COPY public.client_role (client_id, role_id) FROM stdin;
c79c9868-2970-425c-b318-5387f1667aea	1
13edfc2b-449d-4e64-9db6-830ff635142e	2
b897dbfd-fbc0-484f-9eec-6441e2672d6d	1
\.


--
-- Data for Name: schema_migrations; Type: TABLE DATA; Schema: public; Owner: fit-admin
--

COPY public.schema_migrations (version, dirty) FROM stdin;
1	f
\.


--
-- Name: role_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: fit-admin
--

SELECT pg_catalog.setval('public.role_role_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

