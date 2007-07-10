#!/bin/sh
createdb -Upostgres shock
createlang -Upostgres -L/usr/lib/pgsql plpgsql shock
psql -Upostgres shock < shock.sql
