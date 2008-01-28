package org.b1n.receiver.persistence;

import java.sql.Types;

import org.hibernate.dialect.PostgreSQLDialect;

/**
 * @author Marcio Ribeiro
 * @date Jan 28, 2008
 */
public class WithTimeZonePostgreSQLDialect extends PostgreSQLDialect {
    public WithTimeZonePostgreSQLDialect() {
        registerColumnType(Types.DATE, "timestamp with time zone");
        registerColumnType(Types.TIMESTAMP, "timestamp with time zone");
    }
}
