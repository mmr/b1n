package org.b1n.framework.web.logic;

import java.lang.reflect.ParameterizedType;
import java.util.List;

import javax.persistence.PersistenceException;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.persistence.Entity;
import org.b1n.framework.persistence.EntityDao;
import org.vraptor.annotations.Out;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 * @param <E> entidade.
 */
public abstract class CrudLogic<E extends Entity> {

    @SuppressWarnings("unused")
    @Out
    private List<E> data;

    private Class<E> entityClass;

    /**
     * Carrega dados.
     */
    @SuppressWarnings("unchecked")
    public void list() {
        EntityDao<Entity> dao = DaoLocator.getDao(getEntityClass());
        data = (List<E>) dao.findAll();
    }

    /**
     * Salva entidade.
     * @param entity entidade a ser salva.
     */
    public void save(final E entity) {
        if (entity.getId() == null) {
            insert(entity);
        } else {
            update(entity);
        }
    }

    /**
     * Salva entidade.
     * @param entity entidade.
     */
    private void insert(final E entity) {
        beforeInsert(entity);
        if (canInsert(entity)) {
            entity.save();
        }
    }

    /**
     * Salva entidade.
     * @param entity entidade a ser salva.
     */
    private void update(final E entity) {
        beforeUpdate(entity);
        if (canUpdate(entity)) {
            entity.save();
        }
    }

    /**
     * Remove entidade.
     * @param entity entidade.
     */
    public void remove(final E entity) {
        beforeRemove(entity);
        if (canRemove(entity)) {
            entity.remove();
        }
    }

    /**
     * Oportunidade para adicionar comportamento antes de remover entidade.
     * @param entity entidade a ser removida.
     */
    protected void beforeRemove(final E entity) {
        // hook
    }

    /**
     * Oportunidade para adicionar comportamento antes de inserir entidade.
     * @param entity entidade a ser salva.
     */
    protected void beforeInsert(final E entity) {
        // hook
    }

    /**
     * Oportunidade para adicionar comportamento antes de salvar entidade.
     * @param entity entidade a ser salva.
     */
    protected void beforeUpdate(final E entity) {
        // hook
    }

    /**
     * Pode se sobrescrito por filhos para checagem antes de inserir entidade.
     * @param entity entidade a ser inserida.
     * @return <code>true</code> se pode inserir entidade, <code>false</code> se nao.
     */
    protected boolean canInsert(final E entity) {
        return true;
    }

    /**
     * Pode se sobrescrito por filhos para checagem antes de salvar entidade.
     * @param entity entidade a ser salva.
     * @return <code>true</code> se pode salvar entidade, <code>false</code> se nao.
     */
    protected boolean canUpdate(final E entity) {
        return true;
    }

    /**
     * Pode se sobrescrito por filhos para checagem antes de remover entidade.
     * @param entity entidade a ser removida.
     * @return <code>true</code> se pode remover entidade, <code>false</code> se nao.
     */
    protected boolean canRemove(final E entity) {
        return true;
    }

    /**
     * @return classe de entidade.
     */
    @SuppressWarnings("unchecked")
    protected Class<E> getEntityClass() {
        try {
            if (entityClass == null) {
                entityClass = (Class<E>) ((ParameterizedType) getClass().getGenericSuperclass()).getActualTypeArguments()[0];
            }
            return entityClass;
        } catch (final ClassCastException e) {
            throw new PersistenceException(e);
        }
    }
}
