<div class="modal">
  <a href="index.php" class="modal__close" >Закрыть</a>

  <h2 class="modal__heading">Вход на сайт</h2>
  
  <form class="form" action="index.php?login" method="post">
    <div class="form__row">
      <label class="form__label" for="email">E-mail <sup>*</sup></label>

      <input class="form__input <?= (isset($login_errors["email"])) ? "form__input--error" : "" ?>" type="text" name="email" id="email" value="<?= (isset($_POST["email"]) ? $_POST["email"] : "") ?>" placeholder="Введите e-mail">
      
       <? if (isset($login_errors["email"])){?>
        <p class="form__message"><?= $login_errors["email"];  ?></p>
      <? } ?>
      
    </div>

    <div class="form__row">
      <label class="form__label" for="password">Пароль <sup>*</sup></label>

      <input class="form__input <?= (isset($login_errors["password"])) ? "form__input--error" : "" ?>" type="password" name="password" id="password" value="<?= (isset($_POST["password"]) ? $_POST["password"] : "") ?>" placeholder="Введите пароль">
      
      <? if (isset($login_errors["password"])){?>
        <p class="form__message"><?= $login_errors["password"];  ?></p>
      <? } ?>
      
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="login" value="Войти">
    </div>
  </form>
</div>
