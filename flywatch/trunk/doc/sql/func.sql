CREATE OR REPLACE FUNCTION func_get_leg_push_overflow(integer, timestamp, interval, interval) RETURNS text AS '
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
