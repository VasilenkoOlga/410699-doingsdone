 

        <main class="content__main">
          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="index.php?register" method="post">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input <?= (isset($errors_regist["email"])) ? "form__input--error" : "" ?> " type="text" name="email" id="email" value="<?= (isset($_POST["email"]) ? $_POST["email"] : "") ?>" placeholder="Введите e-mail">

              <?php if (isset($errors_regist['email'])): ?>
              <p class="form__message"><?= $errors_regist["email"];  ?></p>
              <?php endif; ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>

              <input class="form__input <?= (isset($errors_regist["password"])) ? "form__input--error" : "" ?>" type="password" name="password" id="password" value="<?= (isset($_POST["password"]) ? $_POST["password"] : "") ?>" placeholder="Введите пароль">
              
              <?php if (isset($errors_regist['password'])): ?>
              <p class="form__message"><?= $errors_regist["password"];  ?></p>
              <?php endif; ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>

              <input class="form__input <?= (isset($errors_regist["name"])) ? "form__input--error" : "" ?>" type="text" name="name" id="name" value="<?= (isset($_POST["name"]) ? $_POST["name"] : "") ?>" placeholder="Введите имя">
              
              <?php if (isset($errors_regist['name'])): ?>
              <p class="form__message"><?= $errors_regist["name"];  ?></p>
              <?php endif; ?>
            </div>

            <div class="form__row form__row--controls">
              <?php if (isset($errors_regist)): ?>
              <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
              <?php endif; ?>

              <div class="form__row form__row--controls">
              <input class="button" type="submit" name="register" value="Зарегистрироваться">
                </div>
            </div>
          </form>
          </main>
