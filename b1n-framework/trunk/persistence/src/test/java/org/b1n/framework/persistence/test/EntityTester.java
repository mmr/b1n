package org.b1n.framework.persistence.test;

import java.lang.reflect.Constructor;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Map;
import java.util.Set;

import org.apache.commons.collections.iterators.LoopingIterator;
import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.Entity;
import org.b1n.framework.persistence.EntityDao;
import org.b1n.framework.persistence.EntityNotFoundException;
import org.b1n.framework.persistence.JpaEntity;
import org.b1n.framework.utils.EnumUtils;

/**
 * Testador de entidades.
 * @author Marcio Ribeiro (mmr)
 * @created Dec 13, 2008
 */
public final class EntityTester {

    private static final RandomTestData RND = new RandomTestData();

    /**
     * Construtor.
     */
    private EntityTester() {
        // nothing
    }

    /**
     * Testa o tipo de entidade passada.
     * @param <E> tipo de entidade.
     * @param entityClass classe de entidade.
     * @throws Exception caso algo de inesperado ocorra.
     */
    public static <E extends Entity> void testEntity(final Class<E> entityClass) throws Exception {
        // Insert
        final E entity = entityClass.newInstance();
        fillEntity(entityClass, entity);
        entity.save();

        EntityDao<E> entityDao = DaoLocator.getDao(entityClass);
        E loadedEntity = entityDao.findById(entity.getId());
        compareData(entityClass, entity, loadedEntity);

        // Update
        fillEntity(entityClass, entity);
        entity.save();
        loadedEntity = entityDao.findById(entity.getId());
        compareData(entityClass, entity, loadedEntity);

        // Remove
        entity.remove();
        try {
            entityDao.findById(entity.getId());
            throw new RuntimeException("Could not remove entity");
        } catch (final EntityNotFoundException e) {
            // ok!
        }

    }

    /**
     * Preenche entidade com valores aleatorios.
     * @param <E> tipo de entidade.
     * @param entityClass classe de entidade.
     * @param entity instancia de entidade a ser preenchida.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static <E extends Entity> void fillEntity(final Class<E> entityClass, final E entity) throws Exception {
        Class<? super E> superClass = entityClass;
        while (superClass != JpaEntity.class) {
            for (final Method method : superClass.getDeclaredMethods()) {
                if (method.getName().startsWith("set") && !method.getName().equals("setId")) {
                    invokeSetter(entity, method);
                }
            }
            superClass = superClass.getSuperclass();
        }
    }

    /**
     * Invoca setter para atributo de entidade.
     * @param <E> tipo de entidade.
     * @param entity instancia de entidade tendo setter invocado.
     * @param method metodo de setter.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static <E extends Entity> void invokeSetter(final E entity, final Method method) throws Exception {
        if (!Modifier.isPublic(method.getModifiers())) {
            return;
        }

        final Class<?> parameterType = method.getParameterTypes()[0];
        final Object parameter = instantiateParameter(parameterType);
        method.invoke(entity, parameter);
    }

    /**
     * Compara todos campos de entidade.
     * @param <E> tipo de entidade.
     * @param entityClass classe de entidade.
     * @param entity instancia de entidade sendo comparada.
     * @param loadedEntity instancia de entidade carregada do banco de dados.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static <E extends Entity> void compareData(final Class<E> entityClass, final E entity, final E loadedEntity) throws Exception {
        Class<? super E> superClass = entityClass;
        while (superClass != JpaEntity.class) {
            for (final Method method : superClass.getDeclaredMethods()) {
                if (Modifier.isPublic(method.getModifiers()) && (method.getName().startsWith("get") || method.getName().startsWith("is"))) {
                    Object o1 = method.invoke(entity, new Object[]{});
                    Object o2 = method.invoke(loadedEntity, new Object[]{});
                    if (o1 == null && o2 != null || !o1.equals(o2)) {
                        throw new RuntimeException("Expected " + o1 + " got " + o2 + ".");
                    }
                }
            }
            superClass = superClass.getSuperclass();
        }
    }

    /**
     * Cria instancia de parametro do tipo passado.
     * @param parameterType tipo de parametro.
     * @return instancia de parametro.
     * @throws Exception caso algo de inesperado ocorra.
     */
    @SuppressWarnings("unchecked")
    private static Object instantiateParameter(final Class<?> parameterType) throws Exception {
        Object randomValue = RND.get(parameterType);

        if (randomValue != null) {
            return randomValue;
        }

        if (Entity.class.isAssignableFrom(parameterType)) {
            return createSavedEntity((Class<? extends Entity>) parameterType);
        }

        return createInstance(parameterType);
    }

    /**
     * Cria instancia de classe.
     * @param clazz classe.
     * @return instancia de classe.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static Object createInstance(final Class<?> clazz) throws Exception {
        final Constructor<?> constructor = clazz.getDeclaredConstructor((Class[]) null);
        if (!Modifier.isPublic(constructor.getModifiers()) || !Modifier.isPublic(constructor.getDeclaringClass().getModifiers())) {
            constructor.setAccessible(true);
        }
        return constructor.newInstance(new Object[0]);
    }

    /**
     * Cria instancia de entidade nao salva.
     * @param <E> tipo de entidade.
     * @param entityClass classe de entidade.
     * @return instancia de entidade nao salva.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static <E extends Entity> E createUnsavedEntity(final Class<E> entityClass) throws Exception {
        final E entity = entityClass.newInstance();
        fillEntity(entityClass, entity);
        return entity;
    }

    /**
     * Cria instancia de entidade salva.
     * @param <E> tipo de entidade.
     * @param entityClass classe de entidade.
     * @return instancia de entidade salva.
     * @throws Exception caso algo de inesperado ocorra.
     */
    private static <E extends Entity> E createSavedEntity(final Class<E> entityClass) throws Exception {
        final E entity = createUnsavedEntity(entityClass);
        entity.save();
        return entity;
    }

    /**
     * Classe com dados aleatorios para teste.
     * @author Marcio Ribeiro
     * @date May 18, 2008
     */
    private static final class RandomTestData {
        private static final int MAX_ITEMS = 100;

        private static final int MAX_RANDOM = 666;

        private Map<Class<?>, Iterator<?>> data = null;

        /**
         * Construtor.
         */
        RandomTestData() {
            init();
        }

        /**
         * Devolve um dado aleatorio do tipo passado.
         * @param parameterType tipo de dado.
         * @return dado aleatorio para teste.
         */
        Object get(final Class<?> parameterType) {
            if (parameterType == Long.TYPE || Long.class.isAssignableFrom(parameterType) || parameterType == Integer.TYPE || Integer.class.isAssignableFrom(parameterType)) {
                return data.get(Long.TYPE).next();
            }

            if (parameterType == Double.TYPE || parameterType == Float.TYPE || Double.class.isAssignableFrom(parameterType) || Float.class.isAssignableFrom(parameterType)) {
                return data.get(Double.TYPE).next();
            }

            if (String.class.isAssignableFrom(parameterType)) {
                return data.get(String.class).next();
            }

            if (Enum.class.isAssignableFrom(parameterType)) {
                return getRandomEnumValue(parameterType);
            }

            if (parameterType == Boolean.TYPE || Boolean.class.isAssignableFrom(parameterType)) {
                if (((int) Math.random() * 2) == 1) {
                    return Boolean.TRUE;
                } else {
                    return Boolean.FALSE;
                }
            }

            return null;
        }

        /**
         * Devolve valor aleatorio para classe de enum passado.
         * @param parameterType tipo de enum.
         * @return valor aleatorio para classe de enum passado.
         */
        @SuppressWarnings("unchecked")
        private Object getRandomEnumValue(final Class<?> parameterType) {
            return EnumUtils.getRandomValue((Class<Enum>) parameterType);
        }

        /**
         * Popula dados aleatorios para teste.
         */
        void init() {
            data = new HashMap<Class<?>, Iterator<?>>();

            Set<Long> longs = new HashSet<Long>();
            Set<Double> doubles = new HashSet<Double>();
            Set<String> strings = new HashSet<String>();

            for (int i = 0; i < MAX_ITEMS; i++) {
                longs.add((long) (MAX_RANDOM * Math.random()));
                doubles.add(MAX_RANDOM * Math.random());
                strings.add(String.valueOf(MAX_RANDOM * Math.random()));
            }

            data.put(Long.TYPE, new LoopingIterator(longs));
            data.put(Double.TYPE, new LoopingIterator(doubles));
            data.put(String.class, new LoopingIterator(strings));
        }
    }
}
