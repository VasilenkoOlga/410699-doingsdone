<div class="modal">
  <a class="modal__close" href="index.php">Закрыть</a>

  <h2 class="modal__heading">Добавление задачи</h2>

  <form class="form"  action="index.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup>
      <? if (isset($errors['name'])){?>
        <p class="form__message"><?= $errors['name'];  ?></p>
      <? } ?></label>

      <input class="form__input <? if (isset($errors['name'])){
      echo "form__input--error";
       } ?>" type="text" name="name" id="name" value="" placeholder="Введите название">
    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект <sup>*</sup>
      <? if (isset($errors['project'])){?>
        <p class="form__message"><?= $errors['project'];  ?></p>
      <? } ?></label>

      <select class="form__input form__input--select <? if (isset($errors['project'])){
      echo "form__input--error";
       } ?>" name="project" id="project">
        <? foreach($projects as $val): 
        if ($val != "Все"){
        ?>
        <option value="<?= $val['id']; ?>"><?= $val['name']; ?></option>
        <? } endforeach; ?>
      </select>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения
      
      <? if (isset($errors['date_deadline'])){?>
        <p class="form__message"><?= $errors['date_deadline'];  ?></p>
      <? } ?>
      
      </label>
        
      <input class="form__input form__input--date" type="date" name="date_deadline" id="date" value="<?= (isset($_POST["date"])) ? $_POST["date"] : ""; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        
      
    </div>

    <div class="form__row">
      <label class="form__label" for="preview">Файл</label>

      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="preview" id="preview" value="">

        <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="projects" value="Добавить">
    </div>
  </form>
</div>
