package org.b1n.jirator.web.logic;

import java.util.Comparator;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.TreeSet;
import java.util.Map.Entry;

import org.b1n.framework.persistence.DaoLocator;
import org.b1n.framework.web.logic.CrudLogic;
import org.b1n.jirator.domain.Participant;
import org.b1n.jirator.domain.Task;
import org.b1n.jirator.domain.TaskDao;
import org.vraptor.annotations.Component;
import org.vraptor.annotations.Out;
import org.vraptor.annotations.Parameter;

/**
 * @author Marcio Ribeiro
 * @date May 3, 2008
 */
@Component("task")
public class TaskLogic extends CrudLogic<Task> {
    @Out
    private Set<Entry<Participant, TaskList>> data;

    @Out
    @Parameter
    private Date startDate;

    @Out
    @Parameter
    private Date endDate;

    /**
     * Construtor. Sugere datas para pesquisa. Fim = data corrente, Inicio = uma semana antes da data corrente.
     */
    public TaskLogic() {
        endDate = new Date();

        final int daysInAWeek = 7;
        final int hoursInADay = 24;
        final int minutesInAnHour = 60;
        final int secondsInAMinute = 60;
        final int miliSecondsInASecond = 1000;
        startDate = new Date(endDate.getTime() - daysInAWeek * hoursInADay * minutesInAnHour * secondsInAMinute * miliSecondsInASecond);
    }

    /**
     * Cria lista de tarefas.
     */
    public void list() {
        if (startDate == null || endDate == null) {
            throw new IllegalStateException("Campos de data devem ser preenchidos.");
        }

        TaskDao dao = DaoLocator.getDao(Task.class);
        List<Task> tasks = dao.findByDateInterval(startDate, endDate);
        Map<Participant, TaskList> tmp = new HashMap<Participant, TaskList>();
        for (Task task : tasks) {
            Participant p = task.getParticipant();
            if (tmp.containsKey(p)) {
                tmp.get(p).addTask(task);
            } else {
                tmp.put(p, new TaskList(p, task));
            }
        }
        data = new TreeSet<Map.Entry<Participant, TaskList>>(new Comparator<Entry<Participant, TaskList>>() {
            /**
             * Compara duas entradas.
             * @param o1 primeira entrada.
             * @param o2 segunda entrada.
             * @return -1 se for menor, 0 se for igual, 1 se for maior.
             */
            public int compare(final Entry<Participant, TaskList> o1, final Entry<Participant, TaskList> o2) {
                return o2.getValue().getTotalPoints().compareTo(o1.getValue().getTotalPoints());
            }
        });

        data.addAll(tmp.entrySet());
    }
}
