package org.b1n.jirator.web.logic;

import java.util.List;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.web.logic.CrudLogic;
import org.b1n.jirator.domain.Participant;
import org.b1n.jirator.domain.ParticipantDao;
import org.vraptor.annotations.Component;
import org.vraptor.annotations.Out;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Component("participant")
public class ParticipantLogic extends CrudLogic<Participant> {
    @SuppressWarnings("unused")
    @Out
    private List<Participant> data;

    /**
     * Lista usuarios.
     */
    public void list() {
        ParticipantDao dao = DaoLocator.getDao(Participant.class);
        data = dao.findAll();
    }
}
