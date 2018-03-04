<div class="modal">
  <a href="index.php" class="modal__close">Закрыть</a>

  <h2 class="modal__heading">Добавление проекта</h2>

  <form class="form"  action="index.php?modal-project" method="post">
    <div class="form__row">
      <label class="form__label" for="project_name">Название <sup>*</sup>
      <? if (isset($project_errors['name'])){?>
        <p class="form__message"><?= $project_errors['name'];  ?></p>
      <? } ?></label>

      <input class="form__input <? if (isset($project_errors['name'])){
      echo "form__input--error";
       } ?>" type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="add_project" value="Добавить">
    </div>
  </form>
</div>
