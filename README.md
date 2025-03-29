# Joomla Article Link Cleaner

Цей проєкт містить набір PHP-скриптів для очищення зовнішніх посилань у статтях сайтів, які побудовані на CMS Joomla. Основна мета — замінити всі зовнішні гіперпосилання у полі `fulltext` на текстовий варіант для збереження змісту без активних зовнішніх переходів.

## 📌 Завдання

- Знайти та обробити зовнішні посилання:
  - `<a href="...">анкор</a>` → `https://... анкор`
  - `[ПОЧАТОК_ПОСИЛАННЯ ... КІНЕЦЬ_ПОСИЛАННЯ]` → `https://...`
  - "Голі" посилання → залишити у вигляді тексту
- Працювати з кількома базами даних для різних піддоменів:
  - `kharkov.mycityua.com`
  - `lviv.mycityua.com`
  - `dnipro.mycityua.com`
  - `donetsk.mycityua.com`
- Перевірити результати до та після обробки

## 🗂 Структура проєкту

```
/public_html/
│
├── kharkov/
│   ├── replace_links_kharkiv.php
│   ├── preview_links_list_kharkiv.php
│   └── view_articles_by_id_kharkiv.php
│
├── lviv/
│   ├── replace_links_lviv.php
│   ├── preview_links_list_lviv.php
│   └── view_articles_by_id_lviv.php
│
├── dnepr/
│   ├── replace_links_dnipro.php
│   ├── preview_links_list_dnipro.php
│   └── view_articles_by_id_dnipro.php
│
├── donetsk/
│   ├── replace_links_donetsk.php
│   ├── preview_links_list_donetsk.php
│   └── view_articles_by_id_donetsk.php
```

## ⚙️ Використання

### 🔍 Перевірка зовнішніх посилань

1. Відкрий у браузері:
   ```
   https://mycityua.com/<місто>/preview_links_list_<місто>.php
   ```

2. Скрипт виведе всі знайдені зовнішні посилання разом з ID статей.

---

### 🔁 Замінити посилання у базі

1. Відкрий:
   ```
   https://mycityua.com/<місто>/replace_links_<місто>.php
   ```

2. Скрипт обробить усі статті й оновить базу.

---

### 📝 Перегляд вмісту окремих статей

1. Відкрий:
   ```
   https://mycityua.com/<місто>/view_articles_by_id_<місто>.php
   ```

2. Заздалегідь у коді скрипта задай ID потрібних статей.

---



## 🧑‍💻 Автор

**Andriy Sydor**  

