# 🏕 Skautský systém – Webová aplikácia na správu úloh

Tento repozitár obsahuje webovú aplikáciu pre skautské oddiely, ktorá umožňuje zaznamenávať plnenie úloh, spravovať odborky a využívať umelú inteligenciu na porovnávanie podobných aktivít.

---

## 📦 Požiadavky (Webová aplikácia)

Na správne fungovanie systému je potrebné mať na serveri:

- PHP **8.0** alebo vyššie  
- Composer
- MySQL  
- Webový server (napr. Apache alebo Nginx)

---

## ⚙️ Inštalácia (Webová aplikácia)

### 1. Klonovanie repozitára

Naklonuj repozitár do **webového root adresára** na serveri:

```bash
git clone https://github.com/kristianrusnak/Skauting.git
```

### 2. Composer

V koreňovom adresári spusti inštaláciu PHP composer:

```bash
composer install
```

### 3. Vytvorenie MySQL databázy

- Pripoj sa do MySQL a vytvor databázu s názvom `skaut`.
- Použite SQL skript zo zložky `/SQL` na vytvorenie štruktúry databázy.

### 4. Konfigurácia databázového pripojenia

PHP aplikácia
- Otvor súbor: `/src/scripts/Utilities/QueryBuilder.php`
- V ňom uprav nasledovné prístupové údaje:
```php
{
  $capsule->addConnection([
    'host'      => 'your_host',
    'database'  => 'your_database',
    'username'  => 'your_username',
    'password'  => 'your_password'
]);
}
```

---

## 🗂 Štruktúra projektu

- Celá aplikačná logika sa nachádza v priečinku `/src`.
- Tento adresár obsahuje súbory pre autentifikáciu, správu používateľov, databázové operácie a zobrazovanie jednotlivých stránok.

**Odporúčanie:** Po úspešnej konfigurácii pripojenia a vytvorení databázy nastav automatické presmerovanie na `home.php`, aby bol používateľ po načítaní aplikácie rovno presunutý na úvodnú stránku.

---

# ✅ Hotovo

Po splnení všetkých krokov by mala byť aplikácia pripravená na používanie.

---
# ⚠️ Volitetelná AI funkcia (porovnávanie úloh)

Časť aplikácie využívajúca OpenAI API na porovnávanie úloh je **voliteľná** – teda aplikácia bude plne funkčná aj bez nastaveného AI modelu. Táto funkcionalita je samostatná a neovplyvňuje základné používanie systému.

---

## 📦 Požiadavky (AI)

Na správne fungovanie AI časti systému je potrebné mať na serveri:

- Python **3.11**
- OpenAI **API kľúč**

---

## ⚙️ Inštalácia (AI)

### 1. OpenAI API kľúč

Na fungovanie porovnávania úloh pomocou umelej inteligencie potrebuješ účet na [OpenAI](https://platform.openai.com/).

- Po registrácii si vytvor **API kľúč**.
- Vlož ho do súboru: `/python/config.json`

```json
{
  "openai_api_key": "your-secret-api-key"
}
```

### 2. Konfigurácia databázového pripojenia

- Otvor súbor: `/python/OpenAiEmbedding.py`
- Nastav správne údaje pre pripojenie k databáze:

```python
self._mydb = mysql.connector.connect(
  host="your_host",
  user="your_username",
  password="your_password",
  database="skaut"
)
```

### 3. Inštalácia Python knižníc

Pre správne fungovanie embedding skriptov je potrebné nainštalovať nasledujúce knižnice:

- openai
- mysql-connector-python
- numpy
- scikit-learn

```bash
pip install openai mysql-connector-python numpy scikit-learn
```

### 4. Nastavenie spúšťania Python skriptu z PHP

V súbore `/src/scripts/Tasks/Manager/MatchTaskManager.php` je potrebné zabezpečiť správne spustenie Python skriptu podľa operačného systému a jeho cesty.

Pre Windows použite nasledujúci príkaz:
```php
shell_exec("start /B python " . dirname(__DIR__, 4) . "\\python\\embedNewTask.py " . escapeshellarg($task_id) . " " . escapeshellarg($text) . " > NUL 2>&1");
```

Pre MacOS a Linux použite:
```php
shell_exec("python3 " . dirname(__DIR__, 4) . "/python/embedNewTask.py " . escapeshellarg($task_id) . " " . escapeshellarg($text) . " > /dev/null 2>&1 &");
```

**Poznámka:** Uisti sa, že príkaz `python` odkazuje na správnu verziu Pythonu. V prípade potreby ho nahraď napr. `python3.11` alebo absolútnou cestou k Pythonu.

---

# ✅ Hotovo