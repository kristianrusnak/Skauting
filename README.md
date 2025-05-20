# 🏕 Skautský systém – Webová aplikácia na správu úloh

Tento repozitár obsahuje webovú aplikáciu pre skautské oddiely, ktorá umožňuje zaznamenávať plnenie úloh, spravovať odborky a využívať umelú inteligenciu na porovnávanie podobných aktivít.

---

## 📦 Požiadavky

Na správne fungovanie systému je potrebné mať na serveri:

- PHP **8.0** alebo vyššie  
- Composer  
- Python **3.11**  
- MySQL  
- Webový server (napr. Apache alebo Nginx)

---

## ⚙️ Inštalácia

### 1. Klonovanie repozitára

Naklonuj repozitár do **webového root adresára** na serveri:

```bash
git clone https://github.com/TBA/TBA /var/www/html
```

### 2. Composer závislosti

V koreňovom adresári spusti inštaláciu PHP závislostí:

```bash
composer install
```

### 3. OpenAI API kľúč

Na fungovanie porovnávania úloh pomocou umelej inteligencie potrebuješ účet na [OpenAI](https://platform.openai.com/).

- Po registrácii si vytvor **API kľúč**.
- Vlož ho do súboru: `/python/config.json`

```bash
{
  "openai_api_key": "your-secret-api-key"
}
```

### 4. Vytvorenie MySQL databázy

- Pripoj sa do MySQL a vytvor databázu s názvom `skaut`.
- Použi SQL skript zo zložky `/SQL` na vytvorenie štruktúry databázy.

### 5. Konfigurácia databázového pripojenia

- Otvor súbor: `/src/scripts/Utilities/QueryBuilder.php`
- V ňom uprav nasledovné prístupové údaje:
```bash
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

## ✅ Hotovo

Po splnení všetkých krokov by mala byť aplikácia pripravená na používanie.

V prípade problémov alebo pripomienok neváhaj otvoriť issue.
