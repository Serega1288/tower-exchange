# Tower Exchange

WordPress-сайт Tower Exchange Kyiv з Docker-оточенням і кастомною темою на основі макета `Exchange Desk`.

## Локальні адреси

- WordPress: <http://localhost:8080>
- Adminer: <http://localhost:8081>
- Адмінка WordPress: <http://localhost:8080/wp-admin/>

## Швидкий старт

Перед першим запуском покладіть ліцензійний архів ACF Pro у
`plugins-archives/advanced-custom-fields-pro.zip`. Архів ігнорується Git і не
публікується в репозиторії. Bootstrap потребує активного ACF Pro.

```bash
copy .env.example .env
npm install
npm run wp:up
npm run wp:bootstrap
npm run dev
```

`wp:bootstrap` є ідемпотентною командою: перший запуск створює відсутні ACF-групи, меню та початкову головну сторінку. Наступні запуски не перезаписують контент конструктора, меню або налаштування, які вже редагував користувач.

## Команди

```bash
npm run wp:up          # запустити Docker
npm run wp:down        # зупинити Docker
npm run wp:logs        # показати логи
npm run wp:bootstrap   # початкове налаштування WordPress
npm run db:export      # експортувати базу
npm run db:import      # імпортувати базу
npm run build          # перевірити JavaScript теми
npm run dev            # BrowserSync для live reload
```

## Структура інтеграції

```text
html/                         статичний HTML-референс
project-theme/
  assets/                     CSS, JS і логотипи з верстки
  inc/
    template/                 шаблони секцій конструктора
    acf.php                   глобальна ACF Options Page
    box-constructor.php       диспетчер Flexible Content
  tools/bootstrap-wordpress.php
  header.php
  footer.php
  page-constructor.php
  old/                        попередні файли starter-теми
```

## Редагування контенту

- `Tower — налаштування` в адмінці — логотипи, назва бренду, CTA та footer.
- `Сторінки → Головна` — ACF Flexible Content-конструктор із десяти секцій.
- `Вигляд → Меню` — окремі desktop, mobile та footer-меню.
- Кожну секцію можна переставити, повторити або вимкнути перемикачем `Вимкнути блок`.
- Калькулятор є ізольованим блоком і може бути вимкнений без впливу на інші секції.

## Важливі файли

- Статичне джерело: `html/index.html`
- Активна тема: `project-theme`
- Резерв старої теми: `project-theme/old`
- Дамп перед інтеграцією: `database/tower-pre-integration.sql` (локальний, ігнорується Git)

## Деплой теми через Deployer for Git

Гілка `theme-deploy` містить лише вміст `project-theme` у корені. Після коміту змін у `main` її потрібно зібрати й опублікувати так:

```powershell
$sourceCommit = git rev-parse main
$deployCommit = git subtree split --prefix=project-theme $sourceCommit
git diff --exit-code "${sourceCommit}:project-theme" "${deployCommit}^{tree}"
git push origin "${deployCommit}:refs/heads/theme-deploy"
```

Налаштування пакета в плагіні:

- тип пакета: `Theme`;
- провайдер: `GitHub`;
- репозиторій: `https://github.com/Serega1288/tower-exchange` без `.git`;
- гілка: `theme-deploy`;
- `Miscellaneous → Flush cache`: увімкнено;
- для автоматичного деплою додати `Push-to-Deploy/Webhook URL` плагіна до GitHub Webhooks з типом `application/json` і подією `push`.

Deployer for Git формує папку теми з назви репозиторію: `tower-exchange`. Після першого встановлення цю тему потрібно активувати та перевірити призначення меню. Версійні зміни ACF-контенту застосовуються окремо командою bootstrap з фактичної папки активної теми.

Локальні `.env`, SQL-дампи, `node_modules` і ліцензійні zip-плагіни не додаються до Git.
