


CREATE TABLE "interface" (
        "if_id"     TEXT NOT NULL,
        "if_desc"   TEXT,
        Primary Key ("if_id")
);
INSERT INTO "interface" values('xl0','Internet connection');
INSERT INTO "interface" values('rl0','BCP connection');
INSERT INTO "interface" values('rl1','Client Network');

CREATE TABLE "account" (
	"acc_address" inet NOT NULL,
	"acc_b_in" integer DEFAULT '0' NOT NULL,
	"acc_b_out" integer DEFAULT '0' NOT NULL,
	"acc_p_in" integer DEFAULT '0' NOT NULL,
	"acc_p_out" integer DEFAULT '0' NOT NULL,
	"acc_date" date DEFAULT 'today' NOT NULL,
	Constraint "account_pkey" Primary Key ("acc_date", "acc_address")
);

CREATE TABLE "company" (
	"cpy_id" SERIAL,
	"cpy_name" TEXT NOT NULL,
	Primary Key ("cpy_id")
);

CREATE TABLE "device_type" (
	"dvt_id" TEXT NOT NULL,
	"dvt_descr" TEXT,
	Primary Key ("dvt_id")
);
INSERT INTO "device_type" values('FIXO','Fixo');
INSERT INTO "device_type" values('PPTP','PPTP');
INSERT INTO "device_type" values('WLES','Wireless');
INSERT INTO "device_type" values('MAN','MAN');

CREATE TABLE "device" (
	"dev_id" SERIAL,
	"dvt_id" TEXT NOT NULL,
	"dev_if" TEXT, 
	"dev_nat" inet,
	"dev_name" TEXT,
	"dev_address" inet NOT NULL,
	"cpy_id" integer NOT NULL,
	Primary Key ("dev_id")
);
ALTER TABLE "device" ADD FOREIGN KEY ("dvt_id") REFERENCES "device_type"        ON DELETE NO ACTION;
ALTER TABLE "device" ADD FOREIGN KEY ("cpy_id") REFERENCES "company"            ON DELETE NO ACTION;
ALTER TABLE "device" ADD FOREIGN KEY ("dev_if") REFERENCES "interface" (if_id)  ON DELETE NO ACTION;

CREATE TABLE "device_group" (
        "dvt_id" TEXT NOT NULL,

	"dvg_id" SERIAL,
	"dvg_name" TEXT NOT NULL,
	"dvg_descr" TEXT,
	"cpy_id" integer NOT NULL,
	Primary Key ("dvg_id")
);
ALTER TABLE "device_group" ADD FOREIGN KEY ("cpy_id") REFERENCES "company"      ON DELETE NO ACTION;
ALTER TABLE "device_group" ADD FOREIGN KEY ("dvt_id") REFERENCES "device_type"  ON DELETE NO ACTION;

CREATE TABLE "device_x_group" (
	"dev_id" integer NOT NULL,
	"dvg_id" integer NOT NULL,
	Primary Key ("dev_id", "dvg_id")
);
ALTER TABLE "device_x_group" ADD FOREIGN KEY ("dev_id") REFERENCES "device"       ON DELETE NO ACTION;
ALTER TABLE "device_x_group" ADD FOREIGN KEY ("dvg_id") REFERENCES "device_group" ON DELETE NO ACTION;

CREATE TABLE "access_list" (
	"acl_id" SERIAL,
	"acl_from" integer NOT NULL,
	"acl_to" integer NOT NULL,
	Primary Key ("acl_id")
);
CREATE UNIQUE INDEX acl_u ON access_list (acl_from,acl_to);
ALTER TABLE "access_list" ADD FOREIGN KEY ("acl_from") REFERENCES "device_group" (dvg_id)   ON DELETE NO ACTION;
ALTER TABLE "access_list" ADD FOREIGN KEY ("acl_to") REFERENCES "device_group" (dvg_id)     ON DELETE NO ACTION;


CREATE TABLE "man"
(
    "dev_id"    integer NOT NULL,

    "man_id"    SERIAL PRIMARY KEY,
    "man_code"  integer NOT NULL
);
ALTER TABLE "man" ADD FOREIGN KEY ("dev_id") REFERENCES "device" (dev_id)   ON DELETE NO ACTION;

CREATE TABLE "pptp"
(
    "dev_id"    integer NOT NULL,

    "ppt_id"    SERIAL PRIMARY KEY,
    "ppt_login" TEXT    NOT NULL,
    "ppt_passwd"    TEXT    NOT NULL
);
ALTER TABLE "pptp" ADD FOREIGN KEY ("dev_id") REFERENCES "device" (dev_id)   ON DELETE NO ACTION;

CREATE TABLE "queue"
(
    "que_id"    SERIAL PRIMARY KEY,
    "que_date"  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "que_command"   TEXT    NOT NULL,
    "que_done"  integer NOT NULL DEFAULT '0'
);


--
--   .-----< own >--|company|
--   |
--   | .---< is >---|device_type|
--   | |
-- |device|---<device_group>---|group|---<acl>--.
--                                 \            |
--                                  `-----------'


CREATE VIEW rules AS SELECT DISTINCT f.dev_address as from_addr,
       f.dev_if as from_if, 
       f.dev_nat as from_nat,
       f.dvt_id as from_type,
       t.dev_address as to_addr,
       t.dev_if as to_if,
       t.dev_nat as to_nat,
       t.dvt_id as to_type
       FROM device f NATURAL JOIN device_x_group fg
                   JOIN access_list ON fg.dvg_id = acl_from
                   JOIN (device_x_group tg NATURAL JOIN device t)
                                           ON tg.dvg_id = acl_to;

