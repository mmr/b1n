/*
 * $Id: flywatch.sql,v 1.68 2006/04/22 23:44:54 mmr Exp $
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
DROP VIEW 
    "view_acf",
    "view_apt",
    "view_cmb",
    "view_grp",
    "view_catering_order",
    "view_handling_request",
    "view_general_declaration",
    "view_permit_request",
    "view_cat_ctc",
    "view_hdl_ctc",
    "view_pmt_ctc",
    "view_leg_pax",
    "view_opr",
    "view_page",
    "view_pax",
    "view_pax_vst",
    "view_usr_fnc",
    "view_active_usr" RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Triggers
DROP TRIGGER "trig_uniqueness" ON "aircraft" RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Rules
DROP RULE "rule_default" ON "aircraft" RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Functions
DROP FUNCTION func_default_acf() RESTRICT;
DROP FUNCTION func_similar_leg(integer, integer) RESTRICT;
DROP FUNCTION func_list_leg(text, text, text, text, integer, integer) RESTRICT;
DROP FUNCTION func_list_leg_pdf(text) RESTRICT;
DROP FUNCTION func_get_leg_push_overflow(integer, timestamp, interval, interval) RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Tables
    -- RelationShips
DROP TABLE
    "cat_ctc",
    "fdt_fod",
    "grp_fnc",
    "grp_usr",
    "hdl_ctc",
    "leg_pax",
    "pmt_ctc",
    "pax_vst" RESTRICT;

    -- Entities
DROP TABLE
    "leg",
    "aircraft",
    "airport",
    "caterer",
    "pax",
    "cmb",
    "contact",
    "operator",
    "country",
    "file",
    "food",
    "foodtype",
    "function",
    "group",
    "handler",
    "link",
    "occupation",
    "citizenship",
    "permit",
    "service",
    "syslink",
    "user",
    "visatype" RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Dropping Explicit Sequences
DROP SEQUENCE"seq_trip" RESTRICT;

-- -------------------------------------------------------------------------------------------------------------------
-- Admin 
    -- Entities
CREATE TABLE "function"
(
    fnc_id      SERIAL  NOT NULL PRIMARY KEY,
    fnc_name    TEXT    NOT NULL,
    fnc_desc    TEXT        NULL,
    fnc_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(fnc_name)
);

CREATE TABLE "group"
(
    grp_id      SERIAL      NOT NULL PRIMARY KEY,
    grp_name    TEXT        NOT NULL,
    grp_desc    TEXT        NULL,
    grp_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "user"
(
    usr_id      SERIAL      NOT NULL PRIMARY KEY,

    usr_login   TEXT        NOT NULL UNIQUE,
    usr_passwd  TEXT        NULL,

    usr_name    TEXT        NULL,
    usr_email   TEXT        NULL,
    usr_nick    TEXT        NULL,
    usr_dob_dt  TIMESTAMP   NULL,       -- Day Of Birth

    usr_phone   TEXT        NULL,
    usr_phone_city_code     INT NULL,
    usr_phone_country_code  INT NULL,
    usr_mobile  TEXT        NULL,

    usr_active  INT         NULL    DEFAULT 1,
    usr_expire_dt   TIMESTAMP   NULL,

    usr_start_page  TEXT        NULL,

    usr_add_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------------------------------------------------------------
    -- RelationShips
-- Group X User
CREATE TABLE "grp_usr"
(
    grp_id      INT     NOT NULL,       -- FK - group
    usr_id      INT     NOT NULL,       -- FK - user 

    gus_id      SERIAL  NOT NULL    PRIMARY KEY,

    gus_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(grp_id, usr_id)
);

-- Group X Function
CREATE TABLE "grp_fnc"
(
    grp_id      INT     NOT NULL,       -- FK - group
    fnc_id      INT     NOT NULL,       -- FK - function 

    gfn_id      SERIAL  NOT NULL    PRIMARY KEY,

    gfn_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(grp_id, fnc_id)
);

-- -------------------------------------------------------------------------------------------------------------------
-- Fly
    -- Entities
CREATE TABLE "aircraft"
(
    apt_id      INT         NOT NULL,   -- FK - airport (HomeBase)
    opr_id      INT         NOT NULL,   -- FK - operator

    acf_id      SERIAL      NOT NULL PRIMARY KEY,
    acf_registry    TEXT    NULL UNIQUE,            -- Prefix
    acf_model   TEXT        NULL,
    acf_desc    TEXT        NULL,
    acf_vendor  TEXT        NULL,
    acf_noise_cert  INT     NOT NULL    DEFAULT '3',    -- Noise Certifcation: Stage II/III/IV/V
    acf_mtow    TEXT        NULL,                       -- Max Take Off Weight
    acf_satcom_country_code TEXT    NULL,   -- Sattelite Telephone/Communicator
    acf_satcom_city_code    TEXT    NULL,
    acf_satcom  TEXT        NULL,
    acf_default INT         NULL    DEFAULT '0',

    acf_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "airport"
(
    cat_id      INT         NULL,       -- FK - caterer
    hdl_id      INT         NOT NULL,   -- FK - handler
    pmt_id      INT         NULL,       -- FK - permit
    cty_id      INT         NOT NULL,   -- FK - country

    apt_id      SERIAL      NOT NULL PRIMARY KEY,
    apt_name    TEXT        NOT NULL,
    apt_desc    TEXT        NULL,
    apt_city    TEXT        NULL,
    apt_icao    char(4)     NULL,
    apt_iata    char(3)     NULL,
    apt_dst_start_dt    TIMESTAMP   NULL,   -- Daylight Saving Time START
    apt_dst_end_dt      TIMESTAMP   NULL,   -- Daylight Saving Time END
    apt_timezone    TEXT    NULL,

    apt_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE "caterer"
(
    cat_id      SERIAL      NOT NULL PRIMARY KEY,
    cat_name    TEXT        NOT NULL,
    cat_desc    TEXT        NULL,

    cat_email   TEXT        NULL,
    cat_sita    TEXT        NULL,
    cat_aftn    TEXT        NULL,

    cat_fax     TEXT        NULL,
    cat_phone   TEXT        NULL,
    cat_phone_city_code     INT NULL,
    cat_phone_country_code  INT NULL,
    cat_mobile  TEXT        NULL,

    cat_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "country"
(
    cty_id      SERIAL  NOT NULL PRIMARY KEY,
    cty_name    TEXT    NOT NULL,
    cty_desc    TEXT    NULL,
    cty_code    char(2) NULL,
    cty_add_dt  TIMESTAMP NULL  DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "contact"
(
    ctc_id      SERIAL      NOT NULL PRIMARY KEY,
    ctc_name    TEXT        NOT NULL,
    ctc_desc    TEXT        NULL,
    ctc_email   TEXT        NULL,

    ctc_fax     TEXT        NULL,
    ctc_phone   TEXT        NULL,
    ctc_phone_city_code     INT NULL,
    ctc_phone_country_code  INT NULL,
    ctc_mobile  TEXT        NULL,

    ctc_provider    char(1) NOT NULL,
    /*
        H: Handler
        C: Caterer
        P: Permit
    */

    ctc_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "leg"
(
    acf_id          INT         NOT NULL,   -- FK - aircraft
    apt_id_arrive   INT         NOT NULL,   -- FK - airport (arrive)
    apt_id_depart   INT         NOT NULL,   -- FK - airport (depart)

    cmb_id_pic      INT         NOT NULL,   -- FK - cmb : Pilot  In Command
    cmb_id_sic      INT         NOT NULL,   -- FK - cmb : Second In Command
    cmb_id_extra1   INT         NULL,       -- FK - cmb : Extra 1
    cmb_id_extra2   INT         NULL,       -- FK - cmb : Extra 2

    leg_id          SERIAL      NOT NULL PRIMARY KEY,
    leg_trip        INT         NOT NULL,   -- TRIP Number
    leg_fuel        INT         NULL,
    leg_wind        INT         NULL,
    leg_distance    INT         NULL,
    leg_ete_i       INTERVAL    NOT NULL,   -- Estimated Time of EnRoute
    leg_etd_dt      TIMESTAMP   NULL,       -- Estimated Time of Departure
    leg_groundtime_i INTERVAL   NULL,       -- Null(if wanted calculated), 00:45:00 or 01:00:00
    leg_keeptrack_dt TIMESTAMP  NOT NULL,   -- Internal Control Var (It is the REAL ETD)
    leg_remarks     TEXT        NULL,

    leg_add_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "food"
(
    fod_id      SERIAL  NOT NULL PRIMARY KEY,
    fod_name    TEXT    NOT NULL UNIQUE,
    fod_desc    TEXT    NULL,
    fod_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "foodtype"
(
    fdt_id      SERIAL  NOT NULL PRIMARY KEY,
    fdt_name    TEXT    NOT NULL UNIQUE,
    fdt_desc    TEXT    NULL,
    fdt_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "handler"
(
    hdl_id      SERIAL  NOT NULL PRIMARY KEY,
    hdl_name    TEXT    NOT NULL,
    hdl_desc    TEXT        NULL,

    hdl_email   TEXT        NULL,
    hdl_sita    TEXT        NULL,
    hdl_aftn    TEXT        NULL,
    hdl_arinc   TEXT        NULL,       -- Radio Frequency

    hdl_fax     TEXT        NULL,
    hdl_phone   TEXT        NULL,
    hdl_phone_city_code     INT NULL,
    hdl_phone_country_code  INT NULL,
    hdl_mobile  TEXT        NULL,

    hdl_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

-- CrewMemBer
CREATE TABLE "cmb"
(
    occ_id      INT     NOT NULL,       -- FK - occupation
    cts_id      INT     NOT NULL,       -- FK - citizenship

    cmb_id      SERIAL  NOT NULL PRIMARY KEY,
    cmb_name    TEXT    NULL,
    cmb_email   TEXT    NULL,
    cmb_nick    TEXT    NULL,
    cmb_dob_dt  TIMESTAMP       NULL,   -- Day Of Birth

    cmb_phone   TEXT            NULL,
    cmb_phone_city_code     INT NULL,
    cmb_phone_country_code  INT NULL,
    cmb_mobile  TEXT            NULL,
    
    cmb_cdac    INT     NOT NULL,       -- License
    cmb_atp     INT         NULL,       -- License
    cmb_cp      INT         NULL,       -- License

    cmb_ppt_nbr     TEXT      NULL UNIQUE,  -- PassPorT NumBeR
    cmb_ppt_exp_dt  TIMESTAMP   NULL,       -- PassPorT Expiration Date
    cmb_ppt_issue_dt TIMESTAMP  NULL,       -- PassPorT Issue Date

    cmb_med_exp_dt  TIMESTAMP NULL,
    cmb_ifr_exp_dt  TIMESTAMP NULL,
    cmb_type_exp_dt TIMESTAMP NULL,
    cmb_cat2_exp_dt TIMESTAMP NULL,

    cmb_add_dt      TIMESTAMP   NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "citizenship"
(
    cts_id      SERIAL  NOT NULL PRIMARY KEY,
    cts_name    TEXT    NOT NULL    UNIQUE,
    cts_desc    TEXT        NULL,
    cts_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "occupation"
(
    occ_id      SERIAL  NOT NULL PRIMARY KEY,

    occ_name    TEXT    NOT NULL,
    occ_plain   TEXT        NULL,   -- For Dummies
    occ_desc    TEXT        NULL,
    occ_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "operator"
(
    cty_id      INT     NOT NULL,       -- FK - country

    opr_id      SERIAL  NOT NULL PRIMARY KEY,
    opr_name    TEXT    NOT NULL,
    opr_desc    TEXT        NULL,
    opr_address TEXT    NOT NULL,
    opr_city    TEXT    NOT NULL,

    opr_phone_country_code  INT NULL,
    opr_phone_city_code     INT NULL,
    opr_hangar_phone    TEXT    NULL,
    opr_hangar_fax      TEXT    NULL,
    opr_hangar_mobile   TEXT    NULL,
    opr_coffice_phone   TEXT    NULL,
    opr_coffice_fax     TEXT    NULL,
    opr_coffice_email   TEXT    NULL,

    opr_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "pax"
(
    cts_id      INT     NOT NULL,       -- FK - citizenship

    pax_id      SERIAL  NOT NULL PRIMARY KEY,
    pax_name    TEXT    NULL,
    pax_desc    TEXT    NULL,
    pax_dob_dt  TIMESTAMP   NULL,
    pax_email   TEXT        NULL,

    pax_phone   TEXT    NULL,
    pax_phone_city_code     INT NULL,
    pax_phone_country_code  INT NULL,
    pax_mobile  TEXT    NULL,

    pax_ppt_nbr     TEXT      NULL UNIQUE,  -- PassPorT NumBeR
    pax_ppt_exp_dt  TIMESTAMP   NULL,       -- PassPorT Expiration Date
    pax_ppt_issue_dt TIMESTAMP  NULL,       -- PassPorT Issue Date

    pax_add_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "service"
(
    srv_id      SERIAL  NOT NULL PRIMARY KEY,
    srv_name    TEXT    NULL,
    srv_desc    TEXT    NULL,
    srv_provider    char(1) NOT NULL,
    /*
        H: Handler
        P: Permit
    */
    srv_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "permit"
(
    pmt_id      SERIAL  NOT NULL PRIMARY KEY,
    pmt_name    TEXT        NULL,
    pmt_desc    TEXT        NULL,

    pmt_email   TEXT        NULL,
    pmt_sita    TEXT        NULL,
    pmt_aftn    TEXT        NULL,

    pmt_fax     TEXT        NULL,
    pmt_phone   TEXT        NULL,
    pmt_phone_city_code     INT NULL,
    pmt_phone_country_code  INT NULL,
    pmt_mobile  TEXT        NULL,

    pmt_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "visatype"
(
    vst_id      SERIAL  NOT NULL PRIMARY KEY,
    vst_name    TEXT    NOT NULL,
    vst_desc    TEXT        NULL,
    vst_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "link"
(
    usr_id      INT     NOT NULL,   -- FK - user

    lnk_id      SERIAL  NOT NULL PRIMARY KEY,
    lnk_name    TEXT    NOT NULL,
    lnk_url     TEXT    NOT NULL,
    lnk_desc    TEXT        NULL,

    lnk_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "syslink"
(
    usr_id      INT     NOT NULL,   -- FK - user

    slk_id      SERIAL  NOT NULL PRIMARY KEY,
    slk_name    TEXT    NOT NULL,
    slk_url     TEXT    NOT NULL,
    slk_desc    TEXT        NULL,

    slk_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------------------------------------------------------------------------------------------------
    -- RelationShips
        -- FoodType X Food
CREATE TABLE "fdt_fod"
(
    fdt_id      INT     NOT NULL,       -- FK - foodtype
    fod_id      INT     NOT NULL,       -- FK - food

    fdd_id      SERIAL  NOT NULL    PRIMARY KEY,

    fdd_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(fdt_id, fod_id)
);

        -- Pax X VisaType
CREATE TABLE "pax_vst"
(
    pax_id      INT     NOT NULL,       -- FK - pax
    vst_id      INT     NOT NULL,       -- FK - visatype

    pvs_id      SERIAL  NOT NULL    PRIMARY KEY,
    pvs_issue_dt        TIMESTAMP   NULL,
    pvs_exp_dt  TIMESTAMP   NULL,

    pvs_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(pax_id, vst_id)
);

        -- Leg X Pax
CREATE TABLE "leg_pax"
(
    leg_id      INT     NOT NULL,       -- FK - leg
    pax_id      INT     NOT NULL,       -- FK - pax

    lpa_id      SERIAL  NOT NULL    PRIMARY KEY,

    lpa_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(leg_id, pax_id)
);

        -- Caterer
            -- Caterer X Contact
CREATE TABLE "cat_ctc"
(
    cat_id      INT     NOT NULL,       -- FK - caterer
    ctc_id      INT     NOT NULL,       -- FK - contact

    cct_id      SERIAL  NOT NULL    PRIMARY KEY,

    cct_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(cat_id, ctc_id)
);

        -- Handler
            -- Handler X Contact
CREATE TABLE "hdl_ctc"
(
    hdl_id      INT     NOT NULL,       -- FK - handler
    ctc_id      INT     NOT NULL,       -- FK - contact

    hct_id      SERIAL  NOT NULL    PRIMARY KEY,
    
    hct_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(hdl_id, ctc_id)
);

        -- Permit
            -- Permit X Contact
CREATE TABLE "pmt_ctc"
(
    pmt_id      INT     NOT NULL,       -- FK - permit
    ctc_id      INT     NOT NULL,       -- FK - contact

    pct_id      SERIAL  NOT NULL    PRIMARY KEY,
    
    pct_add_dt  TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(pmt_id, ctc_id)
);

    -- Docs
        -- File
CREATE TABLE "file"
(
    fil_id          SERIAL  NOT NULL    PRIMARY KEY,
    fil_fake_name   TEXT    NOT NULL,
    fil_desc        TEXT    NULL,
    fil_type        char(1) NOT NULL    DEFAULT 'M',
        -- I - Image
        -- P - PDF
        -- M - Misc
    fil_add_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP 
);

-- -------------------------------------------------------------------------------------------------------------------
-- Sequence
CREATE SEQUENCE "seq_trip" START 55;

-- -------------------------------------------------------------------------------------------------------------------
-- Views

-- Admin
CREATE VIEW "view_active_usr" AS
    SELECT
        *
    FROM
        "user"
    WHERE
        usr_active = '1' AND
        usr_expire_dt IS NULL OR usr_expire_dt >= CURRENT_TIMESTAMP;

CREATE VIEW "view_usr_fnc" AS
    SELECT DISTINCT
        usr_id,
        fnc_name 
    FROM
        "function"
        NATURAL JOIN "grp_fnc"
        NATURAL JOIN "grp_usr"
        NATURAL JOIN "view_active_usr";

CREATE VIEW "view_grp" AS
    SELECT
        grp_id,
        grp_name,
        fnc_id,
        fnc_name,
        usr_id,
        usr_name,
        usr_login
    FROM
        "group"
        NATURAL LEFT OUTER JOIN ("grp_fnc" NATURAL JOIN "function")
        NATURAL LEFT OUTER JOIN ("grp_usr" NATURAL JOIN "view_active_usr");

CREATE VIEW "view_page" AS
    SELECT
        SUBSTRING(fnc_name FROM '^[^:]+') AS page
    FROM
        "function"
    WHERE
        SUBSTRING(fnc_name FROM '^[^:]+') !~ '^(docs|itinerary|pax)'
    UNION
    (
        SELECT
            SUBSTRING(fnc_name FROM '^[^ ]+ [^ ]+') AS page
        FROM
            "function"
    )
    ORDER BY page;

-- Data
CREATE VIEW "view_acf" AS
    SELECT
        aircraft.*,
        apt_name,
        opr_name
    FROM
        aircraft
        NATURAL JOIN "airport"
        NATURAL LEFT OUTER JOIN "operator";

CREATE VIEW "view_apt" AS
    SELECT
        airport.*,
        cat_name,
        hdl_name,
        pmt_name,
        cty_name
    FROM
        airport
        NATURAL LEFT OUTER JOIN "caterer"
        NATURAL LEFT OUTER JOIN "handler"
        NATURAL LEFT OUTER JOIN "permit"
        NATURAL LEFT OUTER JOIN "country";

CREATE VIEW "view_cmb" AS
    SELECT
        cmb.*,
        occ_name,
        cmb_name || ' (' || occ_name || ')' AS cmb_occ_name
    FROM
        cmb
        NATURAL LEFT OUTER JOIN "occupation";

CREATE VIEW "view_opr" AS
    SELECT
        operator.*,
        cty_name
    FROM
        operator
        NATURAL LEFT OUTER JOIN "country";


-- Pax
CREATE VIEW "view_pax" AS
    SELECT
        pax.*,
        cts_name,
        pax_name || ' (' || cts_name || ')' AS pax_cts_name
    FROM
        pax 
        NATURAL LEFT OUTER JOIN "citizenship";


CREATE VIEW "view_pax_vst" AS
    SELECT
        pvs_id, 
        pvs_issue_dt,
        pvs_exp_dt,
        pax_name,
        pax_name || ' (' || cts_name || ')' AS pax_cts_name,
        vst_name
    FROM
        pax_vst
        NATURAL JOIN 
        (
            "pax"
            NATURAL LEFT OUTER JOIN "visatype"
            NATURAL LEFT OUTER JOIN "citizenship"
        );

-- Agent
CREATE VIEW "view_cat_ctc" AS
    SELECT
        cat_id,
        ctc_id,
        ctc_name
    FROM
        "cat_ctc"
        NATURAL JOIN "contact";

CREATE VIEW "view_hdl_ctc" AS
    SELECT
        hdl_id,
        ctc_id,
        ctc_name
    FROM
        "hdl_ctc"
        NATURAL JOIN "contact";

CREATE VIEW "view_pmt_ctc" AS
    SELECT
        pmt_id,
        ctc_id,
        ctc_name
    FROM
        "pmt_ctc"
        NATURAL JOIN "contact";

-- Itinerary
CREATE VIEW "view_leg_pax" AS
    SELECT
        leg_id,
        pax_id,
        pax_cts_name
    FROM
        "leg_pax"
        NATURAL JOIN "view_pax";

CREATE VIEW "view_catering_order" AS    
    SELECT
        leg_id,

    -- (1) Caterer Data
        cat_name,
        cat_phone_country_code,
        cat_phone_city_code,
        cat_phone,
        cat_fax,
        cat_mobile,

    -- (2) Operator
        opr_name,
        opr_address,
        opr_city,
        cty_name AS opr_country, -- Country
        opr_phone_country_code,
        opr_phone_city_code,
        opr_coffice_phone,
        opr_coffice_fax,
        opr_hangar_phone,
        opr_hangar_fax,
        opr_hangar_mobile,
        opr_coffice_email,

    -- (3) Aircraft
        acf_model,
        acf_registry,
        acf_mtow,
        acf_noise_cert,
        acf_satcom_country_code,
        acf_satcom_city_code,
        acf_satcom
    FROM
        "leg"
        NATURAL JOIN "aircraft"
        NATURAL JOIN ("operator" NATURAL JOIN "country")
        JOIN         ("airport"  NATURAL JOIN "caterer") ON (leg.apt_id_arrive = airport.apt_id);

CREATE VIEW "view_handling_request" AS    
    SELECT
        leg_id,

    -- (1) Handler Data
        hdl_name,
        hdl_phone_country_code,
        hdl_phone_city_code,
        hdl_phone,
        hdl_fax,
        hdl_mobile,
        hdl_arinc,

    -- (2) Operator
        opr_name,
        opr_address,
        opr_city,
        cty_name AS opr_country, -- Country
        opr_phone_country_code,
        opr_phone_city_code,
        opr_coffice_phone,
        opr_coffice_fax,
        opr_hangar_phone,
        opr_hangar_fax,
        opr_hangar_mobile,
        opr_coffice_email,

    -- (3) Aircraft
        acf_model,
        acf_registry,
        acf_mtow,
        acf_noise_cert,
        acf_satcom_country_code,
        acf_satcom_city_code,
        acf_satcom,

    -- (4) Crew Member (PIC - Pilot In Command)
        -- (4a) PIC - Pilot In Command (Captain)
        pic.cmb_name    AS pic_cmb_name,
        pic.cts_name    AS pic_cmb_cts_name,
        pic.cmb_ppt_nbr AS pic_cmb_ppt_nbr,
        TO_CHAR(pic.cmb_dob_dt, 'DDMONYY')  AS pic_cmb_dob_dt,
        pic.cmb_cdac    AS pic_cmb_cdac,
        pic.cmb_atp     AS pic_cmb_atp,
        pic.cmb_cp      AS pic_cmb_cp,

        -- (4b) SIC - Second In Command (First Officer)
        sic.cmb_name    AS sic_cmb_name,
        sic.cts_name    AS sic_cmb_cts_name,
        sic.cmb_ppt_nbr AS sic_cmb_ppt_nbr,
        TO_CHAR(sic.cmb_dob_dt, 'DDMONYY')  AS sic_cmb_dob_dt,
        sic.cmb_cdac    AS sic_cmb_cdac,
        sic.cmb_atp     AS sic_cmb_atp,
        sic.cmb_cp      AS sic_cmb_cp,

        -- (4c) Extra1 - (Copilot 1)
        ex1.cmb_name    AS ex1_cmb_name,
        ex1.cts_name    AS ex1_cmb_cts_name,
        ex1.cmb_ppt_nbr AS ex1_cmb_ppt_nbr,
        TO_CHAR(ex1.cmb_dob_dt, 'DDMONYY')  AS ex1_cmb_dob_dt,
        ex1.cmb_cdac    AS ex1_cmb_cdac,
        ex1.cmb_atp     AS ex1_cmb_atp,
        ex1.cmb_cp      AS ex1_cmb_cp,

        -- (4d) Extra2 - (Copilot 2)
        ex2.cmb_name    AS ex2_cmb_name,
        ex2.cts_name    AS ex2_cmb_cts_name,
        ex2.cmb_ppt_nbr AS ex2_cmb_ppt_nbr,
        TO_CHAR(ex2.cmb_dob_dt, 'DDMONYY')  AS ex2_cmb_dob_dt,
        ex2.cmb_cdac    AS ex2_cmb_cdac,
        ex2.cmb_atp     AS ex2_cmb_atp,
        ex2.cmb_cp      AS ex2_cmb_cp
    FROM
        "leg"
        NATURAL JOIN "aircraft"
        NATURAL JOIN ("operator" NATURAL JOIN "country")
        JOIN         ("airport"  NATURAL JOIN "handler")    ON (leg.apt_id_arrive = airport.apt_id)
        JOIN         ("cmb" NATURAL JOIN "citizenship") pic ON (leg.cmb_id_pic = pic.cmb_id)  
        JOIN         ("cmb" NATURAL JOIN "citizenship") sic ON (leg.cmb_id_sic = sic.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex1 ON (leg.cmb_id_extra1 = ex1.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex2 ON (leg.cmb_id_extra2 = ex2.cmb_id);

CREATE VIEW "view_permit_request" AS    
    SELECT
        leg_id,
 
    -- (1) Handler Data
        pmt_name,
        pmt_phone_country_code,
        pmt_phone_city_code,
        pmt_phone,
        pmt_fax,
        pmt_mobile,

    -- (2) Operator
        opr_name,
        opr_address,
        opr_city,
        cty_name AS opr_country, -- Country
        opr_phone_country_code,
        opr_phone_city_code,
        opr_coffice_phone,
        opr_coffice_fax,
        opr_hangar_phone,
        opr_hangar_fax,
        opr_hangar_mobile,
        opr_coffice_email,

    -- (3) Aircraft
        acf_model,
        acf_registry,
        acf_mtow,
        acf_noise_cert,
        acf_satcom_country_code,
        acf_satcom_city_code,
        acf_satcom,

    -- (4) Crew Member (PIC - Pilot In Command)
        -- (4a) PIC - Pilot In Command (Captain)
        pic.cmb_name    AS pic_cmb_name,
        pic.cts_name    AS pic_cmb_cts_name,
        pic.cmb_ppt_nbr AS pic_cmb_ppt_nbr,
        TO_CHAR(pic.cmb_dob_dt, 'DDMONYY')  AS pic_cmb_dob_dt,
        pic.cmb_cdac    AS pic_cmb_cdac,
        pic.cmb_atp     AS pic_cmb_atp,
        pic.cmb_cp      AS pic_cmb_cp,

        -- (4b) SIC - Second In Command (First Officer)
        sic.cmb_name    AS sic_cmb_name,
        sic.cts_name    AS sic_cmb_cts_name,
        sic.cmb_ppt_nbr AS sic_cmb_ppt_nbr,
        TO_CHAR(sic.cmb_dob_dt, 'DDMONYY')  AS sic_cmb_dob_dt,
        sic.cmb_cdac    AS sic_cmb_cdac,
        sic.cmb_atp     AS sic_cmb_atp,
        sic.cmb_cp      AS sic_cmb_cp,

        -- (4c) Extra1 - (Copilot 1)
        ex1.cmb_name    AS ex1_cmb_name,
        ex1.cts_name    AS ex1_cmb_cts_name,
        ex1.cmb_ppt_nbr AS ex1_cmb_ppt_nbr,
        TO_CHAR(ex1.cmb_dob_dt, 'DDMONYY')  AS ex1_cmb_dob_dt,
        ex1.cmb_cdac    AS ex1_cmb_cdac,
        ex1.cmb_atp     AS ex1_cmb_atp,
        ex1.cmb_cp      AS ex1_cmb_cp,

        -- (4d) Extra2 - (Copilot 2)
        ex2.cmb_name    AS ex2_cmb_name,
        ex2.cts_name    AS ex2_cmb_cts_name,
        ex2.cmb_ppt_nbr AS ex2_cmb_ppt_nbr,
        TO_CHAR(ex2.cmb_dob_dt, 'DDMONYY')  AS ex2_cmb_dob_dt,
        ex2.cmb_cdac    AS ex2_cmb_cdac,
        ex2.cmb_atp     AS ex2_cmb_atp,
        ex2.cmb_cp      AS ex2_cmb_cp
    FROM
        "leg"
        NATURAL JOIN "aircraft"
        NATURAL JOIN ("operator" NATURAL JOIN "country")
        JOIN         ("airport"  NATURAL JOIN "permit") ON (leg.apt_id_arrive = airport.apt_id)
        JOIN         ("cmb" NATURAL JOIN "citizenship") pic ON (leg.cmb_id_pic = pic.cmb_id)  
        JOIN         ("cmb" NATURAL JOIN "citizenship") sic ON (leg.cmb_id_sic = sic.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex1 ON (leg.cmb_id_extra1 = ex1.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex2 ON (leg.cmb_id_extra2 = ex2.cmb_id);

CREATE VIEW "view_general_declaration" AS    
    SELECT
        leg_id,

    -- (1) Operator
        opr_name,
        opr_address,
        opr_city,
        cty_name AS opr_country, -- Country
        opr_phone_country_code,
        opr_phone_city_code,
        opr_coffice_phone,
        opr_coffice_fax,
        opr_hangar_phone,
        opr_hangar_fax,
        opr_hangar_mobile,
        opr_coffice_email,

    -- (2) Aircraft
        acf_model,
        acf_registry,
        acf_mtow,
        acf_noise_cert,
        acf_satcom_country_code,
        acf_satcom_city_code,
        acf_satcom,

    -- (3) Crew Member (PIC - Pilot In Command)
        -- (3a) PIC - Pilot In Command (Captain)
        pic.cmb_name    AS pic_cmb_name,
        pic.cts_name    AS pic_cmb_cts_name,
        pic.cmb_ppt_nbr AS pic_cmb_ppt_nbr,
        TO_CHAR(pic.cmb_dob_dt, 'DDMONYY')  AS pic_cmb_dob_dt,
        pic.cmb_cdac    AS pic_cmb_cdac,
        pic.cmb_atp     AS pic_cmb_atp,
        pic.cmb_cp      AS pic_cmb_cp,

        -- (3b) SIC - Second In Command (First Officer)
        sic.cmb_name    AS sic_cmb_name,
        sic.cts_name    AS sic_cmb_cts_name,
        sic.cmb_ppt_nbr AS sic_cmb_ppt_nbr,
        TO_CHAR(sic.cmb_dob_dt, 'DDMONYY')  AS sic_cmb_dob_dt,
        sic.cmb_cdac    AS sic_cmb_cdac,
        sic.cmb_atp     AS sic_cmb_atp,
        sic.cmb_cp      AS sic_cmb_cp,

        -- (3c) Extra1 - (Copilot 1)
        ex1.cmb_name    AS ex1_cmb_name,
        ex1.cts_name    AS ex1_cmb_cts_name,
        ex1.cmb_ppt_nbr AS ex1_cmb_ppt_nbr,
        TO_CHAR(ex1.cmb_dob_dt, 'DDMONYY')  AS ex1_cmb_dob_dt,
        ex1.cmb_cdac    AS ex1_cmb_cdac,
        ex1.cmb_atp     AS ex1_cmb_atp,
        ex1.cmb_cp      AS ex1_cmb_cp,

        -- (3d) Extra2 - (Copilot 2)
        ex2.cmb_name    AS ex2_cmb_name,
        ex2.cts_name    AS ex2_cmb_cts_name,
        ex2.cmb_ppt_nbr AS ex2_cmb_ppt_nbr,
        TO_CHAR(ex2.cmb_dob_dt, 'DDMONYY')  AS ex2_cmb_dob_dt,
        ex2.cmb_cdac    AS ex2_cmb_cdac,
        ex2.cmb_atp     AS ex2_cmb_atp,
        ex2.cmb_cp      AS ex2_cmb_cp
    FROM
        "leg"
        NATURAL JOIN "aircraft"
        NATURAL JOIN ("operator" NATURAL JOIN "country")
        JOIN         ("cmb" NATURAL JOIN "citizenship") pic ON (leg.cmb_id_pic = pic.cmb_id)  
        JOIN         ("cmb" NATURAL JOIN "citizenship") sic ON (leg.cmb_id_sic = sic.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex1 ON (leg.cmb_id_extra1 = ex1.cmb_id)
        LEFT JOIN    ("cmb" NATURAL JOIN "citizenship") ex2 ON (leg.cmb_id_extra2 = ex2.cmb_id);

-- -------------------------------------------------------------------------------------------------------------------
-- Foreign Keys
    -- Admin
ALTER TABLE "grp_usr" ADD FOREIGN KEY (grp_id) REFERENCES "group" (grp_id)      ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "grp_usr" ADD FOREIGN KEY (usr_id) REFERENCES "user"  (usr_id)      ON DELETE CASCADE ON UPDATE CASCADE;
        
ALTER TABLE "grp_fnc" ADD FOREIGN KEY (grp_id) REFERENCES "group" (grp_id)      ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "grp_fnc" ADD FOREIGN KEY (fnc_id) REFERENCES "function" (fnc_id)   ON DELETE CASCADE ON UPDATE CASCADE;

    -- Data
ALTER TABLE "aircraft" ADD FOREIGN KEY (apt_id) REFERENCES "airport" (apt_id)   ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "aircraft" ADD FOREIGN KEY (opr_id) REFERENCES "operator" (opr_id)  ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "airport" ADD FOREIGN KEY (cat_id) REFERENCES "caterer" (cat_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "airport" ADD FOREIGN KEY (hdl_id) REFERENCES "handler" (hdl_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "airport" ADD FOREIGN KEY (pmt_id) REFERENCES "permit"  (pmt_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "airport" ADD FOREIGN KEY (cty_id) REFERENCES "country" (cty_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "fdt_fod" ADD FOREIGN KEY (fdt_id) REFERENCES "foodtype" (fdt_id)   ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "fdt_fod" ADD FOREIGN KEY (fod_id) REFERENCES "food" (fod_id)       ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE "cmb" ADD FOREIGN KEY (occ_id) REFERENCES "occupation" (occ_id)     ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "cmb" ADD FOREIGN KEY (cts_id) REFERENCES "citizenship" (cts_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "operator" ADD FOREIGN KEY (cty_id) REFERENCES "country" (cty_id)   ON DELETE NO ACTION ON UPDATE NO ACTION;

    -- Agent
        -- Caterer
ALTER TABLE "cat_ctc" ADD FOREIGN KEY (cat_id) REFERENCES "caterer" (cat_id)    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "cat_ctc" ADD FOREIGN KEY (ctc_id) REFERENCES "contact" (ctc_id)    ON DELETE CASCADE ON UPDATE CASCADE;


        -- Handler
ALTER TABLE "hdl_ctc" ADD FOREIGN KEY (hdl_id) REFERENCES "handler" (hdl_id)    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "hdl_ctc" ADD FOREIGN KEY (ctc_id) REFERENCES "contact" (ctc_id)    ON DELETE CASCADE ON UPDATE CASCADE;

        -- Permit
ALTER TABLE "pmt_ctc" ADD FOREIGN KEY (pmt_id) REFERENCES "permit" (pmt_id)     ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "pmt_ctc" ADD FOREIGN KEY (ctc_id) REFERENCES "contact" (ctc_id)    ON DELETE CASCADE ON UPDATE CASCADE;

    -- Pax
ALTER TABLE "pax" ADD FOREIGN KEY (cts_id) REFERENCES "citizenship" (cts_id)    ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "pax_vst" ADD FOREIGN KEY (pax_id) REFERENCES "pax" (pax_id)        ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "pax_vst" ADD FOREIGN KEY (vst_id) REFERENCES "visatype" (vst_id)   ON DELETE CASCADE ON UPDATE CASCADE;

    -- Itinerary
ALTER TABLE "leg" ADD FOREIGN KEY (acf_id) REFERENCES "aircraft" (acf_id)       ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "leg" ADD FOREIGN KEY (apt_id_arrive) REFERENCES "airport" (apt_id) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "leg" ADD FOREIGN KEY (apt_id_depart) REFERENCES "airport" (apt_id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "leg" ADD FOREIGN KEY (cmb_id_pic) REFERENCES "cmb" (cmb_id)        ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "leg" ADD FOREIGN KEY (cmb_id_sic) REFERENCES "cmb" (cmb_id)        ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "leg" ADD FOREIGN KEY (cmb_id_extra1) REFERENCES "cmb" (cmb_id)     ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "leg" ADD FOREIGN KEY (cmb_id_extra2) REFERENCES "cmb" (cmb_id)     ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE "leg_pax" ADD FOREIGN KEY (leg_id) REFERENCES "leg" (leg_id)        ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "leg_pax" ADD FOREIGN KEY (pax_id) REFERENCES "pax" (pax_id)        ON DELETE CASCADE ON UPDATE CASCADE;

    -- BookMark
ALTER TABLE "link" ADD FOREIGN KEY (usr_id) REFERENCES "user" (usr_id)          ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "syslink" ADD FOREIGN KEY (usr_id) REFERENCES "user" (usr_id)       ON DELETE CASCADE ON UPDATE CASCADE;


-- -------------------------------------------------------------------------------------------------------------------
-- Functions
CREATE FUNCTION func_default_acf() RETURNS TRIGGER AS '
DECLARE
BEGIN
    IF (NEW.acf_default = 1) AND (OLD.acf_default = 0) THEN
        UPDATE "aircraft" SET acf_default = 0 WHERE acf_default = 1;
    END IF;

    RETURN NEW;
END;'
LANGUAGE 'plpgsql';

--    Find Similar Leg
--    Usage:  SELECT func_similar_leg(apt_depart_id, apt_arrive_id);
--    Return: String = leg_ete_i|leg_distance|leg_fuel
CREATE FUNCTION func_similar_leg(int, int) RETURNS text AS '
DECLARE
    -- Args
    depart  ALIAS FOR $1;
    arrive  ALIAS FOR $2;

    -- Auxiliar Vars
    ret_row record;
    ret     text;
BEGIN
    SELECT INTO ret_row
        le.leg_ete_i || ''|'' ||

        -- Just concatenate leg_distance if It Is Not Null
        CASE WHEN (ld.leg_distance IS NOT NULL) THEN
            ld.leg_distance  || ''|''
        ELSE
            ''|''
        END ||

        -- Just concatenate leg_fuel if It Is Not Null
        CASE WHEN (lf.leg_fuel IS NOT NULL) THEN
            lf.leg_fuel
        ELSE
            0
        END AS data
    FROM
        "leg" le
        LEFT OUTER JOIN "leg" ld ON (ld.leg_distance IS NOT NULL)
        LEFT OUTER JOIN "leg" lf ON (lf.leg_fuel     IS NOT NULL)
    WHERE
        le.apt_id_depart = depart AND le.apt_id_arrive = arrive AND
        (
            (ld.apt_id_depart = depart AND ld.apt_id_arrive = arrive) OR
            (ld.apt_id_depart IS NULL)
        )
        AND
        (
            (lf.apt_id_depart = depart AND lf.apt_id_arrive = arrive) OR
            (lf.apt_id_depart IS NULL)
        )
    ORDER BY
        le.leg_trip, le.leg_keeptrack_dt DESC
    LIMIT 1;

    IF FOUND THEN
        ret := ret_row.data;
    ELSE
        ret := NULL;
    END IF;

    RETURN ret;
END;'
LANGUAGE 'plpgsql';

--  List Legs
--  Usage:
--      SELECT
--          *
--      FROM
--          func_list_leg(search_field, search_value, search_order, search_order_type, limit_quantity, limit_offset) AS 
--          (
--              apt_name_depart text,
--              apt_name_arrive text,
--              apt_timezone_depart text,
--              apt_timezone_arrive text,
--    
--              leg_etd_dt text,
--              leg_etd_localtime_dt text,
--    
--              leg_ete_i interval,
--    
--              leg_eta_dt text,
--              leg_eta_localtime_dt text,
--              leg_groundtime_i interval,
--    
--              leg_id integer,
--              leg_trip integer,
--              leg_distance integer,
--              leg_wind integer,
--              leg_fuel integer,
--              leg_remarks text
--          );
--
--        Return: SETOF RECORD
-- Obs: ORDERBY is done but unavailable, for now
CREATE FUNCTION func_list_leg(text, text, text, text, integer, integer) RETURNS SETOF record AS '
DECLARE
    -- Args
    field           ALIAS FOR $1;
    value           ALIAS FOR $2;
--    orderby         ALIAS FOR $3;
--    ordertype       ALIAS FOR $4;
    limit_qtd       ALIAS FOR $5;
    limit_offset    ALIAS FOR $6;

    -- Control Vars
    ret             record;
    query           text;

    -- Auxiliar Vars
    aux_tz          interval;
    aux_gt          record;
    aux_where       text;
    aux_orderby     text;
    aux_ordertype   text;
    aux_limit       text;
BEGIN
    IF field IS NULL OR LENGTH(field) = 0 THEN
        aux_where := '''';
    ELSIF value IS NULL OR LENGTH(value) = 0 THEN
        aux_where := '' WHERE '' || field || '' ILIKE ''''%'' || value || ''%'''' OR '' || field || '' IS NULL'';
    ELSE
        aux_where := '' WHERE '' || field || '' ILIKE ''''%'' || value || ''%'''''';
    END IF;

--    IF ordertype IS NULL OR LENGTH(ordertype) = 0 THEN
        aux_ordertype := ''ASC'';
--    ELSE
--        aux_ordertype := ordertype;
--    END IF;

--    IF orderby IS NULL OR LENGTH(orderby) = 0 THEN
        aux_orderby := '' ORDER BY leg_trip, leg_keeptrack_dt ASC'';
--    ELSE
--        aux_orderby := '' ORDER BY LOWER('' || orderby || '') '' || ordertype;
--    END IF;

    IF limit_qtd IS NULL OR limit_qtd <= 0 THEN
        aux_limit := ''''; 
    ELSE
        aux_limit := '' LIMIT '' || limit_qtd  || '' OFFSET '' || limit_offset;
    END IF;

    query := ''
        SELECT
            -- Airports
                -- (Depart) Concat ICAO with apt_name if NOT NULL
            CASE WHEN (apt_depart.apt_icao IS NOT NULL) THEN
                apt_depart.apt_name || '''' ('''' || apt_depart.apt_icao || '''')''''
            ELSE
                apt_depart.apt_name
            END AS apt_name_depart,

                -- (Arrive) Concat ICAO with apt_name if NOT NULL
            CASE WHEN (apt_arrive.apt_icao IS NOT NULL) THEN
                apt_arrive.apt_name || '''' ('''' || apt_arrive.apt_icao || '''')''''
            ELSE
                apt_arrive.apt_name
            END AS apt_name_arrive,

            -- TimeZones
                -- (Depart) Daylight Saving Time?
            CASE WHEN (CURRENT_TIMESTAMP BETWEEN apt_depart.apt_dst_start_dt AND apt_depart.apt_dst_end_dt) THEN
                (apt_depart.apt_timezone::real + 1)::text || ''''*''''
            ELSE
                apt_depart.apt_timezone
            END AS apt_timezone_depart,
                
                -- (Arrive) Daylight Saving Time?
            CASE WHEN (CURRENT_TIMESTAMP BETWEEN apt_arrive.apt_dst_start_dt AND apt_arrive.apt_dst_end_dt) THEN
                (apt_arrive.apt_timezone::real + 1)::text || ''''*''''
            ELSE
                apt_arrive.apt_timezone
            END AS apt_timezone_arrive,

            -- ETD (Estimated Time of Depart)
            leg_keeptrack_dt::text AS leg_etd_dt,
            NULL::text AS leg_etd_localtime_dt,

            -- ETE (Estimated Time of Enroute) 
            leg_ete_i::interval,

            -- ETA (Estimated Time of Arrive)
            (leg_keeptrack_dt::timestamp + leg_ete_i::time)::text AS leg_eta_dt,
            NULL::text AS leg_eta_localtime_dt,

            -- Ground Time
            leg_groundtime_i::interval,

            -- Misc
            leg_id AS id,
            leg_trip,
            leg_distance,
            leg_wind,
            leg_fuel,
            leg_remarks
        FROM
            "leg"
            LEFT JOIN "airport" apt_depart ON (apt_id_depart = apt_depart.apt_id)
            LEFT JOIN "airport" apt_arrive ON (apt_id_arrive = apt_arrive.apt_id)
        '' || aux_where || aux_orderby || aux_limit;

    -- RAISE NOTICE ''Query: %'', query;

    FOR ret IN EXECUTE query LOOP
        -- Applying TimeZone To Achieve LocalTime
            -- Depart
        IF POSITION(''*'' IN ret.apt_timezone_depart) > 0 THEN
            aux_tz := (''1 hour''::interval * SUBSTRING(ret.apt_timezone_depart, 1, LENGTH(ret.apt_timezone_depart) - 1)::real)::interval;
        ELSE
            aux_tz := (''1 hour''::interval * ret.apt_timezone_depart::real)::interval;
        END IF;
        ret.leg_etd_localtime_dt := TO_CHAR((ret.leg_etd_dt::timestamp + aux_tz::interval)::timestamp, ''Dy DDMONYYYY @ HH24:MI'');

            -- Arrive
        IF POSITION(''*'' IN ret.apt_timezone_arrive) > 0 THEN
            aux_tz := (''1 hour''::interval * SUBSTRING(ret.apt_timezone_arrive, 1, LENGTH(ret.apt_timezone_arrive) - 1)::real)::interval;
        ELSE
            aux_tz := (''1 hour''::interval * ret.apt_timezone_arrive::real)::interval;
        END IF;
        ret.leg_eta_localtime_dt := TO_CHAR((ret.leg_eta_dt::timestamp + aux_tz::interval)::timestamp, ''Dy DDMONYYYY @ HH24:MI'');

        -- GroundTime
        IF ret.leg_groundtime_i IS NULL THEN
            SELECT INTO aux_gt
                AGE(leg_keeptrack_dt::timestamp, ret.leg_eta_dt::timestamp) AS leg_groundtime_i
            FROM
                "leg"
            WHERE
                leg_keeptrack_dt > ret.leg_etd_dt
            ORDER BY
                leg_keeptrack_dt
            LIMIT 1;

            IF FOUND THEN
                ret.leg_groundtime_i := aux_gt.leg_groundtime_i;
            END IF;
        END IF;

        -- GMT Time
        ret.leg_etd_dt := TO_CHAR(ret.leg_etd_dt::timestamp, ''DDMONYY @ HH24:MI'')::text || '' GMT'';
        ret.leg_eta_dt := TO_CHAR(ret.leg_eta_dt::timestamp, ''DDMONYY @ HH24:MI'')::text || '' GMT'';

        RETURN NEXT ret;
    END LOOP; 
    RETURN ret;
END;
' LANGUAGE 'plpgsql';

--  List Legs for PDF Generation
--  ids list should be ':' separated
--  Usage:
--      SELECT
--          *
--      FROM
--          func_list_leg_pdf(ids) AS 
--          (
--              apt_name_depart text,
--              apt_name_arrive text,
--              apt_timezone_depart text,
--              apt_timezone_arrive text,
--    
--              leg_etd_dt text,
--              leg_etd_localtime_dt text,
--    
--              leg_eta_dt text,
--              leg_eta_localtime_dt text,
--          );
--
--        Return: SETOF RECORD
CREATE FUNCTION func_list_leg_pdf(text) RETURNS SETOF record AS '
DECLARE
    -- Args
    ids             ALIAS FOR $1;

    -- Control Vars
    ret             record;
    query           text;
    ids_len         integer;
    occurence       integer NOT NULL DEFAULT 1;
    
    -- Auxiliar Vars
    aux_tz          intervaL;
    aux_id          text;
    aux_ids         text;
    i               integer NOT NULL DEFAULT 1;
BEGIN
    query := ''
        SELECT
            -- Airports
                -- (Depart) Concat ICAO with apt_name if NOT NULL
            CASE WHEN (apt_depart.apt_icao IS NOT NULL) THEN
                apt_depart.apt_name || '''' ('''' || apt_depart.apt_icao || '''')''''
            ELSE
                apt_depart.apt_name
            END AS apt_name_depart,

                -- (Arrive) Concat ICAO with apt_name if NOT NULL
            CASE WHEN (apt_arrive.apt_icao IS NOT NULL) THEN
                apt_arrive.apt_name || '''' ('''' || apt_arrive.apt_icao || '''')''''
            ELSE
                apt_arrive.apt_name
            END AS apt_name_arrive,

            -- TimeZones
                -- (Depart) Daylight Saving Time?
            CASE WHEN (CURRENT_TIMESTAMP BETWEEN apt_depart.apt_dst_start_dt AND apt_depart.apt_dst_end_dt) THEN
                (apt_depart.apt_timezone::real + 1)::text || ''''*''''
            ELSE
                apt_depart.apt_timezone
            END AS apt_timezone_depart,

                -- (Arrive) Daylight Saving Time?
            CASE WHEN (CURRENT_TIMESTAMP BETWEEN apt_arrive.apt_dst_start_dt AND apt_arrive.apt_dst_end_dt) THEN
                (apt_arrive.apt_timezone::real + 1)::text || ''''*''''
            ELSE
                apt_arrive.apt_timezone
            END AS apt_timezone_arrive,

            -- ETD (Estimated Time of Depart)
            leg_keeptrack_dt::text AS leg_etd_dt,
            NULL::text AS leg_etd_localtime_dt,

            -- ETA (Estimated Time of Arrive)
            (leg_keeptrack_dt::timestamp + leg_ete_i::time)::text AS leg_eta_dt,
            NULL::text AS leg_eta_localtime_dt
        FROM
            "leg"
            LEFT JOIN "airport" apt_depart ON (apt_id_depart = apt_depart.apt_id)
            LEFT JOIN "airport" apt_arrive ON (apt_id_arrive = apt_arrive.apt_id)
        WHERE
            leg_id IS NULL'';

    aux_ids := ids;

    WHILE POSITION('':'' IN aux_ids) > 0 LOOP
        aux_ids := SUBSTRING(aux_ids FROM (POSITION('':'' IN aux_ids) + 1));
        occurence := occurence + 1;
    END LOOP;

    FOR i IN 1..occurence LOOP
        query := query || '' OR leg_id = '' || SPLIT_PART(ids, '':'', i);
    END LOOP;
 
    query := query || '' ORDER BY leg_trip, leg_keeptrack_dt ASC'';

    FOR ret IN EXECUTE query LOOP
        -- Applying TimeZone To Achieve LocalTime
            -- Depart
        IF POSITION(''*'' IN ret.apt_timezone_depart) > 0 THEN
            aux_tz := (''1 hour''::interval * SUBSTRING(ret.apt_timezone_depart, 1, LENGTH(ret.apt_timezone_depart) - 1)::real)::interval;
        ELSE
            aux_tz := (''1 hour''::interval * ret.apt_timezone_depart::real)::interval;
        END IF;
        ret.leg_etd_localtime_dt := TO_CHAR((ret.leg_etd_dt::timestamp + aux_tz::interval)::timestamp, ''Dy DDMONYYYY @ HH24:MI'');

            -- Arrive
        IF POSITION(''*'' IN ret.apt_timezone_arrive) > 0 THEN
            aux_tz := (''1 hour''::interval * SUBSTRING(ret.apt_timezone_arrive, 1, LENGTH(ret.apt_timezone_arrive) - 1)::real)::interval;
        ELSE
            aux_tz := (''1 hour''::interval * ret.apt_timezone_arrive::real)::interval;
        END IF;
        ret.leg_eta_localtime_dt := TO_CHAR((ret.leg_eta_dt::timestamp + aux_tz::interval)::timestamp, ''Dy DDMONYYYY @ HH24:MI'');

        -- GMT Time
        ret.leg_etd_dt := TO_CHAR(ret.leg_etd_dt::timestamp, ''DDMONYY @ HH24:MI'')::text || '' GMT'';
        ret.leg_eta_dt := TO_CHAR(ret.leg_eta_dt::timestamp, ''DDMONYY @ HH24:MI'')::text || '' GMT'';

        RETURN NEXT ret;
    END LOOP; 

    RETURN ret;
END;
' LANGUAGE 'plpgsql';

--  Check whether the leg you are trying to add will overflow/overlap the non-null ETD leg (of the current trip or not)
--  It returns a string with the trip and keeptrack of the *overflowed* leg concatenated with a "|"
--  Usage:
--      SELECT func_get_leg_push_overflow(trip, keeptrack, ete, groundtime)
CREATE FUNCTION func_get_leg_push_overflow(integer, timestamp, interval, interval) RETURNS text AS '
DECLARE
    -- Args
    trip        ALIAS FOR $1;
    keeptrack   ALIAS FOR $2;
    ete         ALIAS FOR $3;
    groundtime  ALIAS FOR $4;

    -- Auxiliar Vars
    ret_row     record;
    ret         text;
BEGIN
    SELECT INTO ret_row
        leg_trip,
        leg_etd_dt
    FROM
        "leg"
    WHERE
        leg_etd_dt IS NOT NULL AND 
        leg_keeptrack_dt > keeptrack AND 
        leg_keeptrack_dt < 
        (
            SELECT
                leg_keeptrack_dt + ete + groundtime
            FROM
                "leg"
            WHERE
                leg_trip = trip AND
                leg_etd_dt IS NULL AND
                leg_keeptrack_dt > keeptrack
            ORDER BY
                leg_keeptrack_dt DESC
            LIMIT 1
        )
    LIMIT 1;

    IF FOUND THEN
        ret := ret_row.leg_trip || ''|'' || ret_row.leg_etd_dt;
    ELSE
        ret := NULL;
    END IF;

    RETURN ret;
END;'
LANGUAGE 'plpgsql';

-- -------------------------------------------------------------------------------------------------------------------
-- Triggers
    -- Only one Aircraft can be the Default (makes sense, hehe)
CREATE TRIGGER "trig_uniqueness" BEFORE
    UPDATE ON "aircraft"
    FOR EACH ROW EXECUTE PROCEDURE func_default_acf();

-- -------------------------------------------------------------------------------------------------------------------
-- Rules
    -- Only one Aircraft can be the Default (makes sense, hehe)
CREATE RULE "rule_default" AS ON
    INSERT TO "aircraft" WHERE (NEW.acf_default = 1)
       DO UPDATE "aircraft" SET acf_default = '0' WHERE acf_default = '1';

-- -------------------------------------------------------------------------------------------------------------------
-- System Data
-- Functions
-- Admin
    -- User
INSERT INTO "function" (fnc_name) VALUES ('admin: user add');
INSERT INTO "function" (fnc_name) VALUES ('admin: user change');
INSERT INTO "function" (fnc_name) VALUES ('admin: user delete');
INSERT INTO "function" (fnc_name) VALUES ('admin: user view');
INSERT INTO "function" (fnc_name) VALUES ('admin: user list');

    -- Group
INSERT INTO "function" (fnc_name) VALUES ('admin: group add');
INSERT INTO "function" (fnc_name) VALUES ('admin: group change');
INSERT INTO "function" (fnc_name) VALUES ('admin: group delete');
INSERT INTO "function" (fnc_name) VALUES ('admin: group view');
INSERT INTO "function" (fnc_name) VALUES ('admin: group list');

-- Data
    -- Aircraft
INSERT INTO "function" (fnc_name) VALUES ('data: aircraft add');
INSERT INTO "function" (fnc_name) VALUES ('data: aircraft change');
INSERT INTO "function" (fnc_name) VALUES ('data: aircraft delete');
INSERT INTO "function" (fnc_name) VALUES ('data: aircraft view');
INSERT INTO "function" (fnc_name) VALUES ('data: aircraft list');

    -- Airport
INSERT INTO "function" (fnc_name) VALUES ('data: airport add');
INSERT INTO "function" (fnc_name) VALUES ('data: airport change');
INSERT INTO "function" (fnc_name) VALUES ('data: airport delete');
INSERT INTO "function" (fnc_name) VALUES ('data: airport view');
INSERT INTO "function" (fnc_name) VALUES ('data: airport list');

    -- Citizenship
INSERT INTO "function" (fnc_name) VALUES ('data: citizenship add');
INSERT INTO "function" (fnc_name) VALUES ('data: citizenship change');
INSERT INTO "function" (fnc_name) VALUES ('data: citizenship delete');
INSERT INTO "function" (fnc_name) VALUES ('data: citizenship view');
INSERT INTO "function" (fnc_name) VALUES ('data: citizenship list');

    -- Contact
INSERT INTO "function" (fnc_name) VALUES ('data: contact add');
INSERT INTO "function" (fnc_name) VALUES ('data: contact change');
INSERT INTO "function" (fnc_name) VALUES ('data: contact delete');
INSERT INTO "function" (fnc_name) VALUES ('data: contact view');
INSERT INTO "function" (fnc_name) VALUES ('data: contact list');

    -- Country
INSERT INTO "function" (fnc_name) VALUES ('data: country add');
INSERT INTO "function" (fnc_name) VALUES ('data: country change');
INSERT INTO "function" (fnc_name) VALUES ('data: country delete');
INSERT INTO "function" (fnc_name) VALUES ('data: country view');
INSERT INTO "function" (fnc_name) VALUES ('data: country list');

    -- Crew Member
INSERT INTO "function" (fnc_name) VALUES ('data: cmb add');
INSERT INTO "function" (fnc_name) VALUES ('data: cmb change');
INSERT INTO "function" (fnc_name) VALUES ('data: cmb delete');
INSERT INTO "function" (fnc_name) VALUES ('data: cmb view');
INSERT INTO "function" (fnc_name) VALUES ('data: cmb list');

    -- Food
INSERT INTO "function" (fnc_name) VALUES ('data: food add');
INSERT INTO "function" (fnc_name) VALUES ('data: food change');
INSERT INTO "function" (fnc_name) VALUES ('data: food delete');
INSERT INTO "function" (fnc_name) VALUES ('data: food view');
INSERT INTO "function" (fnc_name) VALUES ('data: food list');

    -- FoodType
INSERT INTO "function" (fnc_name) VALUES ('data: foodtype add');
INSERT INTO "function" (fnc_name) VALUES ('data: foodtype change');
INSERT INTO "function" (fnc_name) VALUES ('data: foodtype delete');
INSERT INTO "function" (fnc_name) VALUES ('data: foodtype view');
INSERT INTO "function" (fnc_name) VALUES ('data: foodtype list');

    -- Occupation
INSERT INTO "function" (fnc_name) VALUES ('data: occupation add');
INSERT INTO "function" (fnc_name) VALUES ('data: occupation change');
INSERT INTO "function" (fnc_name) VALUES ('data: occupation delete');
INSERT INTO "function" (fnc_name) VALUES ('data: occupation view');
INSERT INTO "function" (fnc_name) VALUES ('data: occupation list');

    -- Operator
INSERT INTO "function" (fnc_name) VALUES ('data: operator add');
INSERT INTO "function" (fnc_name) VALUES ('data: operator change');
INSERT INTO "function" (fnc_name) VALUES ('data: operator delete');
INSERT INTO "function" (fnc_name) VALUES ('data: operator view');
INSERT INTO "function" (fnc_name) VALUES ('data: operator list');

    -- Service
INSERT INTO "function" (fnc_name) VALUES ('data: service add');
INSERT INTO "function" (fnc_name) VALUES ('data: service change');
INSERT INTO "function" (fnc_name) VALUES ('data: service delete');
INSERT INTO "function" (fnc_name) VALUES ('data: service view');
INSERT INTO "function" (fnc_name) VALUES ('data: service list');

    -- VisaType
INSERT INTO "function" (fnc_name) VALUES ('data: visatype add');
INSERT INTO "function" (fnc_name) VALUES ('data: visatype change');
INSERT INTO "function" (fnc_name) VALUES ('data: visatype delete');
INSERT INTO "function" (fnc_name) VALUES ('data: visatype view');
INSERT INTO "function" (fnc_name) VALUES ('data: visatype list');

-- Pax
    -- Pax
INSERT INTO "function" (fnc_name) VALUES ('pax: pax add');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax change');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax delete');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax view');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax list');

    -- Pax Visa
INSERT INTO "function" (fnc_name) VALUES ('pax: pax_vst add');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax_vst change');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax_vst delete');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax_vst view');
INSERT INTO "function" (fnc_name) VALUES ('pax: pax_vst list');

-- Agent
    -- Caterer
INSERT INTO "function" (fnc_name) VALUES ('agent: caterer add');
INSERT INTO "function" (fnc_name) VALUES ('agent: caterer change');
INSERT INTO "function" (fnc_name) VALUES ('agent: caterer delete');
INSERT INTO "function" (fnc_name) VALUES ('agent: caterer view');
INSERT INTO "function" (fnc_name) VALUES ('agent: caterer list');

    -- Handler
INSERT INTO "function" (fnc_name) VALUES ('agent: handler add');
INSERT INTO "function" (fnc_name) VALUES ('agent: handler change');
INSERT INTO "function" (fnc_name) VALUES ('agent: handler delete');
INSERT INTO "function" (fnc_name) VALUES ('agent: handler view');
INSERT INTO "function" (fnc_name) VALUES ('agent: handler list');

    -- Permit
INSERT INTO "function" (fnc_name) VALUES ('agent: permit add');
INSERT INTO "function" (fnc_name) VALUES ('agent: permit change');
INSERT INTO "function" (fnc_name) VALUES ('agent: permit delete');
INSERT INTO "function" (fnc_name) VALUES ('agent: permit view');
INSERT INTO "function" (fnc_name) VALUES ('agent: permit list');

-- Itinerary
    -- Leg
INSERT INTO "function" (fnc_name) VALUES ('itinerary: leg add');
INSERT INTO "function" (fnc_name) VALUES ('itinerary: leg change');
INSERT INTO "function" (fnc_name) VALUES ('itinerary: leg delete');
INSERT INTO "function" (fnc_name) VALUES ('itinerary: leg view');
INSERT INTO "function" (fnc_name) VALUES ('itinerary: leg list');

-- Docs
    -- File
INSERT INTO "function" (fnc_name) VALUES ('docs: file add');
INSERT INTO "function" (fnc_name) VALUES ('docs: file change');
INSERT INTO "function" (fnc_name) VALUES ('docs: file delete');
INSERT INTO "function" (fnc_name) VALUES ('docs: file view');
INSERT INTO "function" (fnc_name) VALUES ('docs: file list');

-- BookMark
    -- Link
INSERT INTO "function" (fnc_name) VALUES ('bookmark: link add');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: link change');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: link delete');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: link view');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: link list');

    -- SysLink
INSERT INTO "function" (fnc_name) VALUES ('bookmark: syslink add');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: syslink change');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: syslink delete');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: syslink view');
INSERT INTO "function" (fnc_name) VALUES ('bookmark: syslink list');


-- -------------------------------------------------------------------------------------------------------------------
-- Dummy Data
    -- Admin (mmr, 123123)
INSERT INTO "user" (usr_login, usr_passwd, usr_name) VALUES ('mmr', 'wOdSM2yMDRDA2LleDNLIRbhX0ve54lIW5G4H9aeJ/lo=', 'mmr');
INSERT INTO "group" (grp_name) VALUES ('Mmr Group');
INSERT INTO "grp_usr" (grp_id, usr_id) VALUES ('1', '1');
INSERT INTO "grp_fnc" (grp_id, fnc_id) SELECT '1', fnc_id FROM "function";

-- Guest User
INSERT INTO "user" (usr_login, usr_passwd, usr_name) VALUES ('guest', 'Ojjd4cm3C5lCHxZmzWGgCzBHVgdjy5w6PlPrpWPBqQ4=', 'GuestUser');
INSERT INTO "group" (grp_name) VALUES ('Guest  Group');
INSERT INTO "grp_usr" (grp_id, usr_id) VALUES ('2', '2');
INSERT INTO "grp_fnc" (grp_id, fnc_id) SELECT '2', fnc_id FROM "function" WHERE fnc_name ~* '^(([^a]|a[^d]|ad[^m])[a-z: ]+add)|([a-z: ]+(view|list))$';

    -- Data
        -- Country
INSERT INTO "country" (cty_name, cty_code) VALUES ('Brazil',    'BR');
INSERT INTO "country" (cty_name, cty_code) VALUES ('USA',       'US');
INSERT INTO "country" (cty_name, cty_code) VALUES ('Italy',     'IT');
INSERT INTO "country" (cty_name, cty_code) VALUES ('Germany',   'GR');
INSERT INTO "country" (cty_name, cty_code) VALUES ('Japan',     'JP');

        -- Operator
INSERT INTO "operator" 
(
    cty_id,
    opr_name,
    opr_city, opr_address,
    opr_phone_country_code, opr_phone_city_code,
    opr_hangar_phone, opr_hangar_fax,
    opr_coffice_phone, opr_coffice_fax,
    opr_coffice_email, opr_desc
)
VALUES
(
    (SELECT cty_id FROM "country" WHERE cty_name ~* 'bra[sz]il'),
    'Matterhorn Empreendimentos e Participacoes Ltda.',
    'Sao Paulo', 'Av. Brigadeiro Faria Lima, 1485 - 10o TN',
    '55', '11',
    '50904136', '50322406',
    '30953095', '30953040',
    'lyra@b4br.net', 'SP 01452-002'
);

        -- Caterer
INSERT INTO "caterer" (cat_name) VALUES ('Ferrucio');
INSERT INTO "caterer" (cat_name) VALUES ('WhereTheHeckIsMySpaceBar');
INSERT INTO "caterer" (cat_name) VALUES ('Speed Racer');

        -- Handler
INSERT INTO "handler" (hdl_name) VALUES ('Jet Shuberies');
INSERT INTO "handler" (hdl_name) VALUES ('Sbrubles Danke');
INSERT INTO "handler" (hdl_name) VALUES ('Insane In The Membrane');

        -- Permit
INSERT INTO "permit" (pmt_name) VALUES ('US Customs');
INSERT INTO "permit" (pmt_name) VALUES ('Fernando de Noronha');
INSERT INTO "permit" (pmt_name) VALUES ('X Racer');

        -- Airport
INSERT INTO "airport"
(
    hdl_id,
    cat_id,
    pmt_id,
    cty_id,
    apt_dst_start_dt, apt_dst_end_dt,
    apt_name, apt_timezone, apt_city
)
VALUES
(
    (SELECT cat_id FROM "caterer" WHERE cat_name ~* 'ferrucio'),
    (SELECT hdl_id FROM "handler" WHERE hdl_name ~* 'membrane'),
    (SELECT pmt_id FROM "permit"  WHERE pmt_name ~* 'noronha'),
    (SELECT cty_id FROM "country" WHERE cty_name ~* 'bra[sz]il'),
    '2002-10-10', '2003-02-10',
    'Cumbica', '-3', 'Sao Paulo'
);

INSERT INTO "airport"
(
    hdl_id,
    cat_id,
    pmt_id,
    cty_id,
    apt_dst_start_dt, apt_dst_end_dt,
    apt_name, apt_timezone, apt_city
)
VALUES
(
    (SELECT cat_id FROM "caterer" WHERE cat_name ~* 'ferrucio'),
    (SELECT hdl_id FROM "handler" WHERE hdl_name ~* 'jet'),
    (SELECT pmt_id FROM "permit"  WHERE pmt_name ~* 'fernando'),
    (SELECT cty_id FROM "country" WHERE cty_name ~* 'usa'),
    '2002-06-10', '2002-10-10',
    'Little Rock', '-4', 'Dunno'
);


INSERT INTO "airport"
(
    hdl_id,
    cat_id,
    pmt_id,
    cty_id,
    apt_dst_start_dt, apt_dst_end_dt,
    apt_name, apt_timezone, apt_city
)
VALUES
(
    (SELECT cat_id FROM "caterer" WHERE cat_name ~* 'ferrucio'),
    (SELECT hdl_id FROM "handler" WHERE hdl_name ~* 'jet'),
    (SELECT pmt_id FROM "permit"  WHERE pmt_name ~* 'racer'),
    (SELECT cty_id FROM "country" WHERE cty_name ~* 'jap'),
    '2002-06-10', '2002-10-10',
    'Foo Bar', '+3', 'Gotham City'
);

INSERT INTO "airport"
(
    hdl_id,
    cat_id,
    pmt_id,
    cty_id,
    apt_dst_start_dt, apt_dst_end_dt,
    apt_name, apt_timezone, apt_city
)
VALUES
(
    (SELECT cat_id FROM "caterer" WHERE cat_name ~* 'ferrucio'),
    (SELECT hdl_id FROM "handler" WHERE hdl_name ~* 'jet'),
    (SELECT pmt_id FROM "permit"  WHERE pmt_name ~* 'racer'),
    (SELECT cty_id FROM "country" WHERE cty_name ~* 'germ'),
    '2002-06-10', '2002-10-10',
    'ZuperZaper', '+12.75', 'Xuxu City'
);

        -- Aircraft
INSERT INTO "aircraft"
(
    apt_id,
    opr_id,
    acf_model, acf_registry, acf_mtow, acf_default,
    acf_satcom_country_code, acf_satcom_city_code, acf_satcom
)
VALUES
(
    (SELECT apt_id FROM "airport"  WHERE apt_name ~* 'cumbica'),
    (SELECT opr_id FROM "operator" WHERE opr_name ~* 'matterhorn'),
    'Falcon 900C (F900)', 'PR-SEA', '46500', '1',
    '1', '651', '7964791'
);

        -- Citizenship
INSERT INTO "citizenship" (cts_name) VALUES ('Brazilian');
INSERT INTO "citizenship" (cts_name) VALUES ('Italian');
INSERT INTO "citizenship" (cts_name) VALUES ('American');
INSERT INTO "citizenship" (cts_name) VALUES ('Greek');
INSERT INTO "citizenship" (cts_name) VALUES ('French');
INSERT INTO "citizenship" (cts_name) VALUES ('Japanese');
INSERT INTO "citizenship" (cts_name) VALUES ('Chinese');

        -- Contact
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Judiaelsson', 'H');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Cremilda', 'H');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Armando Barraca', 'H');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Sergio Mello Rego', 'C');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Serafim da Silva', 'C');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Seu Creisson', 'C');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Duty Officer', 'P');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Serafim da Silva', 'P');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Sr. Creisson', 'P');
INSERT INTO "contact" (ctc_name, ctc_provider) VALUES ('Marieta', 'P');

        -- Occupation
INSERT INTO "occupation" (occ_name) VALUES ('Commander');
INSERT INTO "occupation" (occ_name) VALUES ('Chief Pilot');
INSERT INTO "occupation" (occ_name) VALUES ('Freelancer');
INSERT INTO "occupation" (occ_name) VALUES ('Mechanic');

        -- Crew Member
INSERT INTO "cmb" (cts_id, occ_id, cmb_name, cmb_cdac, cmb_ppt_exp_dt, cmb_ppt_nbr) VALUES ('1', '1', 'Marcio Ribeiro', '123123', '2003-10-10', 'CN-1343');
INSERT INTO "cmb" (cts_id, occ_id, cmb_name, cmb_cdac, cmb_ppt_exp_dt, cmb_ppt_nbr) VALUES ('4', '2', 'Priscila Ribeiro', '454545', '2003-10-10', 'CO-1344');
INSERT INTO "cmb" (cts_id, occ_id, cmb_name, cmb_cdac, cmb_ppt_exp_dt, cmb_ppt_nbr) VALUES ('5', '4', 'Luis Nardella', '343435', '2003-10-10', 'CP-1344');
INSERT INTO "cmb" (cts_id, occ_id, cmb_name, cmb_cdac, cmb_ppt_exp_dt, cmb_ppt_nbr) VALUES ('7', '3', 'Rafael Matos', '666666', '2003-10-10', 'CI-1344');
INSERT INTO "cmb" (cts_id, occ_id, cmb_name, cmb_cdac, cmb_ppt_exp_dt, cmb_ppt_nbr) VALUES ('6', '1', 'Foobar Man', '345444', '2003-10-10', 'KQ-1344');

        -- Pax
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('1', 'Marcio Ribeiro', 'CN-1343');
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('4', 'Foobar Yeah', 'FO-8900');
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('3', 'Silva Silva', 'KK-9898');
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('2', 'Daimien Rupert', 'CN-7777');
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('7', 'Melissa Maria', 'II-0202');
INSERT INTO "pax" (cts_id, pax_name, pax_ppt_nbr) VALUES ('5', 'Priscila Ribeiro', 'ZO-9393');

        -- Food Type
INSERT INTO "foodtype" (fdt_name) VALUES ('Snack');
INSERT INTO "foodtype" (fdt_name) VALUES ('Dinner');
INSERT INTO "foodtype" (fdt_name) VALUES ('BreakFast');

        -- Service
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Fuel', 'H');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Ice',  'H');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Aircraft Wash', 'H');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Magazines', 'C');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Newspaper', 'C');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('US Customs', 'P');
INSERT INTO "service" (srv_name, srv_provider) VALUES ('Fernando de Noronha Customs', 'P');

        -- Visa Type
INSERT INTO "visatype" (vst_name) VALUES ('US');
INSERT INTO "visatype" (vst_name) VALUES ('Greek');
INSERT INTO "visatype" (vst_name) VALUES ('Italian');
INSERT INTO "visatype" (vst_name) VALUES ('Japanese');
