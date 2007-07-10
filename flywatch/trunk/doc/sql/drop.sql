/*
 * $Id: drop.sql,v 1.5 2003/02/19 22:51:12 binary Exp $
 *
 * No Left, Center or Right Copy Permited.
 *
 * Marcio Ribeiro <marcio@b1n.org>
 *
 * Originally this SQL Script was made for PostgreSQL 7.1.3
 * Later on updated to run well in 7.3
 *
 */

-- The Drops are ordened in Dependency Order (then, alphabetically).
-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Views
DROP VIEW "view_acf" CASCADE;
DROP VIEW "view_apt" CASCADE;
DROP VIEW "view_cmb" CASCADE;
DROP VIEW "view_grp" CASCADE;
DROP VIEW "view_catering_order" CASCADE;
DROP VIEW "view_handling_request" CASCADE;
DROP VIEW "view_permit_request" CASCADE;
DROP VIEW "view_cat_ctc" CASCADE;
DROP VIEW "view_hdl_ctc" CASCADE;
DROP VIEW "view_pmt_ctc" CASCADE;
DROP VIEW "view_opr" CASCADE;
DROP VIEW "view_page" CASCADE;
DROP VIEW "view_pax" CASCADE;
DROP VIEW "view_pax_vst" CASCADE;
DROP VIEW "view_usr_fnc" CASCADE;
DROP VIEW "view_active_usr" CASCADE;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Triggers
DROP TRIGGER "trig_uniqueness" ON "aircraft" CASCADE;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Rules
DROP RULE "rule_default" ON "aircraft" CASCADE;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Functions
DROP FUNCTION func_default_acf() CASCADE;
DROP FUNCTION func_similar_leg(integer, integer)  CASCADE;
DROP FUNCTION func_list_leg(text, text, text, text, integer, integer) CASCADE;
DROP FUNCTION func_list_leg_pdf(text) CASCADE;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Tables
    -- RelationShips
DROP TABLE "cat_ctc" CASCADE;
DROP TABLE "fdt_fod" CASCADE;
DROP TABLE "grp_fnc" CASCADE;
DROP TABLE "grp_usr" CASCADE;
DROP TABLE "hdl_ctc" CASCADE;
DROP TABLE "leg_pax" CASCADE;
DROP TABLE "pmt_ctc" CASCADE;
DROP TABLE "pax_vst" CASCADE;

    -- Entities
DROP TABLE "leg"        CASCADE;
DROP TABLE "aircraft"   CASCADE;
DROP TABLE "airport"    CASCADE;
DROP TABLE "caterer"    CASCADE;
DROP TABLE "pax"        CASCADE;
DROP TABLE "citizenship" CASCADE;
DROP TABLE "cmb"        CASCADE;
DROP TABLE "contact"    CASCADE;
DROP TABLE "operator"   CASCADE;
DROP TABLE "country"    CASCADE;
DROP TABLE "file"       CASCADE;
DROP TABLE "food"       CASCADE;
DROP TABLE "foodtype"   CASCADE;
DROP TABLE "function"   CASCADE;
DROP TABLE "group"      CASCADE;
DROP TABLE "handler"    CASCADE;
DROP TABLE "link"       CASCADE;
DROP TABLE "occupation" CASCADE;
DROP TABLE "permit"     CASCADE;
DROP TABLE "service"    CASCADE;
DROP TABLE "syslink"    CASCADE;
DROP TABLE "user"       CASCADE;
DROP TABLE "visatype"   CASCADE;
-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Explicit Sequences
DROP SEQUENCE"seq_trip" CASCADE;
