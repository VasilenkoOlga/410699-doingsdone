
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.html" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item<?php if (!isset($_REQUEST['tasks_filter'])) { ?> tasks-switch__item--active<?php } ?>">Все задачи</a>
                        <a href="?tasks_filter=today" class="tasks-switch__item<?php if (isset($_REQUEST['tasks_filter']) && $_REQUEST['tasks_filter'] == 'today') { ?> tasks-switch__item--active<?php } ?>">Повестка дня</a>
                      <a href="?tasks_filter=tomorrow" class="tasks-switch__item<?php if (isset($_REQUEST['tasks_filter']) && $_REQUEST['tasks_filter'] == 'tomorrow') { ?> tasks-switch__item--active<?php } ?>">Завтра</a>
                      <a href="?tasks_filter=old" class="tasks-switch__item<?php if (isset($_REQUEST['tasks_filter']) && $_REQUEST['tasks_filter'] == 'old') { ?> tasks-switch__item--active<?php } ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <a href="?show_completed">
                            <input class="checkbox__input visually-hidden" type="checkbox" 
                                <?= ($showCompleted) ? "checked" : "" ; ?>
                             >
                            <span class="checkbox__text">Показывать выполненные</span>
                        </a>
                    </label>
                </div>

                <table class="tasks">
                    <?php foreach ($task_table as $task) { ?>
                      <?php 
                      if ($showCompleted == 0 && $task['date_done'] !== NULL) {
                        continue;
                      }
                      ?>
                   
                      <tr class='tasks__item task <?= ($task['date_done'] !== NULL) ? 'task--completed' : ""; ?>
                          <?= time_to_task($task["date_deadline"]) ? "task--important" : "";  ?> '>
                        <td class='task__select'>
                          <label class='checkbox task__checkbox' <?= ($task['date_done'] !== NULL) ? 'checked' : ""; ?> >
                            <input class='checkbox__input visually-hidden' type='checkbox' <?= ($task['date_done'] !== NULL) ? 'checked' : ""; ?> >
                            <a href="?complete_task_id=<?=$task['id'];?>">
                              <input class="checkbox__input visually-hidden" type="checkbox"<? if ($task['date_done'] !== NULL) { ?> checked<? } ?>>
                              <span class='checkbox__text'><?= $task['task']; ?></span>
                            </a>
                          </label>
                        </td>
                        <td class='task__date'><?= date('d.m.Y', strtotime($task['date_deadline'])); ?></td>
                        <td class='task__controls'>
                        </td>
                      </tr>
                   
                    <? } ?>
                  
                </table>