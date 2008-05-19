package org.b1n.framework.utils;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

/**
 * @author Marcio Ribeiro
 * @date May 18, 2008
 */
public final class EnumUtils {

    /**
     * Classe utilitaria, nao deve ser instanciada.
     */
    private EnumUtils() {
        // do nothing
    }

    /**
     * Devolve o enum para o valor passado.
     * @param <E> tipo de enum.
     * @param enumClass classe de enum.
     * @param value valor.
     * @return enum.
     */
    @SuppressWarnings("unchecked")
    public static <E extends Enum<E>> E getEnumByValue(final Class<E> enumClass, final Object value) {
        return getEnumByValue(enumClass, value, null);
    }

    /**
     * Devolve enum para o valor passado.
     * @param <E> tipo de enum.
     * @param enumClass classe de enum.
     * @param value valor.
     * @param valueMethod nome de metodo de valor do enum.
     * @return enum.
     */
    public static <E extends Enum<E>> E getEnumByValue(final Class<E> enumClass, final Object value, final String valueMethod) {
        try {
            E[] values = getValues(enumClass);

            for (E v : values) {
                if (valueMethod != null) {
                    Method method = v.getClass().getMethod(valueMethod);
                    Object objValue = method.invoke(v);
                    if ((objValue == null && value == null) || (objValue != null && value != null && objValue.equals(value))) {
                        return v;
                    }
                } else {
                    if (v.equals(value)) {
                        return v;
                    }
                }
            }
        } catch (final IllegalAccessException e) {
            throw new CouldNotGetEnumException(e);
        } catch (final InvocationTargetException e) {
            throw new CouldNotGetEnumException(e);
        } catch (final NoSuchMethodException e) {
            throw new CouldNotGetEnumException(e);
        }
        throw new CouldNotGetEnumException("Enum nao encontrado: " + enumClass + " : " + value);
    }

    /**
     * Devolve array de valores para classe de enum passada.
     * @param <E> tipo de enum.
     * @param enumClass classe de enum.
     * @return array com enums para classe passada.
     * @throws IllegalAccessException caso nao consiga executar metodo para trazer valores de enum.
     * @throws InvocationTargetException caso nao consiga executar metodo para trazer valores de enum.
     * @throws NoSuchMethodException caso nao consiga executar metodo para trazer valores de enum.
     */
    @SuppressWarnings("unchecked")
    private static <E> E[] getValues(final Class<E> enumClass) throws IllegalAccessException, InvocationTargetException, NoSuchMethodException {
        return (E[]) enumClass.getMethod("values").invoke(null);
    }

    /**
     * Devolve um valor aleatorio.
     * @param <E> tipo de enum.
     * @param enumClass classe de enum.
     * @return enum aleatorio.
     */
    public static <E extends Enum<E>> E getRandomValue(final Class<E> enumClass) {
        try {
            E[] values = getValues(enumClass);
            return values[(int) (Math.random() * (values.length - 1))];
        } catch (final IllegalAccessException e) {
            throw new CouldNotGetEnumException(e);
        } catch (final InvocationTargetException e) {
            throw new CouldNotGetEnumException(e);
        } catch (final NoSuchMethodException e) {
            throw new CouldNotGetEnumException(e);
        }
    }
}
