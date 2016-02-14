-- Uso:
-- SELECT * FROM n_inf(n) AS (data DATE);
--

CREATE OR REPLACE FUNCTION n_inf(int) RETURNS SETOF record AS '
DECLARE
  n ALIAS FOR $1;
  q TEXT;
  r RECORD;
BEGIN
  q := ''
    SELECT data
    FROM (
      SELECT data
      FROM sena
      WHERE 
        d1 = ''||n||'' OR d2 = ''||n||'' OR d3 = ''||n||'' OR
        d4 = ''||n||'' OR d5 = ''||n||'' OR d6 = ''||n||''
    ) AS foo'';

  --  RAISE NOTICE ''Query: %'', q;

  FOR r IN EXECUTE q LOOP
    RETURN NEXT r;
  END LOOP;

  RETURN r;
END;' LANGUAGE 'plpgsql';

-- Uso:
-- SELECT * FROM n_seg(n) AS (data DATE, data_seg DATE);
--

CREATE OR REPLACE FUNCTION n_seg(int) RETURNS SETOF RECORD AS '
DECLARE
  n ALIAS FOR $1;
  q TEXT;
  r RECORD;
BEGIN
  q := ''
    SELECT data, data_seg
    FROM (
      SELECT data, (data - ''''3 days''''::INTERVAL)::DATE AS data_seg
      FROM sena
      WHERE (
        d1 = ''||n||'' OR d2 = ''||n||'' OR d3 = ''||n||'' OR
        d4 = ''||n||'' OR d5 = ''||n||'' OR d6 = ''||n||''
      )
      AND data IN (
        SELECT (data + ''''3 days''''::INTERVAL)
        FROM sena
        WHERE (
          d1 = ''||n||'' OR d2 = ''||n||'' OR d3 = ''||n||'' OR
          d4 = ''||n||'' OR d5 = ''||n||'' OR d6 = ''||n||''
        )
      )
      UNION (
        SELECT data, (data - ''''4 days''''::INTERVAL)::DATE AS data_seg
        FROM sena
        WHERE (
          d1 = ''||n||'' OR d2 = ''||n||'' OR d3 = ''||n||'' OR
          d4 = ''||n||'' OR d5 = ''||n||'' OR d6 = ''||n||''
        )
        AND data IN (
          SELECT (data + ''''4 days''''::INTERVAL)
          FROM sena
          WHERE (
            d1 = ''||n||'' OR d2 = ''||n||'' OR d3 = ''||n||'' OR
            d4 = ''||n||'' OR d5 = ''||n||'' OR d6 = ''||n||''
          )
        )
      )
    ) as foo'';

  RAISE NOTICE ''Query: %'', q;

  FOR r IN EXECUTE q LOOP
    RETURN NEXT r;
  END LOOP;

  RETURN r;
END;' LANGUAGE 'plpgsql';
