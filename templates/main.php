
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.html" method="post">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
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
                    <?php foreach ($task_table as $key => $value): ?>
                    <?php if(($showCompleted == 1 && $value['realization'] == true ) || $value['realization'] == false  && $showCompleted == 0): ?>
                      <tr class='tasks__item task <?= ($value['realization']) ? "task--completed" : ""; ?>
                          <?= time_to_task($value["date"]) ? "task--important" : "";  ?> '>
                        <td class='task__select'>
                          <label class='checkbox task__checkbox' <?= ($value['realization']) ? 'checked' : ""; ?> >
                            <input class='checkbox__input visually-hidden' type='checkbox' <?= ($value['realization']) ? 'checked' : ""; ?> >
                            <a href="/"><span class='checkbox__text'><?= $value['task']; ?></span></a>
                          </label>
                        </td>
                        <td class='task__date'><?= $value['date']; ?></td>
                        <td class='task__controls'>
                        </td>
                      </tr>
                    <?php endif; ?>
                    <? endforeach; ?>
                  <?php if ($showCompleted): ?>
                  <tr class="tasks__item task task--completed">
                    <td class="task__select">
                      <label class="checkbox task__checkbox">
                          <input class="checkbox__input visually-hidden" type="checkbox" checked>
                          <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
                      </label>
                    </td>
                    <td class="task__date">10.04.2017</td>
                    <td class="task__controls"></td>
                  </tr>
                  <?php endif; ?>
                </table>